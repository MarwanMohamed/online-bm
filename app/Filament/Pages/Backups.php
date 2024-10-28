<?php

namespace App\Filament\Pages;

use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

class Backups extends BaseBackups
{
    protected static ?int $navigationSort = 5;
    public static function canAccess(): bool
    {
        return \Auth::user()->hasPermissionTo('Backup');
    }
}