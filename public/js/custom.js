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

$('.hasDatepicker').datepicker({
    format: "dd.mm.yyyy",
    weekStart: 1,
    language: "bg",
    todayHighlight: true
});

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