<?php
require_once 'Connection.php';

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
    while($row = $result->fetch_assoc()) {
        $crosswordData[] = $row;
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
   <title>Manage Table</title>
   <meta charset="utf-8" />
   <link href="pc_styles.css" rel="stylesheet"  />
   <link href="pc_cword.css" rel="stylesheet" />
</head>

<body>
 
   <article>
   <div class="crossword-container">
   <table id="crossword">
     
   <?php
// Define the number of rows and columns
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 11;
$columns = isset($_POST['columns']) ? intval($_POST['columns']) : 9;
$sup_row = isset($_POST['sup_row']) ? intval($_POST['sup_row']) : 1;
$sup_column = isset($_POST['sup_column']) ? intval($_POST['sup_column']) : 2;
$sup_no = isset($_POST['sup_no']) ? intval($_POST['sup_no']) : 2;
$blank_row = isset($_POST['blank_row']) ? intval($_POST['blank_row']) : 3;
$blank_column = isset($_POST['blank_column']) ? intval($_POST['blank_column']) : 2;

echo "<form method='post'>";
echo "<label for='rows'>Rows:</label>";
echo "<input type='number' name='rows' value='$rows'>";
echo "<label for='columns'>Columns:</label>";
echo "<input type='number' name='columns' value='$columns'><br>";
echo "<label for='sup_row'>Sup Row:</label>";
echo "<input type='number' name='sup_row' value='$sup_row'>";
echo "<label for='sup_column'>Sup Column:</label>";
echo "<input type='number' name='sup_column' value='$sup_column'><br>";
echo "<label for='sup_no'>Sup No:</label>";
echo "<input type='number' name='sup_no' value='$sup_no'><br>";
echo "<label for='blank_row'>Blank Row:</label>";
echo "<input type='number' name='blank_row' value='$blank_row'>";
echo "<label for='blank_column'>Blank Column:</label>";
echo "<input type='number' name='blank_column' value='$blank_column'><br>";
echo "<input type='submit' value='Update'>";
echo "</form>";

echo "<div class='crossword-container'>";
echo "<table id='crossword'>";
for ($row_index = 0; $row_index < $rows; $row_index++) {
    echo "<tr>";
    for ($col_index = 0; $col_index < $columns; $col_index++) {
        // Determine if the current cell should have the blank class
        $is_sup = ($row_index == $sup_row && $col_index == $sup_column);
        $is_blank = ($row_index == $blank_row && $col_index == $blank_column);

        // Generate input fields or leave cell blank
        if ($is_sup) {
            // Place sup in desired cell
            echo "<td style='width: 50px;'>$sup_no</td>";

        } elseif ($is_blank) {
            // Place blank in desired cell
            echo "<td style='width: 50px;' class=\"blank\"></td>";
        } else {
            // Example: Generate input field with appropriate attributes
            echo "<td style='width: 50px;'><input id=\"input_{$row_index}_{$col_index}\" type=\"text\" size=\"1\" maxlength=\"1\" class=\"crossword-input\"></td>";
        }
    }
    echo "</tr>";
}

echo "</table>";
?>

</table>

      <div id="crossButtons">
      <button id="checkScoreBtn">Check Score</button>
      <button id="showErrorsBtn">Show Errors</button>
      <button id="resetButton">Reset Puzzle</button>
      <a href="word.php"><button id="NextBtn">Next Puzzle</button></a>
      </div>

   </article>
   <aside id="Clues">
      <h1>Clues</h1>
      <div>
      <?php

      // Check if the 'across' query was successful
      if ($result_across->num_rows > 0) {
          echo "<h2>Across</h2><ul>";
          while ($row = $result_across->fetch_assoc()) {
              echo "<li>" . $row['clue_text'] . "</li>";
          }
          echo "</ul>";
      } else {
          echo "No across clues found.";
      }

      // Check if the 'down' query was successful
      if ($result_down->num_rows > 0) {
          echo "<h2>Down</h2><ul>";
          while ($row = $result_down->fetch_assoc()) {
              echo "<li>" . $row['clue_text'] . "</li>";
          }
          echo "</ul>";
      } else {
          echo "No down clues found.";
      }
      ?>
      </div>
   </aside>
   <script src="pc_cword.js"></script>
</body>
</html>