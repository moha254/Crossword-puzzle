<?php
session_start(); // Start or resume a session

require_once 'Connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the POST data is set
if (isset($_POST["crossword_id"]) && isset($_POST["delete_cells"])) {
    // Get the id of the crossword grid from the POST request
    $crossword_id = $_POST["crossword_id"];

    // Delete all cells associated with the crossword grid
    $sql_delete_cells = "DELETE FROM cword_cells WHERE crossword_id = ?";
    $stmt_delete_cells = $conn->prepare($sql_delete_cells);
    $stmt_delete_cells->bind_param("i", $crossword_id);
    if (!$stmt_delete_cells->execute()) {
        echo "Error deleting cells: " . $conn->error;
        exit(); // Terminate execution
    }

    // Redirect back to wherever you need to go after deletion
    header("Location: index3.php");
    exit();
}

// Check if the POST data is set for deleting the entire grid
if (isset($_POST["crossword_id"]) && isset($_POST["delete_grid"])) {
    // Get the id of the crossword grid from the POST request
    $crossword_id = $_POST["crossword_id"];

    // Delete the crossword grid itself
    $sql_delete_grid = "DELETE FROM crosswords WHERE id = ?";
    $stmt_delete_grid = $conn->prepare($sql_delete_grid);
    $stmt_delete_grid->bind_param("i", $crossword_id);
    if (!$stmt_delete_grid->execute()) {
        echo "Error deleting grid: " . $conn->error;
        exit(); // Terminate execution
    }

    // Redirect back to wherever you need to go after deletion
    header("Location: index3.php");
    exit();
}

if (isset($_SESSION['AdminLoginId'])) {
    header("location: AdminLogin.php");
    exit(); // Stop further execution
}

