
$(window).load(function() {
    $('[data-role="navbar"] li a').on('click', function() {
        var root = location.protocol + '//' + location.host;
        window.location.href = root + '/app/' + $(this).attr('data-url');
    });

    $('[data-url="logout"]').on('click', function() {
        var root = location.protocol + '//' + location.host;
        window.location.href = root + '/session/end';
    });

    /*if ( navigator.geolocation ) {
        function success(pos) {
            // Location found, show map with these coordinates
            drawMap(new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude));
        }
        function fail(error) {
            drawMap(myLatLng);  // Failed to find location, show default map
        }
        // Find the users current position.  Cache the location for 5 minutes, timeout after 6 seconds
        navigator.geolocation.getCurrentPosition(success, fail, {maximumAge: 500000, enableHighAccuracy:true, timeout: 6000});
    } else {
        drawMap(myLatLng);  // No geolocation support, show default map
    }

    function drawMap(latlng) {
        var myOptions = {
            zoom: 17,
            center: latlng,
            //mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("subpoenaMap"), myOptions);
        // Add an overlay to the map of current lat/lng
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
        });

        google.maps.event.addListenerOnce(map, 'idle', function(){
            google.maps.event.trigger(map, 'resize');
            map.setCenter(marker.getPosition());
        });
    }*/
});

$( document ).on( "pagecreate", "#routes-page", function() {
    document.getElementById('submit').addEventListener('click', function() {
        $( "#mapDialog" ).popup( "open" );
        calculateAndDisplayRoute();
    });

    function calculateAndDisplayRoute() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: {lat: 42.135408, lng: 24.745290} // Plovdiv
        });
        directionsDisplay.setMap(map);

        var waypts = [];
        
        $('#waypoints input').each(function(k,v) {
            if ($(v).is(":checked")) {
                var location = {lat: parseFloat($(v).attr('lat')), lng: parseFloat($(v).attr('lng'))};
                waypts.push({
                    location: location,
                    stopover: true
                });
            }
        });

        var originLatLng = {lat: parseFloat($('#start option:selected').attr('lat')), lng: parseFloat($('#start option:selected').attr('lng'))};
        var destLatLng = {lat: parseFloat($('#end option:selected').attr('lat')), lng: parseFloat($('#end option:selected').attr('lng'))};

        directionsService.route({
        origin: originLatLng,
        destination: destLatLng,
        waypoints: waypts,
        optimizeWaypoints: true,
        travelMode: 'DRIVING'
        }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
            var summaryPanel = document.getElementById('directions-panel');
            summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
                var routeSegment = i + 1;
                summaryPanel.innerHTML += '<b>Спирка: ' + routeSegment +
                    '</b><br>';
                summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
                summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
                summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            }
        } else {
            window.alert('Directions request failed due to ' + status);
        }
        });
    }
});
        
