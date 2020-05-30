@extends('layout')

@section('page-body')
    <!-- provide the csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div id="map"></div>
@endsection

@push('scripts')

    <script>
        var map = L.map('map').setView([14.567264226512432, 121.02156182531817], 14.0);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            tms: false,
        }).addTo(map);

        var polygonFeatureGroup = L.featureGroup().addTo(map);
        var geoJsonFeatureGroup = L.featureGroup().addTo(map);
        var hospitals = JSON.parse( JSON.stringify( {!! json_encode($hospitals) !!} ) );
        
        var markers = L.markerClusterGroup();
        for(var counter = 0; counter < hospitals.length; counter++)
        {
            var marker = L.marker([hospitals[counter]['lat'], hospitals[counter]['lng']]);
            marker.bindPopup("<strong>" + hospitals[counter]['cfname'] + "</strong> <br/> "
            + "City: " + ( hospitals[counter]['city_mun'] != '' ? ucwords(hospitals[counter]['city_mun']) : 'N/A' ));
            markers.addLayer(marker);
        }

        map.addLayer(markers);

        addChloroplethMap();
        
        function ucwords (str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($word) {
                return $word.toUpperCase();
            });
        }

        function addChloroplethMap()
        {
            var geojsonLayer = $.getJSON("philippines_pop_density.geojson", function(data){
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
    </script>

@endpush