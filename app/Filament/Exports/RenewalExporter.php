<?php

namespace App\Filament\Exports;

use App\Models\Insurance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class RenewalExporter extends Exporter
{
    protected static ?string $model = Insurance::class;

    public static function getColumns(): array
    {
        return [
//            ExportColumn::make('name'),  //->label('Name (الاسم)'),
//            ExportColumn::make('qid'),  //->label('ID (رقم البطاقة)'),
//            ExportColumn::make('mobile'),  //->label('Mobile (رقم الجوال)'),
//            ExportColumn::make('phone'),  //->label('Phone (رقم الهاتف)'),
//            ExportColumn::make('email'),  //->label('Email (البريد الالكترونى)'),
//            ExportColumn::make('vhl_reg_no'),  //->label('plate No (رقم اللوحة)'),
//            ExportColumn::make('opt_1'),  //->label('type of vehicle (نوع السيارة)'),
//            ExportColumn::make('vhl_class'),  //->label('model (الطراز)'),
//            ExportColumn::make('vhl_year'),  //->label('year (سنة الصنع)'),
//            ExportColumn::make('vhl_color'),  //->label('color (اللون)'),
//            ExportColumn::make('vhl_body_type'),  //->label('body type (شكل الهيكل)'),
//            ExportColumn::make('opt_3'),  //->label('cylinder (عدد السلندرات)'),
//            ExportColumn::make('vhl_engine'),  //->label('engine No (رقم المحرك)'),
//            ExportColumn::make('vhl_chassis'),  //->label('Chassis No (رقم الشاصي)'),
//            ExportColumn::make('passengers'),  //->label('No. of seats (عدد الركاب)'),
//            ExportColumn::make('start_date'),  //->label('start_date (التأمين)'),
//            ExportColumn::make('end_date'),  //->label('end_date (فترة)'),
//            ExportColumn::make('base_amount'),  //->label('Permium (مبلغ التأمين)'),
//            ExportColumn::make('discount'),  //->label('Discount (خصم)'),
//            ExportColumn::make('total_amount'),  //->label('Net (الصافى)'),
//            ExportColumn::make('vhl_make'),  //->label('Make'),

            ExportColumn::make('date')->label('Date'),
            ExportColumn::make('getStatus.status')->label('Reference #'),
            ExportColumn::make('name')->label('Name'),
            ExportColumn::make('policy status')->label('Policy Status'),
            ExportColumn::make('qid')->label('Qatar ID'),
            ExportColumn::make('ad_verified')->label('Commit'),
            ExportColumn::make('ad_verify_date')->label('Commit By'),

        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return Insurance::query()
            ->selectRaw('0 as id')
            ->limit(1);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your renewal export has completed';


        return $body;
    }
}


