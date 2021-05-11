<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">

    <!-- Libs CSS -->
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/fonts/feather/feather.min.css">
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/libs/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/libs/quill/dist/quill.core.css">
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/libs/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/libs/highlight.js/styles/vs2015.css">

    <!-- Map -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css' rel='stylesheet' />

    <!-- Theme CSS -->

    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/css/theme.min.css" id="stylesheetLight">

    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/css/theme-dark.min.css" id="stylesheetDark">

      <link href="{{ asset('css/style.css') }}" rel="stylesheet" />


    <title>@yield('title')</title>
  </head>
  <body>

    <div id="app">

        @yield('content')

    </div>

            <!-- JAVASCRIPT
    ================================================== -->
    <!-- Libs JS -->
    <script src="https://dashkit.goodthemes.co/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/@shopify/draggable/lib/es5/draggable.bundle.legacy.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/autosize/dist/autosize.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/chart.js/dist/Chart.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/dropzone/dist/min/dropzone.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/highlightjs/highlight.pack.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/list.js/dist/list.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/quill/dist/quill.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="https://dashkit.goodthemes.co/assets/libs/chart.js/Chart.extension.min.js"></script>

    <!-- Map -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>

    <!-- Theme JS -->
    <script src="https://dashkit.goodthemes.co/assets/js/theme.min.js"></script>

  </body>
</html>
