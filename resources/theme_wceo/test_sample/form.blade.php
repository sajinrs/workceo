@extends('layouts.test')
@push('head-script')


@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                        <div class="card">
                            <div class="card-header">
                                <h5>Default Form Layout</h5><span>Using the <a href="#">card</a> component, you can extend the default collapse behavior to create an accordion.</span>
                            </div>
                            <div class="card-body">
                                <form class="theme-form">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0" for="exampleInputEmail1">Email address</label>
                                        <input class="form-control" id="exampleInputEmail1" type="email" aria-describedby="emailHelp" placeholder="Enter email"><small class="form-text text-muted" id="emailHelp">We'll never share your email with anyone else.</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input class="form-control" id="exampleInputPassword1" type="password" placeholder="Password">
                                    </div>
                                    <div class="checkbox p-0">
                                        <input id="dafault-checkbox" type="checkbox">
                                        <label class="mb-0" for="dafault-checkbox">Remember my preference</label>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary">Submit</button>
                                <button class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>

            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-lg-12">
                    {!! Form::open(['id'=>'createCompany','class'=>'card ajax-form','method'=>'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('app.add') @lang('app.company')</h4>
                        <div class="card-options"><a class="card-options-collapse" href="#" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <h5 class="mb-4">Company Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name" class="required">@lang('modules.accountSettings.companyName')</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_email" class="required">@lang('modules.accountSettings.companyEmail')</label>
                                        <input type="email" class="form-control" id="company_email" name="company_email"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">@lang('modules.accountSettings.companyPhone')</label>
                                        <input type="tel" class="form-control" id="company_phone" name="company_phone"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">@lang('modules.accountSettings.companyWebsite')</label>
                                        <input type="text" class="form-control" id="website" name="website"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">@lang('modules.accountSettings.companyLogo')</label>

                                        <div class="col-md-12">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 250px; height: 80px;">

                                                    <img src="" alt=""/>
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="width: 250px; height: 80px;"></div>
                                                <div>
                                    <span class="btn btn-sm btn-success btn-file">
                                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                                        <span class="fileinput-exists"> @lang('app.change') </span>
                                        <input type="file" name="logo" id="logo"> </span>
                                                    <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                                       data-dismiss="fileinput"> @lang('app.remove') </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address" class="required">@lang('modules.accountSettings.companyAddress')</label>
                                        <textarea class="form-control" id="address" rows="5"
                                                  name="address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.defaultCurrency')</label>
                                        <select name="currency_id" id="currency_id" class="form-control select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.defaultTimezone')</label>
                                        <select name="timezone" id="timezone" class="form-control select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.changeLanguage')</label>
                                        <select name="locale" id="locale" class="form-control select2">
                                            <option value="en">English
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('app.status')</label>
                                        <select name="status" id="status" class="form-control select2">
                                            <option value="active">@lang('app.active')</option>
                                            <option value="inactive">@lang('app.inactive')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-4 mb-4">

                            <h5 class="mb-4">@lang('modules.company.accountSetup')</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="required">@lang('app.email')</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="required">@lang('modules.employees.employeePassword')</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                               value="">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-primary"> <i class="fa fa-check"></i> @lang('app.save')</button>

                        </div>
                    </div>
                    {!! Form::close() !!}


                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
@endpush