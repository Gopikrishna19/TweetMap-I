var map, hmap;
// initialize google maps
function initialize() {
    // declare map preperties
    var mapProp = {
        center: new google.maps.LatLng(51.508742, -0.120850),
        zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    // call Google Maps
    map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
    hmap = new google.maps.Map(document.getElementById("heatMap"), mapProp);
}
google.maps.event.addDomListener(window, 'load', initialize);


// update maps with markers using the latitude and longitude from the server
function updateMap(key) {
    // make an ajax call to get pins from the server
    // pass the selected keyword as the parameter
    $.ajax({
        url: "ajax/pins.php?q=" + key,
        success: function (markers) {
            // initialize the map again to clear it
            initialize();

            // define new bounds
            var bounds = new google.maps.LatLngBounds(), i, infoWindow = new google.maps.InfoWindow();

            // populate the tweets into info windows for the marker
            var infoWindows = [];
            for (i = 0; i < markers.length; ++i) {
                t = $("<div class='info_content' />").html($("<p />").html(markers[i][0]))
                infoWindows.push(t);
                delete t;
            }
            // for heat map
            var arr = [];

            // for each pin from the ajax set the marker on the map
            for (i = 0; i < markers.length; ++i) {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);

                // add position to arr
                arr.push(position);

                // extend the map bounds to include the new marker
                bounds.extend(position);

                // new marker
                marker = new google.maps.Marker({
                    position: position,
                    map: map
                });

                // add an onclick listener to the marker to display the info window i.e. the tweet
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    if (infoWindow) infoWindow.close();
                    infoWindow = new google.maps.InfoWindow();
                    return function () {
                        infoWindow.setContent(infoWindows[i][0]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));

                // fit the bounds convering all the markers
                map.fitBounds(bounds);
                hmap.fitBounds(bounds);
            }

            // generate heat map
            (new google.maps.visualization.HeatmapLayer({
                data: new google.maps.MVCArray(arr)
            })).setMap(hmap);
        }
    });
}