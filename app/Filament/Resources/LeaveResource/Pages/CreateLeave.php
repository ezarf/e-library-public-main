<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['status'])) {
            $data['status'] = 'pending';
        }

        if (empty($data['user_id'])) {
            $data['user_id'] = Auth::user()->id;
        }

        return $data;
    }
}
