<?php

namespace App\Filament\Resources\ComprehensiveResource\Pages;

use App\Filament\Resources\ComprehensiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ListComprehensives extends ListRecords
{
    protected static string $resource = ComprehensiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
           Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        try {
            parent::mount();
        } catch (ModelNotFoundException $e) {
            Notification::make()
                ->title('Record Not Found')
                ->body('The requested comprehensive insurance record was not found or may have been deleted.')
                ->danger()
                ->send();
            
            $this->redirect(static::getResource()::getUrl('index'));
        }
    }
}
