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
                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(20)"><i data-feather="alert-circle"></i> <span>@lang('app.menu.pageTips')</span></a>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Container-fluid starts-->
    <div class="container-fluid">
            <div class="row">
              <div class="col-xl-9 xl-70">
                  <div id="searchResult">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="card">
                                  <div class="title p-20"> Loading...</div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-xl-3 xl-30">
                <div class="default-according style-1 faq-accordion job-accordion" id="accordionoc">
                  <div class="row">
                    <div class="col-xl-12">
                      <div class="card">
                        
                        <div class="collapse show" id="collapseicon" aria-labelledby="collapseicon" data-parent="#accordion">
                          <div class="card-body filter-cards-view animate-chk">
                          {!! Form::open(['id'=>'filterForm','class'=>'ajax-form','method'=>'POST']) !!}
                                <div class="job-filter">
                                <div class="faq-form">
                                    <input class="form-control" type="text" id="keyword" name="keyword" placeholder="Search.."><i class="search-icon" data-feather="search"></i>
                                </div>
                                </div>
                                <div class="checkbox-animated">
                                <div class="learning-header"><span class="f-w-600">Categories</span></div>
                                @forelse($faqCategories as $faqCategory)
                                  <div class="checkbox checkbox-primary">
                                    <input id="checkbox-primary-{{ $faqCategory->id }}" type="checkbox" name="faqcat[]" value="{{ $faqCategory->id }}">
                                    <label for="checkbox-primary-{{ $faqCategory->id }}"><i class="{{$faqCategory->fontawesome_code}} ??"></i> {{ $faqCategory->name }} ({{count($faqCategory->faqs)}})</label>
                                  </div>
                                @empty
                                    @lang('messages.noFaqCreated')
                                @endforelse
                                </div>
                                <button id="Catfilter" class="btn btn-primary text-center" type="button">@lang('app.filterResults')</button>
                                <button id="clearFilter" class="btn btn-outline-secondary text-center mt-3" type="button">@lang('app.clearFilter')</button>
                            {!! Form::close() !!}
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    
                  </div>
                </div>
              </div>
            </div>

                <div class="row">
                  
                </div>
          </div>


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="faqDetailsModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="faq-details-modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
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
    function showFaqDetails(id) {
        var url = '{{ route('admin.faqs.details', ':id')}}';
        url = url.replace(':id', id);

        $.ajaxModal('#faqDetailsModal', url);
    }

    loadFaq();

    $('#keyword').keyup(function(e){
        if(e.keyCode == 13) { loadFaq(); }
    });

    $('#Catfilter').click(function () {
        loadFaq();
    });

    $('#clearFilter').click(function () {
        $("#filterForm")[0].reset() 
        loadFaq();
    });

    $(document).ajaxComplete(function() {
        $('.pagination li a').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.easyAjax({
                url: url,
                type: "POST",
                container: '#filterForm',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function (response) {
                    //$(response.view).appendTo("#searchResult").fadeIn(500);
                    $('#searchResult').html(response.view);
                },
                error: function (response) {
                    console.log(response);
                }
            })
        });
    });

    function loadFaq() {
        var url = '{{route('admin.faqs.search')}}';
        $.easyAjax({
            url: url,
            type: "POST",
            container: '#filterForm',
            data: $('#filterForm').serialize(),
            dataType: 'json',
            success: function (response) {
                //$(response.view).appendTo("#searchResult").fadeIn(500);
                $('#searchResult').html(response.view);
            },
            error: function (response) {
                console.log(response);
            }
        })
    }

    




</script>
@endpush

