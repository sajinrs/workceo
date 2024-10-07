@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.details')</li>
                        </ol>
                    </div>
                </div>
                
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary btn-sm">Edit Employee Details</a>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper employeData">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-view text-center">                                
                                    <div class="profile-basic">
                                        <div class="row mb-2">
                                            <div class="col-md-12 mb-3"><img class="img-100 rounded-circle" alt="" src="{{$employee->image_url}}" data-original-title="" title=""></div>
                                            <div class="col-md-12">
                                            <h5 class="mb-1">{{ ucwords($employee->name) }}</h5>
                                            <p class="mb-1"><i class="fa fa-envelope"></i> {{$employee->email}}</p>
                                            @if($employee->phone)<p class="mb-1"><i class="fa fa-phone"></i> {{$employee->phone}}</p> @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="selling-update text-center"><i class="fa fa-check-square"></i>
                                        <h6>@lang('modules.employees.tasksDone')</h6>
                                        <h5 class="mb-0 f-18">{{ $taskCompleted }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="selling-update text-center"><i class="fa fa-clock"></i>
                                        <h6>@lang('modules.employees.hoursLogged')</h6>
                                        <h5 class="mb-0 f-18">{{ $hoursLogged }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="selling-update text-center"><i class="fa fa-sign-in-alt"></i>
                                        <h6>@lang('modules.leaves.leavesTaken')</h6>
                                        <h5 class="mb-0 f-18">{{ $leavesCount }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="selling-update text-center"><i class="fa fa-sign-in-alt"></i>
                                        <h6>@lang('modules.leaves.remainingLeaves')</h6>
                                        <h5 class="mb-0 f-18">{{ ($allowedLeaves-count($leaves)) }}</h5>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        </div>

                        

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                           
                  <div class="card-body">
                    <ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
                      <li class="nav-item"><a class="nav-link active show" id="activity-tab" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="true" data-original-title="" title="">@lang('modules.employees.activity')</a></li>

                      <li class="nav-item"><a class="nav-link" id="profile-info-tab" data-toggle="tab" href="#info-profile" role="tab" aria-controls="info-profile" aria-selected="false" data-original-title="" title="">@lang('modules.employees.profile')</a></li>

                      <li class="nav-item"><a class="nav-link" id="projects-info-tab" data-toggle="tab" href="#info-projects" role="tab" aria-controls="info-projects" aria-selected="false" data-original-title="" title="">@lang('app.menu.projects')</a></li>

                      <li class="nav-item"><a class="nav-link" id="tasks-info-tab" data-toggle="tab" href="#info-tasks" role="tab" aria-controls="info-tasks" aria-selected="false" data-original-title="" title="">@lang('app.menu.tasks')</a></li>

                      <li class="nav-item"><a class="nav-link" id="logs-info-tab" data-toggle="tab" href="#info-logs" role="tab" aria-controls="info-logs" aria-selected="false" data-original-title="" title="">@lang('app.menu.timeLogs')</a></li>

                      <li class="nav-item"><a class="nav-link" id="document-info-tab" data-toggle="tab" href="#info-document" role="tab" aria-controls="info-document" aria-selected="false" data-original-title="" title="">@lang('app.menu.documents')</a></li>

                      <li class="nav-item"><a class="nav-link" id="vehicle-info-tab" data-toggle="tab" href="#info-vehicle" role="tab" aria-controls="info-vehicle" aria-selected="false" data-original-title="" title="">Vehicles</a></li>

                    </ul>
                    <div class="tab-content" id="info-tabContent">
                      <div class="tab-pane fade  active show" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                        <div class="steamline">
                            @forelse($activities as $key=>$activity)
                            <div class="media">
                                <img class="rounded-circle image-radius img-80 m-r-15" src="{{ $employee->image_url }}" />
                                <div class="media-body">
                                <h6 class="mb-0 f-w-700">{{ ucwords($employee->name) }}</h6>
                                <p>{!! ucfirst($activity->activity) !!} {{ $activity->created_at->diffForHumans() }} </p>
                                </div>
                            </div>

                            
                                @if(count($activities) > ($key+1))
                                    <hr>
                                @endif
                            @empty
                                <div>@lang('messages.noActivityByThisUser')</div>
                            @endforelse
                        </div>
                      </div>

                      <div class="tab-pane fade user-profile" id="info-profile" role="tabpanel" aria-labelledby="profile-info-tab">
                      <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('modules.employees.employeeId')</h6>
                                <span>{{ ucwords($employee->employeeDetail->employee_id) }}</span>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('modules.employees.fullName')</h6>
                                <span>{{ ucwords($employee->name) }}</span>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('app.mobile')</h6>
                                <span>{{ $employee->mobile ?? 'NA'}}</span>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('app.email')</h6>
                                <span>{{ $employee->email }}</span>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('app.address')</h6>
                                <span>{{ (!is_null($employee->employeeDetail)) ? $employee->employeeDetail->address : 'NA'}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('app.designation')</h6>
                                <span>{{ (!is_null($employee->employeeDetail) && !is_null($employee->employeeDetail->designation)) ? ucwords($employee->employeeDetail->designation->name) : 'NA' }}</span>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('app.department')</h6>
                                <span>{{ (!is_null($employee->employeeDetail) && !is_null($employee->employeeDetail->department)) ? ucwords($employee->employeeDetail->department->team_name) : 'NA' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('modules.employees.slackUsername')</h6>
                                <span>{{ (!is_null($employee->employeeDetail)) ? '@'.$employee->employeeDetail->slack_username : 'NA' }}</span>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('modules.employees.joiningDate')</h6>
                                <span>{{ (!is_null($employee->employeeDetail)) ? $employee->employeeDetail->joining_date->format($global->date_format) : 'NA' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('modules.employees.gender')</h6>
                                <span>{{ $employee->gender }}</span>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('app.skills')</h6>
                                <span>{{implode(', ', $employee->skills()) }}</span>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class=" ttl-info text-left ttl-border">
                                <h6>@lang('modules.employees.hourlyRate')</h6>
                                <span>{{ (count($employee->employee) > 0) ? $employee->employee[0]->hourly_rate : 'NA' }}</span>
                            </div>
                        </div>

                    </div>
                    {{--Custom fields data--}}
                        @if(isset($fields))
                        <div class="row">
                            @foreach($fields as $field)
                                <div class="col-sm-12 col-md-6">
                                    <div class=" ttl-info text-left ttl-border">
                                    <h6>{{ ucfirst($field->label) }}</h6>
                                    <span>
                                        @if( $field->type == 'text')
                                            {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}
                                        @elseif($field->type == 'password')
                                            {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}
                                        @elseif($field->type == 'number')
                                            {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}

                                        @elseif($field->type == 'textarea')
                                        {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}

                                        @elseif($field->type == 'radio')
                                            {{ $field->values[$employeeDetail->custom_fields_data['field_'.$field->id]] }}
                                        @elseif($field->type == 'select')
                                                {{ $field->values[$employeeDetail->custom_fields_data['field_'.$field->id]] }}
                                        @elseif($field->type == 'checkbox')
                                            {{ $field->values[$employeeDetail->custom_fields_data['field_'.$field->id]] }}
                                        @elseif($field->type == 'date')
                                            {{ isset($employeeDetail->dob)?Carbon\Carbon::parse($employeeDetail->dob)->format($global->date_format):Carbon\Carbon::now()->format($global->date_format)}}
                                        @endif
                                    </span>

                                </div>
                                </div>
                            @endforeach
                        </div>
                        @endif

                    {{--custom fields data end--}}
                      </div>

                      <div class="tab-pane fade" id="info-projects" role="tabpanel" aria-labelledby="projects-info-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('app.project')</th>
                                    <th>@lang('app.deadline')</th>
                                    <th>@lang('app.completion')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($projects as $key=>$project)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td><a href="{{ route('admin.projects.show', $project->id) }}">{{ ucwords($project->project_name) }}</a></td>
                                        <td>@if($project->deadline){{ $project->deadline->format($global->date_format) }}@else - @endif</td>
                                        <td>
                                            <?php

                                            if ($project->completion_percent < 50) {
                                            $statusColor = 'danger';
                                            }
                                            elseif ($project->completion_percent >= 50 && $project->completion_percent < 75) {
                                            $statusColor = 'warning';
                                            }
                                            else {
                                            $statusColor = 'success';
                                            }
                                            ?>

                                            <h5>@lang('app.progress')<span class="pull-right">{{  $project->completion_percent }}%</span></h5><div class="progress">
                                                <div class="progress-bar progress-bar-{{ $statusColor }}" aria-valuenow="{{ $project->completion_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->completion_percent }}%" role="progressbar"> <span class="sr-only">{{ $project->completion_percent }}% @lang('app.completed')</span> </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">@lang('messages.noProjectFound')</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                      </div>

                      <div class="tab-pane fade" id="info-tasks" role="tabpanel" aria-labelledby="tasks-info-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkbox checkbox-info">
                                    <input type="checkbox" id="hide-completed-tasks">
                                    <label for="hide-completed-tasks">@lang('app.hideCompletedTasks')</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                                id="tasks-table">
                                <thead>
                                <tr>
                                    <th>@lang('app.id')</th>
                                    <th>@lang('app.project')</th>
                                    <th>@lang('app.task')</th>
                                    <th>@lang('app.dueDate')</th>
                                    <th>@lang('app.status')</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                      </div>                     

                      <div class="tab-pane fade" id="info-logs" role="tabpanel" aria-labelledby="logs-info-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="timelog-table">
                                <thead>
                                <tr>
                                    <th>@lang('app.id')</th>
                                    <th>@lang('app.project')</th>
                                    <th>@lang('modules.employees.startTime')</th>
                                    <th>@lang('modules.employees.endTime')</th>
                                    <th>@lang('modules.employees.totalHours')</th>
                                    <th>@lang('modules.employees.memo')</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                      </div>

                      <div class="tab-pane fade" id="info-document" role="tabpanel" aria-labelledby="document-info-tab">
                      <button class="btn btn-sm btn-info addDocs mb-4" onclick="showAdd()"><i
                                class="fa fa-plus"></i> @lang('app.add')</button>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="70%">@lang('app.name')</th>
                                    <th>@lang('app.action')</th>
                                </tr>
                                </thead>
                                <tbody id="employeeDocsList">
                                @forelse($employeeDocs as $key=>$employeeDoc)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td width="70%">{{ ucwords($employeeDoc->name) }}</td>
                                        <td>
                                            <a href="{{ route('admin.employee-docs.download', $employeeDoc->id) }}"
                                            data-toggle="tooltip" data-original-title="Download" class="badge badge-dark"><i class="fa fa-download"></i></a>
                                            <a target="_blank" href="{{ asset_url('employee-docs/'.$employeeDoc->user_id.'/'.$employeeDoc->hashname) }}"
                                            data-toggle="tooltip" data-original-title="View" class="badge badge-info"><i class="fa fa-search"></i></a>
                                            <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $employeeDoc->id }}" data-pk="list" class="badge badge-danger sa-params"><i class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">@lang('messages.noDocsFound')</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                      </div>

                      <div class="tab-pane fade" id="info-vehicle" role="tabpanel" aria-labelledby="vehicle-info-tab">

                        @forelse($vehicles as $key=>$vehicle)
                            <div class="media mb-0">
                                <img class="image-radius-5 m-r-15" src="{{$vehicle->image_url}}" alt="" data-original-title="" title="" width="58" height="58">
                                <div class="media-body">
                                    <h6 class="mb-0"><a href="{{route('admin.vehicles.show', [$vehicle->id])}}">{{$vehicle->vehicle_name}}</a></h6>
                                    <p>Plate Number: {{$vehicle->license_plate}}</p>
                                </div>
                            </div>
                            <hr />

                            @empty
                                Vehicles not Assigned
                            @endforelse
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
    </div>

    {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="edit-column-form" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
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
<script>
    // Show Create employeeDocs Modal
    function showAdd() {
        var url = "{{ route('admin.employees.docs-create', [$employee->id]) }}";
        $.ajaxModal('#edit-column-form', url);
    }

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('file-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted file!",
                icon: "warning",
                buttons: ["No, cancel please!", "Yes, delete it!"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.employee-docs.destroy',':id') }}";
                url = url.replace(':id', id);
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('#employeeDocsList').html(response.html);
                            }
                        }
                    });
                }
            });
        });

    

    $('#leave-table').dataTable({
        responsive: true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0, 'width': '20%' },
            { responsivePriority: 2, targets: 1, 'width': '20%' }
        ],
        "autoWidth" : false,
        searching: false,
        paging: false,
        info: false
    });

    var table;

    function showTable() {

        if ($('#hide-completed-tasks').is(':checked')) {
            var hideCompleted = '1';
        } else {
            var hideCompleted = '0';
        }

        var url = '{{ route('admin.employees.tasks', [$employee->id, ':hideCompleted']) }}';
        url = url.replace(':hideCompleted', hideCompleted);

        table = $('#tasks-table').dataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: url,
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            "order": [[0, "desc"]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'project_name', name: 'projects.project_name', width: '20%'},
                {data: 'heading', name: 'heading', width: '20%'},
                {data: 'due_date', name: 'due_date'},
                {data: 'column_name', name: 'taskboard_columns.column_name'},
            ]
        });
    }

    $('#hide-completed-tasks').click(function () {
        showTable();
    });

    $('#tasks-table').on('click', '.show-task-detail', function () {
        $(".right-sidebar").addClass("right-sidebar-width-auto");
        $(".right-sidebar").slideDown(50).addClass("show shw-rside");
        var id = $(this).data('task-id');
        var url = "{{ route('admin.all-tasks.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }
            }
        });
    })

    showTable();
    
</script>

<script>
    var table2;

    function showTable2(){

        var url = '{{ route('admin.employees.time-logs', [$employee->id]) }}';

        table2 = $('#timelog-table').dataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: url,
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            "order": [[ 0, "desc" ]],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'project_name', name: 'projects.project_name' },
                { data: 'start_time', name: 'start_time' },
                { data: 'end_time', name: 'end_time' },
                { data: 'total_hours', name: 'total_hours' },
                { data: 'memo', name: 'memo' }
            ]
        });
    }

    showTable2();
</script>
@endpush

