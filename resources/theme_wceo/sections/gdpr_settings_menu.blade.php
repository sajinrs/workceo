{{--@section('other-section')

<div class="container-fluid">--}}
        <div class="card">
            {{--<div class="card-header">--}}
                {{--<h5>Links and buttons</h5>--}}
            {{--</div>--}}
            <div class="card-body p-0">
                <div class="list-group">
                    <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action"><i class="fa fa-arrow-left"></i> Back</a>
                    <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.index') active @endif" href="{{ route('admin.gdpr.index') }}">
                      General
                    </a>

    <a class="list-group-item list-group-item-action @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.right-to-data-portability') active @endif"
         href="{{ route('admin.gdpr.right-to-data-portability') }}">Right to data portability</a>
    <a class="list-group-item list-group-item-action @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.right-to-erasure') active @endif"
        href="{{ route('admin.gdpr.right-to-erasure') }}">Right to Erasure</a>
    <a class="list-group-item list-group-item-action @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.right-to-informed') active @endif"
        href="{{ route('admin.gdpr.right-to-informed') }}">Right to be informed</a>

    <a class="list-group-item list-group-item-action @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.right-of-access') active @endif"
        href="{{ route('admin.gdpr.right-of-access') }}">Right of access/Right to rectification</a>

    <a class="list-group-item list-group-item-action @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.consent') active @endif"
        href="{{ route('admin.gdpr.consent') }}">Consent</a>
   </div>
            </div>
        </div>
    {{--</div>--}}
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script>
    var screenWidth = $(window).width();
    if(screenWidth <= 768){

        $('.tabs-vertical').each(function() {
            var list = $(this), select = $(document.createElement('select')).insertBefore($(this).hide()).addClass('settings_dropdown form-control');

            $('>li a', this).each(function() {
                var target = $(this).attr('target'),
                    option = $(document.createElement('option'))
                        .appendTo(select)
                        .val(this.href)
                        .html($(this).html())
                        .click(function(){
                            if(target==='_blank') {
                                window.open($(this).val());
                            }
                            else {
                                window.location.href = $(this).val();
                            }
                        });

                if(window.location.href == option.val()){
                    option.attr('selected', 'selected');
                }
            });
            list.remove();
        });

        $('.settings_dropdown').change(function () {
            window.location.href = $(this).val();
        })

    }
</script>
{{--@endsection--}}