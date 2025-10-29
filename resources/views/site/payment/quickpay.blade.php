@extends('site.layout')
@section('content')
    <div class="breadcrumb">
        <div class="container">
            <div class="row">
                <nav>
                    <a class="breadcrumb-item" href="/"><i class="fa fa-home"></i> Home&nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    <span class="breadcrumb-item active" href="payment/quickpay">Quick Payment</span>
                </nav>
            </div>
        </div>
    </div>

    <div id="free-promo">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif
                </div>
                <h6>Please enter your reference number to get details about your policy</h6><br>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nmi_qatar_id">Reference #</label>
                        <input type="text" class="form-control" name="policy_id" id="policy_id">
                    </div>
                    <div class="form-group">
                        <input type="button" class="btn btn-common" name="viewDetail" id="viewDetail"
                               value="View Details">
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div id="mkPayGif"></div>
                    <div id="reDetails" style="display:none;" class="pull-right">

                        <form class="cmxform" id="SubFrm" name="SubFrm" action="/payment/select-payment" method="post">
                            @csrf
                            <div class="form-group">
                                <p id="quickpaydltsid">Your insurance policy has been issued. Kindly pay the policy amount to activate your policy</p>
                                <h4 style="color: #b50555"><b>Policy Amount : QAR <span id="totalPrice">0.00</span></b>
                                </h4><span id="amounttext"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1"></label>
                                <input type="hidden" class="btn btn-dark" name="nmi_referno" id="policy_input"
                                       value="nmi_referno">
                                <input type="hidden" class="btn btn-dark" name="nmi_total_price" id="total_amount_input"
                                       value="nmi_total_price">
                                <input type="button" class="btn btn-dark" name="cancel" id="cancel" value="Cancel">
                                <input type="submit" class="btn btn-common" name="submit" id="submit" value="Next">
                            </div>
                    </div>
                </div>
            </div>
            <div class="back-button">
                <a href="/">
                    <i class="fa fa-chevron-left">&nbsp;&nbsp;</i> Back to Home </a>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#quickpaydltsid").hide();
            //cancel button click------------------------------------------
            $("#cancel").click(() => {
                window.location.href = "/";
            });

            $("#SubFrm").submit(function () {
                var form = $(this);
                $('#submit').prop('disabled', true);
                $('#submit').val('Please wait...');
                form.unbind('submit').submit();
            });

            // validate the comment form when it is submitted
            // $("#renewFrm").validate();

            // validate signup form on keyup and submit
            $(document).on('click', '#viewDetail', function (e) {
                e.preventDefault();
                var policy_id = $('#policy_id').val();
                $('#policy_input').val(policy_id);
                if (policy_id == 0 || policy_id == "") {
                    $('#infMsg').remove();
                    $('#policy_id').after('<span style="color: red;font-style:italic;"  id="infMsg">Please Enter Reference number</span>');
                    return false;
                } else {
                    $.post({
                        url: "/payment/getPolicyPayDetails",
                        data: {
                            policy_id: policy_id,
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token here
                        },
                        dataType: "json",
                        success: function (res) {
                            //var data = jQuery.parseJSON(res);
                            $('#mkPayGif').html('<img src="/assets/img/spin.gif">');
                            //$('#feed-container').prepend(data);
                            $('#reDetails').hide();
                            $('#mkPayGif').show();
                            $('#infMsg').remove();
                            if (res == 0) {
                                setTimeout(function () {
                                    $('#infMsg').remove();
                                    $('#reDetails').after('<span style="font-size: 25px; color: red;"  id="infMsg"><strong>No Information Available</strong></span>');
                                    $('#mkPayGif').hide();
                                }, 600);
                            } else if (res == 2) { //isExist
                                setTimeout(function () {
                                    $('#infMsg').remove();
                                    $('#reDetails').after('<span style="font-size: 25px; color: red;"  id="infMsg"><strong>Already Paid For This Reference </strong></span>');
                                    $('#mkPayGif').hide();
                                }, 600);
                            } else {
                                let amount = res.amount;
                                let policyTypeDisplay = res.policy_type_display || 'Insurance';
                                
                                // Update the message dynamically
                                let dynamicMessage = `Your ${policyTypeDisplay} insurance policy has been issued. Kindly pay the policy amount to activate your policy`;
                                $("#quickpaydltsid").text(dynamicMessage);
                                
                                if (amount && res.policy_id) {
                                    $("#quickpaydltsid").hide();
                                    //$("#amounttext").text("(Additional amount " + amount + ")");
                                    $('#nmi_total_price').val(amount);
                                    $('#Amount').val(amount);
                                    $('#nmi_referno').val(res.ref_no);
                                    $('#infMsg').remove();
                                    setTimeout(function () {
                                        $('#mkPayGif').hide();
                                        $('#totalPrice').text(amount);
                                        $('#total_amount_input').val(amount);
                                        $('#vehiclePlate').text("");
                                        $('#payAmount1').text(amount);
                                        $('#reDetails').show();
                                    }, 600);
                                } else {
                                    $("#quickpaydltsid").show();
                                    $('#infMsg').remove();
                                    setTimeout(function () {
                                        $('#mkPayGif').hide();
                                        $('#totalPrice').text(amount);
                                        $('#total_amount_input').val(amount);
                                        $('#vehiclePlate').text(res.vhl_reg_no);
                                        $('#payAmount').text(amount);
                                        $('#reDetails').show();
                                    }, 600);
                                    $('#nmi_total_price').val(amount);
                                    $('#Amount').val(amount);
                                    $('#nmi_referno').val(res.policy_id);
                                    $('#nmi_mobno').val(res.mobile);
                                    $('#nmi_email').val(res.email);
                                }
                            }
                            //console.log(res);
                        },
                        error: function () {
                            alert("Error posting feed.");
                        }
                    });
                }
            });
            //alert("submitted!");
        });
    </script>
@stop
