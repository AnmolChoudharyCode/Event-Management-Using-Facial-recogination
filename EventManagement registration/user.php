<?php
include("db/connection.php");


// Getting User Info from uid
$userQuery = "SELECT * FROM user WHERE uid=" . $_GET['uid'];
$userResult = mysqli_query($conn, $userQuery);

$userName = "";
$userEmail = "";
$userContact = "";
$userPassword = "";
$uid = "";
$eid = "";

while ($row = mysqli_fetch_array($userResult)) {
    $userName = $row['name'];
    $userEmail = $row['email'];
    $userContact = $row['mobile'];
    $userPassword = $row['password'];
    $uid = $row['uid'];
    $eid = $row['eid'];
}



//Getting Registered Event Info from eid
$eventQuery = "SELECT * FROM events WHERE eid=" . $eid;
$eventResult = mysqli_query($conn, $eventQuery);

$eventDate = "";
$eventTitle = "";
$eventDescription = "";
$eventVenue = "";

while ($row = mysqli_fetch_array($eventResult)) {
    $eventDate = $row['date'];
    $eventTitle = $row['title'];
    $eventDescription = $row['description'];
    $eventVenue = $row['venue'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management | User Login</title>

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
    <script src="js/user.js"></script>


</head>

<body>

    <!-- NavBar -->
    <nav class="navbar navbar-dark bg-dark p-3">
        <span class="navbar-brand mb-0 h1 ">Event Management - User Login</span>
        <button class="btn btn-outline-warning my-2 my-sm-0" id="btn-user-logout"> <i class="fas fa-cog"></i> LOGOUT</button>
    </nav>

    <!-- Description -->
    <div class="container-fluid p-3">
        <p class="caption"><strong>Hello User,</strong><br>
            Welcome to User Login<br>
            Here is the List of Functionalities you can execute<br>
            1. Edit Your Profile Information<br>
            2. Upload Required Profile Pictures<br>
            Don't forget to logout once you are done
        </p>
        <hr>
    </div>

    <div class="container p-4">

        <div class="row">
            <div class="col-lg-6 col-12 user-info pt-4 ">
                <h3> User Information <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-update-user">Edit Profile </button></h3>
                <hr>
                <h4 class="mb-4"><i class="far fa-user-circle"></i> <?= $userName ?></h4>
                <h4 class="mb-4"><i class="fas fa-mobile-alt "></i> <?= $userContact ?></h4>
                <h4 class="mb-4"><i class="far fa-envelope-open"></i> <?= $userEmail ?></h4>
            </div>

            <div class="col-lg-6 col-12 event-info pt-4 ">
                <h3> Event Information</h3>
                <hr>
                <h4 class="mb-4"><i class="far fa-user-circle"></i> <?= $eventTitle ?></h4>
                <h4 class="mb-4"><i class="far fa-calendar-alt"></i> <?= $eventDate ?></h4>
                <h4 class="mb-4"><i class="fas fa-map-marker-alt"></i> <?= $eventVenue ?></h4>
            </div>

        </div>
        <hr>
        <form id="form-upload-photo" method="POST">
            <h4>Upload Photo Guidelines</h4>
            <h6 class="mt-4">
                1. Please upload your Recent Photos<br><br>
                2. Avoid Group photos or Selfies<br><br>
                3. Please upload as many photos as you can<br><br>
            </h6>
            <input type="text" name="uid" value="<?= $uid ?>" hidden>
            <label for="upload-photo" class="form-label mb-3">Choose Your Photo</label>
            <input type="file" class="form-control mb-3" id="upload-photo" name="upload-photo" required>

            <div id="alert-upload-photo-success" class="alert alert-success  collapse">
                <strong>Photo Uploaded Successfully!!</strong> <br>See you at event :)
                <button type="button" class="close-alert-upload-photo-success">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div id="alert-upload-photo-fail" class="alert alert-danger  collapse">
                <strong>Something Went Wrong!!</strong> <br>Sorry Our Code is Broken :(
                <button type="button" class="close-alert-upload-photo-fail">
                    <span aria-hidden="true">X</span>
                </button>
            </div>


            <button type="submit" class="btn btn-primary mb-3"><i class="fa fa-paper-plane" aria-hidden="true" id="btn-upload-photo"></i> Upload Photo</button>
        </form>
    </div>



    <!-- Update User Modal -->
    <div class="modal fade" id="modal-update-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-update-user" method="POST">
                    <div class="modal-body">

                        <input type="text" class="form-control" id="eid" name="eid" placeholder="e.g Farewell" required hidden value="<?= $eid ?>">
                        <input type="text" class="form-control" id="uid" name="uid" placeholder="e.g Farewell" required hidden value="<?= $uid ?>">


                        <div class="mb-3">
                            <label for="participant-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="participant-name" name="participant-name" placeholder="John Doe" value="<?= $userName ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" required maxlength="10" placeholder="9999999999" value="<?= $userContact ?>"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="johndoe@gmail.com" value="<?= $userEmail ?>" required>
                            <small class="text-muted">We'll never share your email with anyone else.</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?= $userPassword ?>" required>
                            <small class="text-muted">you will be using same password for furthur login</small>
                        </div>

                        <div id="alert-update-user-success" class="alert alert-success  collapse">
                            <strong>Profile Updated Successfully</strong> <br>Please refresh page to see updated profile
                            <button type="button" class="close-alert-register-event-success">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>

                        <div id="alert-update-user-fail" class="alert alert-danger  collapse">
                            <strong>Something Went Wrong!!</strong> <br>Sorry Our Code is Broken :(
                            <button type="button" class="close-alert-register-event-fail">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-update-user">Update <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Update User Modal Ends -->

</body>


</html>