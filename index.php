<!DOCTYPE html>
<html>
<head>
    <title>jQuery Ajax - PHP CRUD Application</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<style>
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    padding-top: 100px; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.9); 
}

.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
    opacity: unset;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}
</style>

<body>
<div class="container">
    <h3 class='text-center'>PHP CRUD Application</h3>
    <hr>
    <div class='row'>
        <div id="add-users" class="col-md-5">
            <form id='frm' method="post" enctype="multipart/form-data">
				<h2>Add New User</h2><br>
                <div class="form-group">
                    <label>User Name</label>
                    <input type="text" class="form-control" name="name" id='name' required placeholder="Enter User Name">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" id='email' required placeholder="Enter Email">
                </div>
                <div class="form-group">
                    <label>Mobile No</label>
                    <input type="text" class="form-control" name="mobile" id='mobile' required placeholder="Enter Mobile Number">
                </div>
                <div class="form-group">
                    <label>Upload Image</label>
                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" required>
                </div>
                <input type="hidden" class="form-control" name="uid" id='uid' required value='0' placeholder="">
                <button type="submit" name="submit" id="but" class="btn btn-success">Add User</button>
                <button type="button" id="clear" class="btn btn-warning">Clear</button>
            </form>
        </div>
        <div id="list-users" class="col-md-7">
			<h2>List All Users</h2><br>
            <table class="table table-bordered" id='table'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Image</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "config.php";
                    $sql = "SELECT * FROM user";
                    $res = $con->query($sql);
                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr class='{$row["UID"]}'>
                                <td>{$row["NAME"]}</td>
                                <td>{$row["EMAIL"]}</td>
                                <td>{$row["MOBILE"]}</td>
                                <td><img src='{$row["IMAGE"]}' class='zoomable' width='50' height='50'></td>

                                <td><a href='#' class='btn btn-primary edit' uid='{$row["UID"]}'>Edit</a></td>
                                <td><a href='#' class='btn btn-danger del' uid='{$row["UID"]}'>Delete</a></td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="imgModal">
</div>

<script>
$(document).ready(function(){
// Get the modal
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("imgModal");
    
    $('body').on('click', '.zoomable', function(){
        modal.style.display = "block";
        modalImg.src = this.src;
    });

    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() { 
        modal.style.display = "none";
    }
});
$(document).ready(function(){
    $("#clear").click(function(){
        $("#name").val("");
        $("#email").val("");
        $("#mobile").val("");
        $("#uid").val("0");
        $("#fileToUpload").val("");
        $("#but").text("Add User");
    });

    $("#frm").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $("#but").text("Wait...");
            },
            success: function(res){
                var uid = $("#uid").val();
                if (uid == "0") {
                    $("#table").find("tbody").append(res);
                } else {
                    $("#table").find("." + uid).html(res);
                }
                $("#clear").click();
                $("#but").text("Add User");
            }
        });
    });

    $("body").on("click", ".del", function(e){
        e.preventDefault();
        var uid = $(this).attr("uid");
        var btn = $(this);
        if (confirm("Are You Sure ? ")) {
            $.ajax({
                type: 'POST',
                url: 'ajax_delete.php',
                data: {id: uid},
                beforeSend: function(){
                    $(btn).text("Deleting...");
                },
                success: function(res){
                    if (res) {
                        btn.closest("tr").remove();
                    }
                }
            });
        }
    });

    $("body").on("click", ".edit", function(e){
        e.preventDefault();
        var uid = $(this).attr("uid");
        $("#uid").val(uid);
        var row = $(this);
        var name = row.closest("tr").find("td:eq(0)").text();
        $("#name").val(name);
        var email = row.closest("tr").find("td:eq(1)").text();
        $("#email").val(email);
        var mobile = row.closest("tr").find("td:eq(2)").text();
        $("#mobile").val(mobile);
        $("#but").text("Update User");
    });
});
</script>
</body>
</html>
