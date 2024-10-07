{{--@section('other-section')
<div class="container-fluid">--}}
        <div class="card">
            {{--<div class="card-header">--}}
                {{--<h5>Links and buttons</h5>--}}
            {{--</div>--}}
            <div class="card-body p-0">
                <div class="list-group">

        <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.settings.index') active @endif" href="{{ route('admin.settings.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('app.menu.settings')</a>


             {{--<a class="list-group-item list-group-item-action  @if($type == 'admin') active @endif" href="{{ route('admin.module-settings.index') }}" >@lang('app.menu.adminModule')</a>--}}

               <a class="list-group-item list-group-item-action  @if($type == 'employee') active @endif" href="{{ route('admin.module-settings.index') }}?type=employee" >@lang('app.menu.employeeModule')</a>

                  <a class="list-group-item list-group-item-action  @if($type == 'client') active @endif" href="{{ route('admin.module-settings.index') }}?type=client" >@lang('app.menu.clientModule')</a>
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