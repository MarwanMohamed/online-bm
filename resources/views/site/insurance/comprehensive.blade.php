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

        .file-preview {
            display: none;
            margin-top: 10px;
        }

        .file-preview img {
            max-width: 100px;
            max-height: 100px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .form-control.success {
            border-color: #28a745;
        }

        .form-control.error {
            border-color: #dc3545;
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .insurance_logo {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .insurance_logo:hover img {
            transform: scale(1.05);
        }

        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

      

        .progress-step {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-bottom: 3px solid #e9ecef;
            color: #6c757d;
        }

        .progress-step.active {
            border-bottom-color: #b50555;
            color: #b50555;
            font-weight: bold;
        }

        .progress-step.completed {
            border-bottom-color: #28a745;
            color: #28a745;
        }

        @media (max-width: 768px) {
            .progress-indicator {
                flex-wrap: wrap;
            }
            
            .progress-step {
                flex-basis: 50%;
                margin-bottom: 10px;
                font-size: 12px;
            }
            
            .form-section {
                padding: 15px;
                margin-bottom: 20px;
            }
            
            .col-md-3, .col-md-4 {
                margin-bottom: 15px;
            }
        }

        .loading-spinner {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        .loading-spinner i {
            font-size: 24px;
            color: #b50555;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                    <span class="breadcrumb-item active">Comprehensive Insurance</span>
                </nav>
            </div>
        </div>
    </div>
    <div id="free-promo">
        <form id="thirdPartFrm" autocomplete="off" name="thirdPartFrm"
              action="/insurance/comprehensive" method="post" enctype="multipart/form-data"
              novalidate="novalidate">
            @csrf
            <div class="container">
                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6><strong>Please fix the following errors:</strong></h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Display Success Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Progress Indicator -->
                <div class="row">
                    <h6 style="color:#b50555"><strong>Owner Details</strong></h6>
                    <p>Please enter vehicle owner details (يرجى إدخال البيانات الشخصية لمالك السيارة)</p>
                    <div class="col-md-3">
                        <div class="radio">
                            <label style="display:block"><input type="radio" name="owner_type" value="I" checked="">
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
                <div class="row" id="vehicle-section">
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
                                <label for="vhl_body_type">Body Type</label><label class="pull-right"
                                                                                   for="vhl_body_type">نوع الهيكل</label>
                                <select class="form-control" name="vhl_body_type" id="vhl_body_type">
                                    <option value="">--Select--</option>
                                    @foreach($bodyTypes as $bodyType)
                                        <option value="{{$bodyType['name']}}">{{$bodyType['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px;">
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
                            <label for="start_date">Insurance Start date </label><label class="pull-right"
                                                                                        for="start_date"></label>
                            <input type="text" readonly class="form-control" name="start_date" id="start_date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">Insurance End date</label><label class="pull-right"
                                                                                   for="end_date"></label>
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
                                            @if($result['logo'])
                                            <img src="/assets/img/insurancelogos/{{ $result['logo']}}"
                                                 alt="{{$result['name']}}" class="img-uploads img-thumbnail">
                                            @else
                                                <img src="{{ $result->getFirstMediaUrl()}}"
                                                     alt="{{$result['name']}}" class="img-uploads img-thumbnail">
                                            @endif
                                        </a>
                                    </div>
                                @endforeach
                                <input type="hidden" name="com_id" id="com_id" value="">
                                <input type="hidden" name="add_opt" id="add_opt" value="0">
                                <input type="hidden" name="opt_2" id="opt_2" value="0">
                                <input type="hidden" name="opt_3" id="opt_3" value="0">
                                <input type="hidden" name="opt_4" id="opt_4" value="0">
                                <input type="hidden" name="passengers" id="passengers" value="0">
                                <input type="hidden" name="base_amount" id="base_amount" value="0">
                                <input type="hidden" name="pass_amount" id="pass_amount" value="0">
                                <input type="hidden" name="opt_amount" id="opt_amount" value="0">
                                <input type="hidden" name="discount" id="discount" value="0">
                                <input type="hidden" name="total_amount" id="total_amount" value="0">
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
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <hr>
                    <h6 style="color:#b50555"><strong>Document Upload</strong></h6>
                    <p style="font-weight:500">D. Upload Documents (تحميل المستندات)</p>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="qid_front">Qatar ID Front</label><label class="pull-right" 
                                                                               for="qid_front">البطاقة الشخصية أمامي</label>
                            <input type="file" class="form-control" name="qid_front" id="qid_front" accept="image/*,.pdf">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ist_front">Isthimara Front</label><label class="pull-right" 
                                                                                 for="ist_front">الاستمارة أمامي</label>
                            <input type="file" class="form-control" name="ist_front" id="ist_front" accept="image/*,.pdf">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ist_back">Isthimara Back</label><label class="pull-right" 
                                                                              for="ist_back">الاستمارة خلفي</label>
                            <input type="file" class="form-control" name="ist_back" id="ist_back" accept="image/*,.pdf">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <hr>
                    <!-- <h6 style="color:#b50555"><strong>Payment Details</strong></h6> -->
                    <div class="col-md-12">
                        <div class="container bordered-container" style="max-width:500px">
                            <!-- <div class="form-group">
                              <h6><strong>Total amount for your vehicle insurance policy</strong></h6><br/>
                              <div id="calcMethodShow">
                                <label id="base-amt" style="display:none;">Insurance Premium&nbsp;:&nbsp;QAR&nbsp;<span>0.00</span></label><br/>
                                <label id="pass-amt" style="display:none;">Passenger Price&nbsp;:&nbsp;QAR&nbsp;<span>0.00</span></label><br/>
                                <label id="opt-amt1"  style="display:none;">Replacement Car&nbsp;:&nbsp;QAR&nbsp;<span>0.00</span></label>
                              </div>
                              <h6 id="NetAmount" style="display: none; padding-top:10px;"><b>Net Amount : QAR <span id="totalPrice">0.00</span></b></h6>
                              <hr>
                              <span id="discontShow" style="display: none">Special Discount (0 %) : <span id="discountAmt"></span></span>
                              <h5 style="color:#b50555"><b>Grand Total : QAR <span id="grandTotalPrice">0.00</span></b></h5><br>
                            </div> -->
                            <div class="form-group">
                                <div id="form-summary" style="display: none; margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
                                    <h6 style="color: #b50555; margin-bottom: 10px;"><strong>Form Summary</strong></h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small><strong>Owner:</strong> <span id="summary-owner"></span></small><br>
                                            <small><strong>Email:</strong> <span id="summary-email"></span></small><br>
                                            <small><strong>Mobile:</strong> <span id="summary-mobile"></span></small>
                                        </div>
                                        <div class="col-md-6">
                                            <small><strong>Vehicle:</strong> <span id="summary-vehicle"></span></small><br>
                                            <small><strong>Year:</strong> <span id="summary-year"></span></small><br>
                                            <small><strong>Company:</strong> <span id="summary-company"></span></small>
                                        </div>
                                    </div>
                                </div>
                                <label for="exampleFormControlInput1"></label>
                                <input type="button" class="btn btn-dark" name="cancel" id="cancel"
                                       value="Cancel (إلغاء)">
                                <input type="submit" class="btn btn-common" name="submit" id="submit"
                                       value="Submit (التالى)">
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
                        let vehicles = data
                        $('#vhl_class option').slice(1).remove();
                        $('#vhl_body_type option').slice(1).remove();
                        if (vehicles.length) {
                            let models = vehicles.map((vhl) => '<option value="' + vhl.model_name + '">' + vhl.model_name + '</option>');
                            $('#vhl_class').append(models.join(''));
                        }
                    });
                } else {
                    $('#vhl_class option').slice(1).remove();
                    $('#vhl_body_type option').slice(1).remove();
                }
            });

            // Add body type handling when model is selected
            $('#vhl_class').change(function (event) {
                let make = $('#vhl_make').val();
                let model = $(this).val();
                if (make && model) {
                    // Try to get dynamic body types based on make/model
                    $.get("/insurance/getBodyTypes/" + make + "/" + model, function (data) {
                        $('#vhl_body_type option').slice(1).remove();
                        if (data && data.length) {
                            let bodyTypes = data.map((bodyType) => '<option value="' + bodyType.name + '">' + bodyType.name + '</option>');
                            $('#vhl_body_type').append(bodyTypes.join(''));
                        }
                    }).fail(function() {
                        // If API fails, populate with common body types based on make
                        $('#vhl_body_type option').slice(1).remove();
                        let commonBodyTypes = getCommonBodyTypes(make.toLowerCase());
                        let bodyTypeOptions = commonBodyTypes.map((bodyType) => '<option value="' + bodyType + '">' + bodyType + '</option>');
                        $('#vhl_body_type').append(bodyTypeOptions.join(''));
                    });
                } else {
                    $('#vhl_body_type option').slice(1).remove();
                }
            });

            // Function to get common body types based on vehicle make
            function getCommonBodyTypes(make) {
                const bodyTypesByMake = {
                    'toyota': ['Sedan', 'SUV', 'Hatchback', 'Coupe', 'Truck'],
                    'nissan': ['Sedan', 'SUV', 'Hatchback', 'Coupe', 'Truck'],
                    'bmw': ['Sedan', 'SUV', 'Coupe', 'Convertible', 'Hatchback'],
                    'mercedes': ['Sedan', 'SUV', 'Coupe', 'Convertible', 'Wagon'],
                    'audi': ['Sedan', 'SUV', 'Hatchback', 'Coupe', 'Convertible'],
                    'honda': ['Sedan', 'SUV', 'Hatchback', 'Coupe'],
                    'hyundai': ['Sedan', 'SUV', 'Hatchback'],
                    'kia': ['Sedan', 'SUV', 'Hatchback'],
                    'ford': ['Sedan', 'SUV', 'Hatchback', 'Truck', 'Coupe'],
                    'chevrolet': ['Sedan', 'SUV', 'Hatchback', 'Truck', 'Coupe']
                };
                
                return bodyTypesByMake[make] || ['Sedan', 'SUV', 'Hatchback', 'Coupe', 'Convertible', 'Truck', 'Van', 'Wagon'];
            }
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
                const final = selected.data('final');
                $(this).children('option[value=""]').remove();
                if (curId != 'passengers') {
                    let curOpt = curId.split('_')[1];
                    clearPrice(curOpt);
                    const nextOptId = 'vhl_opt_' + (++curOpt);
                    const nextOpt = $(this).val();
                    const name = selected.data('name');
                    const nameAr = selected.data('ar-name');
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
                    vhl_reg_no: {
                        required: true,
                        minlength: 3
                    },
                    vhl_year: {
                        required: true
                    },
                    vhl_color: {
                        required: true
                    },
                    vhl_body_type: {
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
                    },
                    qid_front: {
                        required: true
                    },
                    ist_front: {
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
                    vhl_reg_no: {
                        required: "Enter vehicle number",
                        minlength: "Enter minimum 3 characters"
                    },
                    vhl_color: {
                        required: "Select Color"
                    },
                    vhl_body_type: {
                        required: "Select Body Type"
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
                    },
                    qid_front: {
                        required: "Upload Qatar ID front"
                    },
                    ist_front: {
                        required: "Upload Isthimara front"
                    }
                },
                submitHandler: submitHandler
            });

            // File upload preview functionality
            $('input[type="file"]').change(function() {
                const file = this.files[0];
                const $input = $(this);
                const $preview = $input.siblings('.file-preview');
                
                if ($preview.length === 0) {
                    $input.after('<div class="file-preview"></div>');
                }
                
                const $previewContainer = $input.siblings('.file-preview');
                
                if (file) {
                    $previewContainer.show();
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $previewContainer.html(`
                                <img src="${e.target.result}" alt="Preview">
                                <div class="file-info">${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>
                            `);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        $previewContainer.html(`
                            <div class="file-info">
                                <i class="fa fa-file"></i> ${file.name} (${(file.size / 1024).toFixed(2)} KB)
                            </div>
                        `);
                    }
                } else {
                    $previewContainer.hide();
                }
            });

            // Clear file previews when form is reset
            $('#cancel').click(function() {
                $('.file-preview').hide();
                $('input[type="file"]').val('');
                if (confirm('Are you sure you want to cancel and clear all data?')) {
                    $('#thirdPartFrm')[0].reset();
                    $('.insurance_active').removeClass('insurance_active');
                    $('#com_id').val('');
                    clearPrice(1);
                }
            });

            // Enhanced form validation with better UX
            $('#thirdPartFrm input, #thirdPartFrm select').on('blur', function() {
                $(this).valid();
            });

            // Auto-format Qatar ID input
            $('#qid, #eid').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                $(this).val(value);
            });

            // Auto-format phone numbers
            $('#phone, #mobile').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                $(this).val(value);
            });

            // Real-time validation feedback
            $('#qid').on('input', function() {
                let qid = $(this).val();
                if (qid.length === 11) {
                    $(this).removeClass('error').addClass('success');
                } else if (qid.length > 0) {
                    $(this).removeClass('success').addClass('error');
                }
            });

            // Ensure proper form submission
            $('#thirdPartFrm').on('submit', function(e) {
                if (!$('#com_id').val()) {
                    e.preventDefault();
                    alert('Please select an insurance company');
                    $('html, body').animate({
                        scrollTop: $('#insMain').offset().top
                    }, 1000);
                    return false;
                }
                updateProgressStep(5);
            });

            // Progress indicator functionality
            function updateProgressStep(stepNumber) {
                $('.progress-step').removeClass('active completed');
                for (let i = 1; i < stepNumber; i++) {
                    $('#step' + i).addClass('completed');
                }
                $('#step' + stepNumber).addClass('active');
            }

            // Update progress based on form completion
            $('#owner-section input, #owner-section select').on('change blur', function() {
                if (isOwnerSectionComplete()) {
                    updateProgressStep(2);
                }
            });

            $('#vehicle-section input, #vehicle-section select').on('change', function() {
                if (isVehicleSectionComplete()) {
                    updateProgressStep(3);
                }
            });

            $('#insMain .insurance_logo').on('click', function() {
                if ($('#com_id').val()) {
                    updateProgressStep(4);
                }
            });

            $('input[type="file"]').on('change', function() {
                if (areRequiredDocumentsUploaded()) {
                    updateProgressStep(5);
                }
            });

            // Helper functions to check section completion
            function isOwnerSectionComplete() {
                const requiredFields = ['name', 'email', 'mobile', 'area'];
                const ownerType = $('input[name="owner_type"]:checked').val();
                
                if (ownerType === 'I') {
                    requiredFields.push('qid');
                } else {
                    requiredFields.push('eid', 'pb_no');
                }

                return requiredFields.every(field => {
                    const value = $(`#${field}`).val() || $(`[name="${field}"]`).val();
                    return value && value.trim() !== '';
                });
            }

            function isVehicleSectionComplete() {
                const requiredFields = ['vhl_make', 'vhl_class', 'vhl_reg_no', 'vhl_color', 'vhl_body_type', 'vhl_year', 'start_date'];
                return requiredFields.every(field => {
                    const value = $(`#${field}`).val();
                    return value && value.trim() !== '';
                });
            }

            function areRequiredDocumentsUploaded() {
                return $('#qid_front')[0].files.length > 0 && $('#ist_front')[0].files.length > 0;
            }

            // Loading helper functions
            function showLoading(selector) {
                $(selector).append('<div class="loading-spinner"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
            }

            function hideLoading(selector) {
                $(selector + ' .loading-spinner').remove();
            }

            // Update form summary
            function updateFormSummary() {
                const ownerName = $('#nmi_fullname').val();
                const email = $('#email').val();
                const mobile = $('#mobile').val();
                const make = $('#vhl_make').val();
                const model = $('#vhl_class').val();
                const year = $('#vhl_year').val();
                const companyName = $('#insMain .insurance_active').closest('.col-md-3').find('h6').text();

                if (ownerName && email && mobile && make && model && year) {
                    $('#summary-owner').text(ownerName);
                    $('#summary-email').text(email);
                    $('#summary-mobile').text(mobile);
                    $('#summary-vehicle').text(make + ' ' + model);
                    $('#summary-year').text(year);
                    $('#summary-company').text(companyName || 'Not selected');
                    $('#form-summary').show();
                }
            }

            // Update summary when relevant fields change
            $('#nmi_fullname, #email, #mobile, #vhl_make, #vhl_class, #vhl_year').on('change blur', updateFormSummary);
            $('#insMain .insurance_logo').on('click', updateFormSummary);
        });
    </script>

@stop