$(document).ready(function () {

    $('#upload-photo').on('change',function(e){
        var filePath = e.target.files[0].name; 
        var allowedExtensions =  /(\.jpg|\.jpeg|\.png)$/i; 
        if (!allowedExtensions.exec(filePath)) { 
        alert('Invalid file Type, Supported File Types are .jpg/.jpeg/.png'); 
        e.target.value = ''; 
        return false; 
        }
    })


    $('#form-upload-photo').submit(function (e) {
        e.preventDefault()
        var formdata = new FormData(this)
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            url: "db/uploadPhoto.php",
            data: formdata,
            beforeSend: function (e) {
                $('#btn-upload-photo i').removeClass('fas fa-paper-plane');
                $('#btn-upload-photo i').addClass('fas fa-spinner fa-spin');

            },
            success: function (data) {
                $('#btn-upload-photo i').removeClass('fas fa-spinner fa-spin');
                $('#btn-upload-photo i').addClass('fa fa-paper-plane');
                $("#form-upload-photo")[0].reset();

                console.log(data)

                if (data == 'success') {
                    $('#alert-upload-photo-success').show('fade')
                } else  {
                    $('#alert-upload-photo-fail').show('fade')
                    hideAlertAfter()
                }

            },
            fail: function (data) {
                $('#btn-upload-photo i').removeClass('fas fa-spinner fa-spin');
                $('#btn-upload-photo i').addClass('fa fa-paper-plane');
                $("#form-upload-photo")[0].reset();
                $('#alert-upload-photo-fail').show('fade')
                hideAlertAfter()
            }
        })

        //closing alerts
        $('.close-alert-upload-photo-success').click(function (e) {
            $('#alert-upload-photo-success').hide();
        });
    
        $('.close-alert-upload-photo-fail').click(function (e) {
            $('#alert-upload-photo-fail').hide();
        });

       

   
    })

    $('#form-update-user').submit(function (e) {
        e.preventDefault()
        var formdata = new FormData(this)
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            url: "db/updateUser.php",
            data: formdata,
            beforeSend: function (e) {
                $('#submit-update-user i').removeClass('fas fa-paper-plane');
                $('#submit-update-user i').addClass('fas fa-spinner fa-spin');

            },
            success: function (data) {
                $('#submit-update-user i').removeClass('fas fa-spinner fa-spin');
                $('#submit-update-user i').addClass('fa fa-paper-plane');
                $("#form-update-user")[0].reset();

                if (data == 'success') {
                    $('#alert-update-user-success').show('fade')
                    location.reload();
                } else {
                    $('#alert-update-user-fail').show('fade')
                    hideAlertAfter()
                }

                console.log("Data : ",data)

            },
            fail: function (data) {
                $('#submit-update-user i').removeClass('fas fa-spinner fa-spin');
                $('#submit-update-user i').addClass('fa fa-paper-plane');
                $("#form-update-user")[0].reset();
                $('#alert-register-event-fail').show('fade')
                hideAlertAfter()
                console.log("failed executed")
            }
        })
    })

    function hideAlertAfter() {
        setTimeout(function (){
            $('#alert-upload-photo-success').hide()
            $('#alert-upload-photo-fail').hide()
            $('#alert-update-user-fail').hide()
            $('#alert-update-user-success').hide()
        },2000);
    }

    $('#btn-user-logout').click(function (e) {
        window.location.href = "db/logout.php";
    })
})

