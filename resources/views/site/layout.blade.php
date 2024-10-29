<!DOCTYPE html>
<html lang="en" style="min-height:100%">
<head>
    <meta charset="utf-8">
    <!-- Viewport Meta Tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @isset($title)
            {{$title}}
        @else
            {{"Qatar Bima Online Insurance"}}
        @endisset
    </title>
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <!-- Main Style -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/main.css')}}">
    <!-- Responsive Style -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/responsive.css')}}">
    <!--Fonts-->
    <link rel="stylesheet" media="screen" href="{{asset('assets/fonts/font-awesome/font-awesome.min.css')}}">
    <!--Favicons-->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/img/favicon.png')}}">
    <!-- Color CSS Styles  -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/colors/rose.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery-ui.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/password_strength.css')}}">
    <style>
        form.cmxform label.error, label.error {
            /* remove the next line when you have trouble in IE6 with labels in list */
            color: #b50555;
            font-style: italic
        }

        input.error {
            border: 1px dotted red;
        }
    </style>

</head>
<body>
<div class="loader"></div>
<!-- Header area wrapper starts -->
<header id="header-wrap">
    <!-- Header area starts -->
    <section id="header">
        <!-- Navbar Starts -->
        <nav class="navbar navbar-light" data-spy="affix" data-offset-top="50">
            <div class="container">
                <button class='navbar-toggler hidden-md-up pull-xs-right' data-target='#main-menu'
                        data-toggle='collapse' type='button'>
                    ☰
                </button>
                <!-- Brand -->
                <a class="navbar-brand" href="/">
                    <img class="img-responsive" src="/assets/img/logo.png" alt="" style="height:inherit">
                </a>
                <div class="collapse navbar-toggleable-sm pull-xs-left pull-md-right" style="padding-top: 20px;"
                     id="main-menu">
                    <div class="navigation">
                        <ul class="nav nav-inline">
                            {{--                            <li class="nav-item dropdown"><a href="#" data-toggle="modal" data-target="#loginModal"><i--}}
                            {{--                                            class="fa fa-key">&nbsp;</i><strong>Login (دخول)</strong></a></li>--}}
                            {{--                            <li class="nav-item dropdown"><a href="#" data-toggle="modal"--}}
                            {{--                                                             data-target="#registerModal"><i--}}
                            {{--                                            class="fa fa-user">&nbsp;</i><strong>Register (تسجيل)</strong></a></li>--}}
                        </ul>
                    </div>
                    <div class="navigation">
                        <ul class="nav nav-inline">
                            {{--                            <li><a href="/dashboard/myprofile"><strong><i class="fa fa-user"></i>&nbsp;My--}}
                            {{--                                        Profile</strong></a></li>--}}
                            {{--                            <li><a href="/dashboard"><strong><i class="fa fa-dashboard"></i>&nbsp;Dashboard</strong></a>--}}
                            {{--                            </li>--}}
                            {{--                            <li><a href="/dashboard/logout"><strong>Logout</strong>&nbsp;<i class="fa fa-sign-out"></i></a>--}}
                            {{--                            </li>--}}
                        </ul>
                    </div>
                    <br><br>
                    <div class="text-center" style="color:#5cb85c"><strong></strong></div>
                    <!-- The Modal login -->
                    <div class="modal fade" id="loginModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="logFrm" class="cmxform">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h5 class="modal-title col-md-11">Login</h5>
                                        <button type="button" class="close col-md-1" data-dismiss="modal">&times;
                                        </button>
                                        <h6 class="col-md-12" style="font-size:14px">Please enter your login
                                            credentials</h6>
                                        <br><span id="noAccess" style="color:red;"></span>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">

                                                <div class="col-md-12 col-height">
                                                    <input type="text" class="form-control-1" name="logUsername"
                                                           id="logUsername" placeholder="Username">
                                                </div>
                                                <div class="col-md-12 col-height">
                                                    <input type="password" class="form-control-1" name="logPwd"
                                                           id="logPwd" placeholder="Password">
                                                </div>
                                                <div class="col-md-12 col-height">
                                                    <span class="text-center"><a href="/customer/forgot">Forgot Password?</a></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-danger" name="logSubmit" id="logSubmit"
                                               value="Submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="registerModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="regFrm" class="cmxform">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h5 class="modal-title col-md-11">Sign up</h5>
                                        <button type="button" class="close col-md-1" data-dismiss="modal">&times;
                                        </button>
                                        <h6 class="col-md-12" style="font-size:14px">Please enter your correct
                                            information to register with us</h6>
                                        <br><span id="existUser" style="color:red;"></span>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="row col-md-12">
                                            <div class="col-md-6">
                                                <div class="radio">
                                                    <label><input type="radio" name="ownerTypeR" value="I" checked>
                                                        Individual</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="radio">
                                                    <label><input type="radio" name="ownerTypeR" value="O"> Organisation</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="col-md-6 col-height">
                                                <input type="text" class="form-control-1" name="reg_fullname"
                                                       id="reg_fullname" placeholder="Full Name">
                                            </div>
                                            <div class="col-md-6 col-height">
                                                <input type="text" class="form-control-1" name="reg_username"
                                                       id="reg_username" placeholder="Username">
                                            </div>
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="col-md-6 col-height" id="myThirdPassword">
                                            </div>
                                            <div class="col-md-6 col-height">
                                                <input type="password" class="form-control-1" name="confirm_pwd"
                                                       id="confirm_pwd" placeholder="Confirm Password">
                                            </div>
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="col-md-6 col-height">
                                                <input type="email" class="form-control-1" name="reg_email"
                                                       id="reg_email" placeholder="Email">
                                            </div>
                                            <div class="col-md-6 col-height" id="establishQR">
                                                <input type="text" class="form-control-1" name="reg_qid" id="reg_qid"
                                                       maxlength="11" placeholder="Qatar ID">
                                            </div>
                                            <div class="col-md-6" id="establishER" style="display:none;">
                                                <input type="text" class="form-control-1" name="reg_establish_id"
                                                       placeholder="Establish Id" id="reg_establish_id" maxlength="8">
                                            </div>
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="col-md-6 col-height">
                                                <input type="text" class="form-control-1" name="reg_mobile"
                                                       id="reg_mobile" placeholder="Mobile Number" maxlength="8">
                                            </div>
                                            <div class="row col-md-6 col-height">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <input type="submit" name="regSubmit" id="regSubmit" class="btn btn-danger"
                                               value="Submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Navbar Starts Hiding for a while-->
                </div>
            </div>
        </nav>
        <!-- Navbar Ends -->
    </section>
