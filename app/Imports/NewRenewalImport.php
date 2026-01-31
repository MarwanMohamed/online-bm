<?php

namespace App\Imports;

use App\Models\Insurance;
use Carbon\Carbon;
use EightyNine\ExcelImport\EnhancedDefaultImport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class NewRenewalImport extends EnhancedDefaultImport
{

    protected array $map = [
        'name'           => 'name_alasm',
        'id'             => 'id_rkm_albtak',
        'mobile'         => 'mobile_rkm_algoal',
        'phone'          => 'phone_rkm_alhatf',
        'email'          => 'email_albryd_alalktron',
        'plate_no'       => 'plate_no_rkm_alloh',
        'type_of_vehicle'=> 'type_of_vehicle_noaa_alsyar',
        'model'          => 'model_altraz',
        'year'           => 'year_sn_alsnaa',
        'color'          => 'color_allon',
        'body_type'      => 'body_type_shkl_alhykl',
        'engine_no'      => 'engine_no_rkm_almhrk',
        'chassis_no'     => 'chassis_no_rkm_alshasy',
        'no_of_seats'    => 'no_of_seats_aadd_alrkab',
        'start_date'     => 'start_date_altamyn',
        'end_date'       => 'end_date_ftr',
        'permium'        => 'permium_mblgh_altamyn',
        'discount'       => 'discount_khsm',
        'net'            => 'net_alsaf',
        'make'           => 'make',
    ];


    public function collection(Collection $rows)
    {
        $userId = Auth::id();
        foreach ($rows as $row) {
            $name = trim($row[$this->map['name']] ?? '');

            if ($name === '') {
                continue; // skip empty row
            }

            $startDateRaw = $row[$this->map['start_date']] ?? null;
            $endDateRaw   = $row[$this->map['end_date']] ?? null;

            $startDate = $startDateRaw
                ? Carbon::createFromFormat('d/m/Y', $startDateRaw)->format('Y-m-d')
                : null;

            $endDate = $endDateRaw
                ? Carbon::createFromFormat('d/m/Y', $endDateRaw)->format('Y-m-d')
                : null;

            Insurance::create([
                'user_id'        => $userId,

                'name'           => $row[$this->map['name']] ?? null,
                'qid'            => $row[$this->map['id']] ?? null,
                'mobile'         => $row[$this->map['mobile']] ?? null,
                'phone'          => $row[$this->map['phone']] ?? null,
                'email'          => $row[$this->map['email']] ?? null,

                'vhl_reg_no'     => $row[$this->map['plate_no']] ?? null,
                'vhl_class'      => $row[$this->map['type_of_vehicle']] ?? null,
                'pb_no'          => 'renewal',//$row[$this->map['model']] ?? null,
                'vhl_year'       => $row[$this->map['year']] ?? null,
                'vhl_color'      => $row[$this->map['color']] ?? null,
                'vhl_body_type'  => $row[$this->map['body_type']] ?? null,

                'vhl_engine'     => $row[$this->map['engine_no']] ?? null,
                'vhl_chassis'    => $row[$this->map['chassis_no']] ?? null,
                'passengers'     => $row[$this->map['no_of_seats']] ?? null,

                'start_date'     => $startDate,
                'end_date'       => $endDate,

                'premium'        => $row[$this->map['permium']] ?? null,
                'discount'       => $row[$this->map['discount']] ?? 0,
                'total_amount'   => $row[$this->map['net']] ?? null,

                'vhl_make'       => $row[$this->map['make']] ?? null,

                // defaults
                'ins_type'       => 'Comprehensive',
                'owner_type'     => 'I',
                'active'         => 1,
                'status'         => 1,
                'excelimp'       => 1,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.name'       => 'required|string',
            '*.id'         => 'required',
            '*.plate_no'   => 'required',
            '*.start_date' => 'required|date',
            '*.end_date'   => 'required|date|after:start_date',
        ];
    }
}
