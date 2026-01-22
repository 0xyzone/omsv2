<?php

namespace App\Filament\Taker\Resources\Orders\Pages;

use App\Filament\Taker\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    
}
