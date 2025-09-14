<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from form
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($name) || empty($email) || empty($phone)) {
        die("All fields are required!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    if (!is_numeric($phone) || strlen($phone) != 10) {
        die("Phone must be 10 digits!");
    }

    $sql_check = "SELECT * FROM entries WHERE email='$email'";
    $result = $conn->query($sql_check);
    if($result->num_rows > 0){
    die("Email already exists!");
    }

    // Insert query
    $sql = "INSERT INTO entries (name, email, phone) VALUES ('$name','$email','$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Entry added successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Form - Your Project Title</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
rel="stylesheet">
</head>
<body class="bg-light">
 <div class="container mt-5">
 <h2 class="mb-4">Add Entry</h2>
 <form method="POST" action="form.php" onsubmit="return validateForm()">
 <div class="mb-3">
   <label class="form-label">Name</label>
   <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
 </div>
 <div class="mb-3">
   <label class="form-label">Email</label>
   <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
 </div>
 <div class="mb-3">
   <label class="form-label">Phone</label>
   <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter Phone" required>
 </div>
 <button type="submit" class="btn btn-success">Submit</button>
 <button type="reset" class="btn btn-secondary">Clear Form</button>
</form>
 </div>
<script>
function validateForm() {
 let name = document.getElementById("name").value;
 let email = document.getElementById("email").value;
 let phone = document.getElementById("phone").value;

 if (name=="" || email=="" || phone=="") { alert("All fields required!");
return false; }

 if (!email.includes("@")) { alert("Invalid email!"); 
 return false; }

 if (phone.length !== 10 || isNaN(phone)) { alert("Phone must be 10 digits!"); 
 return false; }

 alert("Form submitted successfully!");
 return true;
}
</script>
</body>
</html>