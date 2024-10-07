
    <table class="table">
            <thead >
                <tr>
                    <th scope="col">@lang('app.employee')</th>
                    @for($i =1; $i <= $daysInMonth; $i++)
                        <th scope="col">{{ $i }}</th>
                    @endfor
                    <th scope="col">@lang('app.total')</th>
                </tr>
            </thead>
            <tbody>
            @foreach($employeeAttendence as $key => $attendance)
                @php
                    $totalPresent = 0;
                @endphp
                <tr>
                    {{-- <td> {{ substr($key, strripos($key,'#')+strlen('#')) }} </td> --}}
                    <td scope="row"> {!! end($attendance) !!} </td>
                    
                    
                    @foreach($attendance as $key2=>$day)
                        @if(now()->format('m') != request()->get('month'))
                            <td class="text-center">-</td>
                        @else 
                            @if ($key2+1 <= count($attendance))
                                <td class="text-center">
                                    @if($day == 'Absent')
                                        <a href="javascript:;" class="edit-attendance" data-attendance-date="{{ $key2 }}"><i class="fa fa-times text-danger"></i></a>
                                    @elseif($day == 'Holiday')
                                        <a href="javascript:;" class="edit-attendances" data-attendance-date="{{ $key2 }}"><i class="fa fa-star text-warning"></i></a>
                                    @else
                                        @if($day != '-')
                                            @php
                                                $totalPresent = $totalPresent + 1;
                                            @endphp
                                        @endif
                                        {!! $day !!}
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endforeach
                   
                    <td class="text-success">{{ $totalPresent .' / '.(count($attendance)-1) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

