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
