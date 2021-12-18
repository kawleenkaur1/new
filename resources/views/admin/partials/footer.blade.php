<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="{{asset('public/lib/assets/js/libs/jquery-3.1.1.min.js')}}"></script>
<script src="{{asset('public/lib/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/lib/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('public/lib/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('public/lib/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('public/lib/assets/js/app.js')}}"></script>

<script src="{{asset('public/lib/assets/js/custom.js')}}"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

<script src="{{asset('public/lib/plugins/highlight/highlight.pack.js')}}"></script>
<!-- END GLOBAL MANDATORY STYLES -->

<!--  BEGIN CUSTOM SCRIPT FILE  -->
<script src="{{asset('public/lib/assets/js/scrollspyNav.js')}}"></script>
<script src="{{asset('public/lib/plugins/blockui/jquery.blockUI.min.js')}}"></script>

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="{{asset('public/lib/plugins/apex/apexcharts.min.js')}}"></script>
<script src="{{asset('public/lib/assets/js/dashboard/dash_1.js')}}"></script>


<script src="{{asset('public/lib/plugins/flatpickr/flatpickr.js')}}"></script>
    <script src="{{asset('public/lib/plugins/noUiSlider/nouislider.min.js')}}"></script>

    <script src="{{asset('public/lib/plugins/flatpickr/custom-flatpickr.js')}}"></script>
    <script src="{{asset('public/lib/plugins/noUiSlider/custom-nouiSlider.js')}}"></script>
    <script src="{{asset('public/lib/plugins/bootstrap-range-Slider/bootstrap-rangeSlider.js')}}"></script>

    <script src="{{asset('public/lib/assets/js/elements/custom-search.js')}}"></script>


    <script src="{{asset('public/lib/plugins/file-upload/file-upload-with-preview.min.js')}}"></script>

    <script src="{{asset('public/lib/plugins/editors/markdown/simplemde.min.js')}}"></script>
    <script src="{{asset('public/lib/plugins/editors/markdown/custom-markdown.js')}}"></script>

    <script src="{{asset('public/lib/plugins/select2/select2.min.js')}}"></script>
    <script src="{{asset('public/lib/plugins/select2/custom-select2.js')}}"></script>

    <script>

$(".tagging").select2({
    tags: true
});
    </script>

     <!-- BEGIN THEME GLOBAL STYLE -->
     <script src="{{asset('public/lib/plugins/sweetalerts/sweetalert2.min.js')}}"></script>
     <script src="{{asset('public/lib/plugins/sweetalerts/custom-sweetalert.js')}}"></script>

     <script src="{{asset('public/lib/plugins/notification/snackbar/snackbar.min.js')}}"></script>
     <!-- END PAGE LEVEL PLUGINS -->

     <!--  BEGIN CUSTOM SCRIPTS FILE  -->
     <script src="{{asset('public/lib/assets/js/components/notification/custom-snackbar.js')}}"></script>



     <!-- END THEME GLOBAL STYLE -->
    <script>
         new SimpleMDE({
            element: document.getElementById("html_editor0"),
            spellChecker: true,
            // autosave: {
            //     enabled: true,
            //     unique_id: "html_editor1",
            // },
        });
        new SimpleMDE({
            element: document.getElementById("html_editor1"),
            spellChecker: true,
            // autosave: {
            //     enabled: true,
            //     unique_id: "html_editor1",
            // },
        });
        new SimpleMDE({
            element: document.getElementById("html_editor2"),
            spellChecker: true,
            // autosave: {
            //     enabled: true,
            //     unique_id: "html_editor2",
            // },
        });
        new SimpleMDE({
            element: document.getElementById("html_editor3"),
            spellChecker: true,
            // autosave: {
            //     enabled: true,
            //     unique_id: "html_editor3",
            // },
        });
    </script>

    <script>
        //First upload
        var firstUpload = new FileUploadWithPreview('myFirstImage')
        //Second upload
        var secondUpload = new FileUploadWithPreview('mySecondImage')
    </script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script>
    $(document).ready(function() {
        App.init();
    });
</script>



