<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Customer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static string $view = 'filament.pages.customer';

    protected static ?string $navigationLabel = 'Customer';

    protected static ?string $slug = 'customers';
}
