<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>Admin Panel | Contracts</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/fontawesome_5.min.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/feather-icon.css') }}">
    
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/admin/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('themes/wceo/assets/css/light-1.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/floating-labels.css') }}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/responsive.css') }}">    

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link href="{{ asset('css/rounded.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/wceo/assets/css/sweetalert2.css') }}" rel="stylesheet" type="text/css">

    

    
</head>
<body class="fix-sidebar">
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<div id="wrapper">

    <!-- Left navbar-header end -->
    <!-- Page Content -->
    <div id="page-wrapper" style="margin-left: 0px !important;">
        <div class="container">

            <!-- .row -->
            <div class="row" style="margin-top: 70px; !important;">

                <div class="col-md-12" id="estimates">
                    <div class="row m-b-20">
                        <div class="col-md-12">
                            <div class="visible-xs">
                                <div class="clearfix"></div>
                            </div>
                            @if(!$contract->signature)
                                <button type="button" id="accept_action" class="btn btn-secondary pull-right m-r-10" onclick="sign();return false;"> @lang('app.sign')</button>
                            @else
                                <button class="btn btn-default pull-right m-r-10 disabled btn-secondary"> @lang('app.signed')</button>
                            @endif

                            <a href="{{ route("client.contracts.download", $contract->id) }}" class="btn btn-secondary pull-right m-r-10"><i class="fa fa-file-pdf"></i> @lang('app.download')</a>

                            <a href="{{ route("client.contracts.index") }}" class="btn btn-primary pull-left m-r-10"><i class="fa fa-arrow-circle-left"></i> @lang('app.back')</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs border-tab nav-secondary nav-left" id="danger-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="danger-home-tab" data-toggle="tab" href="#contract-summary" role="tab" aria-controls="contract-summary" aria-selected="true"><i class="fa fa-file"></i> @lang('modules.contracts.summery')</a></li>
                                <li class="nav-item"><a class="nav-link" id="profile-danger-tab" data-toggle="tab" href="#contract-discussion" role="tab" aria-controls="contract-discussion" aria-selected="false"><i class="fa fa-comment"></i> @lang('modules.contracts.discussion')</a></li>
                            </ul>

                            <div class="row">
                        <div class="col-md-8">
                            <div class="tab-content" id="danger-tabContent">
                                <div class="tab-pane fade show active" id="contract-summary" role="tabpanel" aria-labelledby="contract-summary-tab">
                                    {!! $contract->contract_detail !!}
                                </div>
                                <div class="tab-pane fade" id="contract-discussion" role="tabpanel" aria-labelledby="profile-danger-tab">
                                    {!! Form::open(['id'=>'addDiscussion','class'=>'ajax-form','method'=>'POST']) !!}
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="form-group">
                                                        <textarea name="message"  id="message"  rows="5" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-action pull-right">
                                            <button type="submit" class="btn btn-info" onclick="addDiscussion();return false;">@lang('modules.contracts.addComment')</button>
                                        </div>
                                        {!! Form::close() !!}

                                        <div class="clearfix"></div>

                                        <section class="comment-box contract_discussion">
                                            <h4>Comment</h4>
                                            <hr>
                                            <ul>
                                            @foreach($contract->discussion as $discussion)
                                            <li id="discussion-row-{{$discussion->id}}">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <img class="align-self-center" src="{{ $discussion->user->image_url }}" />
                                                        <div class="row">
                                                        <div class="col-md-7">
                                                            <h6 class="mt-0 m-b-10">{{ $discussion->user->name }} -<span> {{ $discussion->created_at->diffForHumans(\Carbon\Carbon::now()) }}</span></h6>
                                                        </div>
                                                        <div class="col-md-5">
                                                        
                                                            @if($discussion->from == $user->id)
                                                                <ul class="comment-social float-left float-md-right">
                                                                    <li class="digits"><a href="javascript:;" onclick="edit('{{ $discussion->id }}')"><i class="icon-pencil"></i> Edit</a></li>
                                                                    <li class="digits"><a href="javascript:;" class="remove-discussion" data-discussion-id="{{ $discussion->id }}"><i class="icon-trash"></i> Delete</a></li>
                                                                </ul>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12" id="discussion-{{ $discussion->id }}">
                                                            <p>{{ $discussion->message }}</p>
                                                        </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach                                           
                                            
                                            </ul>
                                        </section>
                  
                                                    
                                </div>                        
                            </div>
                        </div>
                        <div class="col-md-4 p-l-30">
                            <div class="card overview p-20">
                                    <address>
                                        <h3><b class="text-danger">{{ ucwords($global->company_name) }}</b></h3>
                                        <p class="text-muted">{!! nl2br($global->address) !!}</p>
                                    </address>
                                    <h5>@lang('modules.contracts.contractValue'): {{ $global->currency->currency_symbol }} {{ $contract->amount }}</h5>

                                    <table>
                                        <tr>
                                            <td># @lang('modules.contracts.contractNumber')</td>
                                            <td>{{ $contract->id }}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('modules.projects.startDate')</td>
                                            <td>{{ $contract->start_date->format($global->date_format) }}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('modules.contracts.endDate')</td>
                                            <td>{{ $contract->end_date->format($global->date_format) }}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('modules.contracts.contractType')</td>
                                            <td>{{ $contract->contract_type->name }}</td>
                                        </tr>
                                    </table>
                            </div>
                        </div>
                    </div>

                               
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->


    {{--Timer Modal--}}
    <div class="modal fade bs-modal-md in" id="estimateAccept" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('modules.contracts.editDiscussion')</h5>
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
    {{--Timer Modal Ends--}}
