<?php
$insert = false;

//Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$database = "note";

//Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

//Die if connection was not successful
if (!$conn) {
    die("Sorry we failed to connect: " . mysqli_connect_error());
}

if (isset($_GET['delete'])) {
    $Sno = $_GET['delete'];
    $delete = true;
    $sql = "DELETE FROM `note` WHERE `Sno` = $Sno";
    $result = mysqli_query($conn, $sql);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['snoEdit'])) {

        //Update the record
        $Sno = $_POST['snoEdit'];
        $title = $_POST['titleEdit'];
        $description = $_POST["descriptionEdit"];

        $sql = "UPDATE `note` SET `Title`= '$title', `Description`= '$description' WHERE `note`.`Sno` = $Sno";
        $result = mysqli_query($conn, $sql);
    } else {

        $title = $_POST['title'];
        $description = $_POST["description"];

        //Sql query to be executed
        $sql = "INSERT INTO `note` (`Title`, `Description`) VALUES ('$title', '$description')";
        $result = mysqli_query($conn, $sql);

        //Add a new record
        if ($result) {
            header("Location: /CRUD/index.php?insert=true");
            exit();
        } else {
            echo "The record wasn't inserted because of this error --> " . mysqli_error($conn);
        }
    }
} ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To-do List CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.7.11/dist/css/tempus-dominus.min.css"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <!-- Edit modal -->
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
        Edit Modal
    </button> -->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="/CRUD/index.php" method="post">
                        <input type="hidden" name="snoEdit" id="snoEdit" />
                        <div class="form">
                            <div class="mb-3">
                                <label for="title" class="form-label">Note Title :</label>
                                <input type="text" class="form-control" id="titleEdit" name="titleEdit">
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Note Description :</label>
                                <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="1"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Note</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">TaskTracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Help</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">FAQs</a></li>
                            <li><a class="dropdown-item" href="#">Contact Us</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Log out</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <?php
    if ($insert) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong> Your note was inserted succesfully!! </strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
    ?>

    <div class="container flex">
        <div class="left">
            <!-- Calendar goes here -->
            <div id="calendar" style="font-size: 10px; overflow: hidden;"></div>
        </div>

        <div class="right">
            <h3> To-Do Tasks:</h3>
            <form action="/CRUD/index.php?update=true" method="post">
                <div class="form">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Note Title :</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="mb-3">
                        <label for="desc" class="form-label fw-bold">Note Description :</label>
                        <textarea class="form-control" id="desc" name="description" rows="1"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Note</button>
                </div>
            </form>

            <div class="row">
                <?php
                $sql = "SELECT * FROM `note` ORDER BY Tstamp DESC";
                $result = mysqli_query($conn, $sql);
                $Sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $Sno = $Sno + 1;
                    echo '
                    <div class="col-sm-6 col-md-3 mb-3">
                    <div class="card shadow-sm" style="border-radius: 15px;">
                    <div class="card-body">
                    <h5 class="card-subtitle mb-2 text-muted">Task #' . $Sno . '</h5>
                    <h6 class="card-title">Title: ' . htmlspecialchars($row['Title']) . '</h6>
                    <p class="card-text">' . nl2br(htmlspecialchars($row['Description'])) . '</p>
                    
                    <!-- Actions (Edit/Delete) ABOVE Timestamp -->
                    <div class="d-flex justify-content-between mt-3">
                    <button class="edit btn btn-sm btn-primary" data-id="' . $row['Sno'] . '"> Edit </button>
                    <button class="delete btn btn-sm btn-primary" id="d' . $row['Sno'] . '"> Delete </button>                   
                    </div>
                    
                    <!-- Timestamp -->
                    <p class="text-end text-muted mt-2" style="font-size: 0.8rem;">' . date("d M Y, h:i A", strtotime($row['Tstamp'])) . '</p>
                    </div>
                    </div>
                    </div>
                    ';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.7.11/dist/js/tempus-dominus.min.js"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                fixedWeekCount: false,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                },
                dayMaxEventRows: 1,
                datesSet: function(info) {
                    const allRows = calendarEl.querySelectorAll('.fc-daygrid-body .fc-daygrid-week');
                    if (allRows.length > 5) {
                        allRows[5].style.display = 'none';
                    }
                }
            });

            calendar.render();
        });
    </script>

    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit", e.target.parentNode.parentNode);
                const cardBody = e.target.parentNode.parentNode;

                // Select title and description elements
                const titleElement = cardBody.querySelector(".card-title");
                const descriptionElement = cardBody.querySelector(".card-text");

                // Get the actual text content
                const title = titleElement.innerText.replace("Title: ", "").trim();
                const description = descriptionElement.innerText.trim();

                console.log(title, description);
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.dataset.id;
                console.log(e.target.dataset.id);

                //Show modal
                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            });
        });

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("delete", e.target.parentNode.parentNode);
                let Sno = e.target.id.substr(1);

                if (confirm("Are you sure you want to delete this node? ")) {
                    console.log("Yes");
                    window.location = `/CRUD/index.php?delete=${Sno}`;

                } else {
                    console.log("No");

                }
            });
        });
    </script>

</body>

</html>