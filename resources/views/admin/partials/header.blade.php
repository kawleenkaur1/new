<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from designreset.com/cork/ltr/demo3/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 22 May 2020 09:01:59 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>KATLEGO ADMIN </title>
    <link rel="icon" type="image/x-icon" href="{{isset($yt_app_settings->logo_url) ?$yt_app_settings->logo_url:asset('public/uploads/essentials/logo1.png')}}"/>
    <link href="{{asset('public/lib/assets/css/loader.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('public/lib/assets/js/loader.js')}}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{asset('public/lib/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type= "text/css" />
    
    <link href="{{asset('public/lib/assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{asset('public/lib/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('public/lib/assets/css/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" />


    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/assets/css/forms/switches.css') }}">
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <link href="{{asset('public/lib/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" type="text/css" href="{{asset('public/lib/assets/css/elements/alert.css')}}">

    <link href="{{asset('public/lib/assets/css/components/custom-modal.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .btn-light { border-color: transparent; }
    </style>
    <!--  END CUSTOM STYLE FILE  -->

    <link rel="stylesheet" type="text/css" href="{{asset('public/lib/plugins/table/datatable/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/lib/plugins/table/datatable/dt-global_style.css')}}">

    <link rel="stylesheet" href="{{asset('public/lib/plugins/font-icons/fontawesome/css/regular.css')}}">
    <link rel="stylesheet" href="{{asset('public/lib/plugins/font-icons/fontawesome/css/fontawesome.css')}}">

    <link href="{{asset('public/lib/plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('public/lib/plugins/noUiSlider/nouislider.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('public/lib/plugins/flatpickr/custom-flatpickr.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('public/lib/plugins/noUiSlider/custom-nouiSlider.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('public/lib/plugins/bootstrap-range-Slider/bootstrap-slider.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('public/lib/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/lib/assets/css/elements/search.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/lib/plugins/file-upload/file-upload-with-preview.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('public/lib/assets/css/elements/custom-pagination.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('public/lib/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('public/lib/assets/css/dashboard/dash_2.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{asset('public/lib/plugins/editors/markdown/simplemde.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('public/lib/plugins/select2/select2.min.css')}}">

     <!-- BEGIN THEME GLOBAL STYLES -->
     <link href="{{asset('public/lib/plugins/animate/animate.css')}}" rel="stylesheet" type="text/css" />
     <script src="{{asset('public/lib/plugins/sweetalerts/promise-polyfill.js')}}"></script>
     <link href="{{asset('public/lib/plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
     <link href="{{asset('public/lib/plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
     <link href="{{asset('public/lib/assets/css/components/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />
     <!-- END THEME GLOBAL STYLES -->
  <!-- toastr -->
  <link href="{{asset('public/lib/plugins/notification/snackbar/snackbar.min.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>


  <!-- Latest compiled and minified CSS -->
  {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}

</head>
<body>
    <style>
        .green_circle{
            color:#0efa0e;
        }
        .red_circle{
            color:red
        }
        .gray_circle{
            color:gray
        }
        .table > tbody > tr > td {
    border: none;
    color: #000000;
    font-size: 13px;
    letter-spacing: 0px;
}

.table > thead > tr > th {
    color: #1b55e2;
    font-weight: 700;
    font-size: 13px;
    border: none;
    letter-spacing: 1px;
    text-transform: initial;
}
    </style>
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->
