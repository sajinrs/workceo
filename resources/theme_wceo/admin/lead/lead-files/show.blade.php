@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">
<style>
    .file-bg {height: 150px;overflow: hidden; position: relative;}
    .file-bg .overlay-file-box {opacity: .9;position: absolute;top: 0;left: 0;right: 0;height: 100%;text-align: center;}
</style>
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                    <h4 class="m-b-0" style="color: #1d61d2;"><i class="{{ $pageIcon }}"></i> <span class="upper"> {{ __($pageTitle) }} </span> - <span class="font-bold">{{  ucwords($lead->company_name) }}</span></h4>
                        <!--<h3><i class="{{ $pageIcon }}"></i> <span class="font-bold">{{ ucwords($lead->company_name) }}</span></h3>-->
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.leads.index') }}">@lang('modules.lead.lead')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="card">
            <div class="row product-page-main">
                <div class="col-sm-12">
                    <ul class="showProjectTabs nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">
                        <li  class="nav-item"><a class="nav-link" href="{{ route('admin.leads.show', $lead->id) }}"><span>@lang('modules.lead.profile')</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.proposals.show', $lead->id) }}"><span>@lang('modules.lead.proposal')</span></a></li>
                        <li  class="nav-item"><a class="nav-link active" href="{{ route('admin.lead-files.show', $lead->id) }}"><span>@lang('modules.lead.file')</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.leads.followup', $lead->id) }}"><span>@lang('modules.lead.followUp')</span></a></li>
                        @if($gdpr->enable_gdpr)
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.leads.gdpr', $lead->id) }}"><span>GDPR</span></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

                <div class="col-sm-12 p-0" id="files-list-panel">
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                            <div class="card">
                            <div class="card-header">
                                <h4 class="pull-left">@lang('modules.projects.files')</h4>
                                <div class="pull-right">
                                    <a href="javascript:;" id="show-dropzone"
                                           class="btn btn-sm btn-primary btn-outline"><i class="fa fa-upload"></i> @lang('modules.projects.uploadFile')</a>
                                </div>
                            </div>
                            <div class="card-body">
                                

                                <div class="row m-b-20 d-none" id="file-dropzone">
                                    <div class="col-md-12">
                                        <form class="dropzone dropzone-primary" id="multiFileUpload" action="{{ route('admin.lead-files.store') }}">
                                            <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                                                <h6>Drop files here or click to upload.</h6></span>
                                            </div>
                                            {{ csrf_field() }}

                                            {!! Form::hidden('lead_id', $lead->id) !!}

                                            <input name="view" type="hidden" id="view" value="list">

                                            <div class="fallback">
                                                <input name="file" type="file" multiple/>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs border-tab mb-0" role="tablist" id="list-tabs">
                                    <li role="presentation" class="nav-item" data-pk="list">
                                        <a href="#list" class="active nav-link" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class=""> List</span></a>
                                    </li>
                                    <li role="presentation" class="nav-item" data-pk="thumbnail_sec">
                                        <a href="#thumbnail_sec" class="nav-link thumbnail_sec" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="">Thumbnail</span></a>
                                    </li>

                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="list">
                                        <ul class="list-group m-t-15" id="files-list">
                                            @forelse($lead->files as $file)
                                            <li class="list-group-item justify-content-between align-items-center">
                                                {{ $file->filename }}
                                                <span class="pull-right">
                                                <small class="text-muted m-r-10">{{ $file->created_at->diffForHumans() }}</small>

                                                <a target="_blank" href="{{ $file->file_url }}" data-toggle="tooltip" data-original-title="View" class="btn badge-info btn-small"><i class="fa fa-search m-r-0"></i></a>
                                                &nbsp;&nbsp;
                                                <a href="{{ route('admin.lead-files.download', $file->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn badge-dark btn-small"><i class="fa fa-download"></i></a>
                                                &nbsp;&nbsp;
                                                <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $file->id }}" class="btn badge-danger sa-params btn-small" data-pk="list"><i class="fa fa-times"></i></a>
                                                    </span>
                                            </li>
                                            @empty
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            @lang('messages.noFileUploaded')</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="thumbnail_sec">

                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            
    </div>



@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/dropzone/dropzone.js')}}"></script>
<script>
    $('#show-dropzone').click(function () {
        $('#file-dropzone').toggleClass('d-none d-block');
    });

    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    // "myAwesomeDropzone" is the camelized version of the HTML element's ID
    Dropzone.options.multiFileUpload = {
        paramName: "file", // The name that will be used to transfer the file
//        maxFilesize: 2, // MB,
        dictDefaultMessage: "@lang('modules.projects.dropFile')",
        accept: function (file, done) {
            done();
        },
        init: function () {
            this.on("success", function (file, response) {
                var viewName = $('#view').val();
                if(viewName == 'list') {
                    $('#files-list-panel ul.list-group').html(response.html);
                } else {
                    $('#thumbnail_sec').empty();
                    $(response.html).hide().appendTo("#thumbnail_sec").fadeIn(500);
                }
            })
        }
    };

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('file-id');
        var deleteView = $(this).data('pk');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted file!",
            icon: "warning",
            buttons: ["No, cancel please!", "Yes, delete it!"],
            dangerMode: true
        })
            .then((willDelete) => {
                if (willDelete) {

                var url = "{{ route('admin.lead-files.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE', 'view': deleteView},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            if(deleteView == 'list') {
                                $('#files-list-panel ul.list-group').html(response.html);
                            } else {
                                $('#thumbnail_sec').empty();
                                $(response.html).hide().appendTo("#thumbnail_sec").fadeIn(500);
                            }
                        }
                    }
                });
            }
        });
    });

    $('.thumbnail_sec').on('click', function(event) {
        event.preventDefault();
        $('#thumbnail_sec').empty();
        var leadID = "{{ $lead->id }}";
        $.easyAjax({
            type: 'GET',
            url: "{{ route('admin.lead-files.thumbnail') }}",
            data: {
              id: leadID
            },
            success: function (response) {
                $(response.view).hide().appendTo("#thumbnail_sec").fadeIn(500);
            }
        });
    });


    $('#list-tabs').on("shown.bs.tab",function(event){
        var tabSwitch = $('#list').hasClass('active');
        if(tabSwitch == true) {
            $('#view').val('list');
        } else {
            $('#view').val('thumbnail');
        }
    });

</script>
@endpush
