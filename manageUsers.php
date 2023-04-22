<?php
session_start();
if ($_SESSION['admin'] == false) {
    header("location: logs.php");
    exit();
}

// Connect to the database
require "./db/db_connect.php";

if (isset($_GET['delete'])) {
    // operations perfomed using the GET method to delete the note in databse
    $slno = $_GET['delete'];
    $sql = "DELETE FROM `users` WHERE `slno` = $slno";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $delete = true;
    }
} elseif (isset($_POST['slnoEdit'])) {
    // Update the record in database
    $slno = $_POST['slnoEdit'];
    $userName = $_POST['userName'];
    $userFName = $_POST['userFName'];
    $userID = $_POST['userID'];
    $userRegisterNo = $_POST['userRegisterNo'];
    $userNumber = $_POST['userNumber'];
    $userAddress = $_POST['userAddress'];
    $sql = "UPDATE `users` SET `user_name` = '$userName', `user_fname` = '$userFName', `user_id` = '$userID', `user_reg_no` = '$userRegisterNo', `user_mobile` = '$userNumber', `user_address` = '$userAddress' WHERE `slno` = $slno";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $update = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery CSS  -->
    <link rel="stylesheet" href="./css/jquery-ui.css">
    <link rel="stylesheet" href="./css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="./css/buttons.jqueryui.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <!-- Bootstrap CSS  -->
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <style>
        th.sorting.ui-state-default,
        table.dataTable th,
        table.dataTable td {
            text-align: center;
        }
    </style>

    <!-- jQuery JS -->
    <script src="./js/jquery-3.5.1.js"></script>
    <!-- jQuery datatables JS  -->
    <script src="./js/jquery.dataTables.min.js"></script>
    <!-- jQuery UI JS  -->
    <script src="./js/dataTables.jqueryui.min.js"></script>
    <!-- jQuery buttons JS  -->
    <script src="./js/dataTables.buttons.min.js"></script>
    <script src="./js/buttons.jqueryui.min.js"></script>
    <script src="./js/jszip.min.js"></script>
    <script src="./js/pdfmake.min.js"></script>
    <script src="./js/vfs_fonts.js"></script>
    <script src="./js/buttons.html5.min.js"></script>
    <script src="./js/buttons.print.min.js"></script>
    <script src="./js/buttons.colVis.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                lengthChange: false,
                lengthMenu: [
                    [5, 10, 25, -1],
                    ['5 rows', '10 rows', '25 rows', 'Show all']
                ],
                buttons: [
                    'pageLength', 'colvis', 'copy', 'print',
                    {
                        extend: 'spacer',
                        style: 'bar',
                        text: '<b>Export files to :- </b>'
                    },
                    'excel', 'pdf', 'csv',
                    {
                        text: 'JSON',
                        action: function(e, dt, button, config) {
                            var data = dt.buttons.exportData();
                            $.fn.dataTable.fileSave(
                                new Blob([JSON.stringify(data)]),
                                'Export.json'
                            );
                        },
                    },
                ],
            });
            table.buttons().container().insertBefore('#example_filter');
        });
    </script>
    <title>Students List</title>
</head>

<body>
    <!-- Navigation bar -->
    <?php require "./apps/navbar.php"; ?>
    <!-- Edit Modal -->
    <?php require "./apps/editModal.php"; ?>
    <!-- Delete Modal -->
    <?php require "./apps/deleteModal.php"; ?>

    <h1 class="py-3 bg-warning">
        <center>**Title goes here**</center>
    </h1>
    <div class="table-responsive" style="min-height: 66.5vh;">
        <table id="example" class="display">
            <thead>
                <tr>
                    <th scope="col">Sl.no</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Father Name</th>
                    <th scope="col">Student UID</th>
                    <th scope="col">Reg.no</th>
                    <th scope="col">Mobile Number</th>
                    <th scope="col">Student Address</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM `users`";
                $result = mysqli_query($connection, $sql);
                $num = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <tr>
                        <th scope="row">' . $num . '</th>
                        <td>' . $row['user_name'] . '</td>
                        <td>' . $row['user_fname'] . '</td>
                        <td>' . $row['user_id'] . '</td>
                        <td>' . $row['user_reg_no'] . '</td>
                        <td>' . $row['user_mobile'] . '</td>
                        <td>' . $row['user_address'] . '</td>
                        <td>  
                            <div class="d-flex flex-row mb-3">
                                <div class="p-1 m-auto"><button id="edit' . $row['slno'] . '" class="edit btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">&nbsp&nbsp&nbspEdit&nbsp&nbsp</button></div>
                                <div class="p-1 m-auto"><button id="delete' . $row['slno'] . '" class="delete btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button></div>
                            </div>
                        </td>
                    </tr>';
                    $num = $num + 1;
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Footer -->
    <?php require "./apps/footer.php"; ?>
</body>
<script>
    // To edit user info from the database
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e) => {
            console.log(e);
            tr = e.target.parentNode.parentNode.parentNode.parentNode;
            user_name = tr.getElementsByTagName("td")[0].innerText;
            user_fname = tr.getElementsByTagName("td")[1].innerText;
            user_id = tr.getElementsByTagName("td")[2].innerText;
            user_reg_no = tr.getElementsByTagName("td")[3].innerText;
            user_mobile = tr.getElementsByTagName("td")[4].innerText;
            user_address = tr.getElementsByTagName("td")[5].innerText;
            slnoEdit.value = e.target.id.substr(4, );
            userName.value = user_name;
            userFName.value = user_fname;
            userID.value = user_id;
            userRegisterNo.value = user_reg_no;
            userNumber.value = user_mobile;
            userAddress.value = user_address;
        })
    })

    // To delete user info from the database
    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
        element.addEventListener("click", (e1) => {
            slno = e1.target.id.substr(6, );
            document.getElementById('delBtn').onclick = function() {
                window.location = `manageUsers.php?delete=${slno}`;
            }
        })
    })

    // To fix the re-submission error on reloading the webpage
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    // To remove the GET paarmeter from the header when the request is completed
    if (typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", "http://localhost/rfidattendance/manageUsers.php");
    }

    // To add active class to the navbar
    const addClassActive = document.getElementById('manage-users-tab');
    addClassActive.classList.add('active');
</script>

</html>