$( document ).on( "pagecreate", "#index-page", function() {
    // Swipe to remove list item
    $( document ).on( "swipeleft swiperight", "#list li", function( event ) {
        var listitem = $( this ),
        // These are the classnames used for the CSS transition
        dir = event.type === "swipeleft" ? "left" : "right",
        // Check if the browser supports the transform (3D) CSS transition
        transition = $.support.cssTransform3d ? dir : false;
        confirmAndDelete( listitem, transition );
    });
    $( "#list li .address" ).on( "click", function( event ) {
        var self = $(this);
        var latlng = {lat: parseFloat($(this).attr('lat')), lng: parseFloat($(this).attr('lng'))};
        $( "#mapDialog .topic, #mapDialog .error" ).remove();
        $(this).find( ".topic" ).clone().insertAfter( "#address" );

        $( "#mapDialog" ).popup( "open" );

        var myOptions = {
            zoom: 16,
            center: latlng,
            //mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map"), myOptions);
        // Add an overlay to the map of current lat/lng
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
        });

        google.maps.event.addListenerOnce(map, 'idle', function(){
            google.maps.event.trigger(map, 'resize');
            map.setCenter(marker.getPosition());
        });

        $( "#mapDialog #submit" ).on( "click", function() {
            $( "#mapDialog #submit" ).off();
            var addressId = self.parent().attr('subpoena');
            var status = $('input[name="action"]:checked').val();

            var root = location.protocol + '//' + location.host;
            $.post(root+'/app/status', 
                {status: status, addressId: addressId},
                function (resp) {
                    if (resp['error']) {
                        $('p#address').prepend('<p class="error text-center">'+resp['error']+'</p>');
                    } else {
                        $( "#mapDialog" ).popup( "close" );
                        if (resp['status'] == '3') {
                            self.remove();
                            $( "#list" ).listview( "refresh" ).find( ".border-bottom" ).removeClass( "border-bottom" );
                        }
                    }
                }, 'json'
            );
            
        });
    });
    // If it's not a touch device...
    if ( ! $.mobile.support.touch ) {
        // Remove the class that is used to hide the delete button on touch devices
        $( "#list" ).removeClass( "touch" );
        // Click delete split-button to remove list item
        $( ".delete" ).on( "click", function() {
            var listitem = $( this ).parent( "li" );
            confirmAndDelete( listitem );
        });
    }
    function confirmAndDelete( listitem, transition ) {
        // Highlight the list item that will be removed
        listitem.children( ".ui-btn" ).addClass( "ui-btn-active" );
        // Inject topic in confirmation popup after removing any previous injected topics
        $( "#confirm .topic" ).remove();
        listitem.find( ".topic" ).clone().insertAfter( "#question" ).addClass('text-center');
        // Show the confirmation popup
        $( "#confirm" ).popup( "open" );
        // Proceed when the user confirms
        $( "#confirm #yes" ).on( "click", function() {
            // Remove with a transition
            if ( transition ) {
                listitem
                    // Add the class for the transition direction
                    .addClass( transition )
                    // When the transition is done...
                    .on( "webkitTransitionEnd transitionend otransitionend", function() {
                        var root = location.protocol + '//' + location.host;
                        $.post(root+'/app/deliver', 
                            {id: listitem.attr('subpoena')},
                            function (resp) {
                                if (resp != false) {
                                    // ...the list item will be removed
                                    listitem.remove();
                                    // ...the list will be refreshed and the temporary class for border styling removed
                                    $( "#list" ).listview( "refresh" ).find( ".border-bottom" ).removeClass( "border-bottom" );
                                }
                            }, 'json'
                        );
                    })
                    // During the transition the previous button gets bottom border
                    .prev( "li" ).children( "a" ).addClass( "border-bottom" )
                    // Remove the highlight
                    .end().end().children( ".ui-btn" ).removeClass( "ui-btn-active" );
            }
            // If it's not a touch device or the CSS transition isn't supported just remove the list item and refresh the list
            else {
                listitem.remove();
                $( "#list" ).listview( "refresh" );
            }
        });
        // Remove active state and unbind when the cancel button is clicked
        $( "#confirm #cancel" ).on( "click", function() {
            listitem.children( ".ui-btn" ).removeClass( "ui-btn-active" );
            $( "#confirm #yes" ).off();
        });
    }
});

$( document ).on( "pagecreate", "#assign-page", function() {
    $( "#list li .address" ).on( "click", function( event ) {
        var self = $(this);

        $( "#confirm" ).popup( "open" );
        $( "#confirm #yes" ).on( "click", function() {
            $( "#confirm #yes" ).off();
            var root = location.protocol + '//' + location.host;
            $.post(root+'/app/assignSubpoena', 
                {id: self.parent().attr('subpoena')},
                function (resp) {
                    if (resp != false) {
                        self.parent().remove();
                        $( "#list" ).listview( "refresh" ).find( ".border-bottom" ).removeClass( "border-bottom" );
                    }
                }, 'json'
            );
        });
        // Remove active state and unbind when the cancel button is clicked
        $( "#confirm #cancel" ).on( "click", function() {
            self.children( ".ui-btn" ).removeClass( "ui-btn-active" );
            $( "#confirm #yes" ).off();
        });
    });
});