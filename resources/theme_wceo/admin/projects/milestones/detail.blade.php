<div id="event-detail">

    <div class="modal-header">
        <h5 class="modal-title">@lang('modules.projects.milestones') @lang('app.details')</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
        {!! Form::open(['id'=>'updateEvent','class'=>'ajax-form','method'=>'GET']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <h6>@lang('modules.projects.milestoneTitle')</h6>
                        <p>
                            {{ $milestone->milestone_title }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <h6>@lang('modules.projects.milestoneSummary')</h6>
                        <p>{{ $milestone->summary }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @if(!is_null($milestone->currency_id))
                    <div class="col-md-6">
                        <div class="form-group">
                            <h6>@lang('modules.projects.milestoneCost')</h6>
                            <p>
                                {{ $milestone->currency->currency_symbol.$milestone->cost }}

                                @if($milestone->cost > 0 && $milestone->invoice_created == 0)
                                    <a href="{{ route('admin.all-invoices.convert-milestone', $milestone->id) }}" class="btn btn-xs btn-info btn-rounded m-l-15">@lang('app.create') @lang('app.invoice')</a>
                                @elseif($milestone->cost > 0 && $milestone->invoice_created == 1)
                                    <a href="{{ route('admin.all-invoices.show', $milestone->invoice_id) }}" class="btn btn-xs btn-info btn-rounded m-l-15">@lang('app.view') @lang('app.invoice')</a>
                                @endif
                            </p>
                        </div>
                    </div>



                @endif

                <div class="col-xs-6 col-md-3">
                    <div class="form-group">
                        <h6>@lang('app.status')</h6>
                        <p>
                            @if($milestone->status == 'incomplete')
                                <label class="badge badge-danger">@lang('app.incomplete')</label>
                            @else
                                <label class="badge badge-success">@lang('app.complete')</label>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="col-md-12">
                        <h4>@lang('app.menu.tasks')</h4>
                        <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('app.task')</th>
                                        <th>@lang('modules.tasks.assignTo')</th>
                                        <th>@lang('modules.tasks.assignBy')</th>
                                        <th>@lang('app.dueDate')</th>
                                        <th>@lang('app.status')</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($milestone->tasks as $key=>$item)
                                            <tr>
                                                <td>{{ ($key+1) }}</td>
                                                <td>{{ ucfirst($item->heading) }}</td>
                                                <td>
                                                    <?php  //print_r($item->users) ?>
                                                    {{--{{ ucwords($item->user->name) }} --}}
                                                        @foreach ($item->users as $member)
                                                            <a href="{{route('admin.employees.show', $member->id)}}">
                                                            @if($member->image_url)
                                                                    <img data-toggle="tooltip" data-original-title="{{ucwords($member->name)}} " src="{{$member->image_url}}" alt="user" class="img-circle" width="25" height="25">
                                                            @else
                                                                <img data-toggle="tooltip" data-original-title="{{ucwords($member->name)}}" src="{{asset('img/default-profile-2.png')}}"  alt="user" class="img-circle" width="25" height="25">
                                                            </a>
                                                            @endif
                                                        @endforeach

                                                </td>
                                                <td>{{ ucwords($item->create_by->name) }}</td>
                                                <td>{{ $item->due_date->format($global->date_format) }}</td>
                                            <td><label class="badge" style="color:#fff; background-color: {{ $item->board_column->label_color }}">{{ $item->board_column->column_name }}</label></td>
                                            </tr>

                                        @empty
                                        <tr>
                                                <td colspan="5">@lang('messages.noRecordFound')</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                </div>

            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>

</div>
  