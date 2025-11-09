<?php

namespace App\Helpers;

use App\Models\Discount;
use App\Models\Insurance;
use App\Models\Optional;
use App\Models\Thirdparty;

class InsuranceHelper
{
    public function getPrice($data): array
    {
        $priceId = 0;
        if (isset($data['opt_4']) && $data['opt_4'] > 0) {
            $priceId = $data['opt_4'];
        } elseif (isset($data['opt_3']) && $data['opt_3'] > 0) {
            $priceId = $data['opt_3'];
        } elseif (isset($data['opt_2']) && $data['opt_2'] > 0) {
            $priceId = $data['opt_2'];
        }
        $discount = $this->getDiscount();
        if (isset($data['add_opt'])) {
            $optional = Optional::find($data['add_opt']);
        }
        $priceData = Thirdparty::where('id', $priceId)->first();
        $data = [
            'base_amount' => $priceData->base,
            'pass_amount' => ($priceData->passenger > 0) ? ($data['passengers'] * $priceData->passenger) : 0,
            'opt_amount' => 0,
        ];
        $subTotal = $data['base_amount'] + $data['pass_amount'] + $data['opt_amount'];
        $data['discount'] = $subTotal * $discount;
        $data['total_amount'] = $data['base_amount'] + $data['pass_amount'] + $data['opt_amount'] - $data['discount'];

        if (isset($optional)) {
            $data['total_amount'] = $data['total_amount'] + $optional->amount;
        }
        return $data;
    }

    public function getDiscount(): float|int
    {
        $discount = Discount::where('id', '1')->first();
        if ($discount && $discount->status == '1')
            return $discount->percent / 100;
        else
            return 0;
    }

    public function getUniqueRefNo()
    {
        do {
            $key = sprintf('QBT%06d', rand(1, 999999));
            $exits = Insurance::where('policy_id', $key)->first();
        } while ($exits);
        return $key;
    }
}
