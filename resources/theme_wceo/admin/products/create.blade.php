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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.add') @lang('app.menu.products')</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection


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
                        <h5>@lang('app.add') @lang('app.menu.products')</h5> 
                    </div>
                    {!! Form::open(['id'=>'createProduct','class'=>'ajax-form','method'=>'POST']) !!}
                    <div  class="card-body">
                        <div class="form-body" >
                            <div class="vtabs customvtab m-t-10">
                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="form-body">
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-label-group form-group">                                   
                                                                <input placeholder="-" type="text" id="name" name="name" class="form-control-lg form-control">
                                                                <label for="name" class="required">@lang('app.name')</label>                                       
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-label-group form-group">                                   
                                                                <input placeholder="-" type="text" id="price" name="price" class="form-control-lg form-control">
                                                                <label for="price" class="required">@lang('app.price')</label>      
                                                                <span class="help-block">@lang('messages.productPrice')</span>                                 
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-label-group form-group">                              
                                                                <select name="tax[]" id="tax"  multiple="multiple" class="form-control form-control-lg" placeholder="-">
                                                                    @foreach($taxes as $tax)
                                                                        <option value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                                                    @endforeach
                                                                </select>   
                                                                <label for="tax">@lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings" ><i class="icofont icofont-gear"></i></a></label>             
                                                            </div> 

                                                            <div class="form-label-group form-group">                              
                                                                <select name="category" id="category_id" class="form-control form-control-lg hide-search" placeholder="-">
                                                                    <option value="">--</option>
                                                                    @forelse($categories as $category)
                                                                        <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                                                    @empty
                                                                    @endforelse
                                                                </select>   
                                                                <label for="category_id" class="required">@lang('app.category') <a href="javascript:;" id="addProjectCategory" ><i class="icofont icofont-gear"></i></a></label>             
                                                            </div>                                    
                                                            
                                                            <div class="form-group">

                                                                <div class="checkbox checkbox-info">
                                                                    <input id="purchase_allow" name="purchase_allow" value="no"
                                                                        type="checkbox">
                                                                    <label for="purchase_allow">@lang('app.purchaseAllow')</label>
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-label-group form-group">   
                                                                <textarea name="description"  id="description"  rows="5" class="form-control form-control-lg" placeholder="-"></textarea>   
                                                                <label for="description" class="control-label">@lang('app.description')</label>                     
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>               
                
                                            </div>
                                        </div><!--row-->
                                        <div class="clearfix"></div>
                                    </div><!--vhome3-->
                                </div><!--tab-content-->
                            </div> <!--customvtab-->
                        </div>    <!-- .form-body -->
                    </div> <!--card-body-->
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary form-control">@lang('app.cancel')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                    <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div> <!--panel-inverse-->
            </div>
        </div>
    </div>
</div>


{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="taxModal" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
    <div class="modal-dialog modal-md" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading">Modal title</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>                    
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
    $('#tax-settings').on('click', function (event) {
        event.preventDefault();
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.products.store')}}',
            container: '#createProduct',
            type: "POST",
            redirect: true,
            data: $('#createProduct').serialize()
        })
    });

    $('#createProduct').on('click', '#addProjectCategory', function () {
        var url = '{{ route('admin.productCategory.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    });

</script>
@endpush

