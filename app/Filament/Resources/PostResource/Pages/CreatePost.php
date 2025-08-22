<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Events\PostCreated;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Event;

class CreatePost extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make()
        ];
    }

    public function afterCreate()
    {
        Event::dispatch(new PostCreated($this->getRecord()->toArray()));
    }
}
