<?php

namespace App\Filament\Resources\InsuranceResource\Pages;

use App\Filament\Resources\InsuranceResource;
use App\Models\Discount;
use App\Models\Insurance;
use App\Models\Thirdparty;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

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
        $price = $this->getPrice($data);

        return array_merge($data, $price);
    }

    public function getPrice($data)
    {
        if ($data['opt_4'] > 0) {
            $priceId = $data['opt_4'];
        } elseif ($data['opt_3'] > 0) {
            $priceId = $data['opt_3'];
        } elseif ($data['opt_2'] > 0) {
            $priceId = $data['opt_2'];
        }
        $discount = $this->getDiscount();
        $priceData = Thirdparty::where('id', $priceId)->first();
        $data = array(
            'base_amount' => $priceData->base,
            'pass_amount' => ($priceData->passenger > 0) ? ($data['passengers'] * $priceData->passenger) : 0,
            'opt_amount' => 0,
        );
        $subTotal = $data['base_amount'] + $data['pass_amount'] + $data['opt_amount'];
        $data['discount'] = $subTotal * $discount;
        $data['total_amount'] = $data['base_amount'] + $data['pass_amount'] + $data['opt_amount'] - $data['discount'];
        return $data;
    }

    public function getDiscount(): float|int
    {
        $discount = Discount::where('typeId', '1')->first();
        if ($discount->status == '1')
            return $discount->percent / 100;
        else
            return 0;
    }
}
