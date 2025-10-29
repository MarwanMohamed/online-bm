@extends('site.layout')
@section('content')
<div class="breadcrumb">
  <div class="container">
    <div class="row">
      <nav>
        <a class="breadcrumb-item" href="/"><i class="fa fa-home"></i> Home&nbsp;<i class="fa fa-angle-right"></i></a>
        <a class="breadcrumb-item" href="/insurance/new">New Insurance&nbsp;<i class="fa fa-angle-right"></i></a>
        <a class="breadcrumb-item" href="/insurance/thirdparty">Third Party Insurance&nbsp;<i class="fa fa-angle-right"></i></a>
        <span class="breadcrumb-item active">Confirmation</span>
      </nav>
    </div>
  </div>
</div>
<div id="free-promo" style="margin: 30px;">
  <div class="container" style="border: 2px solid #b50555;border-radius: 10px;padding: 20px 40px;">
    <h6 style="color:#b50555">Third Party Motor Insurance Policy</h6><br>
    <div class="row">
      <div class="col-md-6">
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label" style="color:#b50555"><strong>Reference #</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><strong id="policy_id"><?= $data['policy_id'] ?></strong></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Owner Type</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= ($data['owner_type'] == "I") ? 'Individual' : 'Organisation' ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Name / Company</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['name'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Email ID</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['email'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Mobile #</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['mobile'] ?></label>
          </div>
        </div>
      </div>
      <div class="col-md-6">

				<div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label" style="color:#b50555"><strong>Insurance Company</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><strong><?= $data['company'] ?></strong></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Qatar ID/Est. ID</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['qid'] ?></label>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Area</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $area->area ?? null ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Phone #</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['phone'] ?></label>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <hr>
      <div class="col-md-6">
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Vehicle Make</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['vhl_make'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Vehicle Model</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['vhl_class'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Vehicle Plate</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['vhl_reg_no'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Colour</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['vhl_color'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Body Type</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= isset($data['vhl_body_type']) ? $data['vhl_body_type'] : 'N/A' ?></label>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Chassis #</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['vhl_chassis'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Engine #</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['vhl_engine'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Year of Manufacture</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['vhl_year'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Start date</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['start_date'] ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>End date</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $data['end_date'] ?></label>
          </div>
        </div>
        <!--<div class="form-group row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Registration #</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"></label>
          </div>
        </div>-->
      </div>
    </div>
    <div class="row">
      <hr/>
      <div class="col-md-6">
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Type of vehicle</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $opt_1->value ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong style="text-transform: capitalize;"><?= $opt_1->name ?></strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $opt_2->value ?></label>
          </div>
        </div>
        <div class="row" <?= ($data['opt_3'] > 0) ? '' : 'style="display:none"'; ?> >
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong style="text-transform: capitalize;"><?= $opt_2->name ?></strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $opt_3->value ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Passengers</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= ($data['passengers'] > 0) ? $data['passengers'] : 'No'; ?></label>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row" <?= ($data['opt_4'] > 0) ? '' : 'style="display:none"'; ?> >
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong style="text-transform: capitalize;"><?= $opt_3->name ?></strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= $opt_4->value ?? null ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Replacement Car</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= ($data['add_opt'] > 0) ? $add_opt->name : 'No'; ?></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label for="example-text-input" class="col-form-label"><strong>Discount</strong></label>
          </div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-5">
            <label for="example-text-input" class="col-form-label"><?= ($data['discount'] > 0) ? $data['discount'] : 'No'; ?></label>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <hr>
      <div class="col-sm-12">
        <label style="color: #b50555" for="termsNdCondn" class="col-form-label"><strong>
        <input id="termsNdCondn" type="checkbox"> I have read and agree to the Terms and Conditions. I hereby agree that i am fully responsible for the information furnished above. I also understand that any false information will lead to non-issuance of my policy.</strong></label>
      </div>
    </div>
    <div class="row" style="margin-bottom: 20px;">
      <hr>
      <div class="col-md-12">
        <div class="text-center">
          <div class="container bordered-container" style="max-width:500px">
            <div class="form-group">
              <h6><strong>Total amount to pay</strong></h6>
              <hr>
              <div>
                <span>Insurance Premium : QAR <strong><?= number_format($data['base_amount'], 2) ?></strong></span><br>
                <span>Passenger price : QAR <strong><?= number_format($data['pass_amount'], 2) ?></strong></span><br>
                <span <?= ($data['add_opt'] > 0) ? '' : 'style="display: none;"' ?> >Replacement Car : QAR <strong><?= number_format($data['opt_amount'], 2) ?></strong></span><br>
                <span style="display: none;">Road side Assistance : QAR <strong><?= number_format($data['opt_amount'], 2) ?></strong></span>
                <span>Discount : QAR <strong><?= number_format($data['discount'], 2) ?></strong></span>
              </div>
              <hr>
              <h4>Grand Total : QAR <span id="totalPrice"><?= number_format($data['total_amount'], 2) ?></span></h4>
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1"></label>
              <input type="button" class="btn btn-dark" name="cancel" id="cancel" value="Cancel">
              <input  type="button" class="btn btn-common" name="submit" id="submit" value="Next">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@stop

@section('script')
<script type="text/javascript">
  //disable back button browser
  history.pushState(null, null, location.href);
  window.onpopstate = function () {
    history.go(1);
  };
  $(document).ready(function () {
    //cancel button click------------------------------------------
    $("#cancel").click(() => {
      window.location.href = "/";
    });
    $("#submit").click(() => {
      if ($("#termsNdCondn").prop('checked') == false) {
         alert('Please read and agree with the Terms and Conditions');
         return false;
      }
      // $('#submit').prop('disabled', true);
      $('#submit').val('Please wait...');
      window.location.href = "/payment/selectpayment?nmi_total_price="+ $('#totalPrice').text() + '&nmi_referno='+ $('#policy_id').text();
    });
  });
</script>
@stop
