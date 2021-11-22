@php
use App\Models\Share;
use Backpack\Settings\app\Models\Setting;
@endphp


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="images/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <title>
        @yield('pagetitle')
    </title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('css/all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome/css/all.min.css') }}"/>

	<style>
		.icon-doc {
			background-image: url({{ config('app.url') }}/images/doc-icon.png);
		}
	</style>

</head>
<body>
    <script src="{{ mix('js/all.min.js') }}"></script>

    @if (Setting::get('ENABLE_LOADING_ANIMATION') == Share::STATUS_ACTIVE and request('slug') == null and request('displayName') == null)
        <script src="{{ mix('js/queryloader2.min.js') }}" type="text/javascript"></script>
    @endif

    <main class="main">
        <header class="main__header">
            <div class="main__section main__section--bg-gerb pt-9">
                <div class="container text-center">
                    {{-- <img src="{{ config('app.url') }}/images/small-logo.svg" /> --}}
                    <h1>
                        @yield('pagetitle')
                    </h1>
                    <h1 style="display:block;">
                        @if (request('slug'))
                            @yield('company')
                        @else
                            {{ trans('messages.default_company') }}
                        @endif
                    </h1>
                </div>
            </div>
        </header>
        <div class="row contents">
            @yield('content')
        </div>


        <div class="btnUP" id="btnUp">
            <a href="#">
                <i class="icon icon-arr-btnUP"> </i>
            </a>
        </div>


    </main>

    <footer class="footer">
        <div class="row">

            <div class="col-md-6">


                @if (Setting::get('ENABLE_SUBSCRIPTION') == Share::STATUS_ACTIVE)
                    <!-- subscribe -->
                    <div class="mb-6 footer__form offset-3" id="subscription">
                        <h5 class="footer__links">
                            {{ trans('messages.subscribe_email') }}
                        </h5>
                        <form id="createSubscription" action="{{ route('subscribe') }}" method="POST" autocomplete="off">
                                    @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <div class="form-group__wrap">
                                            <label>
                                                {{ trans('messages.companies_email') }}
                                            </label>
                                            <input type="text" id="subscribe" name="email" class="form-control" placeholder="">
                                            <!-- errors -->
                                            <div class="print-msg" style="display:none">
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input class="btn btn-block btn-success btn-lg" type="submit" value="{{ trans('messages.subscribe') }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- subscribe -->

                    <script>
                    // Форма "Подписаться на рассылку"
                    $(function() {
                        $('form#createSubscription').submit(function(e) {
                            e.preventDefault();
                            var $form = $(this);
                            $.ajax({
                                type: $form.attr('method'),
                                url: $form.attr('action'),
                                data: $form.serialize(),
                                success: function(data) {
                                    if($.isEmptyObject(data.error)){
                                        hideMsg();
                                        printSuccessMsg();
                                        $("#subscribe").val("");
                                    } else {
                                        printErrorMsg(data.error);
                                    }
                                }
                            }).done(function() {
                            }).fail(function() {
                                console.log('fail');
                            });
                        });
                    });

                    // Отображение ошибок валидации
                    function printErrorMsg (msg) {
                        $(".print-msg").find("p").html('');
                        $(".print-msg").css('display','block');
                        $.each( msg, function( key, value ) {
                            $(".print-msg").find("p").append('<span class="text-danger">'+value+'</span>');
                        });
                    }

                    // Отображение ошибок валидации
                    function printSuccessMsg () {
                        $(".print-msg").find("p").html('');
                        $(".print-msg").css('display','block');
                        $(".print-msg").find("p").append('<span class="text-success"><?php echo trans('messages.subscription_successfull') ?></span>');
                    }

                    // Скрыть ошибки валидации
                    function hideMsg() {
                        $(".print-msg").css('display','none');
                    }
                    </script>
                @endif

            </div>


            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4 text-right">
                        {{-- <img style="margin-top: 20px;" src="{{ config('app.url') }}/images/small-logo.svg" /> --}}
                    </div>
                    <div class="col-md-8">
                        <div class="text-left">
                            <div class="footer__logo-title">
                                {{ trans('messages.frontend_footer_title') }}
                            </div>
                            <div class="footer__copyright">
                                &copy; {{ date("Y") }}
                            </div>
                            <div class="footer__links mar__20">
                                <a href="admin/" target="_blank">{{ trans('messages.frontend_footer_go_to_admin_panel') }}</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

<script>
// Кнопка "наверх"
$(window).scroll(function(){
    $('#btnUp').addClass('scrolled', $(this).scrollTop() > 100);
});
</script>


@if (Setting::get('ENABLE_LOADING_ANIMATION') == Share::STATUS_ACTIVE and request('slug') == null and request('displayName') == null)
<script>
    window.addEventListener('DOMContentLoaded', function() {
        QueryLoader2(document.querySelector("body"), {
            barColor: "#011736",
            backgroundColor: "#e8ebe6",
            percentage: true,
            barHeight: 3,
            minimumTime: 2000,
            fadeOutTime: 2000
        });
    });
</script>
@endif
