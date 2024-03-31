<?php
require_once 'Connection.php';
session_start(); // Start the session

// Initialize variables
$num_rows = 0;
$num_columns = 0;

$sql_crossword_cells = "SELECT id,row_number, col_number, value, superscript_num FROM cword_cells";
$result_crossword_cells = $conn->query($sql_crossword_cells);

// Fetch crossword cell data from the database
$crosswordData = array();
if ($result_crossword_cells->num_rows > 0) {
    while ($row = $result_crossword_cells->fetch_assoc()) {
        $crosswordData[] = $row;
    }
}



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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category']; // fetching category (e.g., 'Across' or 'Down')
    $clue_text = $_POST['clue_text'];

    $sql = "INSERT INTO clues (category, clue_text) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $category, $clue_text);

    if ($stmt->execute()) {
        header("Location: createcrossword.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mycss.css"> 
	<link rel="stylesheet" href="responsive.css"> 
    <title>Create Clue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
/* Styles for the sections */
section {
    margin-bottom: 20px;
}

h2 {
    font-size: 1.5em;
    color: #333;
}

ol {
    list-style-type: decimal;
    margin-left: 20px;
}

li {
    margin-bottom: 5px;
}

/* Styles for the form */
form {
    margin-top: 20px;
    border: 1px solid #ccc;
    padding: 20px;
    width: 70%;
    margin-left: auto;
    margin-right: auto;
}

label {
    font-weight: bold;
}

textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}
.container {
    display: flex;
    justify-content: space-between;
}

.crossword-section {
    flex: 1;
    margin-right: 20px; /* Adjust spacing between sections */
}

.crossword-grid {
    margin-bottom: 20px; /* Add some space below the crossword grid */
}

.crossword-cell {
    width: 30px; /* Adjust cell width as needed */
    height: 30px; /* Adjust cell height as needed */
    border: 1px solid #ccc; /* Border color */
    text-align: center; /* Center align text within cells */
}

.crossword-input {
    width: 100%;
    height: 100%;
    border: none;
    font-size: 16px; /* Adjust font size as needed */
}

.clue-section {
    flex: 1;
}

.clue-section aside {
    background-color: #f2f2f2; /* Background color for the clue section */
    padding: 10px; /* Add some padding */
}

.clue-column {
    margin-bottom: 20px; /* Add some space between clue columns */
}

.clue-list {
    list-style-type: none; /* Remove bullet points from clue list */
    padding: 0;
}

.clue-list li {
    margin-bottom: 5px; /* Add some space between clues */
}

/* Pagination styles */
.pagination {
    margin-top: 20px; /* Add some space above pagination */
}

.current-page {
    margin: 0 10px; /* Add some space around current page indicator */
}

.btn {
    padding: 10px 20px; /* Adjust button padding */
    background-color: #007bff; /* Button background color */
    color: #fff; /* Button text color */
    text-decoration: none; /* Remove default link underline */
    border: none; /* Remove button border */
    border-radius: 4px; /* Add button border radius */
}

.btn:hover {
    background-color: #0056b3; /* Button background color on hover */
    cursor: pointer; /* Change cursor to pointer on hover */
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
<section>
    <h2>Across</h2>
    <ol id="across">
        <?php
        // Fetch clue data from a database or any other source
        // For demonstration purposes, here we assume the clue data is fetched from a PHP array
        if (isset($_POST['across_clues']) && is_array($_POST['across_clues'])) {
            foreach ($_POST['across_clues'] as $clue) {
                echo '<li>' . htmlspecialchars($clue) . '</li>';
            }
        }
        ?>
    </ol>
</section>
<section>
    <h2>Down</h2>
    <ol id="down">
        <?php
        // Fetch clue data from a database or any other source
        // For demonstration purposes, here we assume the clue data is fetched from a PHP array
        if (isset($_POST['down_clues']) && is_array($_POST['down_clues'])) {
            foreach ($_POST['down_clues'] as $clue) {
                echo '<li>' . htmlspecialchars($clue) . '</li>';
            }
        }
        ?>
    </ol>
</section>

<!-- Form for the admin to provide the words -->
<form method="post" action="table.php">
    <h3>Provide Clues</h3>
    <div>
        <label for="category">Category:</label><br>
        <select name="category" required>
            <option value="Across">Across</option>
            <option value="Down">Down</option>
        </select>
    </div>
    <div>
        <label for="clue_text">Clue Text:</label><br>
        <textarea id="clue_text" name="clue_text" rows="5" cols="50"></textarea><br>

        <label for="superscript">Superscript:</label>
        <input type="number" id="superscript" name="superscript" placeholder="Enter superscript if any" min="0">
    </div>
   
    <input type="submit" value="Submit">
</form>


<section>
<form method="post" action="process_add_cells.php">
    <table>
        <?php
        // Your database connection code here
        
        // Fetch the most recently added crossword_id
        $sql_recent_crossword_id = "SELECT DISTINCT crossword_id FROM cword_cells ORDER BY crossword_id DESC LIMIT 1";
        $result_recent_crossword_id = $conn->query($sql_recent_crossword_id);

        if ($result_recent_crossword_id === false) {
            die("Query failed: " . $conn->error);
        }

        // Check if there are any recent crossword_ids
        if ($result_recent_crossword_id->num_rows > 0) {
            $row = $result_recent_crossword_id->fetch_assoc();
            $crossword_id = $row['crossword_id'];

            // Fetch crossword cell data for the most recently added crossword_id
            $sql_crossword_cells_data = "SELECT row_number, col_number, value, superscript_num FROM cword_cells WHERE crossword_id = ?";
            $stmt_cells = $conn->prepare($sql_crossword_cells_data);
            $stmt_cells->bind_param("i", $crossword_id);
            $stmt_cells->execute();
            $result_cells = $stmt_cells->get_result();

            if ($result_cells === false) {
                die("Query failed: " . $conn->error);
            }

            // Initialize an array to hold the crossword cell data
            $crossword_grid = array();

            // Store crossword cell data into the array based on row and column numbers
            while ($cell_row = $result_cells->fetch_assoc()) {
                $row_number = $cell_row['row_number'];
                $col_number = $cell_row['col_number'];
                $value = $cell_row['value'];
                $superscript_num = $cell_row['superscript_num'];
                $crossword_grid[$row_number][$col_number] = array('value' => $value, 'superscript_num' => $superscript_num);
            }

            // Display the crossword grid
            foreach ($crossword_grid as $row_number => $row_data) {
                echo "<tr>";
                foreach ($row_data as $col_number => $cell_data) {
                    $value = $cell_data['value'];
                    $superscript_num = $cell_data['superscript_num'];
                    $class = empty($value) ? "blank" : ""; // Add class "blank" if cell is empty
                    echo '<td class="crossword-cell ' . $class . '">';
                    echo $value; // Display the value stored in the database
                    if (!empty($superscript_num)) {
                        echo '<sup>' . $superscript_num . '</sup>'; // Display superscript number
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "No recent crossword grids found.";
        }
        ?>
    </table>
</form>




    </section>
    <script src="pc_cword.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
