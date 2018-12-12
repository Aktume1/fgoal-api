<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>FGOAL | Login</title>
        <meta name="description" content="Latest updates and statistic charts">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

        <!--begin::Web font -->
        {{ Html::script('bower_components/fgoal-assets/login/js/webfont.js') }}
        <script>
            WebFont.load({
                google: {
                    "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
                },
                active: function() {
                    sessionStorage.fonts = true;
                }
            });
        </script>

        {{ Html::style('bower_components/fgoal-assets/login/css/vendors.bundle.css') }}
        {{ Html::style('bower_components/fgoal-assets/login/css/style.bundle.css') }}
        {{ Html::style('images/favicon.ico') }}
    </head>

    <!-- end::Head -->

    <!-- begin::Body -->
    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url(images/bg-3.jpg);">
                <div class="m-grid__item m-grid__item--fluid    m-login__wrapper">
                    <div class="m-login__container" >
                        <div class="m-login__logo" style="margin-bottom: 0px">
                            <a href="#">
                                <img src="images/logo_fgoal.png" >
                            </a>
                        </div>
                        <div class="m-login__signin">
                            <div class="m-login__head">
                                <h3 class="m-login__title">Welcome to Fgoal</h3>
                            </div>
                            @foreach ($errors->all() as $error)
                                <p class="m-alert m-alert--outline alert alert-danger alert-dismissible fade show">{{ $error }}</p>
                            @endforeach
                            {!! Form::open(['method' => 'post', 'class' => 'm-login__form m-form']) !!}
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off">
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
                                </div>
                                <div class="row m-login__form-sub">
                                    <div class="col m--align-left m-login__form-left">
                                        <label class="m-checkbox  m-checkbox--focus">
                                            <input type="checkbox" name="remember"> Remember me
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col m--align-right m-login__form-right">
                                        <a href="javascript:;" id="m_login_forget_password" class="m-link">Forget Password ?</a>
                                    </div>
                                </div>
                                <div class="m-login__form-action">
                                    
                                        <!-- <button id="m_login_signin_submit" type="submit" class="btn btn-info m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Sign In</button> -->
                                        {!! Form::button('Login', ['type' => 'submit', 'class' => 'btn btn-info m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary', 'name' => 'submit']) !!}
                                        <a href="{{ route('framgia.login') }}" class="btn btn-success m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Login WSM</a>
                                    
                                </div>
                                {!! Form::token() !!}
                            {!! Form::close() !!}
                        </div>
                        <div class="m-login__forget-password">
                            <div class="m-login__head">
                                <h3 class="m-login__title">Forgotten Password ?</h3>
                                <div class="m-login__desc">Enter your email to reset your password:</div>
                            </div>
                            <form class="m-login__form m-form" action="">
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="text" placeholder="Email" name="email" id="m_email" autocomplete="off">
                                </div>
                                <div class="m-login__form-action">
                                    <button id="m_login_forget_password_submit" class="btn btn-primary m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primaryr">Request</button>
                                    <button id="m_login_forget_password_cancel" class="btn btn-outline-primary m-btn m-btn--pill m-btn--custom m-login__btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
        {{ Html::script('bower_components/fgoal-assets/login/js/vendors.bundle.js') }}
        {{ Html::script('bower_components/fgoal-assets/login/js/scripts.bundle.js') }}
        {{ Html::script('bower_components/fgoal-assets/login/js/login.js') }}
</html>
