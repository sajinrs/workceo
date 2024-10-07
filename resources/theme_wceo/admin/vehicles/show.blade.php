@extends('layouts.app')



@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.vehicles.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.details')</li>
                        </ol>
                    </div>
                </div>
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-primary btn-sm">Edit Vehicle Details</a>

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
                    <div class="card-body user-profile">
                        <div class="row">
                            <div class="col-md-4">
                                <img alt="" src="{{$vehicle->image_url}}" />
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                            <h6>@lang('modules.vehicles.name')</h6>
                                            <span>{{$vehicle->vehicle_name}}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                            <h6>@lang('modules.vehicles.licensePlate')</h6>
                                            <span>{{$vehicle->license_plate}}</span>
                                        </div>
                                    </div>

                                    @if($vehicle->operator_id)
                                        <div class="col-md-6">
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>User Type</h6>
                                                <span>{{($user->roleName == 'vehicle_operator') ? 'Operator' : 'Employee'}}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>Operator</h6>
                                                <span>{{$user->name}}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-6">
                                        <div class="ttl-info text-left ttl-border">
                                            <h6>Year</h6>
                                            <span>{{$vehicle->year}}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="ttl-info text-left ttl-border">
                                            <h6>Make</h6>
                                            <span>{{$vehicle->make}}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="ttl-info text-left ttl-border">
                                            <h6>Model</h6>
                                            <span>{{$vehicle->model}}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="ttl-info text-left ttl-border">
                                            <h6>Status</h6>
                                            <span>
                                                @if($vehicle->status == 'active')
                                                    Active
                                                @elseif($vehicle->status == 'in_shop')
                                                    In Shop
                                                @elseif($vehicle->status == 'out_of_service')
                                                    Out of Service
                                                @elseif($vehicle->status == 'inactive')
                                                    Inactive
                                                @endif
                                            </span>
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

                                        <li class="nav-item"><a class="nav-link" id="photo-info-tab" data-toggle="tab" href="#photo-document" role="tab" aria-controls="info-document" aria-selected="false" data-original-title="" title="">Photos</a></li>

                                        <li class="nav-item"><a class="nav-link" id="document-info-tab" data-toggle="tab" href="#info-document" role="tab" aria-controls="info-document" aria-selected="false" data-original-title="" title="">@lang('app.menu.documents')</a></li>
                                        <li class="nav-item"><a class="nav-link" id="job-info-tab" data-toggle="tab" href="#info-job" role="tab" aria-controls="info-document" aria-selected="false" data-original-title="" title="">@lang('app.menu.jobs')</a></li>

                                    </ul>
                                    <div class="tab-content" id="info-tabContent">
                                        <div class="tab-pane fade  active show" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                            <div class="steamline vehicle-activity">
                                                @forelse($activities as $activity)
                                                    <h6>{{$activity->activity}}</h6><hr />
                                                @empty
                                                    <h6>No Activities</h6>
                                                @endforelse
                                            </div>
                                        </div>  
                                        
                                        <div class="tab-pane fade" id="photo-document" role="tabpanel" aria-labelledby="photo-info-tab">
                                    
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th width="70%">Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="employeeDocsList">
                                                        @php $key=1; @endphp
                                                        @forelse($photos as $photo)
                                                                <tr>
                                                                    <td>{{$key++}}</td>
                                                                    <td>{{$photo->file_name}}</td>
                                                                    <td>
                                                                        <a href="{{ route('admin.vehicles.download-document', $photo->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn btn-small badge-dark"><i class="fa fa-download"></i></a>

                                                                        <a target="_blank" href="{{$photo->file_url}}" data-toggle="tooltip" data-original-title="View" class="btn btn-small badge-info"><i class="fa fa-search"></i></a>
                                                                        <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{$photo->id}}" data-pk="list" class="btn btn-small badge-danger sa-params"><i class="fa fa-times"></i></a>
                                                                    </td>
                                                                </tr>
                                                        @empty
                                                            <tr><td colspan="3">No Photos found.</td></tr>
                                                        @endforelse
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="info-document" role="tabpanel" aria-labelledby="document-info-tab">
                                    
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th width="70%">Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="employeeDocsList">
                                                        @php $docNum = 1; @endphp
                                                        @forelse($documents as $document)
                                                                <tr>
                                                                    <td>{{$docNum++}}</td>
                                                                    <td>{{$document->file_name}}</td>
                                                                    <td>
                                                                        <a href="{{ route('admin.vehicles.download-document', $document->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn btn-small badge-dark"><i class="fa fa-download"></i></a>

                                                                        <a target="_blank" href="{{$document->file_url}}" data-toggle="tooltip" data-original-title="View" class="btn btn-small badge-info"><i class="fa fa-search"></i></a>
                                                                        <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{$document->id}}" data-pk="list" class="btn btn-small badge-danger sa-params"><i class="fa fa-times"></i></a>
                                                                    </td>
                                                                </tr>
                                                        @empty
                                                            <tr><td colspan="3">No Documents found.</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="info-job" role="tabpanel" aria-labelledby="job-info-tab">
                                    
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th width="20px">#</th>
                                                            <th>Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="employeeDocsList">
                                                        @php $docNum = 1; @endphp
                                                        @forelse($projects as $project)
                                                            
                                                                <tr>
                                                                    <td>{{$docNum++}}</td>
                                                                    <td><a href="{{route('admin.projects.show', [$project->id])}}">{{$project->project_name}}</a></td>
                                                                    
                                                                </tr>
                                                        @empty
                                                            <tr><td colspan="3">No Jobs found.</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
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

<script>
    // Show Create employeeDocs Modal
    

    $('body').on('click', '.sa-params', function () {
        
        var $this = $(this);
        var id = $(this).data('file-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted file!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.vehicles.destroy-document',':id') }}";
                url = url.replace(':id', id);
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        
                        success: function (response) {
                            $this.closest('tr').remove();
                        }
                    });
                }
            });
        });
    
</script>


@endpush

