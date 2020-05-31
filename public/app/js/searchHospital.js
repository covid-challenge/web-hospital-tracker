
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
            header_default: $('.header_default')
        }

        return _ui;
    }

    function bindEvents() {
        $(document).on('keyup', ui.search , searchHospital);
    }

    function OnLoad(){
      initGeolocation();
      // onLoadMap();
    }

    function searchHospital(){
      var $url = $(ui.search).data('url');
      var $searchData = $(ui.search).val();
      var $token = $(ui.token).val();

      $.post($url,{"_token": $token,'data': $searchData},function(response){
        var data = response.data;
        var $result = '';

      // if($(ui.search).val() != ''){
        $(ui.container).html($result);
        $.each(data, function(key , val){
          $(ui.header_container).removeAttr('style');
          $(ui.header_default).prop('style', 'display:none !important;');
           $result =  `<div class="ct-activity__card hospital__coordinates" data-coordinates="${val.lat + '/' + val.lng}">
                         <div class="content__block card__content">
                            <span>${val.cfname}</span>
                        </div>
                        <div class="content__block card__content mt-2 ml-2">
                            ${val.city_mun}
                        </div>
                        <div class="content__block card__content mt-2 ml-2">
                            <span class="text-danger">Infected: 12</span>
                        </div>
                      </div>`;
          $(ui.container).append($result);
        });
        // }
        // else{
        //   $(ui.header_default).removeAttr('style');
        //   $(ui.header_container).prop('style', 'display:none !important;');
        // }
      });
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

     function nearestHospitals(){
       var $url = $(ui.nearestHospital).val();
       var $token = $(ui.token).val();
       $.post($url,{"_token": $token,'lat': SearchHospital.lat , 'lng' : SearchHospital.long },function(response){

         var data = response.data;
         var $result = '';

         $(ui.container).html($result);
         $.each(data, function(key , val){
           $(ui.header_container).removeAttr('style');
           $(ui.header_default).prop('style', 'display:none !important;');
            $result =   `<div class="ct-activity__card hospital__coordinates" data-coordinates="${val.lat + '/' + val.lng}">
                          <div class="content__block card__content">
                             <span>${val.cfname}</span>
                         </div>
                         <div class="content__block card__content mt-2 ml-2">
                             ${val.city_mun}
                         </div>
                         <div class="content__block card__content mt-2 ml-2">
                             <span class="text-danger">Infected: 12</span>
                         </div>
                       </div>`;
           $(ui.container).append($result);
         });
       });
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
