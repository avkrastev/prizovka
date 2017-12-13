$(document).ready(function() {

function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 42.135408, lng: 24.745290},
        zoom: 14
    });
    var input = document.getElementById('pac-input');
    
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
}

if ($('#map').length > 0) {
    initMap();
} 

$('table td.operations a.viewUser').on('click', function () {
    var root = location.protocol + '//' + location.host;
    $.post(root+'/employees/view', {userId: $(this).attr('user-id')},
        function (resp) {          
            if (resp['error']) {
                return;
            }
            $('.modal-body h4.name').text(resp['first_name']+' '+resp['last_name']);
            for (var i in resp) {
                $('.modal-body p.'+i).text(resp[i]);
            }       
        }, 'json'
    );
});

$('table td.operations a.viewAddress').on('click', function () {
    var root = location.protocol + '//' + location.host;
    console.log($(this).attr('addressId'));
    $.post(root+'/subpoenas/view', {addressId: $(this).attr('addressId')},
        function (resp) {          
            if (resp['error']) {
                return;
            }
            var myLatLng = {lat: parseFloat(resp['latitude']), lng: parseFloat(resp['longitude'])};

            var map = new google.maps.Map(document.getElementById('map2'), {
                zoom: 17,
                center: myLatLng
            });
    
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: 'Hello World!'
            });

            google.maps.event.trigger(map, 'resize');
        }, 'json'
    );
});

$('.hasDatepicker').datepicker({
    format: "dd.mm.yyyy",
    weekStart: 1,
    language: "bg",
    todayHighlight: true
});

if ($('#assign').val() != '') {
    $('#createQR').val('Зачисли призовка');
}

$('#assign').on('change', function () {
    if ($(this).val() == '') {
        $('#createQR').val('Създай QR код');
    } else {
        $('#createQR').val('Зачисли призовка');
    }
});

$('#createQR').on('click', function (e) {
    if ($('#assign').val() == '') {
        e.preventDefault();
        $('span.download').remove();
        var root = location.protocol + '//' + location.host;
        $.post(root+'/addresses/createQR', 
            {address: $('[name="address"]').val(),
             date: $('[name="date"]').val(),
             number: $('[name="number"]').val()},
            function (resp) {
                $('#qrcode img').attr('src','https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='+resp['data']);
                $('#downloadQR').attr('href', resp['src']).append('<span class="help-block-none form-control-feedback download">За да свалите кода просто кликнете върху него!</span>');
            }, 'json'
        );
    } else {
        $('#addressesForm').submit();
    }
});

function urlobj(url) {
    if (url[0]=='/') url = '/' + url;
    var tmp = url.split('/'), obj = {};

    for (var i=0; i<tmp.length / 2; i++) {
        obj[tmp[i*2]] = tmp[i*2+1] || '';
    }
    return obj;
};

function objurl(obj) {
    var url = [];
    if (typeof(obj)!='object') return false;
    for (var i in obj) (i=='') ? url.push(i) : url.push(i, obj[i]);

    return url.join('/');
};
});