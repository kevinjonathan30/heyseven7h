<?php
session_start();
require_once 'connector/connect.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST["idDeletion"]);
    if($id != "") {
        $sql = "DELETE FROM heyseven7h_student WHERE id=$id; DELETE FROM heyseven7h_private_attendance WHERE student_id=$id";
        if ($conn->multi_query($sql) === TRUE) {
            header("location:private_attendance.php");
        }   
    }
}

if (isset($_SESSION["loggedin"])) {
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Private Attendance - Enterprise</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous">
    </script>
</head>

<body class="sb-nav-fixed">
    <?php include('views/navbar.php'); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">Private Attendance</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Private Attendance</li>
                </ol>
                <a style="float: right;"><button data-toggle='modal' data-target='#addModal' class="btn btn-dark">Add
                        New</button></a>
                <br><br>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Student List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Price/Hour</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                                $tutor_id = $_SESSION["id"];
                                                if($_SERVER["REQUEST_METHOD"] == "GET") {
                                                    if(isset($_GET["id"]) && $_SESSION["role"] == 0) {
                                                        $tutor_id = $_GET["id"];
                                                    }
                                                }  
                                                $sql = "SELECT * FROM heyseven7h_student WHERE tutor_id=$tutor_id";
                                                $result = $conn->query($sql);
                       
                                                if ($result->num_rows > 0) {
                                                  while($row = $result->fetch_assoc()) {
                                                    echo "<td>" . $row["name"]. "</td><td>" . $row["address"]. "</td><td>" .
                                                    $row["phone"]."</td><td>".$row["priceperhour"]."</td><td><a href='student_private.php?id=".$row["id"]."' <button class='btn btn-info' title='Attendance List'><i class='fas fa-bars'></i></button></a>&nbsp;
                                                    <button class='btn btn-warning' title='Edit' onclick='editPage(".$row["id"].")' data-toggle='modal' data-target='#editModal'><i class='fas fa-edit'></i></button>&nbsp;<button class='btn btn-danger' title='Delete' onclick='deletePage(".$row["id"].")' data-toggle='modal' data-target='#deleteModal'><i class='fas fa-trash'></i></button></td></tr></a>";
                                                  } 
                                                }
                                                $conn->close();
                                            ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include('views/footer.php'); ?>
    </div>
    </div>
    <div class="modal fade" id="addModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Name</label>
                    <input type="text" id="StudentNameAdd" class="form-control">
                    <label>Address</label>
                    <input type="text" id="StudentAddressAdd" class="form-control">
                    <label>Phone</label>
                    <input type="text" id="StudentPhoneAdd" class="form-control">
                    <label>Price/hour</label>
                    <input type="number" value=0 min=0 id="StudentPriceAdd" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="addStudent()" class="btn btn-warning"
                        data-dismiss="modal">Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Tutor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="StudentIDEdit" class="form-control" disabled>
                    <label>Name</label>
                    <input type="text" id="StudentNameEdit" class="form-control">
                    <label>Address</label>
                    <input type="text" id="StudentAddressEdit" class="form-control">
                    <label>Phone</label>
                    <input type="text" id="StudentPhoneEdit" class="form-control">
                    <label>Price/hour</label>
                    <input type="number" value=0 min=0 id="StudentPriceEdit" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="submit_edit()" class="btn btn-warning"
                        data-dismiss="modal">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Are you sure you want to delete this Student?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    All the student data related to attendance will be deleted too.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" name="idDeletion" id="idTemp" value=0>
                        <button type="submit" class="btn btn-danger">Delete!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script>
    function addStudent() {
        var name = document.getElementById("StudentNameAdd").value;
        var address = document.getElementById("StudentAddressAdd").value;
        var phone = document.getElementById("StudentPhoneAdd").value;
        var price = document.getElementById("StudentPriceAdd").value;
        if (name != "" && address != "" && phone != "" && price != "") {
            $.ajax({
                url: "connector/add_student.php",
                type: "POST",
                data: {
                    name: name,
                    address: address,
                    phone: phone,
                    price: price
                },
                success: function(data) {
                    location.reload();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    }

    function editPage(id) {
        $.ajax({
            url: "connector/get_student_detail.php",
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById("StudentIDEdit").value = data[0].id;
                document.getElementById("StudentNameEdit").value = data[0].name;
                document.getElementById("StudentAddressEdit").value = data[0].address;
                document.getElementById("StudentPhoneEdit").value = data[0].phone;
                document.getElementById("StudentPriceEdit").value = data[0].priceperhour;
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function submit_edit() {
        var id = document.getElementById("StudentIDEdit").value;
        var name = document.getElementById("StudentNameEdit").value;
        var address = document.getElementById("StudentAddressEdit").value;
        var phone = document.getElementById("StudentPhoneEdit").value;
        var price = document.getElementById("StudentPriceEdit").value;
        if (name != "" && address != "" && phone != "") {
            $.ajax({
                url: "connector/submit_edit_student.php",
                type: "POST",
                data: {
                    id: id,
                    name: name,
                    address: address,
                    phone: phone,
                    price: price
                },
                success: function(data) {
                    location.reload();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    }

    function deletePage(id) {
        document.getElementById("idTemp").value = id;
    }
    </script>
</body>

</html>
<?php

}
else {
    header("location:login.php");
}
?>