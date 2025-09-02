<?php

namespace App\Filament\Resources\ComprehensiveResource\Pages;

use App\Filament\Resources\ComprehensiveResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComprehensive extends CreateRecord
{
    protected static string $resource = ComprehensiveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ins_type'] = 'Comprehensive';
        
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
}
