<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

class IconsColumn extends Column
{
    protected string $view = 'tables.columns.icons-column';

    public function getIcon(): string
    {
        return $this->getState() ?? 'heroicon-o-question-mark-circle';
    }
}
