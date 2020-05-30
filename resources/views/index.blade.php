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
            marker.bindPopup("<strong>" + hospitals[counter]['name'] + "</strong> <br/> "
            + "City: " + ( hospitals[counter]['city'] != '' ? hospitals[counter]['city'] : 'N/A' ) + "<br/>"
            + "Operator Type: " + hospitals[counter]['operator_type'] + "<br/>"
            + "Amenity: " + hospitals[counter]['amenity'] + "<br/>"
            + "Status: " +  hospitals[counter]['status']);
            markers.addLayer(marker);
        }

        map.addLayer(markers);

    </script>

@endpush