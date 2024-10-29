@extends('site.layout')
@section('content')
    <div id="free-promo" style="margin: 30px;">
        <div class="text-center" style="margin-top: 4%;"><h2>Issue Motor Insurance</h2></div>
        <div class="container">
            <div class="row text-center">
                <div style="min-height: 250px;">
                    <div style="margin-top: 4%;">
                        <!--code starts here-->
                        <div class="col-md-6">
                            <a class="thirdParty" href="/insurance/thirdparty"><img type="image"
                                                                                    title="New Insurance"
                                                                                    src="/assets/img/tpa.png"
                                                                                    alt="..."
                                                                                    class="img-thumbnail homepage-icons"></a>
                            <br><a class="newInsurance icon-label">تأمين السيارات ضد الغير<br>Third Party Insurance</a>
                        </div>
                        <div class="col-md-6">
                            <a class="Comprehensive" href="/insurance/comprehensive">
                                <img type="image" title="Renew Insurance"  src="/assets/img/fullcover.png" alt="..."
                                     class="img-thumbnail homepage-icons"></a>
                            <br><a class="reNewInsurance icon-label">تأمين السيارات شامل<br>Full Insurance </a>
                        </div>
                        <!--Ends here-->
                    </div>
                </div>
            </div>
            <div class="back-button">
                <a href="/">
                    <i class="fa fa-chevron-left">&nbsp;&nbsp;</i> Back to Home (العودة الى الصفحة الرئيسية)
                </a>
            </div>
        </div>
    </div>
@stop