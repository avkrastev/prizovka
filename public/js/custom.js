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


$('#createQR').on('click', function () {
    var root = location.protocol + '//' + location.host;
    $.post(root+'/addresses/createQR', 
        {address: $('[name="address"]').val(),
         date: $('[name="date"]').val(),
         number: $('[name="number"]').val()},
        function (resp) {
            $('#qrcode').empty().html(resp);
        }, 'json'
    );
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