</header>
<!-- Header-wrap Section End -->
<!-- Page Header End -->

@yield('content')


<footer class="footer-bottom">
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12">

                    <div class="navigation">
                        <ul>
                            <li><a href="#"><strong>Terms & Condition</strong></a></li>
                            <li><a href="#"><strong>Privacy policy</strong></a></li>
                            <li><a href="#"><strong>About us</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="navigation right-float">
                        <ul>
                            <li><a href="#"><strong>General Insurance </strong></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <p class="copyright-text">
                        &copy; <?php echo date("Y"); ?> Qatar Bima International W.L.L - Design and Development by <a
                                style="color:#fff; font-weight:600" href="http://bluelynx.qa" target="_blank">Blue Lynx
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright  End-->
</footer>
<!-- Footer Section End-->

<!-- Go To Top Link -->
<a href="#" class="back-to-top">
    <i class="fa fa-angle-up">
    </i>
</a>

<!-- JavaScript & jQuery Plugins -->
<!-- jQuery Load -->
<script src="{{asset('assets/js/jquery-min.js')}}"></script>
<!-- Bootstrap JS -->
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<!--WOW Scroll Spy-->
<script src="{{asset('assets/js/wow.js')}}"></script>
<!-- OWL Carousel -->
<script src="{{asset('assets/js/owl.carousel.js')}}"></script>
<!-- ScrollTop -->
<script src="{{asset('assets/js/scroll-top.js')}}"></script>
<!-- Appear -->
<script src="{{asset('assets/js/jquery.appear.js')}}"></script>
<!-- All JS plugin Triggers -->
<script src="{{asset('assets/js/main.js')}}"></script>
<!--for validation-->
<script src="{{asset('assets/js/jquery.validate.js')}}"></script>
<script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/password_strength_lightweight.js')}}"></script>
<script type="text/javascript">
    //login form----------------------------
    $(document).ready(function () {
        //script for body onload gif------------------
        $('.loader').fadeOut();
        //ends here
        $("#myThirdPassword").strength_meter({
            strengthMeterClass: 't_strength_meter'
        });
        // validate signup form on keyup and submit
        var validatorLog;
        submitHandlerLog = function () {
            var lguser = $('#logUsername').val();
            var lgpass = $('#logPwd').val();
            $.ajax({
                type: "POST",
                url: "/customer/signin",
                data: {username: lguser, password: lgpass},
                dataType: "html",
                success: function (res) {
                    console.log(res);
                    if (res == 0) {
                        $('#logSubmit').val('please wait...');
                        setTimeout(function () {
                            $('#noAccess').text('Access denied!!');
                            $('#logSubmit').val('Submit');
                        }, 500);
                        return false;
                    } else {
                        $('#logSubmit').prop('disabled', true);
                        $('#logSubmit').val('please wait...');
                        setTimeout(function () {
                            window.location.href = "/dashboard";
                            $('#loginModal').hide();
                        }, 400);
                    }
                },
                error: function () {
                    alert("Error posting feed.");
                }
            });
        };
        validatorLog = $("#logFrm").validate({
            rules: {
                logUsername: {
                    required: true
                },
                logPwd: {
                    required: true
                }
            },
            messages: {
                logUsername: {
                    required: "Enter your username"
                },
                logPwd: {
                    required: "Enter your password"
                }
            },
            submitHandler: submitHandlerLog
        });
    });

    //registration------------------------------------------
    $(document).ready(function () {
        //owner type radio button change-------------------------------
        $('input:radio[name=ownerTypeR]').change(function () {
            if (this.value == "I") {
                $('#reg_fullname').attr("placeholder", "Full Name");
                $('#establishER').hide();
                $('#establishQR').show();
                $('#reg_establish_id').val("");

            } else if (this.value == "O") {
                $('#reg_fullname').attr("placeholder", "Company Name");
                $('#establishQR').hide();
                $('#establishER').show();
                $('#reg_qid').val("");
            }
        });
        //ends here----------------------------
        // validate signup form on keyup and submit
        var validatorReg;
        submitHandlerReg = function () {
            $('#regSubmit').val('Please Wait...');
            $.ajax({
                type: "POST",
                url: "/customer/signup",
                data: {
                    fullname: $('#reg_fullname').val(),
                    password: $('#reg_password').val(),
                    owner_type: $("input[name='ownerTypeR']:checked").val(),
                    email: $('#reg_email').val(),
                    mobile_no: $('#reg_mobile').val(),
                    username: $('#reg_username').val(),
                    qid: ($('#reg_qid').val()) ? $('#reg_qid').val() : $('#reg_establish_id').val()
                },
                dataType: "html",
                success: function (res) {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        $('#regSubmit').prop('disabled', true);
                        $('#registerModal').hide();
                        window.location.href = "/customer";
                    } else {
                        $('#existUser').text(Object.values(res.data)[0]);
                        $('#regSubmit').val('Submit');
                        return false;
                    }
                },
                error: function () {
                    alert("Error posting feed.");
                    $('#regSubmit').val('Submit');
                }
            });
            //alert("submitted!");
        };
        validatorReg = $("#regFrm").validate({
            rules: {
                reg_fullname: {
                    required: true,
                    minlength: 2
                },
                reg_password: {
                    required: true,
                    minlength: 5
                },
                reg_email: {
                    required: true,
                    email: true
                },
                reg_mobile: {
                    required: true,
                    digits: true,
                    minlength: 8
                },
                reg_username: {
                    required: true,
                    minlength: 5
                },
                confirm_pwd: {
                    required: true,
                    minlength: 5,
                    equalTo: "#reg_password"
                },
                reg_establish_id: {
                    required: true,
                    minlength: 8
                },
                reg_qid: {
                    required: true,
                    minlength: 11
                }

            },
            messages: {
                reg_fullname: {
                    required: "Enter your Full/Company name",
                    minlength: "Minimum 2 characters"
                },
                reg_password: {
                    required: "Password is required",
                    minlength: "Minimum 5 characters"
                },
                reg_email: {
                    required: "Email ID is required",
                    email: "Invalid E-mail ID"
                },
                reg_mobile: {
                    required: "Valid Mobile No. is required",
                    minlength: "Minimum 8 digits",
                    digits: "Invalid Mobile No."
                },
                reg_username: {
                    required: "Enter your preferred username",
                    minlength: "Minimum  5 characters"
                },
                confirm_pwd: {
                    required: "Confirm your password",
                    minlength: "Minimum 5 characters",
                    equalTo: "Password does not match"
                },
                reg_establish_id: {
                    required: "Enter establishment ID",
                    minlength: "Minimum 8 characters"
                },
                reg_qid: {
                    required: "Valid Qatar ID is required",
                    minlength: "Minimum 11 characters"
                }

            },
            submitHandler: submitHandlerReg
        });
    });
</script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-6765085079838418",
        enable_page_level_ads: true
    });
</script>
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '433672eff4d48a5b5e986d103a87178ff1bc746c';
    window.smartsupp || (function (d) {
        var s, c, o = smartsupp = function () {
            o._.push(arguments)
        };
        o._ = [];
        s = d.getElementsByTagName('script')[0];
        c = d.createElement('script');
        c.type = 'text/javascript';
        c.charset = 'utf-8';
        c.async = true;
        c.src = 'https://www.smartsuppchat.com/loader.js?';
        s.parentNode.insertBefore(c, s);
    })(document);
</script>
@yield('script')
</body>
</html>