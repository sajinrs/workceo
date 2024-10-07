@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">
    <link rel="stylesheet" href="{{ asset('themes/wceo/assets/css/dropzone.css') }}">
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
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
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
                                                                        <label class="f-w-600">@lang('app.selectDateRange')</label>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control"  autocomplete="off" id="start-date" placeholder="@lang('app.startDate')"
                                                                                value=""/>
                                                                                <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                                            <input type="text" class="form-control"  autocomplete="off" id="end-date" placeholder="@lang('app.endDate')"
                                                                                value=""/>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.project')</label>
                                                                        <select class="form-control select2" data-placeholder="@lang('app.project')" name="projectID" id="projectID" data-style="form-control">
                                                                            <option value="all">@lang('app.all')</option>
                                                                                @forelse($projects as $project)
                                                                                    <option value="{{$project->id}}">{{ ucfirst($project->project_name) }}</option>
                                                                                @empty
                                                                                @endforelse
                                                                        </select>
                                                                    </div>                                                                    
                                                                </div>

                                                                <div class="product-filter wceo-filter col-sm-6 pr-0">
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
                                            {!! $dataTable->table(['class' => 'display']) !!}
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
    <div class="modal fade bs-modal-md in" id="creditNoteUploadModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">@lang('app.credit-notes.uploadInvoice')</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                <div id="file-upload-box" >
                    <div class="row" id="file-dropzone">
                            <div class="col-md-12">
                                <form action="{{route('admin.creditNoteFile.store')}}" class="dropzone" id="file-upload-dropzone">
                                    {{ csrf_field() }}
                                    <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                                        <h6>Drop files here or click to upload.</h6></span>
                                    </div>
                                    <div class="fallback">
                                        <input name="file" type="file" />
                                    </div>
                                </form>
                            </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('app.save')</button>
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

    <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>

    <script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>
    
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
    <script src="{{ asset('themes/wceo/assets/js/dropzone/dropzone.js') }}"></script>

{!! $dataTable->scripts() !!}

<script>
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    var table;
    var table;
    $(function() {
        $('#allCreditNote-table').on('preXhr.dt', function (e, settings, data) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            // var status = $('#status').val();
            var projectID = $('#projectID').val();

            data['startDate'] = startDate;
            data['endDate'] = endDate;
            data['projectID'] = projectID;
        });
        jQuery('#start-date, #end-date').daterangepicker({
            locale: {
                format: '{{ strtoupper($global->date_picker_format) }}'
            },
            "alwaysShowCalendars": true,
            autoApply: true,
            autoUpdateInput: false,
            language: '{{ $global->locale }}',
            weekStart:'{{ $global->week_start }}',
        }, function(start, end, label) {
            var selectedStartDate = start.format('DD-MM-YYYY'); // selected start
            var selectedEndDate = end.format('DD-MM-YYYY'); // selected end

            $startDateInput = $('#start-date');
            $endDateInput = $('#end-date');

            // Updating Fields with selected dates
            $startDateInput.val(selectedStartDate);
            $endDateInput.val(selectedEndDate);
            var endDatePicker = $endDateInput.data('daterangepicker');
            endDatePicker.setStartDate(selectedStartDate);
            endDatePicker.setEndDate(selectedEndDate);
            var startDatePicker = $startDateInput.data('daterangepicker');
            startDatePicker.setStartDate(selectedStartDate);
            startDatePicker.setEndDate(selectedEndDate);

        });

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('credit-notes-id');
                swal({
                    text: "You will not be able to recover the deleted credit notes!",
                    type: "warning",
                    icon: "warning",
                    buttons: ["No, cancel please!", "Yes, delete it!"],
                    dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('admin.all-credit-notes.destroy',':id') }}";
                        url = url.replace(':id', id);
                        var token = "{{ csrf_token() }}";
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    window.LaravelDataTables["allCreditNote-table"].draw();
                                }
                            }
                        });
                    }
                });
        }); 

        

        $('.table-responsive').on('click', '.credit-notes-upload', function(){
            var creditNoteId = $(this).data('credit-notes-id');
            console.log(creditNoteId);
            $('#file-upload-dropzone').prepend('<input name="credit_note_id", value="' + creditNoteId + '" type="hidden">');
        });
    });

    function loadTable(){
        window.LaravelDataTables["allCreditNote-table"].draw();
    }
    //    $('#file-upload-dropzone').dropzone({
    Dropzone.options.fileUploadDropzone = {
        paramName: "file", // The name that will be used to transfer the file
        dictDefaultMessage: "@lang('modules.projects.dropFile')",
        uploadMultiple: false,
        dictCancelUpload: "Cancel",
        accept: function (file, done) {
            done();
        },
        init: function () {
            this.on('addedfile', function(){
                if(this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
            this.on("success", function (file, response) {
            });
            var newDropzone = this;

            $('#creditNotesUploadModal').on('hide.bs.modal', function(){
                newDropzone.removeAllFiles(true);
            });

        }
    };
    //    });

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    });

    $('#apply-filters').click(function () {
        loadTable();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#projectID').val('all');
        // $('#status').selectpicker('render');
        $('#filter-form').find('select').select2();

        loadTable();
    })

    function exportData(){

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        // var status = $('#status').val();
        var projectID = $('#projectID').val();

        var url = '{{ route('admin.all-credit-notes.export', [':startDate', ':endDate', ':projectID']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':projectID', projectID);

        window.location.href = url;
    }

</script>
@endpush