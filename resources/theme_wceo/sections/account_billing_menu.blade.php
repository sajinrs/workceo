{{--@section('other-section')
<div class="container-fluid">--}}
        <div class="card">
            {{--<div class="card-header">
                <h5>Account & Billing</h5>
            </div>--}}
            <div class="card-body p-0">
                <div class="list-group">
                       <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.billing' || \Illuminate\Support\Facades\Route::currentRouteName() == 'admin.billing.select-package') active @endif" href="{{ route('admin.billing') }}">
                       Billing Overview & Subscription
                       </a>
                     <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.billing.invoices') active @endif" href="{{ route('admin.billing.invoices') }}">
                         Invoices
                    </a>
                     <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.billing.transactions') active @endif" href="{{ route('admin.billing.transactions') }}">
                         Payment History
                     </a>
                    <a class="list-group-item list-group-item-action  @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.billing.payment_settings') active @endif" href="{{ route('admin.billing.payment_settings') }}">
                        Payment Settings
                    </a>
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