<?php

namespace App\Filament\Resources\InsuranceResource\Pages;

use App\Filament\Resources\InsuranceResource;
use App\Helpers\InsuranceHelper;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Insurance;
use App\Models\Thirdparty;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditInsurance extends EditRecord
{
    protected static string $resource = InsuranceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        if ($this->record->read == 0) {
            $this->record->update(['read' => 1]);
        }
        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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

        createLog('Insurance ' . $this->record->policy_id . ' Updated by User:' . Auth::user()->name);
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
