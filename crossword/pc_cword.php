<?php
require_once 'Connection.php';

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
    <title>Daily Crossword</title>
    <meta charset="utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="pc_styles.css" rel="stylesheet" />
    <link href="pc_cword.css" rel="stylesheet" />
    <style>
.container {
    display: flex;
    justify-content: space-between;
}

.crossword-section {
    flex: 1 1 auto; /* Set flex-grow, flex-shrink, and flex-basis */
    margin-right: 20px; /* Adjust spacing between sections */
}

.crossword-grid {
    margin-bottom: 20px; /* Add some space below the crossword grid */
    position: relative;
    right: 9rem;
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
    flex: 1 1 auto; /* Set flex-grow, flex-shrink, and flex-basis */
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
    <header>
        <h1>The <b>Star</b> NewsPaper</h1>
        <nav id="top">
            <ul>
                <li><a href="#">news</a></li>
                <li><a href="#">sports</a></li>
                <li><a href="#">weather</a></li>
                <li><a href="#">entertainment</a></li>
                <li><a href="#">classifieds</a></li>
                <li><a href="Adminlogin.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section>
        <div class="container">
        <div class="row">
            <div class="col-md-5">
            <div class="crossword-section">
                <!-- Crossword Grid -->
                <div class="crossword-grid">
        <table id="crossword">
            <!-- Grid Headers -->
            <caption>Today's Crossword</caption>
            <tr>
    <tr>
    <td colspan="10" id="highScore" style="border: none; text-align: center;">High Score: <span id="high-score-value">0</span></td>
</tr>
<tr>
    <td colspan="10" id="currentScore" style="border: none; text-align: center;">Score: <span id="current-score-value">0</span></td>
</tr>


            <form method="post" action="process_add_cells.php">
                <?php
                // Pagination parameters
                $limit = 1; // Only one record per page
                $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number

                // Count the total number of distinct crossword_ids
                $sql_count_crossword_ids = "SELECT COUNT(DISTINCT crossword_id) AS total FROM cword_cells";
                $result_count_crossword_ids = $conn->query($sql_count_crossword_ids);
                $total_records = $result_count_crossword_ids->fetch_assoc()['total'];

                // Calculate the total number of pages based on the total records and the limit
                $total_pages = ceil($total_records / $limit);

                // Ensure that the page is within the valid range
                $page = max(min($page, $total_pages), 1);

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
                            if (empty($class)) { // Add input only if the class is not "blank"
                                echo '<input type="text" class="crossword-input" data-letter="' . $value . '" name="value[' . $row_number . '][' . $col_number . ']" value="">';
                                if (!empty($superscript_num)) {
                                    echo '<sup>' . $superscript_num . '</sup>'; // Display superscript number
                                }
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
                }
                ?>
            </form>
        </table>
    </div>
    </div>
    </div>
    <div class="col-md-7">
    <div class="clue-section">
        <?php
     
      // Fetch across clues
      $sql_across = "SELECT DISTINCT c.clue_text, c.superscript
      FROM clues c 
      INNER JOIN crosswords cr ON c.crossword_id = cr.id 
      WHERE cr.id = ? AND c.category = 'across'";
      $stmt_across = $conn->prepare($sql_across);
      $stmt_across->bind_param("i", $crossword_id);
      $stmt_across->execute();
      $result_across = $stmt_across->get_result();
      
      // Fetch down clues
      $sql_down = "SELECT DISTINCT c.clue_text, c.superscript
      FROM clues c 
      INNER JOIN crosswords cr ON c.crossword_id = cr.id 
      WHERE cr.id = ? AND c.category = 'down'";
      $stmt_down = $conn->prepare($sql_down);
      $stmt_down->bind_param("i", $crossword_id);
      $stmt_down->execute();
      $result_down = $stmt_down->get_result();
      
      // Display errors if any
      if (!$result_across || !$result_down) {
          echo "Error: " . $conn->error;
      }
      
      // Close statements and connection
      $stmt_across->close();
      $stmt_down->close();
      $conn->close();
      
      // Display the clues
      echo "<div class=\"clue-section\">";
      echo "<aside id=\"Clues\">";
      echo "<div class=\"clue-column\">";
      echo "<h2>Across</h2>";
      echo "<ul class=\"clue-list\">";
      if ($result_across->num_rows > 0) {
          while ($row_across = $result_across->fetch_assoc()) {
              echo "<li>" . $row_across['superscript'] . ". " . $row_across['clue_text'] . "</li>";
          }
      } else {
          echo "<li>No across clues found.</li>";
      }
      echo "</ul>";
      
      // Display down clues
      echo "<div class=\"clue-column\">";
      echo "<h2>Down</h2>";
      echo "<ul class=\"clue-list\">";
      if ($result_down->num_rows > 0) {
          while ($row_down = $result_down->fetch_assoc()) {
              echo "<li>" . $row_down['superscript'] . ". " . $row_down['clue_text'] . "</li>";
          }
      } else {
          echo "<li>No down clues found.</li>";
      }
      echo "</ul></div></div></aside>";
      ?> 
      

<!-- Pagination links/buttons -->

            <div class="col-md-6">
            <div class='pagination mb-5 text-center'>
    <?php
    if ($page > 1) {
        $prev_page = $page - 1;
        echo "<a href='?page=$prev_page' class='btn btn-primary'>Previous</a>"; // Link to previous page
    }
    echo "<span class='current-page'> $page</span>"; // Current page
    if ($page < $total_pages) {
        $next_page = $page + 1;
        echo "<a href='?page=$next_page' class='btn btn-primary'>Next</a>"; // Link to next page
    }
    ?>
</div>

</div>
</div>


            <div class="col-md-6">
                        <div id="crossButtons">
                            <button id="checkScoreBtn">Check Score</button>
                            <button id="showErrorsBtn">Show Errors</button>
                            <button id="resetButton">Reset Puzzle</button>


                        </div>
                        </div>  
                    </div>
                        


    </section>

    <footer>
        <nav id="bottom">
            <ul>
                <li><a href="#"><img id="fbicon" src="pc_fbicon.png" alt="" />facebook</a></li>
                <li><a href="#"><img id="twittericon" src="pc_twittericon.png" alt="" />twitter</a></li>
                <li><a href="#">Publisher's Desk</a></li>
            </ul>
            <ul>
                <li><a href="#">News</a></li>
                <li><a href="#">Sports</a></li>
                <li><a href="#">Weather</a></li>
            </ul>
            <ul>
                <li><a href="#">Puzzles and Games</a></li>
                <li><a href="#">Editorials</a></li>
                <li><a href="#">Classifieds</a></li>
            </ul>
            <ul>
                <li><a href="#">Archives</a></li>
                <li><a href="#">Customer Service</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </nav>
        <p>The Star NewsPaper &copy; 2024 All Rights Reserved</p>
    </footer>
    <script src="pc_cword.js"></script>
   <!-- JS, Popper.js, and Bootstrap bundle (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>