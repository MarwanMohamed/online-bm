@extends('site.layout')
@section('content')
<div id="free-promo" style="margin: 30px;">
  <div class="text-center" style="margin-top: 4%; "><h2>Motor Insurance Policy (وثيقة تامين السيارة)</h2></div>
  <div class="container">
    <div class="row text-center">
      <div style="min-height: 250px;">
        <div style="margin-top: 4%;">
          <!-- Code is here -->
          <div class="col-md-4">
            <a class="newInsurance" href="/insurance/new"><img  type="image" src="/assets/img/newmotor.png" alt="..." class="img-thumbnail homepage-icons"></a>
            <br><a class="newInsurance icon-label">تأمين السيارات<br>New Motor Insurance</a>
          </div>
          <div class="col-md-4">
            <a class="reNewInsurance" href="/renew"><img  type="image" src="/assets/img/renewmotor.png" alt="..." class="img-thumbnail homepage-icons"></a>
            <br><a class="reNewInsurance icon-label">تجديد تأمين السيارات<br>Renew Motor Insurance</a>
          </div>
          <div class="col-md-4">
            <a class="makePayment" href="/payment/quickpay"><img  type="image" src="/assets/img/payment.png" alt="..." class="img-thumbnail homepage-icons"></a>
            <br><a class="makePayment icon-label">الدفع السريع<br>Quick Payment</a>
          </div><br /><br />
          <!-- Ends here -->
        </div>
        <div style="margin-bottom: 20px;"></div>
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
</script>
@stop
