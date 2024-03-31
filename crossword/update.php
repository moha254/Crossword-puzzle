<?php
// Define the number of rows and columns
$rows = isset($_POST['rows']) ? $_POST['rows'] : 11;
$columns = isset($_POST['columns']) ? $_POST['columns'] : 9;
$sup_row = isset($_POST['sup_row']) ? $_POST['sup_row'] : 1;
$sup_column = isset($_POST['sup_column']) ? $_POST['sup_column'] : 2;
$blank_row = isset($_POST['blank_row']) ? $_POST['blank_row'] : 3;
$blank_column = isset($_POST['blank_column']) ? $_POST['blank_column'] : 2;

// Generate the content of the crossword table
for ($row_index = 0; $row_index < $rows; $row_index++) {
    echo "<tr>";
    for ($col_index = 0; $col_index < $columns; $col_index++) {
        // Determine if the current cell should have the blank class
        $is_sup = ($row_index == $sup_row && $col_index == $sup_column);
        $is_blank = ($row_index == $blank_row && $col_index == $blank_column);

        // Generate input fields or leave cell blank
        if ($is_sup) {
            // Place sup in desired cell
            echo "<td></td>";
        } elseif ($is_blank) {
            // Place blank in desired cell
            echo "<td class=\"blank\"></td>";
        } else {
            // Example: Generate input field with appropriate attributes
            echo "<td><input id=\"input_{$row_index}_{$col_index}\" type=\"text\" size=\"1\" maxlength=\"1\" class=\"crossword-input\"></td>";
        }
    }
    echo "</tr>";
}
?>
