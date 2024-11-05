<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Blacklist;
use App\Models\Company;
use App\Models\Discount;
use App\Models\Optional;
use App\Models\Thirdparty;
use App\Models\Vehicle;
use App\Models\VehicleColor;
use App\Models\VehicleModel;
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

    public function thirdparty()
    {
        $title = "Thirdparty Insurance";
        $qb_opt = Optional::where('deleted', 0)
            ->where('parent_id', 3)->get();
        $areas = Area::all();
        $vhlTypes = Thirdparty::where('parent_id', 0)->get();
        $make = Vehicle::all();
        $colors = VehicleColor::where('deleted', 0)->get();
        $companies = Company::where('deleted', 0)->orderBy('priority', 'asc')->where('active', 1)->get();
        $discount = Discount::where('id', '1')->first();
        if ($discount->status == '1') {
            $discount = $discount->percent / 100;
        } else {
            $discount = 0;
        }
        return view('site.insurance.thirdparty', compact('title', 'qb_opt', 'areas', 'vhlTypes', 'make', 'colors', 'companies', 'discount'));
    }

    public function getPrice($id)
    {
        return Thirdparty::where('parent_id', $id)->get();
    }

//    public function addThirdparty(Request $request)
//    {
//        $this->load->model('insuranceModel');
//    $data['title'] = "Third Party Insurance - Information";
//    $data['heading'] = $data['title'];
//    $this->form_validation->set_rules('name', 'Fullname', 'required');
//    $this->form_validation->set_rules('email', 'Email', 'required');
//    $this->form_validation->set_rules('mobile', 'Mobile No', 'required');
//    $this->form_validation->set_rules('vhl_chassis', 'Chasis No', 'required');
//    $this->form_validation->set_rules('opt_1', 'Body Type', 'required');
//    $this->form_validation->set_rules('com_id', 'Insurance Company', 'required');
//    $this->form_validation->set_rules('vhl_reg_no', 'Vehicle Type', 'required');
//    $isBlacklisted = $this->insuranceModel->isBlacklisted(($this->input->post('owner_type') == 'I')? $this->input->post('qid'): $this->input->post('eid'));
//    if(!empty($isBlacklisted)){
//      echo "You're blacklisted, Please contact support";
//    } else if ($this->form_validation->run() == FALSE) {
//      // $data['errors'] = validation_errors();
//      echo validation_errors();
//      // $data['insCompany'] = $this->thirdPartyModel->getInsuranceCompanies();
//      // $data['bodyParnet'] = $this->thirdPartyModel->getParentBodyTypes();
//      // $data['area'] = $this->insuranceModel->getAllAreas();
//      // $this->load->view('thirdparty/thirdp_insurance', $data);
//      // redirect('home/thirdPartyInsurance');
//    } else {
//      $this->session->unset_userdata('session');
//      // user_id
//      $this->session->unset_userdata('dis_conf_session'); //display breakups on confirmation
//      $data = array(
//        'owner_type' => $this->input->post('owner_type'),
//        'name' => $this->input->post('name'),
//        'phone' => $this->input->post('phone'),
//        'mobile' => $this->input->post('mobile'),
//        'email' => $this->input->post('email'),
//        'area' => $this->input->post('area'),
//        'vhl_make' => $this->input->post('vhl_make'),
//        'vhl_class' => $this->input->post('vhl_class'),
//        'vhl_year' => $this->input->post('vhl_year'),
//        'vhl_color' => $this->input->post('vhl_color'),
//        'vhl_chassis' => $this->input->post('vhl_chassis'),
//        'vhl_engine' => $this->input->post('vhl_engine'),
//        'vhl_reg_no' => $this->input->post('vhl_reg_no'),
//        'com_id' => $this->input->post('com_id'),
//        'opt_1' => $this->input->post('opt_1'),
//        'opt_2' => $this->input->post('opt_2'),
//        'opt_3' => (!empty($this->input->post('opt_3'))) ? $this->input->post('opt_3') : 0,
//        'opt_4' => (!empty($this->input->post('opt_4'))) ? $this->input->post('opt_4') : 0,
//        'add_opt' => $this->input->post('add_opt'),
//        'passengers' => (!empty($this->input->post('passengers'))) ? $this->input->post('passengers') : 0,
//        'pb_no' => $this->input->post('pb_no'),
//        'status' => 4
//      );
//      $startDt = strtotime(str_replace('/', '-', $this->input->post('start_date')));
//      $endDt = strtotime('+1 year -1 day', $startDt);
//      $data['start_date'] = date("Y-m-d", $startDt);
//      $data['end_date'] = date("Y-m-d", $endDt);
//      $price = $this->insuranceModel->getPrice($data);
//      $data = array_merge($data, $price);
//      $data['qid'] = ($data['owner_type'] == 'I')? $this->input->post('qid'): $this->input->post('eid');
//      $data['policy_id'] = $this->insuranceModel->getUniqueRefNo();
//      $id = $this->insuranceModel->insertThridParty($data);
//      $this->session->set_userdata('policyData', $data);
//      redirect('insurance/confirm');
//    }
//
//    public function allowQid(Request $request)
//    {
//        $qid = $request->qid;
//        if (!$qid)
//            $qid = $request->eid;
//        if (!$qid)
//            die('Invalid Request');
//        $result = $this->isBlacklisted($qid);
//        if (empty($result))
//            die('true');
//        else
//            die('false');
//    }
//
//    private function isBlacklisted($qid)
//    {
//        return Blacklist::where('qid', $qid)->first();
//    }
//
    public function comprehensive()
    {
        $title = "Comprehensive Insurance";
        $qb_opt = Optional::where('deleted', 0)->where('parent_id', 3)->get();
        $areas = Area::all();
        $vhlTypes = Thirdparty::where('parent_id', 0)->get();
        $make = Vehicle::all();
        $colors = VehicleColor::where('deleted', 0)->get();
        $companies = Company::where('deleted', 0)->orderBy('priority', 'asc')->where('active', 1)->get();
        $discount = Discount::where('id', '1')->first();
        if ($discount->status == '1') {
            $discount = $discount->percent / 100;
        } else {
            $discount = 0;
        }
        return view('site.insurance.comprehensive', compact('title', 'areas', 'make', 'colors', 'companies', 'qb_opt', 'vhlTypes', 'discount'));
    }
}