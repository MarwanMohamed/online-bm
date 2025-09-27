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
