<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('presensi')
            ->url('/presensi')
            ->color('success'),
        ];
    }

    public function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if (Auth::user()?->hasRole('super_admin')) {
            return $query;
        }

        return $query->where('user_id', Auth::id());
    }
}
