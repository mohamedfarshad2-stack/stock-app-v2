<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CustomerStats extends Widget
{
    protected static string $view = 'filament.widgets.customer-stats';

    public $record;

    protected int|string|array $columnSpan = 'full';
}