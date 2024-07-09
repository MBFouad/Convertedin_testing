/* global bicycleRental */
import L from 'leaflet';
// import _ from 'chosen-js';
// window._ = _;

//require('chosen-js');

window.drawMap = async function (id, jsons, lat, long, zoom) {
    const map = await L.map(id)
        .setView([lat, long], zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    function onEachFeature(feature, layer) {
        let popupContent = '';

        if (feature.properties && feature.properties.popupContent) {
            popupContent += feature.properties.popupContent;
        }

        layer.bindPopup(popupContent);
    }

    L.geoJSON(jsons, {
        style: function (feature) {
            return feature.properties && feature.properties.style;
        },

        onEachFeature: onEachFeature,

        pointToLayer: function (feature, latlng) {
            return L.circleMarker(latlng, {
                radius: 8,
                fillColor: "#ff7800",
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            });
        }
    }).addTo(map);
};
