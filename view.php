<?php
include 'config.php';


$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$count_sql = "SELECT COUNT(*) as total FROM entries 
              WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

$sql = "SELECT * FROM entries 
        WHERE name LIKE '%$search%' OR email LIKE '%$search%' 
        ORDER BY id DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>View Entries - Your Project</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
 <h2 class="mb-4">All Entries</h2>

 <!-- 🔹 Search Form -->
 <form method="GET" class="mb-3">
   <input type="text" name="search" class="form-control" 
          placeholder="Search by Name or Email"
          value="<?php echo htmlspecialchars($search); ?>">
 </form>

 <!-- 🔹 Table -->
 <table class="table table-bordered table-striped table-hover">
   <thead class="table-dark">
     <tr>
       <th>ID</th>
       <th>Name</th>
       <th>Email</th>
       <th>Phone</th>
       <th>Created At</th>
     </tr>
   </thead>
   <tbody>
   <?php
   if ($result->num_rows > 0) {
       while($row = $result->fetch_assoc()) {
           echo "<tr>
             <td>{$row['id']}</td>
             <td>{$row['name']}</td>
             <td>{$row['email']}</td>
             <td>{$row['phone']}</td>
             <td>{$row['created_at']}</td>
           </tr>";
       }
   } else {
       echo "<tr><td colspan='5' class='text-center'>No entries found</td></tr>";
   }
   ?>
   </tbody>
 </table>

 <!--Pagination -->
 <nav>
   <ul class="pagination">
     <?php if ($page > 1): ?>
       <li class="page-item">
         <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page-1; ?>">Previous</a>
       </li>
     <?php endif; ?>

     <?php for ($i = 1; $i <= $total_pages; $i++): ?>
       <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
         <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>">
           <?php echo $i; ?>
         </a>
       </li>
     <?php endfor; ?>

     <?php if ($page < $total_pages): ?>
       <li class="page-item">
         <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page+1; ?>">Next</a>
       </li>
     <?php endif; ?>
   </ul>
 </nav>

</div>
</body>
</html>
