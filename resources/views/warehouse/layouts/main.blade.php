<?php $get_settings = yt_app_settings();

$yt_app_settings['yt_app_settings'] = $get_settings;
?>

@include('admin.partials.header',$yt_app_settings)
@include('admin.partials.navbar',$yt_app_settings)

<style>
    .form-group label, label {
    font-size: 15px;
    color: #070707;
    letter-spacing: 1px;
}
label {
    display: inline-block;
    margin-bottom: 8px;
}
</style>

   <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>
        @include('admin.partials.sidebar')

         <!--  BEGIN CONTENT AREA  -->
         <style>
            .table-responsive{
             overflow-x:auto;
            }
         </style>
         <div id="content" class="main-content">
            @include('admin.partials.flash')
             @yield('content')
         </div>

         <div class="footer-wrapper">
            <div class="footer-section f-section-1">
                {{-- <p class="">Copyright © {{date('Y')}} <a target="_blank" href="/">ROZANA</a>, All rights reserved.</p> --}}
            </div>
            <div class="footer-section f-section-2">
                <p class="">Copyright © {{date('Y')}} <a target="_blank" href="/">KATLEGO</a>. Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
            </div>
        </div>
         <!--  END CONTENT AREA  -->
    </div>
     <!--  END MAIN CONTAINER  -->
@include('admin.partials.footer',$yt_app_settings)


