<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css' rel='stylesheet' />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Стили из нового шаблона -->

    <!-- Libs CSS -->
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/css/libs.bundle.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/css/theme.bundle.css" id="stylesheetLight" />
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/css/theme-dark.bundle.css" id="stylesheetDark" />

    @yield('styles')
    <style>body { display: none; }</style>


    <style type="text/css">

        *:hover,
        *:focus,
        *:active
        {
            outline: none;
            box-shadow: none !important;
            -webkit-appearance: none;
        }

        .test {
            overflow-x: auto;
            margin-bottom: 24px;
        }
        .breadcrumb{
            padding-left: 21px;
        }

        .component-fade-enter-active, .component-fade-leave-active {
            transition: opacity .3s ease;
        }
        .component-fade-enter, .component-fade-leave-to
            /* .component-fade-leave-active до версии 2.1.8 */ {
            opacity: 0;
        }

        .main-content{
            margin-left: 250px;
        }

    </style>
</head>

<div id="app">

    {{-- Если мы находимся на странице promouter, то убираем меню совсем--}}
    @if( Request :: is('promouter'))

    @else
        <menu-component></menu-component>

    @endif

    <div class="main-content">
        <div class="header">
            <div class="container-fluid">
                {{--              <div class="header-body">--}}
                {{--                <div class="row align-items-end">--}}
                {{--                  <div class="col">--}}
                {{--                  </div>--}}
                {{--                </div>--}}
                {{--              </div>--}}
            </div>
        </div>

        <div class="container-fluid">
            @if(session('message'))
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                    </div>
                </div>
            @endif
            @if($errors->count() > 0)
                <div class="alert alert-danger">
                    <ul class="list-unstyled">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

</div>




<body class="app header-fixed sidebar-fixed aside-menu-fixed pace-done sidebar-lg-show">

<script src="https://dashkit.goodthemes.co/assets/js/vendor.bundle.js"></script>

<!-- Theme JS -->
<script src="https://dashkit.goodthemes.co/assets/js/theme.bundle.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
@yield('scripts')
</body>

</html>
