@extends('layouts.super-admin')
@push('head-script')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <!-- Plugins css Ends-->
@endpush


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
                                    <div class="col-md-12 p-l-0">
                                        <div id="map-jobs" style="height: 475px; width: auto;"></div>
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
    </script>
    <script>
        var testmap;
        // Initialize and add the map
        function initMap() {
            // The map
            testmap = new google.maps.Map(document.getElementById('map-jobs'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 2,
                fullscreenControl: false,
                streetViewControl: false,
                mapTypeControl: false,});
            geocoder = new google.maps.Geocoder();

        }

    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ Config::get('app.google_map_key') }}&callback=initMap&libraries=places&v=weekly">
    </script>
    <script type="application/javascript">
        $(document).ready(function () {
          let markers = [];
            /*var marker = new google.maps.Marker({
                map: testmap,
                position: {lat: 12, lng: 12},
                icon: '../../public/img/blue_mark.png',
                title: "Job"

            });
            markers.push(marker);*/
          function getMarker(latlng) {
                var marker = new google.maps.Marker({
                    map: testmap,
                    position: latlng,
                    icon: '../../public/img/blue_mark.png',
                    title: "Job"

                });
                marker.addListener("click", () => {
                    // infowindow.open(testmap, marker);
                });
                // testmap.setCenter(latlng);
                return marker;
            }

             setInterval(function(testmap) {

              console.log('ajax call...');

                $.ajax('test-map/getlocs', {
                    dataType: 'json',  // data to submit
                    success: function (data, status, xhr) {
                        deleteMarkers();
                        var markers = [];
                        console.log(data);
                        $.each(data, function(i, item) {
                            //console.log(item);
                           // addMarker(item) ;
                            //let lat = parseFloat(item.lat).toPrecision(50);
                            let lat = Number(item.lat).toExponential(10);
                            // lat = Math.round( item.lat * 1e9 ) / 1e9; //toFixedNumber(lat, 1, 16);
                            var loc = {lat: Number(item.lat), lng: Number(item.lng)};
                            console.log(lat);
                            console.log(Math.floor(item.lat * 100) / 100);
                            console.log(Number(item.lat));
                            addMarker(loc);
                        });
                        showMarkers();

                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('p').append('Error' + errorMessage);
                    }
                });
                    // create a random point inside the bounds
                    // var ptLat = Math.random() * (-34.397);
                    // var ptLng = Math.random() * (150.644);
                    // var latlng = {lat: ptLat, lng: ptLng};
            }, 10000) ;

        /*for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }*/
       // markers = mr;


            function toFixedNumber(num, digits, base){
                var pow = Math.pow(base||10, digits);
                return Math.round(num*pow) / pow;
            }


            // Adds a marker to the map and push to the array.
            function addMarker(position) {
                const marker = new google.maps.Marker({
                    position,
                    testmap,
                });
                markers.push(marker);
            }

// Sets the map on all markers in the array.
            function setMapOnAll(map) {
                for (let i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

// Removes the markers from the map, but keeps them in the array.
            function hideMarkers() {
                setMapOnAll(null);
            }

// Shows any markers currently in the array.
            function showMarkers() {
                console.log('show markers...');
                console.log(markers);
                setMapOnAll(testmap);
            }

// Deletes all markers in the array by removing references to them.
            function deleteMarkers() {
                console.log('delete markers...');
                hideMarkers();
                markers = [];
            }









        });
    </script>

@endpush
