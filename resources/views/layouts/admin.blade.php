<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />--}}
{{--    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />--}}
{{--    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />--}}
{{--    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />--}}
{{--    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />--}}
{{--    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />--}}
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />--}}
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />--}}
<!-- <link href="https://unpkg.com/@coreui/coreui@2.1.16/dist/css/coreui.min.css" rel="stylesheet" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    <!-- Стили из нового шаблона -->
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/fonts/feather/feather.css">
    {{--    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/libs/flatpickr/dist/flatpickr.min.css">--}}
    {{--    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/libs/quill/dist/quill.core.css">--}}
    {{--    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/libs/select2/dist/css/select2.min.css">--}}
    {{--    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/libs/highlight.js/styles/vs2015.css">--}}
    <link href='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/css/theme.min.css" id="stylesheetLight">
    <link rel="stylesheet" href="https://dashkit.goodthemes.co/assets/css/theme-dark.min.css" id="stylesheetDark">
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">--}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Стили из нового шаблона -->

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


<!-- Скрипты из нового шаблона -->
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/jquery/dist/jquery.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/@shopify/draggable/lib/es5/draggable.bundle.legacy.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/autosize/dist/autosize.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/chart.js/dist/Chart.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/dropzone/dist/min/dropzone.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/flatpickr/dist/flatpickr.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/highlightjs/highlight.pack.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/jquery-mask-plugin/dist/jquery.mask.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/list.js/dist/list.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/quill/dist/quill.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/select2/dist/js/select2.min.js"></script>--}}
{{--    <script src="https://dashkit.goodthemes.co/assets/libs/chart.js/Chart.extension.min.js"></script>--}}
{{--    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>--}}
<script src="https://dashkit.goodthemes.co/assets/js/theme.min.js"></script>
<!-- Скрипты из нового шаблона -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>--}}
{{--    <script src="https://unpkg.com/@coreui/coreui@2.1.16/dist/js/coreui.min.js"></script>--}}
{{--    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>--}}
{{--    <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>--}}
{{--    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>--}}
{{--    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>--}}
{{--    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>--}}
{{--    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>--}}
{{--    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>--}}
{{--    <script src="{{ asset('js/main.js') }}"></script>--}}
{{--    <script>--}}
{{--        $(function() {--}}
{{--  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'--}}
{{--  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'--}}
{{--  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'--}}
{{--  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'--}}
{{--  let printButtonTrans = '{{ trans('global.datatables.print') }}'--}}
{{--  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'--}}
{{--  let selectAllButtonTrans = '{{ trans('global.select_all') }}'--}}
{{--  let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'--}}

{{--  let languages = {--}}
{{--    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json'--}}
{{--  };--}}

{{--  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })--}}
{{--  $.extend(true, $.fn.dataTable.defaults, {--}}
{{--    language: {--}}
{{--      url: languages['{{ app()->getLocale() }}']--}}
{{--    },--}}
{{--    columnDefs: [{--}}
{{--        orderable: false,--}}
{{--        className: 'select-checkbox',--}}
{{--        targets: 0--}}
{{--    }, {--}}
{{--        orderable: false,--}}
{{--        searchable: false,--}}
{{--        targets: -1--}}
{{--    }],--}}
{{--    select: {--}}
{{--      style:    'multi+shift',--}}
{{--      selector: 'td:first-child'--}}
{{--    },--}}
{{--    order: [],--}}
{{--    scrollX: true,--}}
{{--    pageLength: 100,--}}
{{--    dom: 'lBfrtip<"actions">',--}}
{{--    buttons: [--}}
{{--      {--}}
{{--        extend: 'selectAll',--}}
{{--        className: 'btn-sm btn-primary',--}}
{{--        text: selectAllButtonTrans,--}}
{{--        exportOptions: {--}}
{{--          columns: ':visible'--}}
{{--        }--}}
{{--      },--}}
{{--      {--}}
{{--        extend: 'selectNone',--}}
{{--        className: 'btn-sm btn-primary',--}}
{{--        text: selectNoneButtonTrans,--}}
{{--        exportOptions: {--}}
{{--          columns: ':visible'--}}
{{--        }--}}
{{--      },--}}
{{--      {--}}
{{--        extend: 'copy',--}}
{{--        className: 'btn btn-sm btn-outline-primary',--}}
{{--        text: copyButtonTrans,--}}
{{--        exportOptions: {--}}
{{--          columns: ':visible'--}}
{{--        }--}}
{{--      },--}}
{{--      {--}}
{{--        extend: 'csv',--}}
{{--        className: 'btn btn-sm btn-outline-primary',--}}
{{--        text: csvButtonTrans,--}}
{{--        exportOptions: {--}}
{{--          columns: ':visible'--}}
{{--        }--}}
{{--      },--}}
{{--      {--}}
{{--        extend: 'excel',--}}
{{--        className: 'btn btn-sm btn-outline-primary',--}}
{{--        text: excelButtonTrans,--}}
{{--        exportOptions: {--}}
{{--          columns: ':visible'--}}
{{--        }--}}
{{--      },--}}
{{--      {--}}
{{--        extend: 'pdf',--}}
{{--        className: 'btn btn-sm btn-outline-primary',--}}
{{--        text: pdfButtonTrans,--}}
{{--        exportOptions: {--}}
{{--          columns: ':visible'--}}
{{--        }--}}
{{--      },--}}
{{--      {--}}
{{--        extend: 'print',--}}
{{--        className: 'btn btn-sm btn-outline-primary',--}}
{{--        text: printButtonTrans,--}}
{{--        exportOptions: {--}}
{{--          columns: ':visible'--}}
{{--        }--}}
{{--      },--}}
{{--      // {--}}
{{--      //   extend: 'colvis',--}}
{{--      //   className: 'btn-default',--}}
{{--      //   text: colvisButtonTrans,--}}
{{--      //   exportOptions: {--}}
{{--      //     columns: ':visible'--}}
{{--      //   }--}}
{{--      // }--}}
{{--    ]--}}
{{--  });--}}

{{--  $.fn.dataTable.ext.classes.sPageButton = '';--}}
{{--});--}}

{{--    </script>--}}
@yield('scripts')
</body>

</html>
