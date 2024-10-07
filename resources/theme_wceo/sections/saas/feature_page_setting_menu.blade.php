@section('other-section')
 <div class="container-fluid">
        <div class="card">
            {{--<div class="card-header">--}}
                {{--<h5>Links and buttons</h5>--}}
            {{--</div>--}}
            <div class="card-body p-0">
                <div class="list-group">


        <a href="{{ route('super-admin.theme-settings') }}" class="list-group-item list-group-item-action"><i class="ti-arrow-left"></i> @lang('app.front') @lang('app.menu.settings')</a>

        <a class="list-group-item list-group-item-action  @if(isset($type) && $type == 'image') active @endif" href="{{ route('super-admin.feature-settings.index') }}?type=image">@lang('app.featureWithImage')</a>

     <a class="list-group-item list-group-item-action @if(isset($type) && $type == 'icon') active @endif"
        href="{{ route('super-admin.feature-settings.index') }}?type=icon">@lang('app.featureWithIcon')</a>

     <a class="list-group-item list-group-item-action @if(isset($type) && $type == 'task') active @endif"
        href="{{ route('super-admin.feature-settings.index') }}?type=task">@lang('app.task') @lang('app.section')</a>

    <a class="list-group-item list-group-item-action @if(isset($type) && $type == 'team') active @endif" href="{{ route('super-admin.feature-settings.index') }}?type=team">@lang('app.team') @lang('app.section')</a>

    <a class="list-group-item list-group-item-action @if(isset($type) && $type == 'bills') active @endif"  href="{{ route('super-admin.feature-settings.index') }}?type=bills">@lang('app.bills') @lang('app.section')</a>

    <a class="list-group-item list-group-item-action @if(isset($type) && $type == 'apps') active @endif" href="{{ route('super-admin.feature-settings.index') }}?type=apps">@lang('app.apps') @lang('app.section')</a>

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