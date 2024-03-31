<?php
// Connect to the database
include "Connection.php";

// Check if the POST data is set
if(isset($_POST["crossword_id"])) {
    // Get the id of the crossword grid from the POST request
    $crossword_id = $_POST["crossword_id"];

    // Delete all cells associated with the crossword grid
    $sql_delete_cells = "DELETE FROM cword_cells WHERE crossword_id = ?";
    $stmt_delete_cells = $conn->prepare($sql_delete_cells);
    $stmt_delete_cells->bind_param("i", $crossword_id);
    if(!$stmt_delete_cells->execute()) {
        echo "Error deleting cells: " . $conn->error;
        exit(); // Terminate execution
    }

    // Delete the crossword grid itself
    $sql_delete_grid = "DELETE FROM crosswords WHERE id = ?";
    $stmt_delete_grid = $conn->prepare($sql_delete_grid);
    $stmt_delete_grid->bind_param("i", $crossword_id);
    if(!$stmt_delete_grid->execute()) {
        echo "Error deleting grid: " . $conn->error;
        exit(); // Terminate execution
    }

    // Redirect back to wherever you need to go after deletion
    header("Location: index3.php");
    exit();
} else {
    echo "Crossword ID not provided.";
}

// Your existing code for displaying the crossword grid goes here
// Ensure that you include the code to display the form containing the hidden input field for crossword_id
?>
