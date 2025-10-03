<?php

namespace App\Http\Controllers;

use App\Helpers\InsuranceHelper;
use App\Models\Area;
use App\Models\Company;
use App\Models\Discount;
use App\Models\Insurance;
use App\Models\Optional;
use App\Models\Thirdparty;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleBodyType;
use App\Models\VehicleColor;
use DateTime;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenewController extends Controller
{
    public function generateRenewal($id)
    {
        $insurance = $this->getInsuranceRow($id);
        if (!$insurance) {
            return response()->json(['error' => 'Insurance not found'], 404);
        }

        $title = "Thirdparty Insurance";
        $qb_opt = Optional::where('deleted', 0)
            ->where('parent_id', 3)->get();
        $areas = Area::all();
        $vhlTypes = Thirdparty::where('parent_id', 0)->get();
        $make = Vehicle::all();
        $colors = VehicleColor::where('deleted', 0)->get();
        $bodyTypes = VehicleBodyType::where('active', 1)->get();
        $companies = Company::where('deleted', 0)->orderBy('priority', 'asc')->where('active', 1)->get();
        $discount = Discount::where('id', '1')->first();
        if ($discount->status == '1') {
            $discount = $discount->percent / 100;
        } else {
            $discount = 0;
        }
        $opt_1 = $this->getVehicleType($insurance->opt_1);
        $opt_2 = $this->getVehicleType($insurance->opt_2);
        $opt_3 = $this->getVehicleType($insurance->opt_3);
        $opt_4 = $this->getVehicleType($insurance->opt_4);
        $add_opt = Optional::where('id', $insurance->add_opt)->first();

        return view('site.insurance.renew', compact('title', 'qb_opt', 'areas', 'vhlTypes', 'make', 'colors', 'bodyTypes', 'companies', 'discount', 'insurance', 'opt_1', 'opt_2', 'opt_3', 'opt_4', 'add_opt'));

    }

    public function renew()
    {
        $footerchk = 1;
        return view('site.renew.renew', compact('footerchk'));
    }

    public function getPolicyDetails(Request $request)
    {
        $idno = $request->input('search_with3');
        $today = date('Y-m-d');
        $nextMonth = new DateTime('+30 days');

        $results = Insurance::whereBetween('expiry_date', [$today . " 00:00:00.000000' AND '", $nextMonth->format('Y-m-d') . " 23:59:59.999999'"])
            ->where('active', 1)
            ->where(function ($query) use ($idno) {
                $query->where('qid', $idno)
                    ->orWhere('vhl_reg_no', $idno)
                    ->orWhere('policy_id', $idno);
            })
            ->get();
        if ($results) {
            $content = view('site.renew.list', compact('results'))->render();
            return response()->json([$content]);
        } else {
            return 0;
        }
    }

    public function renewView(Request $request)
    {
        $decryptId = base64_decode($request->input('token'));
        $data['insurance'] = $this->getInsuranceRow($decryptId);
        $data['qb_opt'] = $this->getQbimaOpt(3);
        $data['add_opt'] = $this->getQbimaOptDetails($data['insurance']->add_opt);
        $data['price'] = (new InsuranceHelper())->getPrice([
            'opt_4' => $data['insurance']->opt_4,
            'opt_3' => $data['insurance']->opt_3,
            'opt_2' => $data['insurance']->opt_2,
            'opt_1' => $data['insurance']->opt_1,
            'passengers' => $data['insurance']->passengers,
            'add_opt' => $data['insurance']->add_opt
        ]);

        if ($data['insurance']) {
            return view('site.renew.view', compact('data', 'decryptId'));
        }
    }

    public function renewConfirm(Request $request)
    {
        $policy_id = $request->input('policy_id');
        $ids = explode("-", $policy_id);

        $new_policy_id = '';
        $insData = Insurance::where('policy_id', $policy_id)->first();

        if (count($ids) == 2) {
            $new_policy_id = $ids[0] . '-' . (1 + $ids[1]);
        } else {
            $new_policy_id = $ids[0] . '-1';
        }
        $add_opt = $request->input('add_opt');
        $data = [
            'owner_type' => $insData->owner_type,
            'name' => $insData->name,
            'qid' => $insData->qid,
            'user_id' => $insData->user_id,
            'phone' => $insData->phone,
            'mobile' => $insData->mobile,
            'email' => $insData->email,
            'area' => $insData->area,
            'vhl_make' => $insData->vhl_make,
            'vhl_class' => $insData->vhl_class,
            'vhl_year' => $insData->vhl_year,
            'vhl_color' => $insData->vhl_color,
            'vhl_body_type' => isset($insData->vhl_body_type) ? $insData->vhl_body_type : null,
            'vhl_chassis' => $insData->vhl_chassis,
            'vhl_engine' => $insData->vhl_engine,
            'vhl_reg_no' => $insData->vhl_reg_no,
            'com_id' => $insData->com_id,
            'opt_1' => $insData->opt_1,
            'opt_2' => $insData->opt_2,
            'opt_3' => $insData->opt_3,
            'opt_4' => $insData->opt_4,
            'add_opt' => $add_opt,
            'passengers' => $insData->passengers,
            'pb_no' => $insData->pb_no,
            'status' => 4
        ];
        $startDt = strtotime($insData->expiry_date . '+1 days');
        $endDt = strtotime('+1 year -1 day', $startDt);
        $data['start_date'] = date("Y-m-d", $startDt);
        $data['end_date'] = date("Y-m-d", $endDt);
        $price = (new InsuranceHelper())->getPrice($data);
        $data = array_merge($data, $price);
        $data['policy_id'] = $new_policy_id;

        $data['read'] = 0;

        Insurance::create($data);


        $opt_1 = $this->getVehicleType($data['opt_1']);
        $opt_2 = $this->getVehicleType($data['opt_2']);
        $opt_3 = $this->getVehicleType($data['opt_3']);
        $opt_4 = $this->getVehicleType($data['opt_4']);
        $recipients = User::all();
        Notification::make()->title('Third Party Insurance Created')->sendToDatabase($recipients);
        $data['company'] = Company::where('id', $data['com_id'])->first()->name;
        $area = Area::where('id', $data['area'])->first();

        $title = "Third Party Insurance - Confirmation";
        return view('site.insurance.confirm', compact('data', 'title', 'area', 'opt_1', 'opt_2', 'opt_3', 'opt_4'));
    }

    private function getVehicleType($id)
    {
        return Thirdparty::where('id', $id)->first();
    }

    private function getQbimaOpt($id)
    {
        return Optional::where('parent_id', $id)->where('deleted', 0)->get();
    }

    private function getQbimaOptDetails($id)
    {
        return Optional::where('id', $id)->first();
    }

    private function getInsuranceRow($refId = NULL)
    {
        return DB::table('insurances as i')
            ->select('i.*', 'c.name as com_name', 'a.area as area_name', 's.status as ins_status')
            ->leftJoin('companies as c', 'c.id', '=', 'i.com_id')
            ->leftJoin('areas as a', 'a.id', '=', 'i.area')
            ->leftJoin('statuses as s', 's.id', '=', 'i.status')
            ->where('i.policy_id', $refId)
            ->first();
    }
}