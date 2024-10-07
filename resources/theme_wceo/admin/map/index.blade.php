@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>

                <div class="col">
                    <div class="btn-group bookmark pull-right" role="group">
                        <button class="btn btn-primary contactTypeadd btn-sm dropdown-toggle" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown">@lang('app.addNew') <i data-feather="plus"></i></button>
                        <div class="dropdown-menu mapdrop_btn" aria-labelledby="btnGroupVerticalDrop1">
                            <a class="dropdown-item" href="{{ route('admin.projects.create') }}">@lang('app.addJob')</a>
                            <a class="dropdown-item" href="{{ route('admin.clients.create') }}">@lang('app.addClient')</a>
                            <a class="dropdown-item" href="{{ route('admin.leads.create') }}">@lang('app.addLead')</a>
                            <a class="dropdown-item" href="{{ route('admin.employees.create') }}">@lang('app.addEmployee')</a>
                            <a class="dropdown-item" href="{{ route('admin.vehicles.create') }}">@lang('app.addVehicle')</a>
                        </div>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(18)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


    <!-- Container-fluid starts-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <ul class="nav border-tab filterBtn" id="top-tab" role="tablist">
                                <li class="nav-item"><a class="btn btn-light active" id="jobs-tab" data-filter="jobs"  data-toggle="tab" href="#top-jobs" role="tab" aria-controls="top-home" aria-selected="true">@lang('app.menu.jobs')</a></li>
                                <li class="nav-item"><a class="btn btn-light" id="clients-tab" data-filter="clients" data-toggle="tab" href="#top-clients" role="tab" aria-controls="top-profile" aria-selected="false">@lang('app.menu.clients')</a></li>
                                <li class="nav-item"><a class="btn btn-light" id="leads-tab" data-filter="leads" data-toggle="tab" href="#top-leads" role="tab" aria-controls="top-contact" aria-selected="false">@lang('app.menu.lead')</a></li>
                                <li class="nav-item"><a class="btn btn-light" id="employees-tab" data-filter="employees" data-toggle="tab" href="#top-employees" role="tab" aria-controls="top-contact" aria-selected="false">@lang('app.menu.employees')</a></li>
                                <li class="nav-item"><a class="btn btn-light" id="vehicles-tab" data-filter="vehicles" data-toggle="tab" href="#top-vehicles" role="tab" aria-controls="top-contact" aria-selected="false">@lang('app.menu.vehicles')</a></li>
                            </ul>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="est-time">
                                <span id="EstClock"></span> EST
                            </div> 
                        </div>
                    </div>
                    <div class="tab-content  m-t-20" id="top-tabContent">
                        <div class="tab-pane fade show active" id="top-jobs" role="tabpanel" aria-labelledby="jobs-tab">
                            <div class="row">
                                <div class="col-md-3 p-r-0">
                                    <div class="maps-left">
                                        <div id="tabItems-jobs" class="">
                                            <ul class="searhMapItems"></ul>
                                            <a href="javascript:;" id="see-more-jobs" class="btn btn-secondary btn-block see-more">Load More</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 p-l-0">
                                    <div id="map-jobs" style="height: 475px; width: auto;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-clients" role="tabpanel" aria-labelledby="clients-tab">
                            <div class="row">
                                <div class="col-md-3 p-r-0">
                                    <div class="maps-left">
                                        <div id="tabItems-clients" class="">
                                            <ul class="searhMapItems"></ul>
                                            <a href="javascript:;" id="see-more-clients" class="btn btn-secondary btn-block see-more">Load More</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 p-l-0">
                                    <div id="map-clients" style="height: 475px; width: auto;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-leads" role="tabpanel" aria-labelledby="leads-tab">
                            <div class="row">
                                <div class="col-md-3 p-r-0">
                                    <div class="maps-left">
                                        <div id="tabItems-leads" class="">
                                            <ul class="searhMapItems"></ul>
                                            <a href="javascript:;" id="see-more-leads" class="btn btn-secondary btn-block see-more">Load More</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 p-l-0">
                                    <div id="map-leads" style="height: 475px; width: auto;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-employees" role="tabpanel" aria-labelledby="employees-tab">
                            <div class="row">
                                <div class="col-md-3 p-r-0">
                                    <div class="maps-left">
                                        <div id="tabItems-employees" class="">
                                            <ul class="searhMapItems"></ul>
                                            <a href="javascript:;" id="see-more-employees" class="btn btn-secondary btn-block see-more">Load More</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 p-l-0">
                                    <div id="map-employees" style="height: 475px; width: auto;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-vehicles" role="tabpanel" aria-labelledby="vehicles-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="vehicleDetails"></div>
                                    
                                </div>
                                <div id="operatorList" class="col-md-3 p-r-0">
                                    <div class="maps-left">
                                        <div id="tabItems-vehicles" class="">
                                            <ul class="searhMapItems"></ul>
{{--                                            <a href="javascript:;" id="see-more-vehicles" class="btn btn-secondary btn-block see-more">Load More</a>--}}
                                        </div>
                                    </div>
                                </div>
                                <div id="pinMap" class="col-md-9 p-l-0">
                                    <div id="map-vehicles" style="height: 475px; width: auto;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
