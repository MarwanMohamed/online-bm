<?php

namespace App\Http\Controllers;

use App\Helpers\InsuranceHelper;
use App\Models\Area;
use App\Models\Blacklist;
use App\Models\Company;
use App\Models\Discount;
use App\Models\Insurance;
use App\Models\Optional;
use App\Models\Thirdparty;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleBodyType;
use App\Models\VehicleColor;
use App\Models\VehicleModel;
use App\Models\VehicleModelDetails;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function index()
    {
        return view('site.insurance.new');
    }

    public function getVhlModels($id)
    {
        return VehicleModel::where('make_id', $id)->get();
    }

    public function getVhlDetails($make)
    {
        $model = VehicleModel::where('model_name', $make)->first();

        $results = VehicleModelDetails::where('model_id', $model->id)
            ->selectRaw('year, seats, cylinder')
            ->distinct()
            ->get();

        $years = [];
        $seats = [];
        $cylinders = [];

        foreach ($results as $result) {
            $years[] = $result->year;
            $seats[] = $result->seats;
            $cylinders[] = $result->cylinder;
        }
        sort($years);
        return [
            'years' => $years,
            'seats' => array_unique($seats),
            'cylinders' => array_unique($cylinders),
        ];
    }

    public function thirdparty()
    {
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
        return view('site.insurance.thirdparty', compact('title', 'qb_opt', 'areas', 'vhlTypes', 'make', 'colors', 'bodyTypes', 'companies', 'discount'));
    }

    public function getPrice($id)
    {
        return Thirdparty::where('parent_id', $id)->get();
    }

    public function confirm(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'vhl_chassis' => 'required',
            'opt_1' => 'required',
            'com_id' => 'required',
            'vhl_reg_no' => 'required',
        ]);

        $isBlacklisted = $this->isBlacklisted(($request->input('owner_type') == 'I') ? $request->input('qid') : $request->input('eid'));
        if (!empty($isBlacklisted)) {
            return redirect()->back()->with(['error' => "You're blacklisted, Please contact support"]);
        }
        $data = array(
            'owner_type' => $request->input('owner_type'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'area' => $request->input('area'),
            'vhl_make' => $request->input('vhl_make'),
            'vhl_class' => $request->input('vhl_class'),
            'vhl_year' => $request->input('vhl_year'),
            'vhl_color' => $request->input('vhl_color'),
            'vhl_body_type' => $request->input('vhl_body_type'),
            'vhl_chassis' => $request->input('vhl_chassis'),
            'vhl_engine' => $request->input('vhl_engine'),
            'vhl_reg_no' => $request->input('vhl_reg_no'),
            'com_id' => $request->input('com_id'),
            'opt_1' => $request->input('opt_1'),
            'opt_2' => $request->input('opt_2'),
            'opt_3' => (!empty($request->input('opt_3'))) ? $request->input('opt_3') : 0,
            'opt_4' => (!empty($request->input('opt_4'))) ? $request->input('opt_4') : 0,
            'add_opt' => $request->input('add_opt'),
            'passengers' => (!empty($request->input('passengers'))) ? $request->input('passengers') : 0,
            'pb_no' => $request->input('pb_no'),
            'status' => 4
        );
        $startDt = strtotime(str_replace('/', '-', $request->input('start_date')));
        $endDt = strtotime('+1 year -1 day', $startDt);
        $data['start_date'] = date("Y-m-d", $startDt);
        $data['end_date'] = date("Y-m-d", $endDt);
        $price = (new InsuranceHelper)->getPrice($data);
        $data = array_merge($data, $price);
        $data['qid'] = ($data['owner_type'] == 'I') ? $request->input('qid') : $request->input('eid');

        $data['policy_id'] = (new InsuranceHelper)->getUniqueRefNo();
        $data['read'] = 0;
        Insurance::create($data);
        $recipients = User::all();

        Notification::make()
            ->title('Third Party Insurance Created')
            ->sendToDatabase($recipients);
        $title = "Third Party Insurance - Confirmation";
        $opt_1 = $this->getVehicleType($data['opt_1']);
        $opt_2 = $this->getVehicleType($data['opt_2']);
        $opt_3 = $this->getVehicleType($data['opt_3']);
        $opt_4 = $this->getVehicleType($data['opt_4']);
        $add_opt = Optional::where('id', $data['add_opt'])->first();
        $area = Area::where('id', $data['area'])->first();
        $data['company'] = Company::where('id', $data['com_id'])->first()->name;
        return view('site.insurance.confirm', compact('title', 'data', 'area', 'opt_1', 'opt_2', 'opt_3', 'opt_4', 'add_opt'));
    }

    public function getVehicleType($id)
    {
        return Thirdparty::where('id', $id)->first();
    }

    public function allowQid(Request $request)
    {
        $qid = $request->qid;
        if (!$qid)
            $qid = $request->eid;
        if (!$qid)
            die('Invalid Request');
        $result = $this->isBlacklisted($qid);
        if (empty($result))
            die('true');
        else
            die('false');
    }

    private function isBlacklisted($qid)
    {
        return Blacklist::where('qid', $qid)->first();
    }

    public function comprehensive()
    {
        $title = "Comprehensive Insurance";
        $qb_opt = Optional::where('deleted', 0)->where('parent_id', 3)->get();
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
        return view('site.insurance.comprehensive', compact('title', 'areas', 'make', 'colors', 'bodyTypes', 'companies', 'qb_opt', 'vhlTypes', 'discount'));
    }
}