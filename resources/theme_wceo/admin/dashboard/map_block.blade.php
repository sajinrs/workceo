{{--<div id="map" style="height: 530px; width: auto;"></div>--}}

 <script>
     var jobs_locations = {!! $jobsData !!};
     var member_locations = {!! $membersData !!};
     console.log(jobs_locations);

    /* jobs_locations.each(function (index,value) {
         alert(value);
     });*/




     function codeAddress(address) {
         geocoder.geocode( { 'address': address}, function(results, status) {
             if (status == 'OK') {
                 map.setCenter(results[0].geometry.location);
                 var marker = new google.maps.Marker({
                     map: map,
                     position: results[0].geometry.location
                 });
             } else {
                 alert('Geocode was not successful for the following reason: ' + status);
             }
         });
     }
    </script>
