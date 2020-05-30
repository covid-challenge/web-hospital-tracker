<!DOCTYPE html>
<html>
  <head>
    <title>Map Test</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/leaflet.css"/>
    <script src="js/leaflet.js"></script>

    <script src="js/L.Control.MousePosition.js"> </script>
    <link rel="stylesheet" href="css/L.Control.MousePosition.css"/>

    <script src="js/L.Control.CoordinatesList.js"> </script>
    <link rel="stylesheet" href="css/L.Control.CoordinatesList.css"/>

    <link rel="stylesheet" href="css/style.css"/>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
      integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"> </script>

    <script src="js/bootstrap.min.js"> </script>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>

    <script src="js/bootstrap.bundle.min.js"> </script>

    <script src="js/fontawesome.min.js"> </script>
    <link rel="stylesheet" href="css/fontawesome.min.css"/>
  </head>

  <body>
    <!-- provide the csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div id="map" class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom"/>
  </body>

</html>

<script>
  var map = L.map('map').setView([14.567264226512432, 121.02156182531817], 14.0);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    tms: false,
    minZoom: 6,
    maxZoom: 16,
    attribution: 'OpenStreetMap'
  }).addTo(map);

  var selectedPoints = [];
  var selectedLines = []; // Contains all polylines in the map.

  var polygonFeatureGroup = L.featureGroup().addTo(map);
  var geoJsonFeatureGroup = L.featureGroup().addTo(map);
  var geoJsons = [];

  var geojsonLayer = $.getJSON("philippines.geojson", function(data){
      L.geoJson(data).addTo(map);
  });
</script>