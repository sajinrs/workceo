@extends('layouts.app')
@push('head-script')
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.notices.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                {!! Form::open(['id'=>'createNotice','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.notices.addNotice')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">                                    
                                        <input type="text" name="heading" id="heading" class="form-control form-control-lg" placeholder="-" autocomplete="nope">
                                        <label for="heading" class="required">@lang("modules.notices.noticeHeading")</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group m-t-15 custom-radio-ml radio-list">
                                        <div class="radio radio-primary">
                                            <input type="radio" id="toEmployee" name="to" value="employee" checked="" />
                                            <label for="toEmployee">@lang('modules.notices.toEmployee')</label>

                                            &nbsp; &nbsp; &nbsp; <input type="radio" id="toClient" name="to" value="client" />
                                            <label for="toClient">@lang('modules.notices.toClients')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="department"> 
                                    <div class="form-label-group form-group">                                   
                                        <select name="team_id" id="team_id" class="form-control form-control-lg" placeholder="-">
                                            <option value=""> -- </option>
                                            @foreach($teams as $team)
                                                @if(sizeof($team->member) != 0)
                                                    <option value="{{ $team->id }}">{{ ucwords($team->team_name) }}</option>
                                                @endif
                                            @endforeach
                                        </select>  
                                        <label for="gender" class="control-label">@lang("app.department")</label>
                                    </div>                                  
                                </div> 

                                <br />
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang("modules.notices.noticeDetails")</label> 
                                        <textarea name="description"  id="description"  rows="3" class="form-control form-control-lg summernote" placeholder="-"></textarea>   
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Assign Icon</label> <br />
                                        <div class="btn-group btn-group-toggle notice-icon" data-toggle="buttons">
                                            <label class="btn btn-primary active">
                                                <input type="radio" checked name="icon" value="fa fa-envelope" required /> <i class="fa fa-envelope"></i>
                                            </label>
                                            <label class="btn btn-primary">
                                                <input type="radio" name="icon" value="fas fa-money-bill-wave-alt" required /> <i class="fas fa-money-bill-wave-alt"></i>
                                            </label>
                                            <label class="btn btn-primary">
                                                <input type="radio" name="icon" value="fa fa-exclamation-triangle" required /> <i class="fa fa-exclamation-triangle"></i>
                                            </label>

                                            <label class="btn btn-primary">
                                                <input type="radio" name="icon" value="fa fa-info-circle" required /> <i class="fa fa-info-circle"></i>
                                            </label>

                                            <label class="btn btn-primary">
                                                <input type="radio" name="icon" value="fa fa-graduation-cap" required /> <i class="fa fa-graduation-cap"></i>
                                            </label>

                                            <label class="btn btn-primary">
                                                <input type="radio" name="icon" value="fa fa-question" required /> <i class="fa fa-question"></i>
                                            </label>

                                            <label class="btn btn-primary">
                                                <input type="radio" name="icon" value="fa fa-paper-plane" required /> <i class="fa fa-paper-plane"></i>
                                            </label>

                                            <label class="btn btn-primary">
                                                <input type="radio" name="icon" value="fa fa-trophy" required /> <i class="fa fa-trophy"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3 offset-md-9">
                            <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

   

@endsection


@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>
<script>

    $('.summernote').summernote({
        height: 150,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });

    $(function () {

        $('.radio-list').click(function () {
            if($('input[name=to]:checked').val() === 'employee') {
                $('#department').removeClass('d-none').addClass('d-block');
            } else {
                $('#department').removeClass('d-block').addClass('d-none');
            }
        })

    });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.notices.store')}}',
            container: '#createNotice',
            type: "POST",
            redirect: true,
            data: $('#createNotice').serialize()
        })
    });
</script>


@endpush

