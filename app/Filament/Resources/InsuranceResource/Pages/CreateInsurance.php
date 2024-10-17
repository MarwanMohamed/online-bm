<?php

namespace App\Filament\Resources\InsuranceResource\Pages;

use App\Filament\Resources\InsuranceResource;
use App\Helpers\InsuranceHelper;
use App\Models\Insurance;
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
        $price = (new InsuranceHelper())->getPrice($data);

        return array_merge($data, $price);
    }
}
