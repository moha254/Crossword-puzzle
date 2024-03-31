<?php
require_once 'Connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if(isset($_SESSION['AdminLoginId'])) {
    header("location: AdminLogin.php");
    exit(); // Stop further execution
}

// Logout functionality
if(isset($_POST['Logout'])) {
    session_destroy();
    header("location: pc_cword.php");
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-BHjE6qWEiqg6hy2emEyt3jp8rUVZ4R1qtoK5QjmEj5W8A4bgv6moK2rOTF+9OzCmMflM7qzVQ6/zk/NOz1Oukw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="mycss.css"> 
	<link rel="stylesheet" href="responsive.css"> 
    <title>index</title>
</head>
<body>
    
<header role="banner">
  <h1>Admin Panel</h1>
  <ul class="utilities">
    <br>
    <li class="users"><a href="#">My Account</a></li>
    <a href="pc_cword.php" style="text-decoration: none; color: #212529;">
    <button type="submit" name="Logout" style="background: none;color: #212529; border: none; cursor: pointer;font-size:26px;">
        Logout
    </button>
</a>
  </ul>
</header>

<nav role='navigation'>
  <ul class="main">
    <li class="dashboard"><a href="AdminPanel.php">Dashboard</a></li>
    <li class="edit"><a href="createcrossword.php">Crosswords</a></li>
    <li class="write"><a href="index.php">Website puzzle</a></li>
    <li class="edit"><a href="creategrid.php">Table Grid</a></li>
    <li class="write"><a href="index3.php">Crosswords Grid</a></li>
    <li class="edit"><a href="createword.php">CWords</a></li>
    <li class="write"><a href="index2.php">Word puzzle</a></li>
    <li class="comments"><a href="#">Ads</a></li>
    <li class="users"><a href="#">Manage Users</a></li>
  </ul>
</nav>

<div class="container">
    <div class="index">
   <?php 
    // Fetch data from the clues table
    $sql = "SELECT * FROM clues";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) : ?>
        <table class='table'>
            <tr>
                <th>Category</th>
                <th>Clue Text</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                  
                    <td><?php echo isset($row["category"]) ? $row["category"] : 'N/A'; ?></td>
                    <td><?php echo isset($row["clue_text"]) ? $row["clue_text"] : ''; ?></td>
                    <td>
                        <a href="EditClue.php?id=<?php echo isset($row["id"]) ? $row["id"] : ''; ?>" class="btn btn-edit btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="deleteClue.php" method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo isset($row["id"]) ? $row["id"] : ''; ?>">
                            <button type="submit" class="btn btn-delete btn-danger">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                            </div>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>No results found</p>
    <?php endif; ?>



    <?php
    // No need to close the connection here since it's already closed after retrieving data
    ?>
</div>
</div>
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous">
    </script>
</body>
</html>
