@extends('layouts.super-admin')
@section('page-title')
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                            href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">

                        <div class="card-header">
                            <h5>  @lang('app.language') @lang('app.menu.settings')</h5>

                            <span><span class="m-r-5">
                                 <a href="{{ route('super-admin.language-settings.create') }}"
                                    class="btn btn-primary">@lang('app.add') @lang('app.language')  <i
                                             class="fa fa-plus" aria-hidden="true"></i></a>
                                    </span><span class="m-r-5">
                                <a href="{{ url('/translations') }}" target="_blank" class="btn btn-warning"><i
                                            class="ti-settings"></i> Translate</a>
                                    </span></span>
                        </div>

                        @include('sections.super_admin_setting_menu')
                        <div class="col-sm-12">
                            <div class="card-body table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('app.language') @lang('app.name')</th>
                                        <th>@lang('app.language_code')</th>
                                        <th>@lang('app.status')</th>
                                        <th class="text-nowrap">@lang('app.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php $i=0; @endphp
                                    @foreach($languages as $key => $language)
                                        <tr id="languageRow{{ $language->id }}">
                                            <td>{{ ++$i }}</td>
                                            <td>{{ ucwords($language->language_name) }}</td>
                                            <td>{{ strtoupper($language->language_code) }}</td>
                                            <td>


                                                <div class="media-body text-left icon-state">
                                                    <label class="switch">
                                                        <input @if($language->status == 'enabled') checked
                                                               @endif
                                                               type="checkbox"><span class="switch-state"></span>
                                                    </label>
                                                </div>


                                            </td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('super-admin.language-settings.edit', [$language->id]) }}"
                                                   data-toggle="tooltip" class="btn btn-outline-info btn-circle m-b-5"
                                                   data-original-title="Edit"><span class="icon-pencil"></span></a>

                                                <a href="javascript:;"
                                                   class="btn btn-outline-danger btn-circle sa-params m-b-5"
                                                   data-toggle="tooltip" data-language-id="{{ $language->id }}"
                                                   data-original-title="Delete"><span class="fa fa-trash-o"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach


                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>    <!-- .row -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });

        $('.change-language-setting').change(function () {
            var id = $(this).data('setting-id');

            if ($(this).is(':checked'))
                var status = 'enabled';
            else
                var status = 'disabled';

            var url = '{{route('super-admin.language-settings.update', ':id')}}';
            url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {'id': id, 'status': status, '_method': 'PUT', '_token': '{{ csrf_token() }}'}
            })
        });
        $('body').on('click', '.sa-params', function () {
            var id = $(this).data('language-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted language!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('super-admin.language-settings.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('#languageRow' + id).fadeOut();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
