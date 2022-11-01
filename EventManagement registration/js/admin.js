$(document).ready(function () {

    //Add Event
    $('#form-add-event').submit(function (e) {
        e.preventDefault()
        var formdata = new FormData(this)
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            url: "db/addEvent.php",
            data: formdata,
            beforeSend: function (e) {
                $('#submit-add-event i').removeClass('fas fa-paper-plane');
                $('#submit-add-event i').addClass('fas fa-spinner fa-spin');

            },
            success: function (data) {
                $('#submit-add-event i').removeClass('fas fa-spinner fa-spin');
                $('#submit-add-event i').addClass('fa fa-paper-plane');
                $("#form-add-event")[0].reset();

                if (data == 'success') {
                    $('#alert-add-event-success').show('fade')
                    hideAlertAfter()
                } else {
                    $('#alert-add-event-fail').show('fade')
                    hideAlertAfter()
                }

                console.log("Data : ", data)

            },
            fail: function (data) {
                $('#submit-add-event i').removeClass('fas fa-spinner fa-spin');
                $('#submit-add-event i').addClass('fa fa-paper-plane');
                $("#form-add-event")[0].reset();
                $('#alert-add-event-fail').show('fade')
                hideAlertAfter()
                console.log("failed executed")
            }
        })
    })

    //Get Event By ID
    $('.btn-edit-event').click(function (e) {
        var eid = $(e.target).attr('data-event-id');
        console.log(eid)

        var formData = new FormData();
        formData.append("eid", eid);


        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            url: "db/getEventById.php",
            data: formData,
            beforeSend: function (e) {
              
            },
            success: function (data) {
                console.log(data)
                var eventResult = JSON.parse(data)
             
                $('#edit-event-id').val(eventResult[0].eid)
                $('#edit-event-date').val(eventResult[0].date)
                $('#edit-event-title').val(eventResult[0].title)
                $('#edit-event-description').val(eventResult[0].description)
                $('#edit-event-venue').val(eventResult[0].venue)
              
                $('#modal-edit-event').modal('show')
            },
            fail: function (data) {
                console.log("Get Event By ID Failed")
            }
        })

    })

    //Edit Event By Id
    $('#modal-edit-event').submit(function (e) {
        e.preventDefault()
        var formdata = new FormData(document.getElementById("form-edit-event"))
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            url: "db/editEvent.php",
            data: formdata,
            beforeSend: function (e) {
                $('#submit-edit-event i').removeClass('fas fa-paper-plane');
                $('#submit-edit-event i').addClass('fas fa-spinner fa-spin');

            },
            success: function (data) {
                $('#submit-edit-event i').removeClass('fas fa-spinner fa-spin');
                $('#submit-edit-event i').addClass('fa fa-paper-plane');
                if (data == 'success') {
                    location.reload();
                } else {
                    $("#form-edit-event")[0].reset();
                    $('#alert-edit-event-fail').show('fade')
                    hideAlertAfter()
                }

                console.log("Data : ", data)

            },
            fail: function (data) {
                $('#submit-edit-event i').removeClass('fas fa-spinner fa-spin');
                $('#submit-edit-event i').addClass('fa fa-paper-plane');
                $("#form-add-event")[0].reset();
                $('#alert-edit-event-fail').show('fade')
                hideAlertAfter()
                console.log("failed executed")
            }
        })
    })


    // Closing Bootstrap Alerts on Close button onClick
    $('.close-alert-add-event-success').click(function (e) {
        $('#alert-add-event-success').hide();
    });

    $('.close-alert-add-event-fail').click(function (e) {
        $('#alert-add-event-fail').hide();
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

    var edit_date_input = $('input[name="edit-event-date"]');
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    edit_date_input.datepicker({
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    })

    function hideAlertAfter() {
        setTimeout(function () {
            $('#alert-add-event-success').hide();
            $('#alert-add-event-fail').hide();
            $('#alert-edit-event-fail').hide();
        }, 4000);
    }



    //For Datatable
    $('#datatable-events').DataTable();
    $('#datatable-users').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print'
        ]
    });

    $('#btn-admin-logout').click(function (e) {
        window.location.href = "db/logout.php";
    })


  
})