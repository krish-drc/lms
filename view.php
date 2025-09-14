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
 <title>View Entries - Library Management System</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
 <h2 class="mb-4">All Entries</h2>

 <!-- Search Form -->
 <form method="GET" class="mb-3">
   <input type="text" name="search" class="form-control" 
          placeholder="Search by Name or Email"
          value="<?php echo htmlspecialchars($search); ?>">
 </form>

 <!-- Table -->
<div class="container-fluid">
 <div class="row">
   <div class="col-md-6 col-lg-12">
     
<div class="table-responsive">
 <table class="table table-bordered table-striped table-hover" id="entriesTable">
   <thead class="table-dark">
     <tr onmouseover="this.style.backgroundColor='#f2f2f2'" 
       onmouseout="this.style.backgroundColor=''">

       <th>ID</th>
       <th onclick="sortTable(1)">Name</th>
       <th onclick="sortTable(2)">Email</th>
       <th onclick="sortTable(3)">Phone</th>
       <th>Created At</th>
       <th>Actions</th>
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
             <td>
                <a href='update.php?id={$row['id']}' class='btn btn-primary btn-sm'>Edit</a>
                <a href='#' class='btn btn-danger btn-sm delete-btn' 
                   data-id='{$row['id']}' data-bs-toggle='modal' data-bs-target='#deleteModal'>
                   Delete
                </a>
             </td>
           </tr>";
       }
   } else {
       echo "<tr><td colspan='6' class='text-center'>No entries found</td></tr>";
   }
   ?>
   </tbody>
  </table>
  </div>

 <!-- Pagination -->
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
</div>
</div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title">Confirm Delete</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
   </div>
   <div class="modal-body">
    Are you sure you want to delete this entry?
   </div>
   <div class="modal-footer">
    <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</a>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
   </div>
  </div>
 </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Sort Table
function sortTable(n) {
  let table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("entriesTable");
  switching = true;
  while (switching) {
    switching = false;
    rows = table.rows;
    for (i = 1; i < (rows.length - 1); i++) {
      shouldSwitch = false;
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}

// Delete Modal Dynamic Link
const deleteButtons = document.querySelectorAll('.delete-btn');
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

deleteButtons.forEach(button => {
  button.addEventListener('click', () => {
    const id = button.getAttribute('data-id');
    confirmDeleteBtn.setAttribute('href', 'delete.php?id=' + id);
  });
});
</script>

</body>
</html>
