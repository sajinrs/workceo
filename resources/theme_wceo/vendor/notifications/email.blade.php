@component('mail::message')

@if(isset($head_img))
{!! $head_img !!}
@endif

{{-- Greeting --}}
@if (! empty($greeting))
# {!! $greeting !!}
@else
@if ($level == 'error')
# Whoops!
@else
# Hello!
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{!! $line !!}

@endforeach

{{-- Action Button --}}
@if (isset($actionText))
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endif

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{!! $line !!}

@endforeach

<!-- Salutation -->
@if (! empty($salutation))
{{ $salutation }}
@else
@if (isset($regards))
@lang('email.regards'),<br>@lang('email.company')
@endif
@endif


@if(isset($level) && $level == 'secondary_footer')
<div style="background-color: #d9d9d9;color: #000;padding: 10px;padding-top: 14px;padding-bottom: 5px;margin-bottom: 20px;border-radius: 6px;overflow: hidden;padding-right: 96px;">
<p style="color:#000;margin:0"><div style="float: left;width: 80px;height: 60px;margin-right: 8px;"><img style="max-height:55px;" src="{{ config('app.logo') }}" /></div>
 {{$cmp_phone}} | {{$cmp_email}} | {{$cmp_web}} | {{$cmp_address}}</p>
</div>
@endif

<!-- Subcopy -->
@if (isset($actionText))
@component('mail::subcopy')
    @if (isset($email))
        <p><img src="{{ asset('img/worksuite-logo.png') }}" style="max-height: 22px" alt="" /><br />Email was sent to: {{ $email }} via WorkCEO Platform. Please do not reply. <a href="http://workceo.com/terms">Terms of Service.</a></p>

    @else
        <p><img src="{{ asset('img/worksuite-logo.png') }}" style="max-height: 22px" alt="" /><br />Email was sent to:--- via WorkCEO Platform. Please do not reply. <a href="http://workceo.com/terms">Terms of Service.</a></p>

    @endif

If you’re having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below
into your web browser: {{ route('login') }}
@endcomponent
@endif
@endcomponent
