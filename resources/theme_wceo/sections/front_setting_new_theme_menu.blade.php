@section('other-section')
 <div class="container-fluid">
        <div class="card">
            {{--<div class="card-header">--}}
                {{--<h5>Links and buttons</h5>--}}
            {{--</div>--}}
            <div class="card-body p-0">
                <div class="list-group">

                    <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.theme-settings') active @endif" href="{{ route('super-admin.theme-settings') }}">@lang('app.menu.chooseTheme')
                    </a>

                     <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.front-settings.index') active @endif" href="{{ route('super-admin.front-settings.index') }}">@lang('app.front') @lang('app.menu.settings')
                    </a>

                     <a class="list-group-item list-group-item-action  @if(isset($type) && $type == 'image') active @endif" href="{{ route('super-admin.feature-settings.index') }}?type=image">@lang('app.feature') @lang('app.menu.settings')
                    </a>


                     <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.testimonial-settings.index') active @endif" href="{{ route('super-admin.testimonial-settings.index') }}">@lang('app.testimonial') @lang('app.menu.settings')
                    </a>

                       <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.client-settings.index') active @endif" href="{{ route('super-admin.client-settings.index') }}">@lang('app.frontClient') @lang('app.menu.settings')
                    </a>

                    <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.faq-settings.index') active @endif" href="{{ route('super-admin.faq-settings.index') }}">@lang('app.faq') @lang('app.menu.settings')
                    </a>

                   <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.contact-settings') active @endif" href="{{ route('super-admin.contact-settings') }}">@lang('app.contact') @lang('app.menu.settings')</a>

                   <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.price-settings') active @endif" href="{{ route('super-admin.price-settings') }}"> @lang('app.price') @lang('app.menu.settings')</a>

                   <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.front-menu-settings.index') active @endif" href="{{ route('super-admin.front-menu-settings.index') }}"> @lang('app.front') @lang('app.menu.menu') @lang('app.menu.settings')</a>

                    <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.footer-settings.footer-text') active @endif" href="{{ route('super-admin.footer-settings.footer-text') }}"> @lang('app.footer') @lang('app.menu.settings')</a>

                       <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.front-widgets.index') active @endif" href="{{ route('super-admin.front-widgets.index') }}">@lang('app.menu.frontWidgets')</a>


                </div>
            </div>
        </div>
    </div>

  
 
    

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
@endsection
