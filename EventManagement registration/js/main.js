

$(document).ready(function () {

    $('#form-login-admin').submit(function (e) {
        e.preventDefault()
        var formdata = new FormData(this)
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            url: "db/adminLogin.php",
            data: formdata,
            beforeSend: function (e) {
                $('#submit-login-admin i').removeClass('fas fa-paper-plane');
                $('#submit-login-admin i').addClass('fas fa-spinner fa-spin');

            },
            success: function (data) {
                $('#submit-login-admin i').removeClass('fas fa-spinner fa-spin');
                $('#submit-login-admin i').addClass('fa fa-paper-plane');
                $("#form-login-admin")[0].reset();

                if (data == 'success') {
                    window.location.href = "admin.php";
                } else if (data == 'fail') {
                    $('#alert-admin-invalid-credentials').show('fade')
                    hideAlertAfter()
                } else {
                    $('#alert-admin-error').show('fade')
                    hideAlertAfter()
                }

            },
            fail: function (data) {
                $('#submit-login-admin i').removeClass('fas fa-spinner fa-spin');
                $('#submit-login-admin i').addClass('fa fa-paper-plane');
                $("#form-login-admin")[0].reset();
                $('#alert-admin-error').show('fade')
                hideAlertAfter()
            }
        })
    })

    $('#form-login-user').submit(function (e) {
        e.preventDefault()
        var formdata = new FormData(this)
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            url: "db/userLogin.php",
            data: formdata,
            beforeSend: function (e) {
                $('#submit-login-user i').removeClass('fas fa-paper-plane');
                $('#submit-login-user i').addClass('fas fa-spinner fa-spin');

            },
            success: function (data) {
                var result = data;
                $('#submit-login-user i').removeClass('fas fa-spinner fa-spin');
                $('#submit-login-user i').addClass('fa fa-paper-plane');
                $("#form-login-user")[0].reset();

                if (result == 'fail') {
                    $('#alert-user-invalid-credentials').show('fade')
                    hideAlertAfter()
                } else if(result == 'error'){
                    $('#alert-user-error').show('fade')
                    hideAlertAfter()
                } else {
                    var userResult = JSON.parse(data);
                    window.location.href = "user.php?uid="+userResult[0].uid;
                }

            },
            fail: function (data) {
                $('#submit-login-user i').removeClass('fas fa-spinner fa-spin');
                $('#submit-login-user i').addClass('fa fa-paper-plane');
                $("#form-login-user")[0].reset();
                $('#alert-user-error').show('fade')
                hideAlertAfter()
            }
        })
    })


    // Closing Bootstrap Alerts on Close button onClick
    $('.close-alert-admin-invalid-credentials').click(function (e) {
        $('#alert-admin-invalid-credentials').hide();
    });

    $('.close-alert-admin-error').click(function (e) {
        $('#alert-admin-error').hide();
    });

    $('.close-alert-user-invalid-credentials').click(function (e) {
        $('#alert-user-invalid-credentials').hide();
    });

    $('.close-alert-user-error').click(function (e) {
        $('#alert-user-error').hide();
    });

})

function hideAlertAfter() {
    setTimeout(function (){
        $('#alert-admin-invalid-credentials').hide();
        $('#alert-admin-error').hide();
        $('#alert-user-invalid-credentials').hide();
        $('#alert-user-error').hide();
    },2000);
}





