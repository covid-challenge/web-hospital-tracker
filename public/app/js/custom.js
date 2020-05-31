let Map = (function(){

    let ui = {};

    function bindUi(){

        this._ui = {
            hospital: $('.ct-activity__card'),
        }

        return _ui;
    }

    function bindEvents() {
        $(document).on('click','.hospital__coordinates' , redirectToMap);
    }

    function OnLoad(){
      // onLoadRedirect();
    }


    function redirectToMap(){
      var container = L.DomUtil.get('map'); if(container != null){ container._leaflet_id = null; }
      var latlng = $(this).data('coordinates');
      var lat = latlng.split('/')[0];
      var lng = latlng.split('/')[1];
      // console.log(latlng);
      if(latlng === '/'){
        var onboarding = $('[data-remodal-id=onboarding]').remodal();
        $('.ct-modal__content').html('');
        $('.ct-modal__content').append(`<h1 style="text-align: center;padding-top: 10%;" class="content__heading">
                                            No coordinates available
                                        </h1>`);
        onboarding.open();
      }
      else {

        var map = new L.map('map').setView([lat, lng], 20);

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

      }
    }


    // function onLoadRedirect(){
    //   var map = L.map('map').setView([-41.2858, 174.78682], 14);
    //
    //   L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    //       attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    //   }).addTo(map);
    //   var onboarding = $('[data-remodal-id=onboarding]').remodal();
    //   onboarding.open();
    // }

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
    Map.init();
});
