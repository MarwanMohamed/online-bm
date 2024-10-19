<?php

namespace App\Helpers;

use App\Models\Discount;
use App\Models\Thirdparty;

class InsuranceHelper
{
    public function getPrice($data): array
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
        $data = [
            'base_amount' => $priceData->base,
            'pass_amount' => ($priceData->passenger > 0) ? ($data['passengers'] * $priceData->passenger) : 0,
            'opt_amount' => 0,
        ];
        $subTotal = $data['base_amount'] + $data['pass_amount'] + $data['opt_amount'];
        $data['discount'] = $subTotal * $discount;
        $data['total_amount'] = $data['base_amount'] + $data['pass_amount'] + $data['opt_amount'] - $data['discount'];
        return $data;
    }

    public function getDiscount(): float|int
    {
        $discount = Discount::where('id', '1')->first();
        if ($discount->status == '1')
            return $discount->percent / 100;
        else
            return 0;
    }
}
