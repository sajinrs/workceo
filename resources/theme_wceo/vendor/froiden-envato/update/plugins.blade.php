@if (!empty($plugins = \Froiden\Envato\Functions\EnvatoUpdate::plugins()))

    <div class="col-md-12">
      <div class="card-header">
        <h4>{{ucwords(config('froiden_envato.envato_product_name'))}} Official Plugins</h4>
       </div>
        <div class="row  m-t-20">
            <div class="new-users">
            @foreach ($plugins as $item)



                      <div class="media">
                            <a href="{{ $item['product_link'] }}" target="_blank">
                                <img src="{{ $item['product_thumbnail'] }}" class="img-responsive rounded-circle image-radius m-r-15" alt="">
                            </a>

                       
                        <div class="media-body">
                          <h6 class="mb-0 f-w-700"> <a href="{{ $item['product_link'] }}" target="_blank" class="font-bold">{{ $item['product_name'] }}  </a></h6>
                          <p> {{ $item['summary'] }}</p>
                        </div><span class="pull-right">

                             <a href="{{ $item['product_link'] }}" target="_blank" class="btn btn-square btn-success-gradien"><i class="fa fa-arrow-right text-white"></i></a>
                             
                       
                      </div>
                      
                   
                   
            @endforeach
                 </div>
        </div>

    </div>
@endif
