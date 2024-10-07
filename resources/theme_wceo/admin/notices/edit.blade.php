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
                            <li class="breadcrumb-item active">@lang('app.edit')</li>
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
                {!! Form::open(['id'=>'updateNotice','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.notices.updateNotice')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">                                    
                                        <input type="text" name="heading" id="heading" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $notice->heading }}" />
                                        <label for="heading" class="required">@lang("modules.notices.noticeHeading")</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group m-t-15 custom-radio-ml radio-list">
                                        <div class="radio radio-primary">
                                            <input type="radio" id="toEmployee" name="to" value="employee" @if($notice->to == 'employee') checked @endif />
                                            <label for="toEmployee">@lang('modules.notices.toEmployee')</label>

                                            &nbsp; &nbsp; &nbsp; <input type="radio" id="toClient" name="to" value="client" @if($notice->to == 'client') checked @endif />
                                            <label for="toClient">@lang('modules.notices.toClients')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 @if($notice->to == 'client') d-none @endif" id="department"> 
                                    <div class="form-label-group form-group">                                   
                                        <select name="team_id" id="team_id" class="form-control form-control-lg" placeholder="-">
                                            <option value=""> -- </option>
                                            @foreach($teams as $team)
                                                @if(sizeof($team->member) != 0)
                                                    <option @if($team->id == $notice->team_id) selected @endif value="{{ $team->id }}">{{ ucwords($team->team_name) }}</option>
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
                                        <textarea name="description"  id="description"  rows="3" class="form-control form-control-lg summernote">{{ $notice->description }}</textarea>   
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Assign Icon</label> <br />
                                        <div class="btn-group btn-group-toggle notice-icon" data-toggle="buttons">
                                            <label class="btn btn-primary  @if($notice->icon == 'fa fa-envelope') active @endif">
                                                <input type="radio" name="icon" value="fa fa-envelope" @if($notice->icon == 'fa fa-envelope') checked @endif /> <i class="fa fa-envelope"></i>
                                            </label>
                                            <label class="btn btn-primary @if($notice->icon == 'fas fa-money-bill-wave-alt') active @endif">
                                                <input type="radio" name="icon" value="fas fa-money-bill-wave-alt" @if($notice->icon == 'fas fa-money-bill-wave-alt') checked @endif /> <i class="fas fa-money-bill-wave-alt"></i>
                                            </label>
                                            
                                            <label class="btn btn-primary @if($notice->icon == 'fa fa-exclamation-triangle') active @endif">
                                                <input type="radio" name="icon" value="fa fa-exclamation-triangle" required /> <i class="fa fa-exclamation-triangle"></i>
                                            </label>

                                            <label class="btn btn-primary @if($notice->icon == 'fa fa-info-circle') active @endif">
                                                <input type="radio" name="icon" value="fa fa-info-circle" required /> <i class="fa fa-info-circle"></i>
                                            </label>

                                            <label class="btn btn-primary @if($notice->icon == 'fa fa-graduation-cap') active @endif">
                                                <input type="radio" name="icon" value="fa fa-graduation-cap" required /> <i class="fa fa-graduation-cap"></i>
                                            </label>

                                            <label class="btn btn-primary @if($notice->icon == 'fa fa-question') active @endif">
                                                <input type="radio" name="icon" value="fa fa-question" required /> <i class="fa fa-question"></i>
                                            </label>

                                            <label class="btn btn-primary @if($notice->icon == 'fa fa-paper-plane') active @endif">
                                                <input type="radio" name="icon" value="fa fa-paper-plane" required /> <i class="fa fa-paper-plane"></i>
                                            </label>

                                            <label class="btn btn-primary @if($notice->icon == 'fa fa-trophy') active @endif">
                                                <input type="radio" name="icon" value="fa fa-trophy" required /> <i class="fa fa-trophy"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.notices.index') }}" class="btn btn-outline-primary gray form-control" >@lang('app.cancel')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                                </div>
                            </div>
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
            url: '{{route('admin.notices.update', [$notice->id])}}',
            container: '#updateNotice',
            type: "POST",
            redirect: true,
            data: $('#updateNotice').serialize()
        })
    });
</script>


@endpush

