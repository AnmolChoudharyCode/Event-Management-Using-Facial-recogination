<?php
include("db/connection.php");
$query = "SELECT * FROM events";
$eventResult = mysqli_query($conn, $query);
$eventCount = str_pad($eventResult->num_rows, 2, '0', STR_PAD_LEFT);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management | Events</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />


    <!-- Font Awsome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin-style.css" />
    <!-- Main JS -->
    <script src="js/events.js"></script>


</head>

<body>

    <!-- NavBar -->
    <nav class="navbar navbar-dark bg-dark p-3">
        <span class="navbar-brand mb-0 h1 ">Event Management - Events</span>
    </nav>

    <!-- Description -->
    <div class="container-fluid p-3">
        <p class="caption">
            <strong>Hello User,</strong>,<br>
            Welcome to Events Page,<br>
            To Register for an Event following Details will be required<br>
            1. Full Name<br>
            2. Active Mobile Number<br>
            3. Active Email ID<br>
            you will also need to create a password, which you will be using for post-registration login.<br>
        </p>
        <hr>
    </div>

    <div class="container">

        <div class="statistics mt-1 ">
            <div class="row">
                <div class="event-statistics col card" style="width: 18rem;">
                    <div class="card-body">
                        <h1><?= $eventCount ?></h1>
                        <h3 class="card-title">Total Events</h3>
                        <p class="card-text">
                            To Register for an event click on Register Button from Action Column
                        </P>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row mt-4 mb-4">
            <div class="col-8 align-self-start">
                <h3>Event List</h3>
            </div>
            <!--<div class="col-4 align-self-end d-flex justify-content-end">
                <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modal-register-event"><i class="fas fa-plus-square"></i> Register for Event</button>
            </div>-->
        </div>

        <table class="table table-striped mt-4" id="datatable-events">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Title</th>
                    <th scope="col">Venue</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_array($eventResult)) {
                    echo '
                            <tr>
                                <td>' . $row['date'] . '</td>
                                <td>' . $row['title'] . '</td>
                                <td>' . $row['venue'] . '</td>
                                <td><button type="button" class="btn btn-warning btn-register-event"   data-event-id="' . $row['eid'] . '"> <i class="fas fa-plus-square"></i> Register</button>
                                </td>
                            </tr>
                            ';
                }
                ?>
            </tbody>
        </table>
    </div>



    <!-- Register Event Modal -->
    <div class="modal fade" id="modal-register-event" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register for Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-register-event" method="POST">
                    <div class="modal-body">

                        <input type="text" class="form-control" id="eid" name="eid" placeholder="e.g Farewell" required hidden>
                      


                        <div class="mb-3">
                            <label for="participant-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="participant-name" name="participant-name" placeholder="John Doe" required>
                        </div>

                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" required maxlength="10" placeholder="9999999999"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="johndoe@gmail.com" required>
                            <small class="text-muted">We'll never share your email with anyone else.</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">you will be using same password for furthur login</small>
                        </div>

                        <div id="alert-register-event-success" class="alert alert-success  collapse">
                            <strong>Registration Successful</strong> <br>Please refresh page to see updated events
                            <button type="button" class="close-alert-register-event-success">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>

                        <div id="alert-register-event-fail" class="alert alert-danger  collapse">
                            <strong>Something Went Wrong!!</strong> <br>Sorry Our Code is Broken :(
                            <button type="button" class="close-alert-register-event-fail">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-register-event">Submit <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Register Modals Ends -->





</body>


</html>