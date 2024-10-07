@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
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
                        <a href="{{ route('admin.ticket-agents.index') }}"  class="btn btn-primary btn-gray btn-sm">@lang('app.menu.ticketSettings') <i class="feather-16" data-feather="settings"></i></a>

                        <a href="{{ route('admin.tickets.create') }}" class="btn btn-outline btn-primary btn-sm">@lang('modules.tickets.addTicket') <i data-feather="plus"></i></a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(16)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
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
                            <div class="row p-l-15 p-t-10">
                                <div class="col-md-3">
                                    <label class="control-label">@lang('app.selectDateRange')</label>
                                    <div class="form-group">
                                        <input class="form-control input-daterange-datepicker" type="text" name="daterange"
                                            value="{{ $startDate.' - '.$endDate }}"/>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                <div class="media card-body" style="padding: 10px">
                                <div class="media-body align-self-center count_by_status count5">
                                
                                
                                    <div id="">
                                        <p>@lang('modules.tickets.totalTickets') 
                                            <a class="mytooltip" href="javascript:void(0)"  data-trigger="hover" data-placement="top" data-content="@lang('modules.tickets.totalTicketInfo')">
                                            <i class="fa fa-info-circle"></i></a>
                                        </p>
                                        <h4><span class="counteNumer" id="totalTickets">0</span></h4>
                                    </div>
                                    
                                    <div id="closed">
                                        <p>@lang('modules.tickets.totalClosedTickets')
                                            <a class="mytooltip" href="javascript:void(0)"  data-trigger="hover" data-placement="top" data-content="@lang('modules.tickets.closedTicketInfo')">
                                            <i class="fa fa-info-circle"></i></a>
                                        </p>
                                        <h4><span class="counteNumer" id="closedTickets">0</span></h4>
                                    </div>

                                    <div id="open">
                                        <p>@lang('modules.tickets.totalOpenTickets')
                                            <a class="mytooltip" href="javascript:void(0)"  data-trigger="hover" data-placement="top" data-content="@lang('modules.tickets.openTicketInfo')">
                                            <i class="fa fa-info-circle"></i></a>
                                        </p>
                                        <h4><span class="counteNumer" id="openTickets">0</span></h4>
                                    </div>

                                    <div id="pending">
                                        <p>@lang('modules.tickets.totalPendingTickets')
                                            <a class="mytooltip" href="javascript:void(0)"  data-trigger="hover" data-placement="top" data-content="@lang('modules.tickets.pendingTicketInfo')">
                                            <i class="fa fa-info-circle"></i></a>
                                        </p>
                                        <h4><span class="counteNumer" id="pendingTickets">0</span></h4>
                                    </div>

                                    <div id="resolved">
                                        <p>@lang('modules.tickets.totalResolvedTickets')
                                            <a class="mytooltip" href="javascript:void(0)"  data-trigger="hover" data-placement="top" data-content="@lang('modules.tickets.resolvedTicketInfo')">
                                            <i class="fa fa-info-circle"></i></a>
                                        </p>
                                        <h4><span class="counteNumer" id="resolvedTickets"></span> </h4>
                                    </div>
                                    

                                </div>
                            </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
            </div>
                @if($ticketCount == 0)
                    <div class="col-md-12">
                        <div class="empty-content text-center">
                            <img src="{{ asset('img/empty/ticket.jpg') }}" alt="user-img" /><br />
                            <b>No Tickets</b><br />
                            No tickets added yet.<br />
                            <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary btn-sm m-t-20">Add Ticket</a>
                        </div>
                    </div>
                @else  
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                        <div class="card-header">
                            <div class="card-title mb-0"> <h5 class="box-title"><i class="icofont icofont-chart-line"></i> @lang('modules.tickets.ticketTrendGraph') </h5></div>


                            </div>
                            <div class="card-body">
                            
                            <ul class="list-inline text-right ticketstats">
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #4c5667;"></i>@lang('modules.invoices.total')</h5>
                                </li>
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #5475ed;"></i>@lang('modules.issues.resolved')</h5>
                                </li>
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #f1c411;"></i>@lang('modules.tickets.totalUnresolvedTickets')</h5>
                                </li>
                            </ul>
                                <div id="morris-area-chart" style="height: 225px;"></div>
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
                                                                                <label class="f-w-600">@lang('modules.tickets.agent')</label>
                                                                                <select class="form-control selectpicker" name="agent_id" id="agent_id" data-style="form-control">
                                                                                    <option value="">@lang('modules.tickets.nofilter')</option>
                                                                                    @forelse($groups as $group)
                                                                                        @if(count($group->enabled_agents) > 0)
                                                                                            <optgroup label="{{ ucwords($group->group_name) }}">
                                                                                                @foreach($group->enabled_agents as $agent)
                                                                                                    <option value="{{ $agent->user->id }}">{{ ucwords($agent->user->name).' ['.$agent->user->email.']' }}</option>
                                                                                                @endforeach
                                                                                            </optgroup>
                                                                                        @endif
                                                                                    @empty
                                                                                        <option value="">@lang('messages.noGroupAdded')</option>
                                                                                    @endforelse
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label class="f-w-600">@lang('app.status')</label>
                                                                                <select class="form-control selectpicker" name="status" id="status" data-style="form-control">
                                                                                    <option value="">@lang('modules.tickets.nofilter')</option>
                                                                                    <option value="open">@lang('modules.tickets.totalOpenTickets')</option>
                                                                                    <option value="pending">@lang('modules.tickets.totalPendingTickets')</option>
                                                                                    <option value="resolved">@lang('modules.tickets.totalResolvedTickets')</option>
                                                                                    <option value="closed">@lang('modules.tickets.totalClosedTickets')</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label class="f-w-600">@lang('modules.tasks.priority')</label>
                                                                                <select class="form-control selectpicker" name="priority" id="priority" data-style="form-control">
                                                                                    <option value="">@lang('modules.tickets.nofilter')</option>
                                                                                    <option value="low">@lang('modules.tasks.low')</option>
                                                                                    <option value="medium">@lang('modules.tasks.medium')</option>
                                                                                    <option value="high">@lang('modules.tasks.high')</option>
                                                                                    <option value="urgent">@lang('modules.tickets.urgent')</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label class="f-w-600">@lang('modules.tickets.channelName')</label>
                                                                                <select class="form-control selectpicker" name="channel_id" id="channel_id" data-style="form-control">
                                                                                    <option value="">@lang('modules.tickets.nofilter')</option>
                                                                                    @forelse($channels as $channel)
                                                                                        <option value="{{ $channel->id }}">{{ ucwords($channel->channel_name) }}</option>
                                                                                    @empty
                                                                                        <option value="">@lang('messages.noTicketChannelAdded')</option>
                                                                                    @endforelse
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label class="f-w-600">@lang('modules.invoices.type')</label>
                                                                                <select class="form-control selectpicker" name="type_id" id="type_id" data-style="form-control">
                                                                                    <option value="">@lang('modules.tickets.nofilter')</option>
                                                                                    @forelse($types as $type)
                                                                                        <option value="{{ $type->id }}">{{ ucwords($type->type) }}</option>
                                                                                    @empty
                                                                                        <option value="">@lang('messages.noTicketTypeAdded')</option>
                                                                                    @endforelse
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
                                                {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
        </div>
    </div>



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

<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/raphael.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/morris.js') }}"></script>



{!! $dataTable->scripts() !!}
<script>
 $(function () {
    $('.mytooltip').popover({
        container: 'body'
    });
    var dcolor = $(".mytooltip").attr("data-theme");
    if(dcolor == "dark") {
        $(".mytooltip").addClass("bg-dark");
    }
});

    var startDate = '{{ \Carbon\Carbon::createFromFormat('m/d/Y', $startDate)->format('Y-m-d') }}';
    var endDate = '{{ \Carbon\Carbon::createFromFormat('m/d/Y', $endDate)->format('Y-m-d') }}';

    $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        cancelClass: 'btn-inverse',

        "locale": {
            "applyLabel": "{{ __('app.apply') }}",
            "cancelLabel": "{{ __('app.cancel') }}",
            "daysOfWeek": [
                "{{ __('app.su') }}",
                "{{ __('app.mo') }}",
                "{{ __('app.tu') }}",
                "{{ __('app.we') }}",
                "{{ __('app.th') }}",
                "{{ __('app.fr') }}",
                "{{ __('app.sa') }}"
            ],
            "monthNames": [
                "{{ __('app.january') }}",
                "{{ __('app.february') }}",
                "{{ __('app.march') }}",
                "{{ __('app.april') }}",
                "{{ __('app.may') }}",
                "{{ __('app.june') }}",
                "{{ __('app.july') }}",
                "{{ __('app.august') }}",
                "{{ __('app.september') }}",
                "{{ __('app.october') }}",
                "{{ __('app.november') }}",
                "{{ __('app.december') }}"
            ],
            "firstDay": {{ $global->week_start }},
        }
    });

    $('.input-daterange-datepicker').on('apply.daterangepicker', function (ev, picker) {
        startDate = picker.startDate.format('YYYY-MM-DD');
        endDate = picker.endDate.format('YYYY-MM-DD');
        showTable();
    });

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })

    var dataVal = {!! json_encode($chartData) !!};

    var ticketGraph =  Morris.Area({
            element: 'morris-area-chart',
            data: dataVal,
            xkey: 'date',
            ykeys: ['total', 'resolved', 'unresolved'],
            labels: ['Total', 'Resolved', 'Unresolved'],
            pointSize: 3,
            fillOpacity: 0.3,
            pointStrokeColors: ['#4c5667', '#5475ed', '#f1c411'],
            behaveLikeLine: true,
            gridLineColor: '#e0e0e0',
            lineWidth: 3,
            hideHover: 'auto',
            lineColors: ['#4c5667', '#5475ed', '#f1c411'],
            resize: true

    });

    $('#ticket-table').on('preXhr.dt', function (e, settings, data) {
        var agentId = $('#agent_id').val();
        if (agentId == "") {
            agentId = 0;
        }

        var status = $('#status').val();
        if (status == "") {
            status = 0;
        }

        var priority = $('#priority').val();
        if (priority == "") {
            priority = 0;
        }

        var channelId = $('#channel_id').val();
        if (channelId == "") {
            channelId = 0;
        }

        var typeId = $('#type_id').val();
        if (typeId == "") {
            typeId = 0;
        }

        var tagId = $('#tag_id').val();
        if (tagId == "") {
            tagId = 0;
        }

        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['agentId'] = agentId;
        data['priority'] = priority;
        data['channelId'] = channelId;
        data['typeId'] = typeId;
        data['tagId'] = tagId;
        data['status'] = status;
    });

    var table;

    function showTable() {

        var agentId = $('#agent_id').val();
        if (agentId == "") {
            agentId = 0;
        }

        var status = $('#status').val();
        if (status == "") {
            status = 0;
        }

        var priority = $('#priority').val();
        if (priority == "") {
            priority = 0;
        }

        var channelId = $('#channel_id').val();
        if (channelId == "") {
            channelId = 0;
        }

        var typeId = $('#type_id').val();
        if (typeId == "") {
            typeId = 0;
        }


        //refresh counts and chart
        var url = '{!!  route('admin.tickets.refreshCount', [':startDate', ':endDate', ':agentId', ':status', ':priority', ':channelId', ':typeId']) !!}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':agentId', agentId);
        url = url.replace(':status', status);
        url = url.replace(':priority', priority);
        url = url.replace(':channelId', channelId);
        url = url.replace(':typeId', typeId);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                $('#totalTickets').html(response.totalTickets);
                $('#closedTickets').html(response.closedTickets);
                $('#openTickets').html(response.openTickets);
                $('#pendingTickets').html(response.pendingTickets);
                $('#resolvedTickets').html(response.resolvedTickets);
                initConter();
                ticketGraph.setData(JSON.parse(response.chartData));
            }
        });

        window.LaravelDataTables["ticket-table"].draw();
    }

    $('#apply-filters').click(function () {
        showTable();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('.count_by_status > div').removeClass('active');
        $("#filter-form select").val('').trigger('change');
        //$('#filter-form').find('select').selectpicker('render');
        showTable();
    })

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('ticket-id');
                swal({

                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted ticket!",
                    icon: "{{ asset('img/warning.png')}}",
                    buttons: ["CANCEL", "DELETE"],
                    dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {

                        var url = "{{ route('admin.tickets.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    window.LaravelDataTables["ticket-table"].draw();
                                }
                            }
                        });
                    }
                });
            });


    
    function initConter() 
    {
        $('.counteNumer').each(function () {
            var $this = $(this);
            jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function () {
                    $this.text(Math.ceil(this.Counter));
                    }
            });
        });
    }  


    showTable();

    function exportData(){

        var agentId = $('#agent_id').val();
        if (agentId == "") {
            agentId = 0;
        }

        var status = $('#status').val();
        if (status == "") {
            status = 0;
        }

        var priority = $('#priority').val();
        if (priority == "") {
            priority = 0;
        }

        var channelId = $('#channel_id').val();
        if (channelId == "") {
            channelId = 0;
        }

        var typeId = $('#type_id').val();
        if (typeId == "") {
            typeId = 0;
        }


        //refresh counts and chart
        var url = '{!!  route('admin.tickets.export', [':startDate', ':endDate', ':agentId', ':status', ':priority', ':channelId', ':typeId']) !!}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':agentId', agentId);
        url = url.replace(':status', status);
        url = url.replace(':priority', priority);
        url = url.replace(':channelId', channelId);
        url = url.replace(':typeId', typeId);

        window.location.href = url;
    }    

    $('.count_by_status div').on('click', function(event) {
        $('.count_by_status > div').removeClass('active');
        $(this).addClass('active');
        $('#status').val($(this).attr('id')).trigger('change');
        showTable();
    }); 
</script>
@endpush