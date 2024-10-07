@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.projects.archive') }}"  class="btn btn-outline btn-primary btn-sm">@lang('modules.tasks.viewArchive') <i class="feather-16" data-feather="archive"></i></a>

                        {{--<a href="{{ route('admin.project-template.index') }}"  class="btn btn-outline btn-primary btn-sm">@lang('app.menu.addProjectTemplate') <i class="fa fa-plus" aria-hidden="true"></i></a>--}}

                        <a href="#" onclick="addJob()" class="btn btn-outline btn-primary btn-sm">@lang('modules.projects.addNewProject') <i data-feather="plus"></i></a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(6)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>


    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->

            <div class="col-md-12 col-sm-12">
                <div class="filter-section">
                    <div class="card browser-widget bw-project">
                        <div class="media card-body" style="padding: 10px">
                            <div class="media-body align-self-center count_by_status">

                                <div id="total">
                                    <p>@lang('modules.dashboard.totalProjects')</p>
                                    <h4><span class="counter" id="totalWorkingDays">{{ $totalProjects }}</span></h4>
                                </div>
                                <div id="overdue">
                                    <p>@lang('modules.tickets.overDueProjects')</p>
                                    <h4><span class="counter" id="daysPresent">{{ $overdueProjects }}</span></h4>
                                </div>
                                <div id="notStarted">
                                    <p>@lang('app.notStarted') @lang('app.menu.jobs')</p>
                                    <h4><span class="counter" id="daysLate">{{ $notStartedProjects }}</span></h4>
                                </div>
                                <div id="finished">
                                    <p>@lang('modules.tickets.completedProjects')</p>
                                    <h4><span class="counter" id="halfDays">{{ $finishedProjects }}</span></h4>
                                </div>
                                <div id="inProcess">
                                    <p>@lang('app.inProgress') @lang('app.menu.jobs')</p>
                                    <h4><span class="counter" id="absentDays">{{ $inProcessProjects }}</span> </h4>
                                </div>
                                <div id="canceled">
                                    <p>@lang('app.canceled') @lang('app.menu.jobs')</p>
                                    <h4><span class="counter" id="holidayDays">{{ $canceledProjects }}</span></h4>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="product-grid">
                    <div class="feature-products">

                        <div class="row">
                            <div class="col-sm-3 p-absolute">
                                <div class="product-sidebar">
                                    <div class="filter-section">
                                        <div class="card">                                            
                                            <div class="left-filter wceo-left-filter" style="top: 68px;">
                                                <div class="card-body filter-cards-view animate-chk">
                                                    <div class="product-filter">
                                                        <form action="" id="filter-form">
                                                            <div class="row"  id="ticket-filters">
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.menu.projects') @lang('app.status')</label>
                                                                        <select class="select2 form-control" data-placeholder="@lang('app.menu.jobs') @lang('app.status')" id="status">
                                                                            <option selected value="all">@lang('app.all')</option>
                                                                            <option
                                                                                    value="not started">@lang('app.notStarted')
                                                                            </option>
                                                                            <option
                                                                                    value="in progress">@lang('app.inProgress')
                                                                            </option>
                                                                            <option
                                                                                    value="on hold">@lang('app.onHold')
                                                                            </option>
                                                                            <option
                                                                                    value="canceled">@lang('app.canceled')
                                                                            </option>
                                                                            <option
                                                                                    value="finished">@lang('app.finished')
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.clientName')</label>
                                                                        <select class="select2 form-control" data-placeholder="@lang('app.clientName')" id="client_id">
                                                                            <option selected value="all">@lang('app.all')</option>
                                                                            @foreach($clients as $client)
                                                                                <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('modules.projects.projectCategory')</label>
                                                                        <select class="select2 form-control" data-placeholder="@lang('modules.projects.projectCategory')" id="category_id">
                                                                            <option selected value="all">@lang('app.all')</option>
                                                                            @foreach($categories as $category)
                                                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="product-filter wceo-filter col-sm-6  pr-0">
                                                                    <button type="button" id="apply-filters" class="btn btn-primary btn-block"> @lang('app.apply')</button>
                                                                </div>
                                                                <div class="product-filter wceo-filter col-sm-6">
                                                                    <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-block pull-right"> @lang('app.reset')</button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($totalProjects == 0)
                        <div class="col-md-12">
                            <div class="empty-content text-center">
                                <img src="{{ asset('img/empty/jobs.jpg') }}" alt="user-img" /><br />
                                <b>No Jobs</b><br />
                                No jobs added yet.<br />
                                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm m-t-20">Add Job</a>
                            </div>
                        </div>
                    @else  
                        <div class="product-wrapper-grid">
                            <div class="row">
                                
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header d-none">
                                            <span class="d-none-productlist filter-toggle">
                                                <button class="btn btn-primary toggle-data">Filters<span class="ml-2"><i class="" data-feather="chevron-down"></i></span></button>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <div class="dt-ext table-responsive">
                                                {!! $dataTable->table(['class' => 'display']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>



    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

    {{--Job Add Modal--}}
    <div class="modal fade bs-modal-md in event-content" id="jobAddModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                @include('admin.event-calendar.job-add')
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

    {{--Client Add Modal--}}
    <div class="modal fade bs-modal-md in event-content" id="clientModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                @include('admin.event-calendar.client-add')
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>    

<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js')}}"></script>



{!! $dataTable->scripts() !!}
<script>    

    $(document).ready(function(){
        jQuery('.job_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            weekStart:'{{ $global->week_start }}',
            dateFormat: '{{ $global->date_picker_format }}',
            language:'en'
        });

        $('.job_time').datetimepicker({
            format: 'LT',
            icons: {
                time: 'fa fa-clock',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-check',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            },
            //onChangeDateTime:calculateTime
        });


        $('#cat_id').change(function (e) {
            var cat_id = $(this).val();        
            var url = "{{ route('admin.all-invoices.get-category-products',':id') }}";
            url = url.replace(':id', cat_id);
            $.easyAjax({
                type: 'GET',
                dataType: 'JSON',
                url: url,
                success: function (data) {
                    $('#products').html('');
                    $('#products').append('<option value="">--</option>');
                    $.each(data, function (index, data) {
                        $('#products').append('<option value="' + data.id + '">' + data.name + '</option>');
                    });
                }
            });
        });

        $('body').on('change', '#products', function () {
            var id = $(this).val();     
            $.easyAjax({
                url:'{{ route('admin.all-invoices.update-item-event') }}',
                type: "GET",
                data: { id: id },
                success: function(response) {
                    $('#jobInvoice').html(response.view);
                    var noOfRows = $(document).find('#jobInvoice .item-row').length;
                    var i = $(document).find('.item_name').length-1;
                    var itemRow = $(document).find('#jobInvoice .item-row:nth-child('+noOfRows+') select.type');
                    itemRow.attr('id', 'multiselect');
                    itemRow.attr('name', 'taxes['+i+'][]');
                    $(document).find('#multiselect').select2();
                    calculateTotal();
                    $("#products")[0].selectedIndex = 0;
                }
            });
        });

        $('#jobAddModal').on('keyup change','.type, .quantity, .cost_per_item', function () {
            calculateTotal();
        });

        function calculateTotal()
        {
            var subtotal = 0;
            //var discount = 0;
            var tax = '';
            var taxList = new Object();
            var taxTotal = 0;
            $(".quantity").each(function (index, element) {
                var itemTax = [];
                var itemTaxName = [];
                $('select.type option:selected').each(function (index) {
                    itemTax[index] = $(this).data('rate');
                    itemTaxName[index] = $(this).text();
                });
                var itemTaxId = $('select.type').val();
                var quantity =  $(this).val();
                var amount = parseFloat($('.cost_per_item').val()) * quantity;
                $('.amount').val(decimalupto2(amount).toFixed(2));

                if(isNaN(amount)){ amount = 0; }

                subtotal = (parseFloat(subtotal)+parseFloat(amount)).toFixed(2);

                if(itemTaxId != ''){
                    for(var i = 0; i<=itemTaxName.length; i++)
                    {
                        if(typeof (taxList[itemTaxName[i]]) === 'undefined'){
                            taxList[itemTaxName[i]] = ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                        }
                        else{
                            taxList[itemTaxName[i]] = parseFloat(taxList[itemTaxName[i]]) + ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                        }
                    }
                }
            });

            $.each( taxList, function( key, value ) {
                if(!isNaN(value)){
                    tax = tax+'<div class="offset-md-8 col-md-2 text-right p-t-10">'
                        +key
                        +'</div>'
                        +'<p class="form-control-static col-xs-6 col-md-2" >'
                        +'<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                        +'</p>';
                    taxTotal = taxTotal+decimalupto2(value);
                }
            });
            


        //calculate total
            var totalAfterDiscount = decimalupto2(subtotal);

            totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

            var total = decimalupto2(totalAfterDiscount+taxTotal);

            $('.total').html(total.toFixed(2));
            $('.total-field').val(total.toFixed(2));
            

        }

        function decimalupto2(num) {
            var amt =  Math.round(num * 100) / 100;
            return parseFloat(amt.toFixed(2));
        }

        $('#saveJob').click(function () {            
            $.easyAjax({
                url: '{{route('admin.projects.store')}}',
                container: '#createProject',
                type: "POST",
                redirect: true,
                data: $('#createProject').serialize(),
                success: function(response){
                    if(response.status == 'success'){
                        $('#createProject')[0].reset();
                        window.location.reload();
                    }              
                }
            })
        });

        //Create Client
        $('#newClient').change(function () {
            if($(this).is(':checked')){
                $('#clientModal').modal({
                    show:true,
                    backdrop: 'static',
                    keyboard: false,
                });
                $('#jobAddModal').modal('hide');
            }        
        });

        $("#clientModal").on("hidden.bs.modal", function () {
            $('#jobAddModal').modal('show');
        });

        $('#saveClient').click(function () {
            $.easyAjax({
                url: '{{route('admin.clients.store')}}',
                container: '#createClient',
                type: "POST",
                redirect: true,
                data: $('#createClient').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        $('#client_id').html(response.teamData);
                        $("#client_id").select2();
                        $('#clientModal').modal('hide');
                        $("#newClient").prop("checked", false);
                        $('#createClient')[0].reset();
                    }
                }
            })
        });

        $('#all_job_members').change(function() {
            if($('#all_job_members').is(":checked")){
                $("#job_user_id > option").prop("selected","selected");
                $("#job_user_id").trigger("change");
            } else {
                $("#job_user_id").val(null).trigger("change");
            }
        });
    });


    var table;    
    $(function() {
        showData();

        $('body').on('click', '.archive', function(){
            var id = $(this).data('user-id');
            swal({

                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.archiveMessage')",
                icon: "warning",
                buttons: ["@lang('messages.confirmNoArchive')", "@lang('messages.confirmArchive')"],
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {

                    var url = "{{ route('admin.projects.archive-delete',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'GET',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                showData();
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({

                title: "Are you sure?",
                text: "You will not be able to recover the deleted job!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {

                        var url = "{{ route('admin.projects.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    showData();
                                }
                            }
                        });
                    }
                });
            });        

        {{--$('#createProject').click(function(){
            var url = "{{ route('admin.projectCategory.create')}}";
            $('#modelHeading').html('Manage Project Category');
            $.ajaxModal('#projectCategoryModal',url);
        })--}}

    });

    function initCounter() 
    {
        $(".counter").counterUp({
            delay: 100,
            time: 1200
        });
    }

    function showData() 
    {
        $('#projects-table').on('preXhr.dt', function (e, settings, data) {
            var status = $('#status').val();
            var clientID = $('#client_id').val();
            var categoryID = $('#category_id').val();
            var teamID = $('#team_id').val();

            data['status'] = status;
            data['client_id'] = clientID;
            data['category_id'] = categoryID;
            data['team_id'] = teamID;
            data['status_by'] = null;
        });
        window.LaravelDataTables["projects-table"].search('').draw();
    }

    function showDataByStatus(id) 
    {
        $('#projects-table').on('preXhr.dt', function (e, settings, data) {
            data['status'] = '';
            data['client_id'] = '';
            data['category_id'] = '';
            data['team_id'] = '';
            var status_by = id;
            data['status_by'] = status_by;
        });
        window.LaravelDataTables["projects-table"].search('').draw();
    }

    $('#apply-filters').click(function (event) {
        event.preventDefault();
        $('.filter-section .count_by_status > div').removeClass('active');
        showData();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('.select2').val('all');
        $('#filter-form').find('select').select2();
        
        $('.filter-section .count_by_status > div').removeClass('active');
        $('#projects-table').on('preXhr.dt', function (e, settings, data) {
            data['status_by'] = null;
        });
        showData();
    });    

    $('.count_by_status div').on('click', function(event) {
        $('.count_by_status > div').removeClass('active');
        $(this).addClass('active');
        showDataByStatus($(this).attr('id'));
    });    

    initCounter();

    function exportData()
    {
        var status = $('#status').val();
        var clientID = $('#client_id').val();

        var url = "{{ route('admin.projects.export', [':status' ,':clientID']) }}";
        url = url.replace(':clientID', clientID);
        url = url.replace(':status', status);
        window.location.href = url;
    } 

    
    
/** Invoice Category */


    function addJob()
    {
        $('#jobAddModal').modal({
            show:true,
            backdrop: 'static',
            keyboard: false,
        });
    }

    function saveJob()
    {
        $.easyAjax({
            url: '{{route('admin.projects.store')}}',
            container: '#createProject',
            type: "POST",
            redirect: true,
            data: $('#createProject').serialize(),
            success: function(response){
                if(response.status == 'success'){
                    window.location.reload();
                }              
            }
        })
    }

    function editClosedJob(id)
    {
        var url = "{{ route('admin.projects.edit',':id') }}";
            url = url.replace(':id', id);

        swal({
            title: "Are you sure?",
            text: "Editing a Closed Job will Reopen the Job",
            icon: "{{ asset('img/warning.png')}}",
            buttons: ["CANCEL", "YES"],
            dangerMode: true
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location = url;
                }
            });        
    }

</script>
@endpush