<script>
    var f3 = flatpickr(document.getElementById('rangeCalendarFlatpickr'), {
    mode: "range"
});
    function get_user_details(id,url){
        $.get(url, function( data ) {


            $('#view_user_details').on('shown.bs.modal', function(){
                $('#view_user_details .load_modal').html(data);
                $('.modal-header .modal-title').html('#'+id);
            });
            $('#view_user_details').on('hidden.bs.modal', function(){
                $('#view_user_details .modal-body').data('');
            });
            $('#view_user_details').modal();
        });
    }

    function load_data_view(id,url,element_id='view_details'){
        $.get(url, function( data ) {


            $('#'+element_id).on('shown.bs.modal', function(){
                $('#'+element_id+' .load_modal').html(data);
                $('.modal-header .modal-title').html('#'+id);
            });
            $('#'+element_id).on('hidden.bs.modal', function(){
                $('#'+element_id+' .modal-body').data('');
            });
            $('#'+element_id).modal();
        });
    }

    function load_category_for_edit(id,url){
        $.get(url, function( data ) {


            $('#edit_vehicle_category').on('shown.bs.modal', function(){
                $('#edit_vehicle_category .load_modal').html(data);
                // $('.modal-header .modal-title').html('#'+id);
            });
            $('#edit_vehicle_category').on('hidden.bs.modal', function(){
                $('#edit_vehicle_category .modal-body').data('');
            });
            $('#edit_vehicle_category').modal();
        });
    }
    function load_subcategory_for_edit(id,url){
        $.get(url, function( data ) {


            $('#edit_vehicle_category').on('shown.bs.modal', function(){
                $('#edit_vehicle_category .load_modal').html(data);
                // $('.modal-header .modal-title').html('#'+id);
            });
            $('#edit_vehicle_category').on('hidden.bs.modal', function(){
                $('#edit_vehicle_category .modal-body').data('');
            });
            $('#edit_vehicle_category').modal();
        });
    }

    function load_data_for_edit(id,url){
        $.get(url, function( data ) {


            $('#edit').on('shown.bs.modal', function(){
                $('#edit .load_modal').html(data);
                // $('.modal-header .modal-title').html('#'+id);
            });
            $('#edit').on('hidden.bs.modal', function(){
                $('#edit .modal-body').data('');
            });
            $('#edit').modal();
        });
    }

    function load_vehicle_type_for_edit(id,url){
        $.get(url, function( data ) {


            $('#edit_vehicle_type').on('shown.bs.modal', function(){
                $('#edit_vehicle_type .load_modal').html(data);
                // $('.modal-header .modal-title').html('#'+id);
            });
            $('#edit_vehicle_type').on('hidden.bs.modal', function(){
                $('#edit_vehicle_type .modal-body').data('');
            });
            $('#edit_vehicle_type').modal();
        });
    }

    function load_package_for_edit(id,url){
        $.get(url, function( data ) {


            $('#edit_package').on('shown.bs.modal', function(){
                $('#edit_package .load_modal').html(data);
                // $('.modal-header .modal-title').html('#'+id);
            });
            $('#edit_package').on('hidden.bs.modal', function(){
                $('#edit_package .modal-body').data('');
            });
            $('#edit_package').modal();
        });
    }

    function readURL(input,id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#'+id)
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200).show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function fetch_subcategory(category_id='category',subcategory_id='subcategory',caller='#'){
        // $(''+caller+''+category_id+'').on('keyup',function(e){
        //     const id = $(this).val();
        //     if(id == ''){
        //         $(''+caller+''+subcategory_id+'').html('<option value="">Choose...</option>');
        //     }
        //     const url="{{url('subcategories_by_category_dropdown_html')}}/"+id;
        //     $.get(url, function( data ) {
        //         // console.log(data);
        //         $(''+caller+''+subcategory_id+'').html(data);
        //     });
        // });
        $(''+caller+category_id+'').on('change',function(e){
            const id = $(this).val();
            $(''+caller+subcategory_id+'').html('<option value="">Choose...</option>');
            const url="{{url('subcategories_by_category_dropdown_html')}}/"+id;
            $.get(url, function( data ) {
                // console.log(data);
                $(''+caller+subcategory_id+'').html(data);
            });
        });
    }

    // var f3 = flatpickr(document.getElementById('rangeCalendarFlatpickr'), {
    //     mode: "range"
    // });


</script>

<script src="{{asset('public/lib/plugins/table/datatable/datatables.js')}}"></script>

<script>
    $('#zero-config').DataTable({
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
           "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        "pageLength": 10
    });

    $('#zero-config-2').DataTable({
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
           "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        "pageLength": 10
    });
</script>

@yield('scripts')



<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script>

   CKEDITOR.replace( 'editor11');
   CKEDITOR.replace( 'editor12');
   CKEDITOR.replace( 'editor13');
   CKEDITOR.replace( 'editor14');
   CKEDITOR.replace( 'editor15');

</script>
</body>

</html>
