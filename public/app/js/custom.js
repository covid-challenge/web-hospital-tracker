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

      if(latlng === '/'){
        var onboarding = $('[data-remodal-id=onboarding]').remodal();
        $('.ct-modal__content').html('');
        $('.ct-modal__content').append(`<h1 style="text-align: center;padding-top: 10%;" class="content__heading">
                                            No coordinates available
                                        </h1>`);
        onboarding.open();
      }
      else {
          map.setView(new L.LatLng(lat, lng), 30);
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

function enablePopulationDensityMap()
{
    densityGeoJson = $.getJSON("luzon_pop_density.geojson", function(data){
        L.geoJson(data, {
            style: style,
            onEachFeature: onEachFeature
        }).addTo(map);
    });

    // get color depending on population density value
    function getColor(density) {
        if (density > 1000)
            return "#800026";
        else if (density > 500)
            return "#BD0026";
        else if (density > 200)
            return "#E31A1C";
        else if (density > 100)
            return "#FC4E2A";
        else if (density > 50)
            return "#FD8D3C";
        else if (density > 20)
            return "#FEB24C";
        else if (density > 10)
            return "#FED976";
        else
            return "#FFEDA0";
    }

    function style(feature) {
        return {
            weight: 2,
            opacity: 0,
            color: "black",
            dashArray: "3",
            fillOpacity: 0.7,
            fillColor: getColor(feature.properties.DN)
        };
    }

    function zoomToFeature(e) {
        map.fitBounds(e.target.getBounds());
    }

    function onEachFeature(feature, layer) {
        layer.on({
            click: zoomToFeature
        });
    }
}
