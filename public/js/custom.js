$(window).load(function() {
    function initMap() {
        var hasCoords = false;
        var zoom = 14;
        var position = {lat: 42.135408, lng: 24.745290}; // Plovdiv

        if ($('#latitude').val() > 0 && $('#longitude').val() > 0) {
            position = {lat: parseFloat($('#latitude').val()), lng: parseFloat($('#longitude').val())};
            zoom = 17;
            hasCoords = true;
        }

        var map = new google.maps.Map(document.getElementById('map'), {
            center: position,
            zoom: zoom
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

        if (hasCoords !== false) {
            marker.setPosition(position);
        }

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

    if ($('#addressesForm').length > 0) {
        var fields = ['pac-input', 'case_number', 'reference_number'];
        validation($('#addressesForm'), fields);
    }

    if ($('#employeesForm').length > 0) {
        var fields = ['first_name', 'last_name', 'email', 'password'];
        validation($('#employeesForm'), fields);
    }

    function validation(form, fields) {
        form.on('submit', function(event) {
            form.off();
            event.preventDefault();

            $('.form-group .has-danger, .has-danger').removeClass('has-danger');
            $('.form-group input').removeClass('form-control-danger');
            $('.form-control-feedback').hide();

            var valid = true;
            $.each(fields, function(k, v) {
                if ($('#'+v).val() == '') {
                    valid = false;
                    $('#'+v).parent().addClass('has-danger');
                    $('#'+v).addClass('form-control-danger');
                    $('#'+v).next('.form-control-feedback').show();
                }
            });

            if (valid !== false) form.submit();
        });
    }

    $('#case_number, #reference_number').on('change', function() {
        if ($('#pac-input').val() != '') {
            createQR();
        }
    });

    if ($('.editSubpoenas').length > 0) {
        createQR();
    }

    function createQR() {
        $('#downloadQR > span.form-control-feedback').remove();
        var root = location.protocol + '//' + location.host;
        var number = $('#case_number').val();
        var refNumber = $('#reference_number').val();
        var lat = $('#latitude').val();
        var lng = $('#longitude').val();

        var data = lat+','+lng+'&'+number+'&'+refNumber;
        data = root+'/addresses/qr?subpoena='+Base64.encode(data);
        var url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='+data;

        $('#qrcode img').attr('src', url);
        $('#downloadQR').append('<span class="help-block-none form-control-feedback">За да свалите кода, кликнете върху него!</span>');
        
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

    $('div.flash-output .alert-success, div.flash-output .alert-danger, div.flash-output .alert-info').on('click', function() {
        $(this).fadeOut(1000);
    });

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

    $('table.subpoenas td a.viewAddress, table.history td a.viewAddress').on('click', function () {
        var root = location.protocol + '//' + location.host;
        $.post(root+'/subpoenas/view', {addressId: $(this).parents('tr').attr('addressId')},
            function (resp) {             
                if (resp['error']) {
                    return; // TODO error show
                }
                for (var i in resp['address']) {
                    $('.modal-body p.'+i).text(resp['address'][i]);
                }
                $('.modal-body p.assigned_to').text(resp['assigned_to']);    

                var myLatLng = {lat: parseFloat(resp['address']['latitude']), lng: parseFloat(resp['address']['longitude'])};

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

    $('table.users td a.delUser').on('click', function () {
        var self = $(this);
        $('#deleteUserModal #yes').on('click', function() {
            $('#deleteUserModal #yes').off();
            var root = location.protocol + '//' + location.host;
            window.location.href = root + '/employees/delete/'+self.attr('user-id');
        }); 
        $('#deleteUserModal #cancel').on('click', function() {
            $('#deleteUserModal #yes').off();
        });      
    });

    if ($('#subpoenaMap').length > 0) {
        var myLatLng = {lat: parseFloat($('#subpoenaMap').attr('lat')), lng: parseFloat($('#subpoenaMap').attr('lng'))};
    
        var map = new google.maps.Map(document.getElementById('subpoenaMap'), {
            zoom: 17,
            center: myLatLng
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map
        });
    }

    $('.hasDatepicker, .input-daterange').datepicker({
        format: "dd.mm.yyyy",
        weekStart: 1,
        language: "bg",
        todayHighlight: true
    });

    if ($('.stats').length > 0) {
        var root = location.protocol + '//' + location.host;
        $.getJSON('/statistics/getStats', function (resp) {
            var brandPrimary = 'rgba(51, 179, 90, 1)';

            if (resp['subpoenasCountCurrentMonth'].length > 0) {
                var subpoenasCountCurrentMonth = new Chart($('#subpoenasCountCurrentMonth'), {
                    type: 'doughnut',
                    data: {
                        labels: resp['subpoenasCountCurrentMonth']['name'],
                        datasets: [
                            {
                                data: resp['subpoenasCountCurrentMonth']['count'],
                                borderWidth: [1, 1, 1],
                                backgroundColor: [
                                    brandPrimary,
                                    "rgba(75,192,192,1)",
                                    "#FFCE56"
                                ],
                                hoverBackgroundColor: [
                                    brandPrimary,
                                    "rgba(75,192,192,1)",
                                    "#FFCE56"
                                ]
                            }]
                        }
                });
            }

            if (resp['subpoenasCountPrevMonth'].length > 0) {
                var subpoenasCountPrevMonth = new Chart($('#subpoenasCountPrevMonth'), {
                    type: 'doughnut',
                    data: {
                        labels: [resp['subpoenasCountPrevMonth']['name']],
                        datasets: [
                            {
                                data: [resp['subpoenasCountPrevMonth']['count']],
                                borderWidth: [1, 1, 1],
                                backgroundColor: [
                                    brandPrimary,
                                    "rgba(75,192,192,1)",
                                    "#FFCE56"
                                ],
                                hoverBackgroundColor: [
                                    brandPrimary,
                                    "rgba(75,192,192,1)",
                                    "#FFCE56"
                                ]
                            }]
                        }
                });
            }

            var subpoenasCountCurrentMonth, subpoenasCountPrevMonth = {
                responsive: true
            };

            var allDeliveredByMonths = new Chart($('#allDeliveredByMonths'), {
                type: 'line',
                data: {
                    labels: ['Януари', 'Февруари', 'Март', 'Април', 'Май', 'Юни', 'Юли', 'Август', 'Септември', 'Октомври', 'Ноември', 'Декември'],
                    datasets: [
                        {
                            label: "Общ брой раздадени призовки по месеци",
                            fill: true,
                            lineTension: 0.3,
                            backgroundColor: "rgba(51, 179, 90, 0.38)",
                            borderColor: brandPrimary,
                            borderCapStyle: 'butt',
                            borderDash: [],
                            borderDashOffset: 0.0,
                            borderJoinStyle: 'miter',
                            borderWidth: 1,
                            pointBorderColor: brandPrimary,
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: brandPrimary,
                            pointHoverBorderColor: "rgba(220,220,220,1)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 1,
                            pointHitRadius: 10,
                            data: resp['allDeliveredByMonths'],
                            spanGaps: false
                        }
                    ]
                }
            });

            var barChartExample = new Chart($('#barChartExample'), {
                type: 'bar',
                data: {
                    labels: ['Януари', 'Февруари', 'Март', 'Април', 'Май', 'Юни', 'Юли', 'Август', 'Септември', 'Октомври', 'Ноември', 'Декември'],
                    datasets: [
                        {
                            label: "Връчени",
                            backgroundColor: [
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)',
                                'rgba(51, 179, 90, 0.6)'
                            ],
                            borderColor: [
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                                'rgba(51, 179, 90, 1)',
                            ],
                            borderWidth: 1,
                            data: resp['allSubpoenasActionPerMonths']['delivered']
                        },
                        {
                            label: "Посетени адреси (без връчване)",
                            backgroundColor: [
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 206, 86, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 206, 86, 1)'
                            ],
                            borderWidth: 1,
                            data: resp['allSubpoenasActionPerMonths']['visited']
                        },
                        {
                            label: "Невръчени",
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 99, 132, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1,
                            data: resp['allSubpoenasActionPerMonths']['not_delivered']
                        }
                    ]
                }
            });
        });

    }

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