var map = L.map('map').setView([14.567264226512432, 121.02156182531817], 14.0);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  tms: false,
  minZoom: 6,
  maxZoom: 16,
  attribution: 'Map of Manila'
}).addTo(map);

var selectedPoints = [];
var selectedLines = []; // Contains all polylines in the map.

var polygonFeatureGroup = L.featureGroup().addTo(map);
var geoJsonFeatureGroup = L.featureGroup().addTo(map);
var geoJsons = [];

var geojsonLayer = $.getJSON("philippines.geojson", function(data){
    L.geoJson(data).addTo(map);
});
