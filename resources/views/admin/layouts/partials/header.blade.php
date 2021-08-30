<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta-tags')

    <title>
        @yield('page-title')
    </title>
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/feather-icon.css') }}">
    <!-- Plugins css start-->
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/animate.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/chartist.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/date-picker.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/owlcarousel.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/prism.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/style.css') }}">
{{--    <link id="color" rel="stylesheet" href="{{ asset('assets/dashboard/css/color-1.css') }}" media="screen">--}}
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/css/responsive.css')}}">

    <link rel="icon" href="{{ asset('images/sage_icon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/sage_icon.png') }}" type="image/x-icon">
    <style>
        .dataTables_wrapper table.dataTable th, .dataTables_wrapper table.dataTable td {
            white-space: nowrap;
        }
         table.dataTable.table thead th.sorting:after,
         table.dataTable.table thead th.sorting_asc:after,
         table.dataTable.table thead th.sorting_desc:after,
         table.dataTable.table thead td.sorting:after,
         table.dataTable.table thead td.sorting_asc:after,
         table.dataTable.table thead td.sorting_desc:after {
             right: 0;
         }
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:before,
        table.dataTable thead .sorting_desc_disabled:after {
            right: 3px;
        }
        div.dt-buttons {
            margin-bottom: 11px;
            float: none;
        }
        .modal-header, .close {
            background: #003399;
            color: #fff;
            opacity: 1;
            text-shadow: none;
        }
        .close:hover {
            color: #c5c5c5;
        }
    </style>
    @yield('page-styles')
</head>
