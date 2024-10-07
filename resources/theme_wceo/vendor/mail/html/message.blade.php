@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ config('app.logo') }}" style="max-height: 45px" alt="">

            <!-- <div style="position: relative;top: 32px !important;margin: 0;"><img src="{{ asset('img/mail/welcome_ads.jpg') }}" style="width:100%;" alt="" /></div> -->
        @endcomponent
    @endslot
    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @if (isset($subcopy))
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endif

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} {{ config('app.name') }}.
        @endcomponent
    @endslot
@endcomponent
