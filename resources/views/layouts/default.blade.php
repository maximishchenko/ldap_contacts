@php
use App\Models\Share;
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

    <title>{{ trans('messages.frontend_main_title') }}</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('css/all.min.css') }}"/>

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
                        {{ trans('messages.frontend_main_title') }}
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


                @yield('subscribe')

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
