<?php

namespace App\View\Components;

use Filament\Forms\Components\Select;
use App\Models\Icon;

class IconPicker extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->searchable();

        $this->options(
            fn() => Icon::query()
                ->limit(20)
                ->pluck('label', 'name')
                ->toArray()
        );


        $this->native(false);

        $this->getSearchResultsUsing(function (string $search): array {

            if (empty($search)) {
                return Icon::query()
                    ->limit(50)
                    ->pluck('label', 'name')
                    ->toArray();
            }

            return Icon::query()
                ->where('name', 'like', "%{$search}%")
                ->limit(50)
                ->pluck('label', 'name')
                ->toArray();
        });


        $this->getOptionLabelUsing(fn($state) => Icon::firstWhere('name', '=', $state)?->label);

        $this->label(trans('icon.icon'));
        $this->searchLabels(trans('icon.search'));
        $this->searchingMessage(trans('icon.searching'));

        $this->allowHtml();
    }
}
