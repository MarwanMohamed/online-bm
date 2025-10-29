@extends('site.layout')
@section('content')
    <div class="breadcrumb">
        <div class="container">
            <div class="row">
                <nav>
                    <a class="breadcrumb-item" href="/"><i class="fa fa-home"></i> Home&nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    <span class="breadcrumb-item active" href="/home/payment">Make payment</span>
                </nav>
            </div>
        </div>
    </div>
    <div id="free-promo">
        <div class="container">

            <div class="row" style="margin-bottom: 20px;">
                <h6 style="color:#b50555; text-align:center">Choose your preferred mode of payment</h6><br>

                <div class="col-md-12">

                    <!-- Start New PGW -->
                    <div class="container bordered-container" style="max-width:500px; margin-bottom:30px">
                        <div class="form-group">
                            <div>
                                <span>Reference Number : <strong>{{$policyRef}}</strong></span><br>
                            </div>
                            <hr>
                            <h6>Grand Total: QAR <span id="totalPrice">{{number_format($total_amount, 2)}}</span></h6>


                            <label for="payment">Pay using : </label>


                            <form  name="SubFrm" action="/payment/tesspaymentspgw"
                                  method="post">
                                @csrf
                                <input type="hidden" name="policy_id" id="policy_id" value="{{$policyRef}}">
                                
                                <div style="text-align: center; margin-top: 30px;">
                                    <button class="btn btn-pay fatora_pay_btn" name="submit" type="submit" style="margin: 0 0 30px 0; padding: 10px 28px;
    background-color: #8a0b43; "><i class="fa fa-money"></i> Proceed to Pay (إدفع)
                                </button>

                                    <img src="{{ asset('assets/images/card-brands.png') }}" alt="Card Brands" style="max-width:300px; width: 100%;
                                     height: auto; display: inline-block; opacity: 0.95; transition: opacity 0.2s ease-in-out;">
                                </div>
                                <br><br>
                                <input type="button" class="btn btn-dark" name="cancel" id="cancel" value="Cancel"
                                       style="float: right; background: #c1c1c1; color: #000;">
                            </form>
                        </div>
                    </div>
                    <br><br><br>
                    <!-- End New PGW -->

                    <!-- <div class="container bordered-container" style="max-width:500px; margin-bottom:30px">
                        <div class="form-group">
                            <div>
                                <span>Reference Number : <strong>{{$policyRef}}</strong></span><br>
                            </div>
                            <hr>
                            <h6>Grand Total: QAR <span id="totalPrice">{{number_format($total_amount, 2)}}</span></h6>


                            <label for="payment">Pay using : </label>


                            <form  name="SubFrm" action="/payment/qcbankpayment"
                                  method="post">
                                @csrf
                                <div class="form-check" style="display: none">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="cardType" value="debit"
                                               checked=""> Debit Card (كارد الراتب) (NAPS)
                                    </label>
                                </div>
                                <input type="hidden" name="nmi_referno" id="nmi_referno" value="{{$policyRef}}">
                                <input type="hidden" name="nmi_total_price" id="nmi_total_price"
                                       value="{{$total_amount}}">
                                <input type="hidden" name="nmi_date" value="">
                                <button class="btn btn-pay fatora_pay_btn" name="submit" type="submit" style="margin: 7px 0 7px 0; padding: 8px 28px;
    background-color: #8a0b43; "><i class="fa fa-money"></i> Debit Card (كارد الراتب) (NAPS)
                                </button>
                            </form>

                            <h6 class="or_heading" style="display:block">OR</h6>

                            <form class="cmxform" id="SubFrm1" name="SubFrm1" action="/payment/dohabankpayment"
                                  method="post">
                                @csrf
                                <div class="form-check" style="display: none">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="cardType" value="credit"
                                               checked=""> Credit Card (فيزا &amp; ماستر) (VISA / Master Card)
                                    </label>
                                </div>

                                <button class="btn btn-pay fatora_pay_btn" name="submit" type="submit"
                                        style="margin: 7px 0 7px 0; padding: 8px 28px; background-color: #8a0b43;"><i
                                            class="fa fa-money"></i> Credit Card (فيزا &amp; ماستر) (VISA / Master Card)
                                </button>

                                <br>
                                <input type="button" class="btn btn-dark" name="cancel" id="cancel" value="Cancel"
                                       style="float: right; background: #c1c1c1; color: #000;">

                                <input type="hidden" name="nmi_referno" id="nmi_referno1" value="{{$policyRef}}">
                                <input type="hidden" name="nmi_total_price" id="nmi_total_price1"
                                       value="{{$total_amount}}">
                                <input type="hidden" name="nmi_date" value="">

                            </form>
                        </div>
                    </div>
                    <br><br><br> -->
                </div>
            </div>

            <div id="qpayForm" style="display:none;"></div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        //disable back button browser
        //  history.pushState(null, null, location.href);
        //  window.onpopstate = function () {
        //    history.go(1);
        //  };
        $(document).ready(function () {
            //cancel button click------------------------------------------
            $("#cancel").click(() => {
                window.location.href = "/";
            });

            $('#SubFrm').submit(function () {
                var crdtypw = $('input:radio[name=cardType]:checked').val();
                if (crdtypw == "debit") {
                    $.get('/payment/qcbankpayment', function (data) {
                        $('#qpayForm').html(data);
                        console.log(data)
                        $('#qpayForm form').submit();
                    });
                    return false;
                } else {
                    return true;
                }
            });
        });
    </script>

@stop
