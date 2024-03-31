<?php
// Check if the ID parameter is not set or empty
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect the user back to the AdminPanel.php page with an error message
    header("Location: AdminPanel.php?error=missing_id");
    exit();
}

require_once 'Connection.php';

// Fetch the details from the database based on the provided ID
$sql = "SELECT * FROM cword_cells WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

// Check if a record was found
if ($result->num_rows === 0) {
    // Redirect the user back to the AdminPanel.php page with an error message
    header("Location: AdminPanel.php?error=invalid_id");
    exit();
}

// Fetch the details as an associative array
$details = $result->fetch_assoc();

// Close the statement
$stmt->close();

?>

<!DOCTYPE html> 
<html lang="en"> 

<head> 
    <meta charset="UTF-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="mycss.css"> 
	<link rel="stylesheet" href="responsive.css"> 

  
    <title>Edit Details</title>
    <style>
/* Style for the form container */
/* Style for container */
.container {
    width: 50%;
    margin: 0 auto;
    text-align: center; /* Center align the contents */
}

/* Additional CSS for the form */
/* Style for form groups */
.form-group {
    margin-bottom: 20px;
    text-align: left; /* Reset text alignment for form elements */
}

/* Style for labels */
label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

/* Style for input fields */
input[type="number"],
input[type="text"],
input[type="checkbox"] {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

/* Style for submit button */
input[type="submit"] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

/* Style for submit button on hover */
input[type="submit"]:hover {
    background-color: #45a049;
}



    </style>
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
<h1>Edit Crossword Cells</h1>
<div class="container">
    <form method="post" action="update_cells.php">
        <?php
        // Check if the database connection is valid
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch details of the cell to be edited from the cword_cells table
        $cell_id = $_GET['id'];
        $sql_cell_details = "SELECT * FROM cword_cells WHERE id = ?";
        $stmt = $conn->prepare($sql_cell_details);
        $stmt->bind_param("i", $cell_id);
        $stmt->execute();
        $result_cell_details = $stmt->get_result();

        if ($result_cell_details === false) {
            die("Query failed: " . $conn->error);
        }

        if ($result_cell_details->num_rows > 0) {
            $details = $result_cell_details->fetch_assoc();
        } else {
            echo "Cell details not found.";
            exit;
        }
        ?>

<div class="form-group">
    <label for="row">Row:</label>
    <input type="number" id="row" name="row" value="<?php echo $details['row_number']; ?>">
</div>
<div class="form-group">
    <label for="column">Column:</label>
    <input type="number" id="column" name="column" value="<?php echo $details['col_number']; ?>">
</div>
<div class="form-group">
    <label for="value">Value:</label>
    <input type="text" id="value" name="value" value="<?php echo $details['value']; ?>">
</div>
<div class="form-group">
    <label for="superscript">Superscript Number:</label>
    <input type="number" id="superscript" name="superscript" value="<?php echo isset($details['superscript_number']) ? $details['superscript_number'] : ''; ?>">
</div>

<!-- Add a hidden input field to store the crossword_id -->
<input type="hidden" name="crossword_id" value="<?php echo $crossword_id; ?>">
<input type="hidden" name="id" value="<?php echo $cell_id; ?>">
<input type="submit" name="update" value="Submit">
</form>
</div>


</body>
</html>
