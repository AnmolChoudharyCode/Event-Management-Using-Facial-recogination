$(document).ready(function () {

    $('#form-register-event').submit(function (e) {
        e.preventDefault()
        var formdata = new FormData(this)
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            url: "db/registerEvent.php",
            data: formdata,
            beforeSend: function (e) {
                $('#submit-register-event i').removeClass('fas fa-paper-plane');
                $('#submit-register-event i').addClass('fas fa-spinner fa-spin');

            },
            success: function (data) {
                $('#submit-register-event i').removeClass('fas fa-spinner fa-spin');
                $('#submit-register-event i').addClass('fa fa-paper-plane');
                $("#form-register-event")[0].reset();

                if (data == 'success') {
                    $('#alert-register-event-success').show('fade')
                    hideAlertAfter()
                } else {
                    $('#alert-register-event-fail').show('fade')
                    hideAlertAfter()
                }

                console.log("Data : ",data)

            },
            fail: function (data) {
                $('#submit-register-event i').removeClass('fas fa-spinner fa-spin');
                $('#submit-register-event i').addClass('fa fa-paper-plane');
                $("#form-register-event")[0].reset();
                $('#alert-register-event-fail').show('fade')
                hideAlertAfter()
                console.log("failed executed")
            }
        })
    })


    //Register Event Button Clicked
    $('.btn-register-event').click(function (e) {
        var eid = $(e.target).attr('data-event-id');
        console.log(eid)
        $('#eid').val(eid)
        $('#modal-register-event').modal('show')
    })


    // Closing Bootstrap Alerts on Close button onClick
    $('.close-alert-register-event-success').click(function (e) {
        $('#alert-register-event-success').hide();
    });

    $('.close-alert-register-event-fail').click(function (e) {
        $('#alert-register-event-fail').hide();
    });

    //For Date Picker
    var date_input = $('input[name="event-date"]');
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    date_input.datepicker({
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    })

    function hideAlertAfter() {
        setTimeout(function () {
            $('#alert-register-event-success').hide();
            $('#alert-register-event-fail').hide();
        }, 4000);
    }



    //For Datatable
    $('#datatable-events').DataTable();

    $('#btn-admin-logout').click(function(e){
        window.location.href = "db/adminLogout.php";
    })
})