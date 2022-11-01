<?php

session_start();
$admin = $_SESSION['admin'];
if ($admin == TRUE) {
    include("db/connection.php");
    $eventQuery = "SELECT * FROM events";
    $userQuery = "SELECT * FROM user";

    $eventResult = mysqli_query($conn, $eventQuery);
    $userResult = mysqli_query($conn, $userQuery);
    $eventCount = str_pad($eventResult->num_rows, 2, '0', STR_PAD_LEFT);
    $userCount = str_pad($userResult->num_rows, 2, '0', STR_PAD_LEFT);
} else {
    header('location: index.html');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management | Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />

    <!-- Font Awsome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin-style.css" />
    <!-- Main JS -->
    <script src="js/admin.js"></script>


</head>

<body>

    <!-- NavBar -->
    <nav class="navbar navbar-dark bg-dark p-3">
        <span class="navbar-brand mb-0 h1 ">Event Management - Admin Login</span>
        <button class="btn btn-outline-warning my-2 my-sm-0" id="btn-admin-logout"> <i class="fas fa-cog"></i> LOGOUT</button>
    </nav>

    <!-- Description -->
    <div class="container-fluid p-3">
        <p class="caption">
            <strong>Hello Admin,</strong><br>
            Welcome to Event Management System Admin Panel, Here is the list of functionalities you can execute<br>
            <strong>1. Create an New Event</strong><br>
            <strong>2. Edit Event Information such as Venue</strong><br>
            <strong>3. Monitor Events & Users</strong><br>
            Don't forget to log out once you are done.<br>
        </p>
        <hr>
    </div>

    <div class="container">

        <div class="statistics mt-1 p-4">
            <div class="row">

                <div class="event-statistics col card" style="width: 18rem;">
                    <div class="card-body">
                        <h1><?= $eventCount ?></h1>
                        <h3 class="card-title">Total Events</h3>
                        <p class="card-text">
                            There can be multiple events happening at the same time.
                            Click on the Add-Event button to Create New Event
                        </P>
                    </div>
                </div>

                <div class="user-statistics col card" style="width: 18rem;">
                    <div class="card-body">
                        <h1><?= $userCount ?></h1>
                        <h3 class="card-title">Total Users</h3>
                        <p class="card-text">
                            Total User Registration count will be displayed here.
                            head over to the Manage User part to see a complete list of users
                        </P>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row mt-4 mb-4">
            <div class="col-8 align-self-start">
                <h3>Manage Events</h3>
            </div>
            <div class="col-4 align-self-end d-flex justify-content-end">
                <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modal-add-event"><i class="fas fa-plus-square"></i> Add Event</button>
            </div>
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
                                <td>
                                <button type="button" data-event-id="' . $row['eid'] . '" class="btn btn-warning btn-edit-event">
                                    <i class="fas fa-edit" data-event-id="' . $row['eid'] . '"></i> 
                                     Edit
                                </button>
                                </td>
                            </tr>
                            ';
                }
                ?>
            </tbody>
        </table>

        <hr>

        <div class="row mt-4 mb-4">
            <div class="col-8 align-self-start">
                <h3>Manage Users</h3>
            </div>
        </div>

        <table class="table table-striped mt-4" id="datatable-users">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">uid</th>
                    <th scope="col">eid</th>
                    <th scope="col">Name</th>
                    <th scope="col">Mobile</th>
                    <th scope="col">Email</th>
                    <th scope="col">First Detected On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_array($userResult)) {
                    echo '
                            <tr>
                                <td>' . $row['uid'] . '</td>
                                <td>' . $row['eid'] . '</td>
                                <td>' . $row['name'] . '</td>
                                <td>' . $row['mobile'] . '</td>
                                <td>' . $row['email'] . '</td>
                                <td>' . $row['first_detected_on'] . '</td>
                            </tr>
                            ';
                }
                ?>
            </tbody>
        </table>




    </div>



    <!-- Edit Event Modal -->
    <div class="modal fade" id="modal-edit-event" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit-event" method="POST">
                    <div class="modal-body">


                        <input type="text" class="form-control" id="edit-event-id" name="edit-event-id" placeholder="Select Event Date" hidden required>


                        <div class="mb-3">
                            <label for="edit-event-date" class="form-label">Date</label>
                            <input type="text" class="form-control" id="edit-event-date" name="edit-event-date" placeholder="Select Event Date" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit-event-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit-event-title" name="edit-event-title" placeholder="e.g Farewell" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit-event-description" class="form-label">Description</label>
                            <textarea type="text-area" class="form-control" id="edit-event-description" name="edit-event-description" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit-event-venue" class="form-label">Venue</label>
                            <input type="text" class="form-control" id="edit-event-venue" name="edit-event-venue" placeholder="e.g Kharghar, Navi Mumbai" required>
                        </div>



                        <div id="alert-edit-event-fail" class="alert alert-danger  collapse">
                            <strong>Something Went Wrong!!</strong> <br>Sorry Our Code is Broken :(
                            <button type="button" class="close-alert-edit-event-fail">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-edit-event">Submit <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modals Ends -->

    <!-- Add Event Modal -->
    <div class="modal fade" id="modal-add-event" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-add-event" method="POST">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="event-date" class="form-label">Date</label>
                            <input type="text" class="form-control" id="event-date" name="event-date" placeholder="Select Event Date" required>
                        </div>

                        <div class="mb-3">
                            <label for="event-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="event-title" name="event-title" placeholder="e.g Farewell" required>
                        </div>

                        <div class="mb-3">
                            <label for="event-description" class="form-label">Description</label>
                            <textarea type="text-area" class="form-control" id="event-description" name="event-description" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="event-venue" class="form-label">Venue</label>
                            <input type="text" class="form-control" id="event-venue" name="event-venue" placeholder="e.g Kharghar, Navi Mumbai" required>
                        </div>

                        <div id="alert-add-event-success" class="alert alert-success  collapse">
                            <strong>Event Added Successfully</strong> <br>Please refresh page to see updated events
                            <button type="button" class="close-alert-add-event-success">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>

                        <div id="alert-add-event-fail" class="alert alert-danger  collapse">
                            <strong>Something Went Wrong!!</strong> <br>Sorry Our Code is Broken :(
                            <button type="button" class="close-alert-add-event-fail">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-add-event">Submit <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Event Modal Ends -->





</body>


</html>