<?php
include 'config.php';

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM entries WHERE id=$id";
    $result = $conn->query($sql);

    if($result->num_rows == 0){
        die("Record not found!");
    }

    $row = $result->fetch_assoc();
}

// Handle form submission
if(isset($_POST['update'])){
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Server-side validation
    if(empty($name) || empty($email) || empty($phone)){
        die("All fields are required!");
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        die("Invalid email format!");
    }
    if(!is_numeric($phone) || strlen($phone) != 10){
        die("Phone must be 10 digits!");
    }

    $sql = "UPDATE entries SET name='$name', email='$email', phone='$phone' WHERE id=$id";
    if($conn->query($sql) === TRUE){
        header("Location: view.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Update Entry</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
 <h2>Update Entry</h2>
 <form method="POST">
   <div class="mb-3">
     <label class="form-label">Name</label>
     <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" class="form-control" 
     oninput="this.value = this.value.toUpperCase()" required>
   </div>
   <div class="mb-3">
     <label class="form-label">Email</label>
     <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="form-control" required>
   </div>
   <div class="mb-3">
     <label class="form-label">Phone</label>
     <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" class="form-control" 
     oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
   </div>
   <button type="submit" name="update" class="btn btn-success">Update</button>
   <a href="view.php" class="btn btn-secondary">Cancel</a>
 </form>
</div>

<script>
document.getElementById("name").addEventListener("input", function() {
  let name = this.value;
  if(name.length < 3){
    this.style.borderColor = "red";
  } else {
    this.style.borderColor = "green";
  }
});
</script>

</body>
</html>
