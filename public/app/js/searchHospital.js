
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
      
      $(ui.search_header).text('Search Results');
      
      $.post($url,{"_token": $token,'data': $searchData},function(response){
        var data = response.data;
        var $result = '';

      // if($(ui.search).val() != ''){
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
                      </div>`;
          $(ui.container).append($result);
        });
      });
    }

    function initGeolocation()
     {
       if ("geolocation" in navigator){
       //check geolocation available
       //try to get user current location using getCurrentPosition() method
       navigator.geolocation.getCurrentPosition(function(position){
          lat = position.coords.latitude;
          long = position.coords.longitude;
          map.setView([lat, long], 16);
          nearestHospitals();
        });
       }else{
         //browser not supported geolocation
         map.setView([lat, long], 16);
         nearestHospitals();
         alert('browser not supported geolocation');
       }
     }

     function nearestHospitals(){
       var $url = $(ui.nearestHospital).val();
       var $token = $(ui.token).val();
       $.post($url,{"_token": $token,'lat': lat , 'lng' : long },function(response){

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
