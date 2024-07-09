/* global $ */
import loadjs from 'loadjs';
import geocomplete from 'geocomplete';

// load googleapis
let apiJs = '//maps.googleapis.com/maps/api/js?key=AIzaSyA_TX9nyVNa8TSMmVf86qs_qa90IywvTyk&sensor=false&libraries=places&callback=callbackInit';
loadjs(apiJs);

// googleapis loaded
window.callbackInit = () => {
    // redefine function addressHandler
    $(document).ready(function () {
        // init addressHandler on the address field
        let address = $('#address');
        if (address.length) {
            window.addressHandler(address, $('#city'), $('#zip_code'), $('#country'), $('#latitude'), $('#longitude'));
        }
    });

}

window.addressHandler = (address, city, zip, country, lat, lon) => {
    let found = false;
    let currentAttemptCounter = 0;
    const maxAttemptCounter = 3;

    let handleNotFound = function () {
        if (found) {
            return;
        }

        ++currentAttemptCounter;
        $.ajax({
            type: 'get',
            data: {},
            url: 'https://nominatim.openstreetmap.org/search?format=json&limit=1&addressdetails=1&q=' + encodeURIComponent(address.val()),
            headers: {
                'Accept-Language': 'en-US'
            },

            success: function (data) {
                if (data.length) {
                    let item = data[0];
                    if (item.address) {

                        // update city
                        let foundCity = item.address.town || item.address.city || item.address.village || '';
                        if (foundCity) {
                            city.val(foundCity);
                        }

                        // update zip
                        if (item.address.postcode) {
                            zip.val(item.address.postcode);
                        }

                        // update country
                        if (item.address.country) {
                            country.val(item.address.country);
                        }

                        // update geo
                        if (item.lat && item.lon) {
                            lat.val(item.lat);
                            lon.val(item.lon);
                        }
                    }
                }
            },

            error: function (error) {
                if (currentAttemptCounter < maxAttemptCounter) {
                    handleNotFound();
                }
            },
        });
    };

    address
        .on('keyup', function (event) {
            found = false;
        })
        .geocomplete({
            'autoselect': false,
            'blur': false,
            'geocodeAfterResult': false
        })
        .bind("geocode:result", function (event, result) {
            let data = {};

            found = true;

            $.each(result.address_components, function (index, object) {
                $.each(object.types, function (index, name) {
                    data[name] = object.long_name;
                    data[name + "_short"] = object.short_name;
                });
            });

            // update city
            let foundCity = data.locality || data.administrative_area_level_1 || '';
            if (foundCity) {
                city.val(foundCity);
            }

            // update zip
            if (data.postal_code) {
                zip.val(data.postal_code);
            }

            // update country
            if (data.country) {
                country.val(data.country);
            }

            // update geo
            if (result.geometry.location.lat() && result.geometry.location.lng()) {
                lat.val(result.geometry.location.lat());
                lon.val(result.geometry.location.lng());
            }

        })
        .bind('geocode:error', function (event, result) {
            found = false;
            setTimeout(handleNotFound, 300);
        });
};
