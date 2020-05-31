
let SearchHospital = (function(){

    let ui = {};

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
    
    function initGeolocation()
     {
       if ("geolocation" in navigator){
       //check geolocation available
       //try to get user current location using getCurrentPosition() method
       navigator.geolocation.getCurrentPosition(function(position){
          SearchHospital.lat = position.coords.latitude;
          SearchHospital.long = position.coords.longitude;
          map.setView([SearchHospital.lat, SearchHospital.long], 30);
          nearestHospitals();
        });
       }else{
          alert('Geolocation is not supported for your browser. Please use a browser that supports this feature and try again.');
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
