
<div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row reportBtn">
                        <div class="col-md-4">
                            <div class="btn-group btn-block " role="group">
                                <button class="dropdown-toggle btn btn-outline-primary text-left" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown"><span>@lang('app.financial')</span> @lang('app.reportSection')</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                    <a class="dropdown-item" href="{{ route('admin.finance-report.index') }}">@lang('app.grossRevenue') @lang('app.report')</a>
                                </div>
                            </div>   
                        </div>

                        <div class="col-md-4">
                            <div class="btn-group btn-block " role="group">
                                <button class="dropdown-toggle btn btn-outline-primary text-left" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown"> <span>@lang('app.client')</span> @lang('app.reportSection') </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                    <a class="dropdown-item" href="{{ route('admin.property-report.index') }}">@lang('app.propertyList') @lang('app.report')</a>
                                </div>
                            </div>   
                        </div>

                        <div class="col-md-4">
                            <div class="btn-group btn-block " role="group">
                                <button class="dropdown-toggle btn btn-outline-primary text-left operationReport" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown"> <span>@lang('app.operations')</span> @lang('app.reportSection') </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                    <a class="dropdown-item" href="{{ route('admin.task-report.index') }}">@lang('app.jobTask') @lang('app.report')</a>
                                    <a class="dropdown-item" href="{{ route('admin.time-log-report.index') }}">@lang('app.menu.timeLogReport') @lang('app.report')</a>
                                    <a class="dropdown-item" href="{{ route('admin.attendance-report.index') }}">@lang('app.menu.attendance') @lang('app.report')</a>
                                </div>
                            </div>   
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>
