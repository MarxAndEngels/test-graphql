<?php
namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCity extends CreateRecord
{
    protected static string $resource = CityResource::class;
    
    // Перенаправить на список после создания
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}