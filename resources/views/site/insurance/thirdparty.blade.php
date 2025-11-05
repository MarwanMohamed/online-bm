@extends('site.layout')
@section('content')
    <style>
        .dott_dropdown {
            display: inline-block;
            padding: 5px;
            outline: 1px dotted #FF0000;
            outline-offset: 2px;
        }

        .dott_dropdown:focus {

            background: #FFF;
            outline: 1px dotted #FF0000;
        }

        .img-uploads {
            -webkit-transform: scale(1, 1);
            -ms-transform: scale(1, 1);
            transform: scale(1, 1);
            transition-duration: 0.3s;
            filter: grayscale(100%);
            -webkit-transition-duration: 0.3s; /* Safari */
            max-width: 90%;
            margin-bottom: 20px;
        }

        .insurance_active {
            outline: 2px solid #b50555;
            outline-offset: -1px;
            filter: grayscale(0%);
        }

        form.cmxform label.error, label.error {
            /* remove the next line when you have trouble in IE6 with labels in list */
            color: red;
            font-style: italic;
        }

        input.error {
            border: 1px dotted red;
        }

        .colorSearchResult {
            list-style: none;
            padding: 0px;
            width: 335px;
            position: absolute;
            margin: 0;
            z-index: 9999;
            max-height: 200px;
            overflow: auto;
        }

        .colorSearchResult li {
            background: #FFF;
            padding: 4px;
        }

        .colorSearchResult li:nth-child(even) {
            background: #FFF;
            color: black;
        }

        .colorSearchResult li:hover {
            cursor: pointer;
        }
    </style>
    <div class="breadcrumb">
        <div class="container">
            <div class="row">
                <nav>
                    <a class="breadcrumb-item" href="/"><i class="fa fa-home"></i> Home&nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    <a class="breadcrumb-item" href="/insurance/new">New Insurance&nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    <span class="breadcrumb-item active">Third Party Insurance</span>
                </nav>
            </div>
        </div>
    </div>
    <div id="free-promo">
        <form id="thirdPartFrm" autocomplete="off" name="thirdPartFrm" action="/insurance/confirm" method="post">
            @csrf
            <div class="container">
                <div class="row">
                    <h6 style="color:#b50555"><strong>Owner Details</strong></h6>
                    <p>Please enter vehicle owner details (يرجى إدخال البيانات الشخصية لمالك السيارة)</p>
                    <div class="col-md-3">
                        <div class="radio">
                            <label style="display:block"><input type="radio" name="owner_type" value="I" checked>
                                Individual (شخصى)</label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="radio">
                            <label style="display:block"><input type="radio" name="owner_type" value="O"> Organisation
                                (مؤسسة)</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <hr>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nmi_fullname" id="chngeOwnerF">Full Name</label><label class="pull-right"
                                                                                               for="nmi_fullname">الاسم
                                الكامل</label>
                            <input type="text" class="form-control" name="name" id="nmi_fullname">
                        </div>
                    </div>
                    <div class="col-md-4" id="establishQ">
                        <div class="form-group">
                            <label for="qid" id="chngeOwnerQ">Qatar ID</label><label class="pull-right" for="qid">رقم
                                البطاقة</label>
                            <input value="" type="text" class="form-control" name="qid" id="qid" maxlength="11">
                        </div>
                    </div>
                    <div class="col-md-4" id="establishE" style="display:none;">
                        <div class="form-group">
                            <label for="eid" id="chngeOwnerQ">Establishment ID</label><label class="pull-right"
                                                                                             for="nmi_fullname">بطاقة
                                قيد المنشأة</label>
                            <input type="text" value="" class="form-control" name="eid" id="eid" maxlength="8">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nmi_email">Email</label><label class="pull-right" for="email">البريد
                                الإلكتروني</label>
                            <input type="email" class="form-control" name="email" id="email" value="">
                        </div>
                    </div>
                    <div class="col-md-4" id="phoneInd">
                        <div class="form-group">
                            <label for="phone">Phone #</label><label class="pull-right" for="phone">رقم الهاتف</label>
                            <input type="tel" class="form-control" name="phone" id="phone" maxlength="8">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mobile">Mobile #</label><label class="pull-right" for="mobile">رقم
                                الجوال</label>
                            <input type="tel" class="form-control" id="mobile" name="mobile" maxlength="8">
                        </div>
                    </div>
                    <div class="col-md-4" id="posboxOrg" style="display: none;">
                        <div class="form-group">
                            <label for="pb_no">P.O Box #</label><label class="pull-right" for="pb_no">صندوق
                                البريد</label>
                            <input type="text" class="form-control" id="pb_no" name="pb_no" maxlength="8">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="area">Area</label><label class="pull-right" for="area">المنطقة</label>
                            <select class="form-control" name="area" id="area">
                                <option value="">Select Area</option>
                                @foreach ($areas as $area)
                                    <option value="{{$area['id']}}">{{$area['area']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <hr/>
                    <h6 style="color:#b50555"><strong>Insurance Details</strong></h6>
                    <div class="col-md-12">
                        <p style="font-weight:500">A. Enter vehicle details (يرجى إدخال بيانات السيارة)</p>
                        <!--*************Vehicle A**************-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vhl_make">Make</label><label class="pull-right" for="vhl_make">نوع
                                    السيارة</label>
                                <select id="vhl_make" class="form-control" name="vhl_make">
                                    <option value="">--Select--</option>
                                    @foreach ($make as $result)
                                        <option value="{{$result['name'] }}"
                                                data-id="{{$result['id']}}">{{$result['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vhl_class">Model</label><label class="pull-right"
                                                                           for="vhl_class">الموديل</label>
                                <select id="vhl_class" class="form-control" name="vhl_class">
                                    <option value="">--Select--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vhl_chassis">Chassis #</label><label class="pull-right" for="vhl_chassis">رقم
                                    الشاصي</label>
                                <input type="text" class="form-control" id="vhl_chassis" name="vhl_chassis">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vhl_engine">Engine #</label><label class="pull-right" for="vhl_engine">رقم
                                    المحرك</label>
                                <input type="text" class="form-control" name="vhl_engine" id="vhl_engine">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vhl_reg_no">Vehicle Plate #</label><label class="pull-right"
                                                                                      for="vhl_reg_no">رقم
                                    السيارة</label>
                                <input type="text" class="form-control" name="vhl_reg_no" id="vhl_reg_no">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vhl_color">Color</label><label class="pull-right"
                                                                           for="vhl_color">اللون</label>
                                <select class="form-control" name="vhl_color" id="vhl_color">
                                    @foreach($colors as $color)
                                        <option value="{{$color['name']}}">{{$color['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php $tenYearBack = date("Y") - 50;  $currentYear = date("Y"); ?>
                                <label for="vhl_year">Year of Manufacture</label><label class="pull-right"
                                                                                        for="vhl_year">سنة الصنع</label>
                                <select class="form-control" name="vhl_year" id="vhl_year">
                                    <option>Select Year</option>
                                    @for ($y = $tenYearBack; $y <= $currentYear; $y++)
                                        <option value="{{$y}}" selected>{{$y}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <hr/>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">Start date </label><label class="pull-right"
                                                                              for="start_date"></label>
                            <input type="text" readonly class="form-control" name="start_date" id="start_date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">End date</label><label class="pull-right" for="end_date"></label>
                            <input type="text" readonly class="form-control" name="end_date" id="end_date">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <p style="font-weight:500">B. Select your preferred Insurance company (شركة التامين)</p>
                        <div class="col-md-12" id="insMain">
                            <div class="form-group">
                                @foreach ($companies as $result)
                                    <div class="col-md-3" style="padding: .35rem .25rem;">
                                        <h6 style="font-size:14px">{{$result['name']}}</h6>
                                        <a class="insurance_logo"
                                           {{($result['active'] == 0) ? 'style="pointer-events:none;filter: contrast(0.7);"' : '' }}
                                           data-id="{{ $result['id']}}">
                                            @if($result->getFirstMediaUrl())
                                                <img src="{{ $result->getFirstMediaUrl()}}"
                                                     alt="{{$result['name']}}" class="img-uploads img-thumbnail">
                                            @else
                                                <img src="/assets/img/insurancelogos/{{ $result['logo']}}"
                                                     alt="{{$result['name']}}" class="img-uploads img-thumbnail">
                                            @endif
                                        </a>
                                    </div>
                                @endforeach
                                <input type="hidden" name="com_id" id="com_id" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="mainCalc">
                    <hr/>
                    <!--*************Vehicle B**************-->
                    <!--********************************************body parent calculation started**********************************************************-->
                    <div class="col-md-12" style="margin-bottom: 10px;" id="vhl_type_select">
                        <p style="font-weight:500">C. Select vehicle options</p>
                        <div class="col-md-4 vhl_opt" id="vhl_opt_1">
                            <div class="form-group">
                                <label for="opt_1">Type of vehicle</label><label class="pull-right" for="opt_1">نوع
                                    السيارة</label>
                                <select class="form-control" name="opt_1" id="opt_1">
                                    <option value="">Please Select</option>
                                    @foreach ($vhlTypes as $result)
                                        <option value="{{$result['id'] }}" data-name="{{$result['name'] }}"
                                                data-ar-name="{{ $result['ar_name']}}">{{ $result['value']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 vhl_opt" id="vhl_opt_2"></div>
                        <div class="col-md-4 vhl_opt" id="vhl_opt_3"></div>
                        <div class="col-md-4 vhl_opt" id="vhl_opt_4"></div>
                        <div class="col-md-4 vhl_opt" id="vhl_opt_5"></div>
                    </div>
                    <!--**********************************************body parent calculation Ends********************************************************-->
                    <div class="col-md-12">
                        <div id="loadAxtraGif"></div>
                        <div class="row" id="qatarBimaxtra" style="display:none;">
                            <hr/>
                            <p style="font-weight:500">D. Select additional Qatar Bima services [Optional]</p>
                            <div class="row">
                                <div class="col-md-5">
                                    <p>Replacement Vehicle (خدمة السيارة البديلة)</p>
                                </div>

                                <div class="col-md-3 radio">
                                    <label><input type="radio" name="add_opt" id="repacementXtra0" value="0"
                                                  data-amount="0" checked>&nbsp;None</label><br/>
                                    @foreach ($qb_opt as $result)
                                        <label><input type="radio" name="add_opt"
                                                      value="{{$result['id'] }}"
                                                      data-amount="{{$result['amount'] }}">&nbsp;{{$result['name']}}
                                        </label><br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!--**************Qatar Bima Xtra option ends***********-->
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <hr>
                    <h6 style="color:#b50555"><strong>Payment Details</strong></h6>
                    <div class="col-md-12">
                        <div class="container bordered-container" style="max-width:500px">
                            <div class="form-group">
                                <h6><strong>Total amount for your vehicle insurance policy</strong></h6><br/>
                                <div id="calcMethodShow">
                                    <label id="base-amt" style="display:none;">Insurance
                                        Premium&nbsp;:&nbsp;QAR&nbsp;<span>0.00</span></label><br/>
                                    <label id="pass-amt" style="display:none;">Passenger
                                        Price&nbsp;:&nbsp;QAR&nbsp;<span>0.00</span></label><br/>
                                    <label id="opt-amt1" style="display:none;">Replacement
                                        Car&nbsp;:&nbsp;QAR&nbsp;<span>0.00</span></label>
                                </div>
                                <h6 id="NetAmount" style="display: none; padding-top:10px;"><b>Net Amount : QAR <span
                                                id="totalPrice">0.00</span></b></h6>
                                <hr>
                                <span id="discontShow"
                                      style="display: none">Special Discount ({{$discount*100 }} %) : <span
                                            id="discountAmt"></span></span>
                                <h5 style="color:#b50555"><b>Grand Total : QAR <span
                                                id="grandTotalPrice">0.00</span></b></h5><br>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1"></label>
                                <input type="button" class="btn btn-dark" name="cancel" id="cancel"
                                       value="Cancel (إلغاء)">
                                <input type="submit" class="btn btn-common" name="submit" id="submit"
                                       value="Next (التالى)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    </div>
@stop

@section('script')
    <script>
        <?php $lastPolicyData = '';
        if ($lastPolicyData && $lastPolicyData['policy_id']): ?>
        if (confirm("You've an unpaid policy with ref # <?= $lastPolicyData['policy_id'] ?>, would you like to view it?")) {
            window.location.href = "/insurance/confirm";
        }
        <?php endif; ?>
        function clearPrice(curOpt) {
            $('.vhl_opt').slice(curOpt).empty();
            $('#qatarBimaxtra').hide();
            $('#base-amt').hide();
            $('#pass-amt').hide();
            $('#opt-amt1').hide();
            $('#NetAmount').hide();
            $('#discontShow').hide();
            $('#qatarBimaxtra input:checked').prop('checked', false);
            $('#repacementXtra0').prop('checked', true);
            $('#totalPrice').html('0.00');
            $('#grandTotalPrice').html('0.00');
            $('#discountAmt').html('0.00');
            $('#base-amt span').html('0.00');
            $('#pass-amt span').html('0.00');
            $('#opt-amt1 span').html('0.00');
        }

        $(document).ready(function () {
            //Company selection
            $('#insMain .insurance_logo').click(function (event) {
                let company = $(this);
                $('#com_id').val(company.data('id'));
                $('#insMain .insurance_logo .insurance_active').removeClass('insurance_active');
                company.children('img').addClass('insurance_active');
            });
            //Owner Type selection
            $('input[name="owner_type"]').change(function (event) {
                if ($(this).val() == 'I') {
                    $("#pb_no").val('');
                    $("#posboxOrg").hide();
                    $("#establishE").hide();
                    $("#establishQ").show();
                } else {
                    $("#establishE").show();
                    $("#posboxOrg").show();
                    $("#establishQ").hide();
                }
            });
            $('#vhl_make').change(function (event) {
                let make_id = $(this).children('option:selected').data('id');
                if (make_id) {
                    $.get("/insurance/getVhlModels/" + make_id, function (data) {
                        let vehicles = data;
                        console.log(vehicles);
                        $('#vhl_class option').slice(1).remove();
                        if (vehicles.length) {
                            let models = vehicles.map((vhl) => '<option value="' + vhl.model_name + '">' + vhl.model_name + '</option>');
                            $('#vhl_class').append(models.join(''));
                        }
                    });
                }
            });
            //start date end date from bootstrap
            $('#start_date').datepicker({
                dateFormat: 'dd/mm/yy',
                minDate: 0
            }).on('change', function (e) {
                let endDate = $(this).datepicker('getDate');
                endDate.setFullYear(endDate.getFullYear() + 1);
                endDate.setDate(endDate.getDate() - 1);
                let dateStr = (endDate.getDate()).toString().padStart(2, '0') +
                    '/' + (endDate.getMonth() + 1).toString().padStart(2, '0') +
                    '/' + endDate.getFullYear();
                $('#end_date').val(dateStr);
            });
            const discount = '';
            $('.vhl_opt').on('change', 'select', function (event) {
                const curId = $(this).attr('id');
                const selected = $(this).children('option:selected');
                console.log(selected)
                const final = selected.data('final');
                $(this).children('option[value=""]').remove();
                console.log(curId)
                if (curId != 'passengers') {
                    let curOpt = curId.split('_')[1];
                    clearPrice(curOpt);
                    const nextOptId = 'vhl_opt_' + (++curOpt);
                    console.log(nextOptId)
                    const nextOpt = $(this).val();
                    const name = selected.data('name');
                    const nameAr = selected.data('ar-name');
                    console.log(final)
                    if (final == 1) {
                        const passPrice = selected.data('pass');
                        const basePrice = Number(selected.data('base'));
                        $('#base-amt span').html(basePrice.toFixed(2));
                        if (passPrice > 0) {
                            const maxPass = selected.data('max-pass');
                            let htmlData = '<div class="form-group"><label for="passengers">' + name +
                                '</label><label class="pull-right" for="passengers">' + nameAr + '</label>' +
                                '<select class="form-control" name="passengers" id="passengers">' +
                                '<option value="">No. of Passengers</option>';
                            for (let i = 1; i <= maxPass; i++) {
                                htmlData = htmlData + '<option data-final="1" value="' + i + '" data-amt="' + i * passPrice + '">' + i + '</option>';
                            }
                            htmlData = htmlData + '</select></div>';
                            $('#' + nextOptId).append(htmlData);
                        } else {
                            $('#qatarBimaxtra').show();
                            $('#base-amt').show();
                            $('#discontShow').show();
                            const totalPrice = basePrice * (1 - discount);
                            $('#totalPrice').html(basePrice.toFixed(2));
                            $('#grandTotalPrice').html(totalPrice.toFixed(2));
                            $('#discountAmt').html((basePrice * discount).toFixed(2));
                            $('#opt-amt1').show();
                            $('#NetAmount').show();
                        }
                    } else if (nextOpt) {
                        $('#vhl_type_select').append('<img src="/assets/img/spin.gif">');
                        $.get("/insurance/getPrice/" + nextOpt, function (data) {
                            $('#vhl_type_select img').remove();
                            let optionsData = data;
                            let htmlData = '<div class="form-group"><label for="opt_' + curOpt + '"> Select ' + name +
                                '</label><label class="pull-right" for="opt_' + curOpt + '">' + nameAr + '</label>' +
                                '<select class="form-control" name="opt_' + curOpt + '" id="opt_' + curOpt +
                                '"><option value="">Please Select</option>';
                            let options = optionsData.map((opt) => '<option value="' + opt.id + '" data-final="' + opt.final +
                                ((opt.name.length) ? ('" data-name="' + opt.name) : '') + ((opt.ar_name.length) ? '" data-ar-name="' + opt.ar_name : '') +
                                ((opt.optional_id) ? ('" data-opt-id="' + opt.optional_id) : '') + ((opt.base > 0) ? '" data-base="' + opt.base : '') +
                                ((opt.passenger > 0) ? '" data-pass="' + opt.passenger + '" data-max-pass="' + opt.max_pass : '') +
                                '">' + opt.value + '</option>');
                            htmlData = htmlData + options.join('') + '</select></div>';
                            $('#' + nextOptId).append(htmlData);
                        });
                    }
                } else {
                    const optPrice = Number($('#qatarBimaxtra input:checked').data('amount'));
                    const baseAmt = Number($('#base-amt span').html());
                    const passAmt = Number(selected.data('amt'));
                    const totalAmt = baseAmt + passAmt + optPrice;
                    const discAmt = totalAmt * discount;
                    const netAmt = totalAmt - discAmt;
                    $('#pass-amt span').html(passAmt.toFixed(2));
                    $('#totalPrice').html(totalAmt.toFixed(2));
                    $('#discountAmt').html(discAmt.toFixed(2));
                    $('#grandTotalPrice').html(netAmt.toFixed(2));
                    $('#qatarBimaxtra').show();
                    $('#NetAmount').show();
                    $('#discontShow').show();
                    $('#base-amt').show();
                    $('#pass-amt').show();
                    $('#opt-amt1').show();
                }
            });
            $('#qatarBimaxtra input').change(function (event) {
                const optPrice = Number($('#qatarBimaxtra input:checked').data('amount'));
                const baseAmt = Number($('#base-amt span').html());
                const passAmt = Number($('#pass-amt span').html());
                const totalAmt = baseAmt + passAmt + optPrice;
                const discAmt = totalAmt * discount;
                const netAmt = totalAmt - discAmt;
                $('#totalPrice').html(totalAmt.toFixed(2));
                $('#grandTotalPrice').html(netAmt.toFixed(2));
                $('#opt-amt1 span').html(optPrice.toFixed(2));
                $('#discountAmt').html(discAmt.toFixed(2));
                $('#opt-amt1').show();
            });
            const submitHandler = function (form) {
                if (!$('#com_id').val()) {
                    alert("Please choose a company");
                    window.location.hash = '#insMain';
                } else {
                    $('#submit').prop('disabled', true);
                    $('#submit').val('Please wait...');
                    form.submit();
                }
            };
            // data-rule-remote=""
            const validator = $("#thirdPartFrm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    qid: {
                        required: true,
                        minlength: 11,
                        maxlength: 11,
                        remote: '/insurance/allowQid'
                    },
                    eid: {
                        required: true,
                        minlength: 8,
                        maxlength: 8,
                        remote: '/insurance/allowQid'
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        minlength: 8
                    },
                    mobile: {
                        required: true,
                        minlength: 8
                    },
                    pb_no: {
                        required: true,
                        minlength: 3
                    },
                    area: {
                        required: true
                    },
                    vhl_make: {
                        minlength: 1,
                        required: true
                    },
                    vhl_class: {
                        minlength: 1,
                        required: true
                    },
                    vhl_chassis: {
                        required: true,
                        minlength: 3
                    },
                    vhl_engine: {
                        required: true,
                        minlength: 3
                    },
                    vhl_reg_no: {
                        required: true,
                        minlength: 3
                    },
                    vhl_year: {
                        required: true
                    },
                    nmi_color: {
                        required: true
                    },
                    opt_1: {
                        minlength: 1,
                        required: true
                    },
                    opt_2: {
                        minlength: 1,
                        required: true
                    },
                    opt_3: {
                        minlength: 1,
                        required: true
                    },
                    opt_4: {
                        minlength: 1,
                        required: true
                    },
                    passengers: {
                        minlength: 1,
                        required: true
                    },
                    start_date: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Enter Full name",
                        minlength: "Enter minimum 5 characters"
                    },
                    qid: {
                        required: "Enter Qatar ID",
                        minlength: "QID must have 11 digits",
                        remote: "Your QID is blacklisted, Please contact support"
                    },
                    eid: {
                        required: "Enter Establishment ID",
                        minlength: "Est. ID must have 8 digits",
                        remote: "Your EID is blacklisted, Please contact support"
                    },
                    email: {
                        required: "Enter Email ID",
                        email: "Enter a valid email"
                    },
                    phone: {
                        required: "Enter Phone number",
                        minlength: "Phone number must have 8 digits"
                    },
                    mobile: {
                        required: "Enter Mobile number",
                        minlength: "Mobile number must have 8 digits"
                    },
                    pb_no: {
                        required: "Enter PO box",
                        minlength: "Enter minimum 3 digits"
                    },
                    area: {
                        required: "Enter Area"
                    },
                    vhl_make: {
                        required: "Select Make of vehicle",
                        minlength: "Select Make of vehicle"
                    },
                    vhl_class: {
                        required: "Select Model of vehicle",
                        minlength: "Select Model of vehicle"
                    },
                    vhl_chassis: {
                        required: "Enter Chassis No",
                        minlength: "Enter minimum 3 characters"
                    },
                    vhl_engine: {
                        required: "Enter Engine No.",
                        minlength: "Enter minimum 3 characters"
                    },
                    vhl_reg_no: {
                        required: "Enter vehicle number",
                        minlength: "Enter minimum 3 characters"
                    },
                    vhl_color: {
                        required: "Select Color"
                    },
                    vhl_year: {
                        required: "Select Year of manufacture"
                    },
                    opt_1: {
                        required: "Select Type of vehicle",
                        minlength: "Select Type of vehicle"
                    },
                    opt_2: {
                        required: "Select Type of vehicle",
                        minlength: "Select Type of vehicle"
                    },
                    opt_3: {
                        required: "Select Type of vehicle",
                        minlength: "Select Type of vehicle"
                    },
                    opt_4: {
                        required: "Select Type of vehicle",
                        minlength: "Select Type of vehicle"
                    },
                    passengers: {
                        required: "Select No. of Passenges",
                        minlength: "Select No. of Passenges"
                    },
                    start_date: {
                        required: "Enter start date"
                    }
                },
                submitHandler: submitHandler
            });
        });
    </script>

@stop