$(document).ready(function() {
    var map = L.map('map').setView([-41.2858, 174.78682], 14);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    var onboarding = $('[data-remodal-id=onboarding]').remodal();
    onboarding.open();
});
