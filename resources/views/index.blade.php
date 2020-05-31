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
            + "City: " + ( hospitals[counter]['city_mun'] != '' ? ucwords(hospitals[counter]['city_mun']) : 'N/A') + "<br/>"
            + "Cases: " + ( hospitals[counter]['isolbed_o'] + hospitals[counter]['beds_ward_o'] + hospitals[counter]['icu_o'] ) );
            markers.addLayer(marker);
        }

        map.addLayer(markers);

        enablePopulationDensityMap();

        function ucwords (str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($word) {
                return $word.toUpperCase();
            });
        }
    </script>

@endpush