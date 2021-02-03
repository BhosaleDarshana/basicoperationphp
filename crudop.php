<?php
$insert = false;
$update = false;
$delete = false;
//create connection
$servername = "localhost";
$username="root";
$password="";
$dbnam="student";

$conn = mysqli_connect($servername,$username,$password,$dbnam);

if(!$conn){
    die("connection failed".mysqli_connect_error());
}

if(isset($_GET['delete'])){
    $sno = $_GET['delete'];
    $delete = true;
    $sql="DELETE FROM `student` WHERE `sno` = '$sno'";
    $result=mysqli_query($conn, $sql);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST["snoEdit"])){
        $sno = $_POST["snoEdit"];
        $name = $_POST["nameEdit"];
        $stream =$_POST["streamEdit"];

        $sql = "UPDATE `student` SET `name` = '$name', `stream`= '$stream' WHERE `sno` = '$sno'";
        $result = mysqli_query($conn, $sql);
        if($result){
            $update = true;
        }else {
            echo "error in update ==>".mysqli_error($conn);
        }
    }else{

    $name = $_POST["name"];
    $stream = $_POST["stream"];

    $sql= "INSERT INTO `student` (`sno`, `name`, `stream`, `dated`) VALUES (NULL, '$name', '$stream', current_timestamp())";
    $result = mysqli_query($conn, $sql);

    if($result){
        //echo "data inserted successfully";
        $insert = true;
    }else {
        echo "error insertion==>".mysqli_error($conn);
    }
    }
    
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="           crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>

    <title>CRUD operation</title>
  </head>
  <body>
    <!-- modal -->
  <div class="modal" tabindex="-1" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Student Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
    <form action="/PHPcode/CRUD-php/crudop.php" method="post">
    <input type="hidden" name="snoEdit" id="snoEdit" >
    <div class="mb-3">
    <label for="nameEdit" class="form-label">Edit student name</label>
    <input type="text" class="form-control" id="nameEdit" name = "nameEdit" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
    <label for="streamEdit" class="form-label">Edit student stream</label>
    <input type="text" class="form-control" id="streamEdit" name="streamEdit">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

 <!-- navigation bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">SCHOOL DATA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="#">ABOUT US</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">CONTACT US</a>
        </li>
        
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

 <!-- FORM of student -->
<div class="container">
<h3>ADD STUDENT DATA HERE</h3>

<div class="container">
<form action="/PHPcode/CRUD-php/crudop.php" method="post">
  <div class="mb-3">
    <label for="name" class="form-label">student name</label>
    <input type="text" class="form-control" id="name" name = "name" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="stream" class="form-label">student stream</label>
    <input type="text" class="form-control" id="stream" name="stream">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

<div class="container">
 <!-- value in database in table format -->
<table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">SNO</th>
      <th scope="col">STUDENT NAME</th>
      <th scope="col">Stream name</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    $sql = "SELECT * FROM `student`";
    $result = mysqli_query($conn, $sql);
    $sno = 0;
    while($row=mysqli_fetch_assoc($result)){
        $sno = $sno+1;
        echo "<tr>
        <th scope='row'>".$sno."</th>
        <td>".$row['name']."</td>
        <td>".$row['stream']."</td>
        <td><button type='button' class='edit btn btn-sm btn-primary' id=".$row['sno']." >EDIT</button>
        <button type='button' class='delete btn btn-sm btn-primary' id = d".$row['sno'].">DELETE</button></td>
      </tr>";
    }
  ?>
    
  </tbody>
</table>
</div>
</div>
    

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    -->

    <script>

    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element)=>{
        element.addEventListener("click",(e)=>{
            console.log('edit', );
            tr = e.target.parentNode.parentNode;
            name = tr.getElementsByTagName("td")[0].innerText;
            stream = tr.getElementsByTagName("td")[1].innerText;
            console.log(name, stream);
            nameEdit.value = name;
            streamEdit.value = stream;
            snoEdit.value = e.target.id;
            console.log(e.target.id);
            $('#editModal').modal('toggle');
        })
    })


    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element)=>{
        element.addEventListener("click",(e)=>{
            console.log('delete', );
            sno=e.target.id.substr(1, );
            if(confirm("Press a button!")){
                console.log('yes');
                window.location = `/PHPcode/CRUD-php/crudop.php?delete=${sno}`;
            }else{
                console.log('no');
            }
        })
    })

    </script>
  </body>
</html>