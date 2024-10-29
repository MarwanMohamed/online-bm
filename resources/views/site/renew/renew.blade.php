@extends('site.layout')
@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap4.min.css">

    <div class="breadcrumb">
        <div class="container">
            <div class="row">
                <nav>
                    <a class="breadcrumb-item" href="/"><i class="fa fa-home"></i> Home&nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    <span class="breadcrumb-item active" href="/renew">Renew Insurance</span>
                </nav>
            </div>
        </div>
    </div>

    <div id="free-promo">

        <div class="container">

            <div class="row">
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                <h6>Renew your existing policy with us</h6><br>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nmi_fullname">Enter Qatar ID / Vehicle Number / Reference Number</label>
                        <input type="text" class="form-control" name="search_with3" id="search_with3">
                    </div>
                    <div class="form-group">
                        <input type="button" class="btn btn-common" name="viewDetail" id="viewDetail"
                               value="View Details">
                    </div>
                </div>
            </div>


            <div class="row" style="padding-top:20px">
                <div id="mkPayGif"></div>
                <div id="reDetails" style="display: none;"></div>
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
@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript"
            src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            // validate signup form on keyup and submit

            $(document).on('click', '#viewDetail', function (e) {
                e.preventDefault();
                var search_with3 = $('#search_with3').val();
                if (search_with3 == "" || search_with3 == 0) {
                    $('#infMsg').remove();
                    $('#search_with3').after('<span style="color: red;font-style:italic;"  id="infMsg">This field must be filled</span>');
                    return false;
                }


                $.ajax({
                    type: "POST",
                    url: "/renew/getPolicyDetails",
                    data: {search_with3: search_with3},
                    dataType: "json",
                    success: function (data) {
                        $('#mkPayGif').html('<img src="/assets/img/spin.gif">');
                        $('#mkPayGif').show();
                        $('#reDetails').hide();
                        $('#infMsg').remove();
                        if (data == 0) {
                            setTimeout(function () {
                                $('#infMsg').remove();
                                $('#reDetails').after('<span style="font-size: 18px; color: #777;"  id="infMsg"><strong>No Information Available</strong></span>');
                                $('#mkPayGif').hide();
                            }, 600);

                        } else {
                            setTimeout(function () {
                                $('#infMsg').remove();
                                $('#reDetails').html(data);
                                $('#mkPayGif').hide();
                                $('#example').DataTable({
                                    lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
                                    fixedHeader: true,
                                    destroy: true //use for reinitialize datatable

                                });
                                $('#reDetails').show();
                            }, 600);


                        }
                        //console.log(data);
                    },
                    error: function () {
                        alert("Error posting feed.");
                    }
                });

            });
            //alert("submitted!");


//view the policy details starts------------------------------

            $(document).off('click', '.viewPolicy', function (e) {
                e.preventDefault();
            });
            $(document).on('click', '.viewPolicy', function (e) {
                e.preventDefault();
                var encryId = $(this).val();
                window.location.href = "/renew/renewview?token=" + encryId;

            });

// policy details ends here-------------------------------------
        });
    </script>
@stop
