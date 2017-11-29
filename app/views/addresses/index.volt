
<section class="forms">
    <div class="container-fluid">
        <header> 
            <h1 class="h3 display">Създаване на QR кодове</h1>
        </header>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <div class="container-fluid ">
                            <div class="row">
                                <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Номер на дело</label>
                                    <input type="text" name="number" placeholder="Номер на дело" class="form-control">
                                </div>
                                <div class="form-group">       
                                    <label>Дата на издаване</label>
                                    <input type="text" name="date" placeholder="Дата на издаване" class="form-control" value="<?php echo date('d.m.Y');?>">
                                </div>
                                <div class="form-group">       
                                    <label>Адрес</label>
                                    <input type="text" name="address" id="pac-input" placeholder="Въведете адрес" class="form-control">
                                </div>
                                <div class="form-group">       
                                    <input type="submit" id="createQR" value="Създай QR код" class="btn btn-primary">
                                </div>
                                <div id="qrcode">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=HelloWorld&amp;size=100x100" alt="" title="" />
                                </div>
                                </div>
                                <div class="col-sm-6">
                                    <div id="map"></div>
                                    <div id="infowindow-content">
                                        <img src="" width="16" height="16" id="place-icon">
                                        <span id="place-name" class="title"></span><br>
                                        <span id="place-address"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 42.135408, lng: 24.745290},
        zoom: 14
    });
    var input = document.getElementById('pac-input');
    var types = document.getElementById('type-selector');
    var strictBounds = document.getElementById('strict-bounds-selector');

    var autocomplete = new google.maps.places.Autocomplete(input);

    // Bind the map's bounds (viewport) property to the autocomplete object,
    // so that the autocomplete requests use the current map bounds for the
    // bounds option in the request.
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var infowindowContent = document.getElementById('infowindow-content');
    infowindow.setContent(infowindowContent);
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        window.alert("No details available for input: '" + place.name + "'");
        return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
        } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);  // Why 17? Because it looks good.
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
        address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || '')
        ].join(' ');
        }

        infowindowContent.children['place-icon'].src = place.icon;
        infowindowContent.children['place-name'].textContent = place.name;
        infowindowContent.children['place-address'].textContent = address;
        infowindow.open(map, marker);
    });

    // Sets a listener on a radio button to change the filter type on Places
    // Autocomplete.
    function setupClickListener(id, types) {
        var radioButton = document.getElementById(id);
        radioButton.addEventListener('click', function() {
        autocomplete.setTypes(types);
        });
    }

    setupClickListener('changetype-all', []);
    setupClickListener('changetype-address', ['address']);
    setupClickListener('changetype-establishment', ['establishment']);
    setupClickListener('changetype-geocode', ['geocode']);

    document.getElementById('use-strict-bounds')
        .addEventListener('click', function() {
            console.log('Checkbox clicked! New state=' + this.checked);
            autocomplete.setOptions({strictBounds: this.checked});
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtCnmwX45uhYbzCjNI7a5FRl4PbthO2LU&libraries=places&callback=initMap&language=bg&region=BG" async defer></script>