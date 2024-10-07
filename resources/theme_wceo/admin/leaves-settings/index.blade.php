@extends('layouts.app')

@section('page-title')
  <div class="col-md-12">
        <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a  href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
@endsection

@push('head-script')
    {{--<link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
@endpush

@section('content')
 <div class="container-fluid">
   <div class="row">
        <div class="col-md-3">
        @include('sections.admin_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('app.update') @lang('app.menu.leaveSettings')</h5>
                           
                        </div>
                        {!! Form::open(['id'=>'createLeaveType','class'=>'ajax-form','method'=>'POST']) !!}
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">
                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <div class="radio-list">
                                            <label class="radio-inline p-r-15">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="leaves_start_from" @if($global->leaves_start_from == 'joining_date') checked @endif id="crypto_currency_joining" value="joining_date">
                                                    <label for="crypto_currency_joining">@lang('modules.leaves.countLeavesFromDateOfJoining')</label>
                                                </div>
                                            </label>
                                            <label class="radio-inline">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="leaves_start_from" @if($global->leaves_start_from == 'year_start') checked @endif id="crypto_currency_year" value="year_start">
                                                    <label for="crypto_currency_year">@lang('modules.leaves.countLeavesFromStartOfYear')</label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table" id="leave-type-table">
                                            <thead>
                                            <tr>
                                                <th>@lang('modules.leaves.leaveType')</th>
                                                <th>@lang('modules.leaves.noOfLeaves')</th>
                                                <th>@lang('app.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($leaveTypes as $key=>$leaveType)
                                                <tr id="type-{{ $leaveType->id }}">
                                                    <td>
                                                        <label class="badge badge-{{ $leaveType->color }}">{{ ucwords($leaveType->type_name) }}</label>
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" value="{{ $leaveType->no_of_leaves }}"
                                                                class="form-control leave-count-{{ $leaveType->id }}">
                                                    </td>
                                                    <td>
                                                        <button type="button" data-type-id="{{ $leaveType->id }}"
                                                                class="btn btn-sm btn-secondary btn-rounded update-category">
                                                            <i class="fa fa-check"></i></button>
                                                        <button type="button" data-cat-id="{{ $leaveType->id }}"
                                                                class="btn btn-sm btn-danger btn-rounded delete-category">
                                                            <i class="fa fa-times"></i></button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">@lang('messages.noLeaveTypeAdded')</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <hr>
                                    
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <label>@lang('app.add') @lang('modules.leaves.leaveType')</label>
                                                    <input type="text" name="type_name" id="type_name"
                                                           class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <label>@lang('modules.customFields.label') @lang('modules.sticky.colors')</label>
                                                    <select id="colorselector" name="color">
                                                        <option value="info" data-color="#5475ed" selected>Blue</option>
                                                        <option value="warning" data-color="#f1c411">Yellow</option>
                                                        <option value="purple" data-color="#ab8ce4">Purple</option>
                                                        <option value="danger" data-color="#ed4040">Red</option>
                                                        <option value="success" data-color="#00c292">Green</option>
                                                        <option value="inverse" data-color="#4c5667">Grey</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-right">
            <div class="form-actions col-md-3  offset-md-9 ">
                <button type="submit" id="save-type" class="btn btn-primary form-control"> @lang('app.save')</button>
            </div>
        </div>
        {!! Form::close() !!}


    </div>
    <!-- .row -->
    </div>
    </div>
    </div>
    </div>

@endsection

@push('footer-script')
    {{--<script src="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.js') }}"></script>--}}
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>

    <script>

        $('#leave-type-table').dataTable({
            responsive: true,
            "columnDefs": [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 1 }
            ],
            searching: false,
            paging: false,
            info: false
        });

        //$('#colorselector').colorselector();

        $('#createLeaveType').submit(function () {
            $.easyAjax({
                url: '{{route('admin.leaveType.store')}}',
                container: '#createLeaveType',
                type: "POST",
                data: $('#createLeaveType').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    }
                }
            })
            return false;
        })

        $('.update-category').click(function () {
            var id = $(this).data('type-id');
            var leaves = $('.leave-count-'+id).val();
            var url = "{{ route('admin.leaveType.update',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {'_token': token, '_method': 'PUT', 'leaves': leaves}
            });
        });

        $('body').on('click', '.delete-category', function () {
            var id = $(this).data('cat-id');
            swal({

                title: "Are you sure?",
                text: "You will not be able to recover the deleted leave type!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.leaveType.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('#type-' + id).fadeOut();
                            }
                        }
                    });
                }
            });
        });

        
        {{--$('#save-type').click(function () {
            $.easyAjax({
                url: '{{route('admin.leaveType.store')}}',
                container: '#createLeaveType',
                type: "POST",
                data: $('#createLeaveType').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        $('#createLeaveType')[0].reset();
                        window.location.reload();
                    }
                }
            })
        });--}}

        $('input[name=leaves_start_from]').click(function () {
            var leaveCountFrom = $('input[name=leaves_start_from]:checked').val();
            $.easyAjax({
                url: '{{route('admin.leaves-settings.store')}}',
                type: "POST",
                data: {'_token': '{{ csrf_token() }}', 'leaveCountFrom': leaveCountFrom}
            })
        });
    </script>


@endpush