$(window).load(function() {

function initMap() {
    var plovdiv = {lat: 42.135408, lng: 24.745290};

    var map = new google.maps.Map(document.getElementById('map'), {
        center: plovdiv,
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
        anchorPoint: new google.maps.Point(0, -29),
    });

    google.maps.event.addDomListener(input, 'keydown', function(event) { 
      if (event.keyCode === 13) { 
          event.preventDefault(); 
      }
    }); 

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("Няма данни за въведения адрес: '" + place.name + "'");
            return;
        }
        // set place coordinates
        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();

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

        createQR();
    });
}

$('#case_number, #reference_number').on('change', function() {
    if ($('#pac-input').val() != '') {
        createQR();
    }
});

function createQR() {
    $('span.download').remove();
    var number = $('#case_number').val();
    var refNumber = $('#reference_number').val();
    var lat = $('#latitude').val();
    var lng = $('#longitude').val();

    var data = 'latlng='+lat+','+lng+',number='+number+',refNumber='+refNumber;
    var url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='+data;

    $('#qrcode img').attr('src', url);
    $('#downloadQR').append('<span class="help-block-none form-control-feedback download">За да свалите кода, кликнете върху него!</span>');

    var root = location.protocol + '//' + location.host;
    $.post(root+'/addresses/createQR', 
        {url: url},
        function (resp) {
            $('#downloadQR').attr('href', resp);
        }, 'json'
    );
}

if ($('#map').length > 0) {
    initMap();
} 

$('div.flash-output .alert-success').fadeOut(2000);

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

$('table.subpoenas td a.viewAddress').on('click', function () {
    var root = location.protocol + '//' + location.host;
    $.post(root+'/subpoenas/view', {addressId: $(this).parents('tr').attr('addressId')},
        function (resp) {       
            if (resp['error']) {
                return; // TODO error show
            }
            for (var i in resp) {
                $('.modal-body p.'+i).text(resp[i]);
            }       
            var myLatLng = {lat: parseFloat(resp['latitude']), lng: parseFloat(resp['longitude'])};

            var map = new google.maps.Map(document.getElementById('map2'), {
                zoom: 17,
                center: myLatLng
            });
    
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: resp['address']
            });

            google.maps.event.addListenerOnce(map, 'idle', function(){
                google.maps.event.trigger(map, 'resize');
                map.setCenter(marker.getPosition());
            });
        }, 'json'
    );
});

$('table.subpoenas tr td:not(.address)').on('click', function() {
    var addressId = $(this).parent().attr('addressId');
    var root = location.protocol + '//' + location.host;
    window.location.replace(root+'/subpoenas/edit/'+addressId);
});

$('.hasDatepicker').datepicker({
    format: "dd.mm.yyyy",
    weekStart: 1,
    language: "bg",
    todayHighlight: true
});

$('form .form-group').eq(0).find('input[type="text"]').focus();

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