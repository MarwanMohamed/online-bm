<?php

namespace App\Filament\Resources\InsuranceResource\Pages;

use App\Filament\Resources\InsuranceResource;
use App\Helpers\InsuranceHelper;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Insurance;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInsurance extends CreateRecord
{
    protected static string $resource = InsuranceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        do {
            $key = sprintf('QBT%06d', rand(1, 999999));
            $exits = Insurance::where('policy_id', $key)->first();
        } while ($exits);
        $data['policy_id'] = $key;
        $data['end_date'] = Carbon::parse($data['start_date'])->addYear()->subDay();
        if (!isset($data['opt_3'])) {
            $data['opt_3'] = 0;
        }

        if (!isset($data['opt_4'])) {
            $data['opt_4'] = 0;
        }

        if (!isset($data['passengers'])) {
            $data['passengers'] = 0;
        }
        $price = (new InsuranceHelper())->getPrice($data);

        // Create or update customer record
        $this->createOrUpdateCustomer($data);

        createLog('New Insurance ' . $data['policy_id'] . ' Created by User:' . Auth::user()->name);
        return array_merge($data, $price);
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
