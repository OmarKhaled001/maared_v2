<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVolunteer extends CreateRecord
{
    protected static string $resource = VolunteerResource::class;

    public function getTitle(): string 
    {
        return 'إضافة متطوع';
    }
}
