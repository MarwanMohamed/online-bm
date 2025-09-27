<?php

namespace App\Filament\Resources\ComprehensiveResource\Pages;

use App\Filament\Resources\ComprehensiveResource;
use App\Models\Customer;
use App\Helpers\InsuranceHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EditComprehensive extends EditRecord
{
    protected static string $resource = ComprehensiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        try {
            parent::mount($record);
            
            // Additional validation after record is loaded
            if ($this->record && $this->record->ins_type !== 'Comprehensive') {
                Notification::make()
                    ->title('Invalid Record Type')
                    ->body('This insurance record is not a comprehensive insurance policy.')
                    ->danger()
                    ->send();
                
                $this->redirect(static::getResource()::getUrl('index'));
                return;
            }

            if ($this->record && $this->record->deleted == 1) {
                Notification::make()
                    ->title('Record Deleted')
                    ->body('This insurance record has been deleted and cannot be edited.')
                    ->danger()
                    ->send();
                
                $this->redirect(static::getResource()::getUrl('index'));
                return;
            }
        } catch (ModelNotFoundException $e) {
            // Let the exception handler deal with this
            throw $e;
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure ins_type remains Comprehensive
        $data['ins_type'] = 'Comprehensive';
        
        // Calculate pricing based on opt_ values
        $pricingData = $this->calculatePricing($data);
        $data = array_merge($data, $pricingData);
        
        // Create or update customer record
        $this->createOrUpdateCustomer($data);
        
        return $data;
    }

    /**
     * Create or update customer record with insurance data
     */
    private function createOrUpdateCustomer(array $data): void
    {
        if (!isset($data['qid']) || !isset($data['name'])) {
            return;
        }

        $customerData = [
            'fullname' => $data['name'],
            'qid' => $data['qid'],
            'mobile_no' => $data['mobile'] ?? null,
            'email' => $data['email'] ?? null,
            'owner_type' => $data['owner_type'] ?? null,
            'active' => 1,
        ];

        // Try to find existing customer by QID
        $customer = Customer::where('qid', $data['qid'])->first();

        if ($customer) {
            // Update existing customer with new information
            $customer->update($customerData);
        } else {
            // Create new customer
            Customer::create($customerData);
        }
    }

    /**
     * Calculate pricing based on opt_ values using InsuranceHelper
     */
    private function calculatePricing(array $data): array
    {
        // Check if we have the required opt_ values
        if (!isset($data['opt_1']) || !$data['opt_1']) {
            return [
                'base_amount' => 0,
                'pass_amount' => 0,
                'opt_amount' => 0,
                'discount' => 0,
                'total_amount' => 0,
            ];
        }

        $pricingData = [
            'opt_1' => $data['opt_1'],
            'opt_2' => $data['opt_2'] ?? null,
            'opt_3' => $data['opt_3'] ?? null,
            'opt_4' => $data['opt_4'] ?? null,
            'passengers' => $data['passengers'] ?? 1,
        ];

        $helper = new InsuranceHelper();
        return $helper->getPrice($pricingData);
    }
}