// Logout functionality
if (isset($_POST['Logout'])) {
    session_destroy();
    header("location: word.php");
    exit(); // Stop further execution
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
$result_crossword_cells = $conn->query($sql_crossword_cells);

if (!$result_crossword_cells) {
    echo "Error: " . $conn->error;
} else {
    // Fetch crossword cell data from the database
    $crosswordData = array();
    if ($result_crossword_cells->num_rows > 0) {
        while ($row = $result_crossword_cells->fetch_assoc()) {
            $crosswordData[] = $row;
        }
    }
}

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch crossword data
$sql_crossword = "SELECT * FROM cword_cells";
$result_crossword = $conn->query($sql_crossword);

// Fetch crossword data from the database
$crosswordData = array();
if ($result_crossword->num_rows > 0) {
    while ($row = $result_crossword->fetch_assoc()) {
        $crosswordData[] = $row;
    }
}


// SQL query to fetch crossword data
$sql_crossword = "SELECT * FROM crossword_puzzle";
$result_crossword = $conn->query($sql_crossword);

// Fetch crossword data from the database
$crosswordData = array();
if ($result_crossword->num_rows > 0) {
    while ($row = $result_crossword->fetch_assoc()) {
        $crosswordData[] = $row;
    }
}

// SQL query to fetch crossword cells data
$sql_cells = "SELECT * FROM cword_cells";
$result_cells = $conn->query($sql_cells);

// Fetch crossword cells data from the database
$cellData = array();
if ($result_cells->num_rows > 0) {
    while ($row = $result_cells->fetch_assoc()) {
        $cellData[] = $row;
    }
}

// SQL query to fetch crossword cells data
$sql_cells = "SELECT * FROM crosswords";
$result_cells = $conn->query($sql_cells);

// Fetch crossword cells data from the database
$cellData = array();
if ($result_cells->num_rows > 0) {
    while ($row = $result_cells->fetch_assoc()) {
        $cellData[] = $row;
    }
}
// Fetch 'across' clues from the database
$sql_across = "SELECT clue_text FROM clues WHERE category = 'Across'";
$result_across = $conn->query($sql_across);
// Fetch 'down' clues from the database
$sql_down = "SELECT clue_text FROM clues WHERE category = 'Down'";
$result_down = $conn->query($sql_down);

// Fetch data from the configuration table
$sql_config = "SELECT * FROM configuration";
$result_config = $conn->query($sql_config);

// Fetch configuration data from the database
$configData = array();
if ($result_config->num_rows > 0) {
    while ($row = $result_config->fetch_assoc()) {
        $configData[] = $row;
    }
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
    <style>
        /* Custom CSS for Crossword Grid */
        .grid-container {
            grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
            grid-gap: 2px;
            border: 2px solid #ccc;
            padding: 5px;
            margin-bottom: 20px;
        }

        .grid-row {
            display: flex;
        }

        .grid-cell {
            flex: 1;
            position: relative;
        }

        .square-box {
            width: 100%;
            height: 90px;
            background-color: #fff;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }

        /* Styling for superscript numbers */
        .superscript {
            /* Positioning */
            position: absolute;
            top: 2px;
            /* Adjust the distance from the top as needed */

            /* Background and border */
            background-color: #fff;
            /* Background color */
            padding: 2px 5px;
            /* Padding around the superscript */
            border-radius: 3px;
            /* Border radius for rounded corners */
            border: 1px solid #ccc;
            /* Border color and width */

            /* Text appearance */
            font-size: 12px;
            /* Font size of the superscript */
        }


        .btn-delete {
            margin-top: 3rem;
        }

        .btn-edit {
            margin-top: 3rem;
        }

        .button-container {
            display: flex;
            gap: 10px;
            /* Adjust the gap between buttons as needed */
        }

        .btn-update {
            background-color: #28a745;
            /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 39px 20px 2px;

            cursor: pointer;
            border-radius: 5px;
        }

        .btn-update:hover {
            background-color: #218838;
            /* Darker green on hover */
        }

        /* Pagination links */
        .pagination {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-pagination {
            background-color: #007bff;
            color: #fff;
            margin-right: 5px;
        }



        /* Edit, Delete, and Update buttons */
        .button-container {
            margin-top: 20px;
        }

        .btn-edit,
        .btn-delete,
        .btn-update {
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #212529;
            position: relative;
            left: 2rem;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
            position: relative;
            left: 3rem;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-update {
            background-color: #28a745;
            color: #fff;
        }

        .btn-update:hover {
            background-color: #218838;
        }

/* Custom CSS for clue section */
.clue-section {
      margin-top: 20px;
    }
    #Clues {
      display: flex;
      justify-content: space-between;
    }
    .clue-column {
      flex: 0 0 48%;
    }
    .clue-list {
      list-style-type: none;
      padding-left: 0;
    }
    .clue-list li {
      margin-bottom: 5px;
    }
    .clue-text{
        position:relative;
        top:1rem;
    }

        table#crossword {
            font-size: 1.4em;
            border: 1px solid rgb(101, 101, 101);
            box-shadow: rgb(51, 51, 51) 5px 5px 15px, rgb(51, 51, 51) -5px -5px 15px;
            border-collapse: collapse;
            color: rgb(101, 101, 101);
            margin: 20px;
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

    <div class="container">
        <div class="index">
            <table id="crossword">
                <?php
                // Pagination parameters
                $limit = 1; // Only one record per page
                $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number

                // Calculate the offset based on the current page
                $offset = ($page - 1) * $limit;

                // Fetch distinct crossword_ids for the current page based on the limit and offset
                $sql_distinct_crossword_ids = "SELECT DISTINCT crossword_id FROM cword_cells LIMIT $limit OFFSET $offset";
                $result_distinct_crossword_ids = $conn->query($sql_distinct_crossword_ids);

                if ($result_distinct_crossword_ids === false) {
                    die("Query failed: " . $conn->error);
                }

                // Define the SQL query for fetching crossword cell data
                $sql_crossword_cells_data = "SELECT id, row_number, col_number, value, superscript_num FROM cword_cells WHERE crossword_id = ?";

                // Display pagination links
                echo "<div class='pagination'>";
                // Count the total number of distinct crossword_ids
                $sql_count_crossword_ids = "SELECT COUNT(DISTINCT crossword_id) AS total FROM cword_cells";
                $result_count_crossword_ids = $conn->query($sql_count_crossword_ids);
                $total_records = $result_count_crossword_ids->fetch_assoc()['total'];

                // Calculate the total number of pages based on the total records and the limit
                $total_pages = ceil($total_records / $limit);

                // Display pagination links
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='?page=$i' class='btn btn-pagination'>$i</a> ";
                }
                echo "</div>";

                // Loop through the distinct crossword_ids for the current page
                while ($row = $result_distinct_crossword_ids->fetch_assoc()) {
                    $crossword_id = $row['crossword_id'];

                    // Fetch crossword cell data
                    $stmt_cells = $conn->prepare($sql_crossword_cells_data);
                    $stmt_cells->bind_param("i", $crossword_id);
                    $stmt_cells->execute();
                    $result_cells = $stmt_cells->get_result();

                    if ($result_cells === false) {
                        die("Query failed: " . $conn->error);
                    }

                    // Display the crossword grid
                    echo "<div id='crossword_$crossword_id' class='grid-container'>";
                    echo "<form method='post' action='update_cells.php'>";
                    // Initialize an array to store grid cells
                    $grid = array();

                    // Loop through the fetched cells and organize them into rows and columns
                    while ($cell_row = $result_cells->fetch_assoc()) {
                        $row_number = $cell_row['row_number'];
                        $col_number = $cell_row['col_number'];
                        $value = $cell_row['value'];
                        $superscript_num = $cell_row['superscript_num'];

                        // Store the cell in the grid array
                        $grid[$row_number][$col_number] = array('id' => $cell_row['id'], 'value' => $value, 'superscript_num' => $superscript_num);
                    }

                    // Loop through the grid array to display the crossword grid
                    foreach ($grid as $row_number => $row) {
                        echo "<div class='grid-row'>";
                        foreach ($row as $col_number => $cell) {
                            $id = $cell['id'];
                            $value = $cell['value'];
                            $superscript_num = $cell['superscript_num'];
                            echo "<div class='grid-cell'>";
                            echo "<input type='hidden' name='updated_cells[$id][id]' value='$id'>";
                            echo "<input type='text' name='updated_cells[$id][value]' class='square-box' value='$value'>";
                            echo "<input type='text' name='updated_cells[$id][superscript]' class='superscript' value='$superscript_num'>";
                            echo "</div>"; // End of grid-cell div
                        }
                        echo "</div>"; // End of grid-row div
                    }
                    echo "<div class='button-container'>";
                    echo "<input type='hidden' name='crossword_id' value='$crossword_id'>";
                    echo "<button type='submit' class='btn btn-update btn-success' name='update_cells'><i class='fas fa-check'></i> Update</button>";
                    echo "</form>";

                    // Edit Grid button
                    // echo "<form action='edit_cells.php' method='post'>";
                    // echo "<input type='hidden' name='crossword_id' value='$crossword_id'>";
                    //echo "<button type='submit' class='btn btn-edit btn-warning' name='edit_grid'><i class='fas fa-edit'></i> Edit Grid</button>";
                    //echo "</form>";

                    // Delete Grid button
                    echo "<form action='index3.php' method='post'>";
                    echo "<input type='hidden' name='crossword_id' value='$crossword_id'>";
                    echo "<button type='submit' class='btn btn-delete btn-danger' name='delete_cells'><i class='fas fa-trash-alt'></i> Delete Grid</button>";
                    echo "</form>";

                    echo "</div>"; // Close button-container

                    echo "</div>"; // End of grid-container div
                }
                ?>
            </table>
        </div>
    </div>

    
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="clue-section">
      <?php
// Assuming $crossword_id is defined elsewhere in your code

// Fetch across clues for the specific crossword
$sql_across = "SELECT DISTINCT c.id, c.clue_text, cc.crossword_id AS cword_cells_crossword_id, c.crossword_id AS clues_crossword_id
FROM clues c 
INNER JOIN crosswords cr ON c.crossword_id = cr.id 
INNER JOIN cword_cells cc ON cr.id = cc.crossword_id 
WHERE cr.id = ? AND c.category = 'across'";
$stmt_across = $conn->prepare($sql_across);
$stmt_across->bind_param("i", $crossword_id);
$stmt_across->execute();
$result_across = $stmt_across->get_result();

// Fetch down clues for the specific crossword
$sql_down = "SELECT DISTINCT c.id, c.clue_text, cc.crossword_id AS cword_cells_crossword_id, c.crossword_id AS clues_crossword_id
FROM clues c 
INNER JOIN crosswords cr ON c.crossword_id = cr.id 
INNER JOIN cword_cells cc ON cr.id = cc.crossword_id 
WHERE cr.id = ? AND c.category = 'down'";
$stmt_down = $conn->prepare($sql_down);
$stmt_down->bind_param("i", $crossword_id);
$stmt_down->execute();
$result_down = $stmt_down->get_result();

// Display the clues
echo "<aside id=\"Clues\">";
echo "<div class=\"clue-column\">";
echo "<h2>Across</h2>";
echo "<ul class=\"clue-list\">";
if ($result_across->num_rows > 0) {
    while ($row_across = $result_across->fetch_assoc()) {
        echo "<li>";
        echo "<span class='clue-text'>" . $row_across['clue_text'] . "</span>";
        echo "<div class='clue-buttons'>";
        echo "<a href='EditClue.php?id=" . $row_across['id'] . "' class='btn btn-edit btn-warning'><i class='fas fa-edit'></i> Edit</a>";
        echo "<form action='deleteClue.php' method='post' style='display: inline;'>";
        echo "<input type='hidden' name='id' value='" . $row_across['id'] . "'>";
        echo "<button type='submit' class='btn btn-delete btn-danger'><i class='fas fa-trash-alt'></i> Delete</button>";
        echo "</form>";
        echo "</div>";
        echo "</li>";
    }
} else {
    echo "<li>No across clues found.</li>";
}
echo "</ul></div>";

// Display down clues
echo "<div class=\"clue-column\">";
echo "<h2>Down</h2>";
echo "<ul class=\"clue-list\">";
if ($result_down->num_rows > 0) {
    while ($row_down = $result_down->fetch_assoc()) {
        echo "<li>";
        echo "<span class='clue-text'>" . $row_down['clue_text'] . "</span>";
        echo "<div class='clue-buttons'>";
        echo "<a href='EditClue.php?id=" . $row_down['id'] . "' class='btn btn-edit btn-warning'><i class='fas fa-edit'></i> Edit</a>";
        echo "<form action='deleteClue.php' method='post' style='display: inline;'>";
        echo "<input type='hidden' name='id' value='" . $row_down['id'] . "'>";
        echo "<button type='submit' class='btn btn-delete btn-danger'><i class='fas fa-trash-alt'></i> Delete</button>";
        echo "</form>";
        echo "</div>";
        echo "</li>";
    }
} else {
    echo "<li>No down clues found.</li>";
}
echo "</ul></div></aside>";

// Close statements
$stmt_across->close();
$stmt_down->close();
?>





    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>

</body>

</html>