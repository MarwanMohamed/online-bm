@extends('site.layout')
@section('content')
    <div class="breadcrumb">
        <div class="container">
            <div class="row">
                <nav>
                    <a class="breadcrumb-item" href="/"><i class="fa fa-home"></i>Home&nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    <a class="breadcrumb-item" href="/renew">Renew Insurance&nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    <span class="breadcrumb-item active">Renew View</span>
                </nav>
            </div>
        </div>
    </div>
    <div id="free-promo" style="margin: 30px;">
        <div class="container" style="border: 2px solid #b50555;border-radius: 10px;padding: 20px 40px;">
            <h6><strong>Motor Insurance Policy</strong></h6>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Reference
                                    No.</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input"
                                   class="col-form-label"><strong><?= $data['insurance']->policy_id ?></strong></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Full Name</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->name ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Email ID</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->email ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Mobile No#</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->mobile ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Qatar ID</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->qid ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Phone</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->phone ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Area</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->area_name ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Vehicle make</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->vhl_make ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Vehicle
                                    Model</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->vhl_class ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Vehicle
                                    Plate</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->vhl_reg_no ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Vehicle
                                    Colour</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->vhl_color ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Body Type</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= isset($data['insurance']->vhl_body_type) ? $data['insurance']->vhl_body_type : 'N/A' ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">

                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Chase No</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input"
                                   class="col-form-label"><?= $data['insurance']->vhl_chassis ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Engine
                                    Number</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->vhl_engine ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Manufacture
                                    Year</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->vhl_year ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <hr/>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="example-text-input" class="col-form-label"><strong>Insurance
                                    Company</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><?= $data['insurance']->com_name ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label for="example-text-input" class="col-form-label"><strong>Replace Car
                                    Chosen</strong></label>
                        </div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5">
                            <div id="replaceDyNrmal"><label for="example-text-input"
                                                            class="col-form-label"><?= ($data['insurance']->add_opt == 0) ? 'No' : $add_opt->name; ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form id="thirdPartFrm" autocomplete="off" name="thirdPartFrm" action="/renew/confirm"
                  method="post">
                @csrf
                <input type="hidden" name="policy_id" value="{{$decryptId}}">
                <div class="row" id="qatarBimaxtra">
                    <div class="col-md-5">
                        <p>Replacement Vehicle (خدمة السيارة البديلة)</p>
                    </div>
                    <div class="col-md-3 radio">
                        <label><input type="radio" name="add_opt" id="repacementXtra0" value="0" data-amount="0"
                                      checked>&nbsp;None</label><br/>
                        @foreach ($data['qb_opt'] as $result)
                        <label><input type="radio" name="add_opt" value="<?= $result['id'] ?>"
                                      data-amount="<?= $result['amount'] ?>"
                                    <?= ($data['insurance']->add_opt == $result['id']) ? 'checked' : '' ?>>&nbsp;<?= $result['name'] ?>
                        </label><br>
                        @endforeach
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <hr>
                    <h6 style="color:#b50555"><strong>Payment Details</strong></h6>
                    <div class="col-md-12">
                        <div class="container bordered-container" style="max-width:500px">
                            <div class="form-group">
                                <h6><strong>Total amount for renewal of your vehicle policy</strong></h6><br/>
                                <div id="calcMethodShow">
                                    <label id="base-amt">Insurance
                                        Premium&nbsp;:&nbsp;QAR&nbsp;<span><?= number_format($data['price']['base_amount'], 2) ?></span></label><br/>
                                    <label id="pass-amt">Passenger
                                        Price&nbsp;:&nbsp;QAR&nbsp;<span><?= number_format($data['price']['pass_amount'], 2) ?></span></label><br/>
                                    <label id="opt-amt1">Replacement
                                        Car&nbsp;:&nbsp;QAR&nbsp;<span><?= number_format($data['price']['opt_amount'], 2) ?></span></label>
                                </div>
                                <h6 id="NetAmount" style="padding-top:10px;"><b>Net Amount : QAR <span
                                                id="totalPrice"><?= number_format($data['price']['total_amount'], 2) ?></span></b>
                                </h6>
                                <hr>
                                <!-- <span id="discontShow" style="display: none">Special Discount ( %) : <span id="discoAmontQar"></span></span> -->
                                <h5 style="color:#b50555"><b>Grand Total : QAR <span
                                                id="grandTotalPrice"><?= number_format($data['price']['total_amount'], 2) ?></span></b>
                                </h5><br>
                                <!-- <input type="hidden" name="thirdPDiscount" id="thirdPDiscount" value=""> -->
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1"></label>
                                <a href="/">
                                    <button class="btn btn-dark">Cancel (إلغاء)</button>
                                </a>
                                <input type="submit" class="btn btn-common" name="submit" id="submit"
                                       value="Next (التالى)">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('script')
    <script>
        $('#qatarBimaxtra input').change(function (event) {
            const optPrice = Number($('#qatarBimaxtra input:checked').data('amount'));
            const baseAmt = Number($('#base-amt span').html());
            const passAmt = Number($('#pass-amt span').html());
            const totalAmt = baseAmt + passAmt + optPrice;
            $('#totalPrice').html(totalAmt.toFixed(2));
            $('#grandTotalPrice').html(totalAmt.toFixed(2));
            $('#opt-amt1 span').html(optPrice.toFixed(2));
        });
    </script>
@stop