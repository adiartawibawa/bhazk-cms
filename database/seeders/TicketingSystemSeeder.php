<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use App\Models\TicketStatusHistory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TicketingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data existing jika ada
        TicketStatusHistory::query()->delete();
        TicketAttachment::query()->delete();
        TicketMessage::query()->delete();
        Ticket::query()->delete();

        // Ambil beberapa user untuk dijadikan customer dan agent
        // Untuk ticketing system, kita asumsikan:
        // - Customer: User dengan role 'User' atau 'Subscriber'
        // - Agent: User dengan role 'Admin', 'Editor', atau 'Author'

        $customers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['User', 'Subscriber']);
        })->take(5)->get();

        $agents = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin', 'Editor', 'Author']);
        })->take(3)->get();

        if ($customers->isEmpty()) {
            // Fallback: ambil user biasa jika tidak ada customer
            $customers = User::take(5)->get();
        }

        if ($agents->isEmpty()) {
            // Fallback: ambil admin atau buat user agent jika tidak ada
            $agents = User::whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })->take(3)->get();

            if ($agents->isEmpty()) {
                $this->command->error('Please seed users with Admin, Editor, or Author roles first!');
                return;
            }
        }

        $tickets = [
            [
                'user_id' => $customers[0]->id,
                'assigned_to' => $agents[0]->id,
                'subject' => 'Login issue - cannot access my account',
                'description' => 'I\'ve been trying to login to my account for the past hour but keep getting an "invalid credentials" error. I\'m sure I\'m using the correct password.',
                'status' => Ticket::STATUS_RESOLVED,
                'priority' => Ticket::PRIORITY_HIGH,
                'type' => Ticket::TYPE_SUPPORT,
                'source' => Ticket::SOURCE_WEB,
                'first_response_at' => now()->subHours(2),
                'resolved_at' => now()->subHour(),
                'response_count' => 3,
                'reopen_count' => 0,
            ],
            [
                'user_id' => $customers[1]->id,
                'assigned_to' => $agents[1]->id,
                'subject' => 'Payment not processed',
                'description' => 'I made a payment 2 days ago but it\'s still showing as pending. The amount has been deducted from my bank account.',
                'status' => Ticket::STATUS_IN_PROGRESS,
                'priority' => Ticket::PRIORITY_MEDIUM,
                'type' => Ticket::TYPE_BILLING,
                'source' => Ticket::SOURCE_EMAIL,
                'first_response_at' => now()->subDay(),
                'response_count' => 2,
                'reopen_count' => 0,
            ],
            [
                'user_id' => $customers[2]->id,
                'assigned_to' => null,
                'subject' => 'Feature request: Dark mode',
                'description' => 'I would love to see a dark mode option in the application. It would be easier on the eyes during night time usage.',
                'status' => Ticket::STATUS_OPEN,
                'priority' => Ticket::PRIORITY_LOW,
                'type' => Ticket::TYPE_FEATURE_REQUEST,
                'source' => Ticket::SOURCE_WEB,
                'response_count' => 0,
                'reopen_count' => 0,
            ],
            [
                'user_id' => $customers[3]->id,
                'assigned_to' => $agents[2]->id,
                'subject' => 'Bug: Image upload not working',
                'description' => 'When I try to upload an image to my profile, I get an error saying "File type not supported" even though it\'s a JPEG file.',
                'status' => Ticket::STATUS_ON_HOLD,
                'priority' => Ticket::PRIORITY_HIGH,
                'type' => Ticket::TYPE_BUG,
                'source' => Ticket::SOURCE_WEB,
                'first_response_at' => now()->subHours(5),
                'response_count' => 1,
                'reopen_count' => 1,
            ],
            [
                'user_id' => $customers[4]->id,
                'assigned_to' => $agents[0]->id,
                'subject' => 'Subscription cancellation',
                'description' => 'I would like to cancel my subscription but cannot find the option to do so in my account settings.',
                'status' => Ticket::STATUS_CLOSED,
                'priority' => Ticket::PRIORITY_MEDIUM,
                'type' => Ticket::TYPE_BILLING,
                'source' => Ticket::SOURCE_CHAT,
                'first_response_at' => now()->subDays(2),
                'resolved_at' => now()->subDay(),
                'closed_at' => now()->subDay(),
                'response_count' => 4,
                'reopen_count' => 0,
            ],
        ];

        $ticketMessages = [];
        $statusHistory = [];
        $attachments = [];

        foreach ($tickets as $ticketData) {
            // Generate ticket number
            $ticketData['ticket_number'] = 'TKT-' . strtoupper(uniqid());

            $ticket = Ticket::create($ticketData);

            // Create initial message from user
            $userMessage = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id,
                'message' => $ticket->description,
                'message_type' => TicketMessage::MESSAGE_TYPE_USER,
                'is_internal' => false,
                'is_first_response' => false,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ]);

            // Add status history for initial creation
            $statusHistory[] = [
                'id' => Str::uuid(),
                'ticket_id' => $ticket->id,
                'changed_by' => $ticket->user_id,
                'old_status' => null,
                'new_status' => $ticket->status,
                'change_reason' => 'Ticket created',
                'created_at' => $ticket->created_at,
                'updated_at' => $ticket->created_at,
            ];

            // Add responses based on ticket status
            if ($ticket->status !== Ticket::STATUS_OPEN) {
                $agentResponse = TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $ticket->assigned_to ?? $agents[0]->id,
                    'message' => $this->getAgentResponse($ticket->type, $ticket->status),
                    'message_type' => TicketMessage::MESSAGE_TYPE_AGENT,
                    'is_internal' => false,
                    'is_first_response' => true,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => $ticket->first_response_at,
                    'updated_at' => $ticket->first_response_at,
                ]);

                // Add status change history if needed
                if ($ticket->status === Ticket::STATUS_RESOLVED || $ticket->status === Ticket::STATUS_CLOSED) {
                    $statusHistory[] = [
                        'id' => Str::uuid(),
                        'ticket_id' => $ticket->id,
                        'changed_by' => $ticket->assigned_to ?? $agents[0]->id,
                        'old_status' => Ticket::STATUS_IN_PROGRESS,
                        'new_status' => $ticket->status,
                        'change_reason' => 'Issue resolved',
                        'created_at' => $ticket->resolved_at ?? $ticket->closed_at,
                        'updated_at' => $ticket->resolved_at ?? $ticket->closed_at,
                    ];
                }
            }

            // Add attachments for some tickets
            if ($ticket->type === Ticket::TYPE_BUG) {
                $attachments[] = [
                    'id' => Str::uuid(),
                    'ticket_id' => $ticket->id,
                    'message_id' => $userMessage->id,
                    'user_id' => $ticket->user_id,
                    'filename' => 'screenshot.png',
                    'original_name' => 'error_screenshot.png',
                    'mime_type' => 'image/png',
                    'size' => 1024 * 512, // 512KB
                    'disk' => 'public',
                    'path' => 'ticket-attachments/screenshot.png',
                    'download_count' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert status history in bulk
        if (!empty($statusHistory)) {
            TicketStatusHistory::insert($statusHistory);
        }

        // Insert attachments in bulk
        if (!empty($attachments)) {
            TicketAttachment::insert($attachments);
        }

        $this->command->info('Ticketing system seeded successfully!');
        $this->command->info('Created ' . count($tickets) . ' tickets with messages and history.');
    }

    /**
     * Generate appropriate agent response based on ticket type and status
     */
    private function getAgentResponse(string $type, string $status): string
    {
        $responses = [
            Ticket::TYPE_SUPPORT => [
                Ticket::STATUS_IN_PROGRESS => "Thank you for reporting this issue. I'm looking into it and will get back to you shortly with an update.",
                Ticket::STATUS_RESOLVED => "The login issue has been resolved. It was caused by a temporary server glitch. Please try logging in again and let me know if you encounter any further issues.",
                Ticket::STATUS_ON_HOLD => "I've identified the issue and need to consult with our technical team for a solution. I'll update you as soon as I have more information."
            ],
            Ticket::TYPE_BILLING => [
                Ticket::STATUS_IN_PROGRESS => "I'm checking your payment status with our billing department. This usually takes 1-2 business days to verify.",
                Ticket::STATUS_RESOLVED => "The payment has been verified and processed. Your account should now reflect the correct balance. Sorry for the inconvenience.",
            ],
            Ticket::TYPE_BUG => [
                Ticket::STATUS_IN_PROGRESS => "Thanks for the detailed report and screenshot. I'm able to reproduce this issue and our development team is working on a fix.",
                Ticket::STATUS_ON_HOLD => "Our developers have identified the root cause and are working on a fix. This will be included in our next update scheduled for next week.",
            ],
            Ticket::TYPE_FEATURE_REQUEST => [
                Ticket::STATUS_IN_PROGRESS => "Thank you for your suggestion! I've forwarded this to our product team for consideration in future updates.",
            ]
        ];

        return $responses[$type][$status] ?? "Thank you for contacting support. I'm looking into your request and will update you soon.";
    }
}