@endsection


@push('footer-script')
<script>
    var token = "{{ csrf_token() }}";
    var url = '{{route('admin.map.filter')}}';
    var vehicleDetailsUrl = "{{ route('admin.map.show-vehicle',':id') }}";
</script>
<script>
    var map =[];
    // Initialize and add the map
    function initMap() {
        // The map
        map['jobs'] = new google.maps.Map(document.getElementById('map-jobs'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 14,
            fullscreenControl: false,
            streetViewControl: false,
            mapTypeControl: false,});
        map['clients'] = new google.maps.Map(document.getElementById('map-clients'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 14,
            fullscreenControl: false,
            streetViewControl: false,
            mapTypeControl: false });
        map['leads'] = new google.maps.Map(document.getElementById('map-leads'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 14,
            fullscreenControl: false,
            streetViewControl: false,
            mapTypeControl: false });
        map['employees'] = new google.maps.Map(document.getElementById('map-employees'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 14,
            fullscreenControl: false,
            streetViewControl: false,
            mapTypeControl: false});
        map['vehicles'] = new google.maps.Map(document.getElementById('map-vehicles'), {
            //center: {lat: -34.397, lng: 150.644},
            zoom: 8,
            fullscreenControl: false,
            streetViewControl: false,
            mapTypeControl: false});

    }

</script>
<script defer
        src="https://maps.googleapis.com/maps/api/js?key={{ Config::get('app.google_map_key') }}&callback=initMap">
</script>
<script src="{{ asset('js/admin/maps_script.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script>
    $(document).ready(function(){

   //function to display time in (EST) New York 
   function estTime(offset=3)
    {
       /* var currentTime = new Date();
        currentTime.setHours(currentTime.getHours()+offset);
            
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();
        
        if(hours === 0){
            hours = 24;
        }
        if(hours < 10){
            hours = "0" + hours;
        }
        if(minutes < 10){
            minutes = "0" + minutes;
        }
        if(seconds < 10){
            seconds = "0" + seconds;
        }*/
        
        var EstClock = document.getElementById('EstClock');      

        var d = new Date();
        var est_d = d.toLocaleString('en-US', { timeZone: 'America/Toronto' });
        var estDate = new Date(est_d);

        var HH = estDate.getHours();
        var MM = estDate.getMinutes();
        if(HH === 0){  HH = 24; }else if(HH < 10){ HH = "0" + HH; }
        if(MM < 10){ MM = "0" + MM; }

        EstClock.innerText = HH + ":" + MM;
    }
    estTime();
    setInterval(estTime, 100);

    $('#top-tab li').click(function(){
        $('#operatorList').removeClass('d-none');
        $('#pinMap').removeClass('col-md-12').addClass('col-md-9 p-l-0');
        $('#vehicleDetails').addClass('d-none');
    })

    @if( Request::get('tab') == 'vehicle')
        $('#vehicles-tab').click();
    @endif
   
 }); 


 </script>

@endpush

