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
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>

                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(19)"><i data-feather="alert-circle"></i> <span>@lang('app.menu.pageTips')</span></a>

                    </div>
                </div>
                
            </div>
        </div>
    </div>


    <!-- Container-fluid starts-->
    <div class="container-fluid">
            
    <div class="row">
        @include('sections.report_menu')

        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div id="reportResult">
                        <div class="reportPreview p-30">
                        <i class="icon-folder"></i>
                            <h4>Select a report from the above menus.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
@endsection


@push('footer-script')

<script>

    function getRevenueReport()
    {
        var id = '';
        var url = "{{ route('admin.finance-report.index') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            dataType: 'JSON',
            success: function (response) {
                if (response.status == "success") {
                    $('#reportResult').html(response.view);
                } else {
                    alert('error');
                    $('#reportResult').html(response);
                }
            }
        });

      
    }
        

    $('.filterBtn li a').click(function(){
        $('.filterBtn li a').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).data('filter');    
        counter = 1;    
        loadContacts(tab);
    });
    
    //loadContacts('jobs');
    
    function loadContacts(tab) {
        var url = '{{route('admin.map.filter')}}';
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: url,
            type: "POST",            
            data: { tab:tab, _token : token},
            dataType: 'json',
            success: function (response) {
                $('#tabItems .searhMapItems').html(response.view);
                $('#see-more').removeClass('disabled');
            },
            error: function (response) {
                console.log(response);
            }
        })
    }
    
    
    $('#see-more').click(function(e){
        e.preventDefault();
        var tab = $('.filterBtn a.active').data('filter');
        var token = "{{ csrf_token() }}";
        counter++;
        var url = '{{route('admin.map.filter')}}?page='+counter;

        $.easyAjax({
            url: url,
            type: "POST",
            data: { tab:tab, _token : token},
            dataType: 'json',
            success: function (response) {
                if(response.view == ""){
                    $('#see-more').addClass('disabled');
                    return true;
                }
                $('#tabItems .searhMapItems').append(response.view);
                jQuery('#tabItems').slimscroll({ scrollBy: '400px' });
            },
            error: function (response) {
                console.log(response);
            }
        })
    });
    
</script>
@endpush

