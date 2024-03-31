<?php
require_once 'Connection.php';
session_start(); // Start the session

// Initialize variables
$num_rows = 0;
$num_columns = 0;


if ($conn) {
    for ($row = 1; $row <= $num_rows; $row++) {
        for ($col = 1; $col <= $num_columns; $col++) {
            // Example grid data insertion query
            $value = ''; // Assuming initially cells are empty
            $is_blocked = 0; // Assuming initially cells are not blocked
            $sql = "INSERT INTO cword_cells (id, row_number, col_number, value, is_blocked) 
                    VALUES (1, $row, $col, '$value', $is_blocked)";

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    echo "Crossword grid generated successfully!";

} else {
    echo "Connection failed!";
}
// Check if the grid data is stored in the session
if (isset($_SESSION['grid_data'])) {
    // Retrieve grid data from session
    $num_rows = $_SESSION['grid_data']['num_rows'];
    $num_columns = $_SESSION['grid_data']['num_columns'];
}

// Process form submission for generating the grid
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_grid'])) {
    // Generate grid based on user input
    $num_rows = $_POST['num_rows'];
    $num_columns = $_POST['num_columns'];
    // Store grid data in session
    $_SESSION['grid_data'] = ['num_rows' => $num_rows, 'num_columns' => $num_columns];
}



// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve number of rows and columns from form
    $num_rows = isset($_POST['num_rows']) ? intval($_POST['num_rows']) : 0;
    $num_columns = isset($_POST['num_columns']) ? intval($_POST['num_columns']) : 0;
} else {
    // Default values
    $num_rows = 11;
    $num_columns = 9;
}

// Define a default value for the cell data
$cellData = [];

// Generate cell data for the grid
for ($row = 1; $row <= $num_rows; $row++) {
    for ($col = 1; $col <= $num_columns; $col++) {
        $cellData[] = [
            'row_index' => $row,
            'column_index' => $col,
            'value' => '', // You can set initial values if needed
            'is_black' => false, // You can set black cells if needed
            'superscript_number' => null // You can set superscript numbers if needed
        ];
    }
}



$sql_crossword_cells = "SELECT id,row_number, col_number, value, superscript_num FROM cword_cells";
$result_crossword_cells = $conn->query($sql_crossword_cells);

// Fetch crossword cell data from the database
$crosswordData = array();
if ($result_crossword_cells->num_rows > 0) {
    while ($row = $result_crossword_cells->fetch_assoc()) {
        $crosswordData[] = $row;
    }
}


// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch crossword data
$sql = "SELECT * FROM crossword_puzzle";
$result = $conn->query($sql);

// Fetch crossword data from the database
$crosswordData = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $crosswordData[] = $row;
    }
}


// SQL query to fetch crossword cells data
$sql_cells = "SELECT * FROM crossword_cells";
$result_cells = $conn->query($sql_cells);

// Fetch crossword cells data from the database
$cellData = array();
if ($result_cells->num_rows > 0) {
    while ($row = $result_cells->fetch_assoc()) {
        $cellData[] = $row;
    }
}
// Define variables to store form data
$num_rows = $num_columns = "";
$errors = array();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form fields
    $num_rows = intval($_POST["num_rows"]);
    $num_columns = intval($_POST["num_columns"]);

    // Check if values are valid
    if ($num_rows <= 0 || $num_columns <= 0) {
        $errors[] = "Number of rows and columns must be greater than zero.";
    } else {
        // Insert data into database
        $sql_insert = "INSERT INTO crosswords (num_rows, num_columns) VALUES ($num_rows, $num_columns)";
        
        if ($conn->query($sql_insert) === TRUE) {
            echo "New record inserted successfully.";
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}




// Fetch 'across' clues from the database
$sql_across = "SELECT clue_text FROM clues WHERE category = 'Across'";
$result_across = $conn->query($sql_across);
// Fetch 'down' clues from the database
$sql_down = "SELECT clue_text FROM clues WHERE category = 'Down'";
$result_down = $conn->query($sql_down);

// Close database connection
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="mycss.css"> 
	<link rel="stylesheet" href="responsive.css"> 
    <title>Create Grid</title>
    <style>
.container {
    max-width: 800px; /* Adjust as needed */
    margin: 0 auto; /* Center the container */
    padding: 20px;
}

article {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 10px;
}

input[type="number"],
input[type="submit"] {
    padding: 8px;
    border-radius: 3px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
}

p {
    margin-bottom: 20px;
}

table {
    border-collapse: collapse;
    width: 100%;
}

td {
    border: 1px solid #ccc;
    padding: 5px;
}

.crossword-input {
    width: 100%;
    border: none;
    text-align: center;
    font-size: 16px;
}

.crossword-cell {
    width: 30px; /* Adjust cell width as needed */
}



    </style>
</head>
<body>
    
<header role="banner">
  <h1>Admin Panel</h1>
  <ul class="utilities">
    <br>
    <li class="users"><a href="#">My Account</a></li>
    <a href="scholarpage.php" style="text-decoration: none; color: #212529;">
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
   <article>
   <form method="post">
        <label for="num_rows">Number of Rows:</label>
        <input type="number" id="num_rows" name="num_rows" value="<?php echo $num_rows; ?>">
        <label for="num_columns">Number of Columns:</label>
        <input type="number" id="num_columns" name="num_columns" value="<?php echo $num_columns; ?>">
        <input type="submit" name="generate_grid" value="Generate Grid">
    </form>

    <form method="post" action="process_add_cells.php">
        <p>Please fill out the grid below. Each box represents a letter in the crossword. If a clue has a number associated with it, enter that number in the box, and it will automatically appear as a superscript. If you leave a cell empty, it will be automatically blocked.</p>
        <table id="crossword">
            <?php for ($row = 1; $row <= $num_rows; $row++) : ?>
                <tr>
                    <?php for ($col = 1; $col <= $num_columns; $col++) : ?>
                        <td class="crossword-cell">
                            <input type="text" class="crossword-input" data-letter="" name="value[<?php echo $row; ?>][<?php echo $col; ?>]" value="">
                            <input type="text" class="super-input" name="superscript_num[<?php echo $row; ?>][<?php echo $col; ?>]" placeholder="Clue">

                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
        <input type="submit" name="submit_crossword" value="Submit Crossword">
    </form>
</article>

    <script src="pc_cword.js"></script>
</body>

</html>