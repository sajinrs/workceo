@extends('layouts.member-app')

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
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>

                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('member.leads.create') }}" class="btn btn-primary btn-sm">@lang('modules.lead.addNewLead') <i class="fa fa-plus"></i> </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->

            <div class="col-sm-12 d-none">
                    <div class="product-wrapper-grid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    
                                    <div class="card-body">
                                        <div class="browser-widget filter-section">
                                            <div class="media">
                                                <div class="media-body align-self-center count_by_status">

                                                    <div id="totalLeads">
                                                        <p>@lang('modules.dashboard.totalLeads')</p>
                                                        <h4><span class="counter" id="totalWorkingDays">{{ $totalLeads }}</span></h4>
                                                    </div>
                                                    <div id="totalClientConverted">
                                                        <p>@lang('modules.dashboard.totalConvertedClient')</p>
                                                        <h4><span class="counter" id="daysPresent">{{$totalClientConverted}}</span></h4>
                                                    </div>
                                                    <div id="totalPendingFollowUps">
                                                        <p>@lang('modules.dashboard.totalPendingFollowUps')</p>
                                                        <h4><span class="counter" id="daysLate">{{ $pendingLeadFollowUps }}</span></h4>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            
                <div class="col-sm-12">
                <div class="product-grid">
                    @if($user->can('view_lead'))
                    <div class="feature-products">
                        <div class="row">
                            <div class="col-sm-3 p-absolute">
                                <div class="product-sidebar">
                                    <div class="filter-section">
                                        <div class="card">
                                            
                                            <div class="left-filter wceo-left-filter">
                                                <div class="card-body filter-cards-view animate-chk">
                                                    <div class="product-filter">
                                                        <form action="" id="filter-form">
                                                            <div class="row"  id="ticket-filters">
                                                                    <div class="product-filter col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="f-w-600">@lang('modules.lead.client')</label>
                                                                            <select class="select2 form-control" name="client" id="client">
                                                                                <option value="all">@lang('modules.lead.all')</option>
                                                                                <option value="lead">@lang('modules.lead.lead')</option>
                                                                                <option value="client">@lang('modules.lead.client')</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-filter col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="f-w-600">@lang('modules.lead.followUp')</label>
                                                                            <select class="form-control select2" name="followUp" id="followUp">
                                                                                <option value="all">@lang('modules.lead.all')</option>
                                                                                <option value="pending">@lang('modules.lead.pending')</option>
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
                    @endif

                    <div class="product-wrapper-grid">
                        <div class="row">
                        
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="form-group">
                                        @if($user->can('add_lead'))
                                            <a href="{{ route('member.leads.create') }}" class="btn btn-outline btn-primary btn-sm">@lang('modules.lead.addNewLead') <i class="fa fa-plus" aria-hidden="true"></i></a>
                                        @endif
                                        @if($user->can('view_lead'))
                                            <a href="javascript:;" id="toggle-filter" class="btn btn-outline btn-secondary btn-sm toggle-filter"><i
                                                    class="fa fa-sliders"></i> @lang('app.filterResults')</a>
                                        @endif
                                        </div>

                                        <div class="dt-ext table-responsive">
                                        {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
   
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="followUpModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
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
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
    
    {!! $dataTable->scripts() !!}
    <script>

    var table;

    showTable()

    $('#users-table').on('preXhr.dt', function (e, settings, data) {
        var client = $('#client').val();
        var followUp = $('#followUp').val();
        data['client'] = client;
        data['followUp'] = followUp;
        data['status_by'] = null;
       
    });

    function showTable(){
        window.LaravelDataTables["users-table"].search('').draw();
    }


      $(function() {
        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({

                title: "Are you sure?",
                text: "You will not be able to recover the deleted lead!",
                icon: "warning",
                buttons: ["No, cancel please!", "Yes, delete it!"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {

                    var url = "{{ route('member.leads.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.LaravelDataTables["users-table"].draw();
                            }
                        }
                    });
                }
            });
        });  

    });

        

       function changeStatus(leadID, statusID){
           var url = "{{ route('member.leads.change-status') }}";
           var token = "{{ csrf_token() }}";

           $.easyAjax({
               type: 'POST',
               url: url,
               data: {'_token': token,'leadID': leadID,'statusID': statusID},
               success: function (response) {
                   if (response.status == "success") {
                       $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
//                        table._fnDraw();
                   }
               }
           });
        }

        $('.edit-column').click(function () {
            var id = $(this).data('column-id');
            var url = '{{ route("admin.taskboard.edit", ':id') }}';
            url = url.replace(':id', id);

            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    $('#edit-column-form').html(response.view);
                    $(".colorpicker").asColorPicker();
                    $('#edit-column-form').show();
                }
            })
        })

        $('body').on('click', '.addFollow', function(){
            var leadID = $(this).data('lead-id');
            var url = '{{ route('member.leads.follow-up', ':id')}}';
            url = url.replace(':id', leadID);

            $('#modelHeading').html('Add Follow Up');
            $.ajaxModal('#followUpModal', url);
        });
        
        
        $('.toggle-filter').click(function () {
            $(".product-wrapper").toggleClass("sidebaron");
            $(".product-sidebar").toggleClass("open");
        })

        function showDataByStatus(id) 
        {
            $('#users-table').on('preXhr.dt', function (e, settings, data) {
                data['client'] = '';
                data['followUp'] = '';
                var status_by = id;
                data['status_by'] = status_by;
            });
            window.LaravelDataTables["users-table"].draw();
        } 

        $('.count_by_status > div').on('click', function(event) {
            $('.count_by_status > div').removeClass('active');
            $(this).addClass('active');
            showDataByStatus($(this).attr('id'));
        });
    </script>
@endpush