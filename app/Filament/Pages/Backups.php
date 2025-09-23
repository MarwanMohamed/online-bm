<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\BackupDestination;
use Livewire\Attributes\Computed;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\DB;

class Backups extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static string $view = 'filament.pages.backups';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Backups';
    protected static ?string $title = 'Backups';
    protected static ?string $navigationGroup = 'Settings';
    
    public static function canAccess(): bool
    {
        return Auth::check();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_backup')
                ->label('Create Backup')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->action(function () {
                    try {
                        // Run backup in background
                        Artisan::queue('backup:run');
                        
                        Notification::make()
                            ->title('Backup Started')
                            ->body('Your backup has been queued and will be processed in the background.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Backup Failed')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function downloadBackup($filename)
    {
        try {
            $filePath = storage_path('app/' . env('APP_NAME', 'laravel-backup') . '/' . $filename);
            
            if (!file_exists($filePath)) {
                Notification::make()
                    ->title('Download Failed')
                    ->body('Backup file not found.')
                    ->danger()
                    ->send();
                return;
            }

            return response()->download($filePath, $filename);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Download Failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function deleteBackup($filename)
    {
        try {
            $filePath = storage_path('app/' . env('APP_NAME', 'laravel-backup') . '/' . $filename);
            
            if (!file_exists($filePath)) {
                Notification::make()
                    ->title('Delete Failed')
                    ->body('Backup file not found.')
                    ->danger()
                    ->send();
                return;
            }

            unlink($filePath);
            
            Notification::make()
                ->title('Backup Deleted')
                ->body('Backup file has been deleted successfully.')
                ->success()
                ->send();
                
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Delete Failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    #[Computed]
    public function getBackupDestinations()
    {
        try {
            $destinations = collect();
            $disks = config('backup.backup.destination.disks', ['local']);
            
            foreach ($disks as $disk) {
                $backupDestination = BackupDestination::create($disk, config('backup.backup.name'));
                $backups = $backupDestination->backups();
                
                $destinations->push((object) [
                    'name' => config('backup.backup.name'),
                    'disk' => $disk,
                    'healthy' => $backups->count() > 0,
                    'amount' => $backups->count(),
                    'newest' => $backups->count() > 0 ? $backups->first()->date()->diffForHumans() : 'No backups',
                    'used_storage' => $this->formatBytes($backups->sum(fn($backup) => $backup->sizeInBytes())),
                ]);
            }
            
            return $destinations;
        } catch (\Exception $e) {
            return collect();
        }
    }

    #[Computed]
    public function getBackups()
    {
        try {
            $backupPath = storage_path('app/' . env('APP_NAME', 'laravel-backup'));
            $backups = collect();
            
            if (is_dir($backupPath)) {
                $files = glob($backupPath . '/backup_*.zip');
                
                foreach ($files as $file) {
                    $backups->push((object) [
                        'path' => env('APP_NAME', 'laravel-backup') . '/' . basename($file),
                        'filename' => basename($file),
                        'disk' => 'local',
                        'date' => date('M j, Y H:i:s', filemtime($file)),
                        'size' => $this->formatBytes(filesize($file)),
                        'size_bytes' => filesize($file),
                        'date_timestamp' => filemtime($file)
                    ]);
                }
            }
            
            return $backups->sortByDesc('date_timestamp');
        } catch (\Exception $e) {
            return collect();
        }
    }
    
    #[Computed]
    public function getQueuedJobsCount()
    {
        try {
            return DB::table('jobs')->where('payload', 'like', '%backup:run%')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}