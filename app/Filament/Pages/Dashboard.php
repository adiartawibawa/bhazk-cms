<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function persistsFiltersInSession(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $title = 'Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -1;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filters')
                    ->schema([
                        Select::make('range')
                            ->label('Quick Range')
                            ->options([
                                '7_days' => 'Last 7 Days',
                                '30_days' => 'Last 30 Days',
                                'this_month' => 'This Month',
                                'last_month' => 'Last Month',
                            ])
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === '7_days') {
                                    $set('startDate', now()->subDays(7));
                                    $set('endDate', now());
                                } elseif ($state === '30_days') {
                                    $set('startDate', now()->subDays(30));
                                    $set('endDate', now());
                                } elseif ($state === 'this_month') {
                                    $set('startDate', now()->startOfMonth());
                                    $set('endDate', now()->endOfMonth());
                                } elseif ($state === 'last_month') {
                                    $set('startDate', now()->subMonth()->startOfMonth());
                                    $set('endDate', now()->subMonth()->endOfMonth());
                                }
                            }),

                        DatePicker::make('startDate')
                            ->label('Start Date')
                            ->default(now()->subDays(30)),

                        DatePicker::make('endDate')
                            ->label('End Date')
                            ->default(now())
                            ->afterOrEqual('startDate'),
                    ])
                    ->columns(3),
            ]);
    }
}
