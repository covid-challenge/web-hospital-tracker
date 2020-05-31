
let SearchHospital = (function(){

    let ui = {},
    long = 121.02156182531817,
    lat = 14.567264226512432;

    function bindUi(){

        this._ui = {
            search: $('.ct-activity__search_input'),
            nearestHospital: $('.nearest_hospital'),
            token: $('.token'),
            container: $('.wt-container'),
            header_container: $('.container_header'),
            header_default: $('.header_default'),
            search_header: $('.search_header')
        }

        return _ui;
    }

    function bindEvents() {
        $(document).on('keyup', ui.search , initSearchGeolocation);
    }

    function OnLoad(){
      initGeolocation();
      // onLoadMap();
    }

    function initSearchGeolocation()
     {
       if ("geolocation" in navigator){
       //check geolocation available
       //try to get user current location using getCurrentPosition() method
       navigator.geolocation.getCurrentPosition(function(position){
          SearchHospital.lat = position.coords.latitude;
          SearchHospital.long = position.coords.longitude;
          searchHospital();
        });
       }else{
         //browser not supported geolocation
         SearchHospital.long = 121.02156182531817;
         SearchHospital.lat = 14.567264226512432;
         searchHospital();
         alert('browser not supported geolocation');
       }
     }

    function searchHospital(){
      var $url = $(ui.search).data('url');
      var $searchData = $(ui.search).val();
      var $token = $(ui.token).val();
      var data = {"_token": $token,"data": $searchData,'lng': SearchHospital.long,'lat': SearchHospital.lat};
      if($(ui.search).val() != ''){
        $.post($url,data,function(response){
          var data = response.data;
          var $result = '';

          $(ui.search_header).text('Search Results');
          $(ui.container).html($result);
          $.each(data, function(key , val){
            var infected = parseInt(val.beds_ward_o + val.icu_o + val.isolbed_o);
            $(ui.header_container).removeAttr('style');
            $(ui.header_default).prop('style', 'display:none !important;');
             $result =  `<div class="ct-activity__card hospital__coordinates" data-coordinates="${val.lat + '/' + val.lng}">
                           <div class="content__block card__content">
                              <span>${val.name}</span>
                          </div>
                          <div class="content__block card__content mt-2 ml-2">
                              ${val.address.city +" "+ val.address.province +" "+ val.address.region}
                          </div>
                          <div class="content__block card__content mt-2 ml-2">
                              <span class="text-danger">Infected: ${val.infected.total}</span>
                          </div>
                          <div class="content__block card__content mt-2 ml-2">
                              <span class="">Distance: ${distanceParser(parseFloat(val.distance * 1000))}</span>
                          </div>
                        </div>`;
            if(val.lat != null || val.lng != null){
              $(ui.container).append($result);
            }
          });
        });
      }
      else {
        initGeolocationNearest();
      }
    }

    function onLoadMap(){
      // var map = L.map('map').setView([14.567264226512432, 121.02156182531817], 14.0);
      var map = L.map('map').setView([SearchHospital.lat, SearchHospital.long], 14.0);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          tms: false,
      }).addTo(map);

      var polygonFeatureGroup = L.featureGroup().addTo(map);
      var geoJsonFeatureGroup = L.featureGroup().addTo(map);
      var hospitals = hospital;

      var markers = L.markerClusterGroup();

      for(var counter = 0; counter < hospitals.length; counter++)
      {
          var marker = L.marker([hospitals[counter]['lat'], hospitals[counter]['lng']]);
          marker.bindPopup("<strong>" + hospitals[counter]['name'] + "</strong> <br/> "
          + "City: " + ( hospitals[counter]['city'] != '' ? ucwords(hospitals[counter]['city']) : 'N/A' ) + "<br/>"
          + "Operator Type: " + ucwords(hospitals[counter]['operator_type']) + "<br/>"
          + "Amenity: " + ucwords(hospitals[counter]['amenity']) + "<br/>"
          + "Status: " +  ucwords(hospitals[counter]['status']));
          markers.addLayer(marker);
      }

      map.addLayer(markers);

      function ucwords (str) {
          return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($word) {
              return $word.toUpperCase();
          });
      }

        var onboarding = $('[data-remodal-id=onboarding]').remodal();
        onboarding.open();
    }

    function initGeolocation()
     {
       if ("geolocation" in navigator){
       //check geolocation available
       //try to get user current location using getCurrentPosition() method
       navigator.geolocation.getCurrentPosition(function(position){
          SearchHospital.lat = position.coords.latitude;
          SearchHospital.long = position.coords.longitude;
          onLoadMap();
          nearestHospitals();
        });
       }else{
         //browser not supported geolocation
         SearchHospital.long = 121.02156182531817;
         SearchHospital.lat = 14.567264226512432;
         onLoadMap();
         nearestHospitals();
         alert('browser not supported geolocation');
       }
     }

    function initGeolocationNearest()
     {
       if ("geolocation" in navigator){
       //check geolocation available
       //try to get user current location using getCurrentPosition() method
       navigator.geolocation.getCurrentPosition(function(position){
          SearchHospital.lat = position.coords.latitude;
          SearchHospital.long = position.coords.longitude;
          nearestHospitals();
        });
       }else{
         //browser not supported geolocation
         SearchHospital.long = 121.02156182531817;
         SearchHospital.lat = 14.567264226512432;
         nearestHospitals();
         alert('browser not supported geolocation');
       }
     }

     function nearestHospitals(){
       var $url = $(ui.nearestHospital).val();
       var $token = $(ui.token).val();
       $.post($url,{"_token": $token,'lat': SearchHospital.lat , 'lng' : SearchHospital.long },function(response){

         var data = response.data;
         var $result = '';
         $(ui.search_header).text('Nearest Hospitals');
         $(ui.container).html($result);
         $.each(data, function(key , val){
           $(ui.header_container).removeAttr('style');
           $(ui.header_default).prop('style', 'display:none !important;');
            $result =   `<div class="ct-activity__card hospital__coordinates" data-coordinates="${val.lat + '/' + val.lng}">
                          <div class="content__block card__content">
                             <span>${val.name}</span>
                         </div>
                         <div class="content__block card__content mt-2 ml-2">
                             ${val.address.city +" "+ val.address.province +" "+ val.address.region}
                         </div>
                         <div class="content__block card__content mt-2 ml-2">
                             <span class="text-danger">Infected: ${val.infected.total}</span>
                         </div>
                         <div class="content__block card__content mt-2 ml-2">
                             <span class="">Distance: ${distanceParser(parseFloat(val.distance * 1000))}</span>
                         </div>
                       </div>`;
           if(val.lat != null || val.lng != null){
             $(ui.container).append($result);
           }
         });
       });
     }

     function distanceParser(distance){
       if(distance > 1000){
         distance = (distance / 1000).toFixed(2) + ' KM';
       }else if (distance > 1 && distance < 1000) {
         distance = distance.toFixed(2) + ' M'
       }else {
         distance = (distance * 100).toFixed(2) + ' CM'
       }
       return distance;
     }

    function init(){
        ui = bindUi();
        bindEvents();
        OnLoad();
    }

    return {
        init: init,
        _ui: ui
    }

})();

$(document).ready(function(){
    SearchHospital.init();
});
