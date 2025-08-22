<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Events\PostUpdated;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Event;

class EditPost extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make()
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['github_starts'] = $this->getRecord()->meta('github_starts');
        $data['github_watchers'] = $this->getRecord()->meta('github_watchers');
        $data['github_forks'] = $this->getRecord()->meta('github_forks');
        $data['downloads_total'] = $this->getRecord()->meta('downloads_total');
        $data['downloads_monthly'] = $this->getRecord()->meta('downloads_monthly');
        $data['downloads_daily'] = $this->getRecord()->meta('downloads_daily');
        return $data;
    }


    public function afterSave()
    {
        Event::dispatch(new PostUpdated($this->getRecord()->toArray()));
    }
}