</div>
<!-- /#wrapper -->

<script src="{{ asset('themes/wceo/assets/js/jquery-3.2.1.min.js')}}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('themes/wceo/assets/js/bootstrap/popper.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/bootstrap/bootstrap.js')}}"></script>
<!-- feather icon js-->

<script src="{{ asset('themes/wceo/assets/js/notify/bootstrap-notify.min.js')}}"></script>

<script src="{{ asset('themes/wceo/assets/js/notify/index.js')}}"></script>

<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{ asset('themes/wceo/assets/js/script.js')}}"></script>


<script src="{{ asset('plugins/froiden-helper/helper.js')}}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>   
<script src="{{ asset('themes/wceo/assets/js/sweet-alert/sweetalert.min.js')}}"></script>

<script>
    $(document).ready(() => {
        let url = location.href.replace(/\/$/, "");

        if (location.hash) {
            const hash = url.split("#");
            $('#myTab a[href="#'+hash[1]+'"]').tab("show");
            url = location.href.replace(/\/#/, "#");
            history.replaceState(null, null, url);
            setTimeout(() => {
                $(window).scrollTop(0);
            }, 400);
        }

        $('a[data-toggle="tab"]').on("click", function() {
            let newUrl;
            const hash = $(this).attr("href");
            if(hash == "#summery") {
                newUrl = url.split("#")[0];
            } else {
                newUrl = url.split("#")[0] + hash;
            }
            // newUrl += "/";
            history.replaceState(null, null, newUrl);
        });
    });

    //Decline estimate
    function addDiscussion() {
        $.easyAjax({
            type:'POST',
            url:'{{route('client.contracts.add-discussion', $contract->id)}}',
            container:'#estimates',
            data: $('#addDiscussion').serialize(),
            success: function(response){
                if(response.status == 'success') {
                    $('#message').val('');
                    window.location.reload();
                }
            }
        })
    }

    //Accept estimate
    function sign() {
        var url = '{{ route('client.contracts.sign-modal', $contract->id) }}';
        $.ajaxModal('#estimateAccept', url);
    }

    //Accept estimate
    function edit(id) {
        var url = '{{ route('client.contracts.edit-discussion', ':id') }}';
        url = url.replace(':id', id);
        $.ajaxModal('#estimateAccept', url);
    }

    $('body').on('click', '.remove-discussion', function(){
        var id = $(this).data('discussion-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted discussion!",
                icon: "warning",
                buttons: ["No, cancel please!", "Yes, delete it!"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('client.contracts.remove-discussion',':id') }}";
                    url = url.replace(':id', id);
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token},
                        success: function (response) {
                            $('#discussion-row-'+id).remove();
                        }
                    });
                }
            });
        }); 

    
</script>

</body>
</html>