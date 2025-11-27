@extends('site.layout')

@section('content')

    <style>
        <style type="text/css">
        <!--
        h1       { font-family:Arial,sans-serif; font-size:24pt; color:#08185A; font-weight:100}
        h2.co    { font-family:Arial,sans-serif; font-size:24pt; color:#08185A; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
        h3.co    { font-family:Arial,sans-serif; font-size:16pt; color:#000000; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
        body     { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#08185A;background-color:#FFFFFF }
        p        { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#FFFFFF }
        a:link   { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
        a:visited{ font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
        a:hover  { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#FF0000 }
        a:active { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#FF0000 }
        tr       { height:25px; }
        tr.shade { height:25px; background-color:#E1E1E1 }
        tr.title { height:25px; background-color:#C1C1C1 }
        td       { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
        td.red   { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#FF0066 }
        td.green { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#00AA00 }
        th       { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#08185A; font-weight:bold; background-color:#E1E1E1; padding-top:0.5em; padding-bottom:0.5em}
        input    { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:bold }
        select   { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:bold; width:463 }
        textarea { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:normal; scrollbar-arrow-color:#08185A; scrollbar-base-color:#E1E1E1 }
        -->
    </style>

    </style>
<div class="breadcrumb">
    <div class="container">
        <div class="row">
            <nav>
                <a class="breadcrumb-item" href="/"><i class="fa fa-home"></i> Home&nbsp;<i class="fa fa-angle-right"></i></a>
                <span class="breadcrumb-item active" href="/home/payment">Payment Confirm</span>
            </nav> 
        </div>
    </div>
</div>
<div id="free-promo">
   
    <form class="cmxform" id="paymentFrm" name="paymentFrm" action="/customer/register" method="post">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <table width="85%" align="center" cellpadding="5" border="0">
                        <tr class="title">
                            <td colspan="2" height="25"><P><strong>&nbsp;Basic Transaction Details</strong></P></td>
                        </tr>
                        
                        <tr>
                            <td align="right"><strong><i>Merchant Transaction Reference: </i></strong></td>
                            <td>{{$data['order_id']}}</td>
                        </tr>
                        
                        <tr>
                            <td align="right"><strong><i>Policy Reference: </i></strong></td>
                            <td>{{$data['policy_ref']}}</td>
                        </tr>
                        
                        <tr class="shade">
                            <td align="right"><strong><i>Transaction Amount: </i></strong></td>
                            <td>{{$data['order_amount']}}</td>
                        </tr>

                        <tr>
                            <td align="right"><strong><i>Transaction Status: </i></strong></td>
                            <td>{{$data['order_status']}}</td>
                        </tr>
                        <tr class="shade">
                            <td align="right"><strong><i>Transaction Date: </i></strong></td>
                            <td>{{$data['order_date']}}</td>
                        </tr>
    
                        <tr>
                            <td align="right">&nbsp;</td>
                        <input type="hidden" name="policyRef" value="1111">
                            <td><input type="button" class="btn btn-common" name="Backorder" id="Backorder" value="Order Again">
{{--                                <input type="submit" class="btn btn-common" name="registerNow" id="registerNow" value="Register Now">--}}
                            </td>

                        </tr>
                    </table>
                </div>
            </div>

        </div> 
    </form>
</div>
@stop

@section('script')
<script type="text/javascript">
//disable back button browser
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    //cancel button click------------------------------------------
    $("#Backorder").click(() => {
        window.location.href = "/";

    });
</script>


@stop
