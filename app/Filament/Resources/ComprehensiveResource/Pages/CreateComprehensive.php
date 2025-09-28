<?php

namespace App\Filament\Resources\ComprehensiveResource\Pages;

use App\Filament\Resources\ComprehensiveResource;
use App\Models\Customer;
use App\Models\Insurance;
use App\Helpers\InsuranceHelper;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComprehensive extends CreateRecord
{
    protected static string $resource = ComprehensiveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate unique policy_id
        do {
            $key = sprintf('QBT%06d', rand(1, 999999));
            $exits = Insurance::where('policy_id', $key)->first();
        } while ($exits);
        $data['policy_id'] = $key;
        
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
            'owner_type' => $data['owner_type'] ?? "O",
            'active' => 1,
        ];

        $customer = Customer::where('qid', $data['qid'])->first();

        if ($customer) {
            $customer->update($customerData);
        } else {
            Customer::create($customerData);
        }
    }
}
