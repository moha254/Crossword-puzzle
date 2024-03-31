<?php
require_once 'Connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cells'])) {
    // Retrieve form data
    $crossword_id = $_POST['crossword_id'];
    $updated_cells = $_POST['updated_cells']; // Assuming this is an array containing the updated cell data

    // Loop through the updated cells and update them in the database
    foreach ($updated_cells as $cell) {
        $id = $cell['id'];
        $value = $cell['value'];
        $superscript = $cell['superscript'];

        // Update the crossword cell in the database
        $sql = "UPDATE cword_cells SET value=?, superscript_num=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $value, $superscript, $id);
        $stmt->execute();
    }

    // Redirect back to the original page after updating
    header("Location: index3.php?success=cells_updated&crossword_id=$crossword_id");
    exit();
} else {
    // Redirect back to the original page with an error message if the update button wasn't clicked
    header("Location: index3.php?error=update_failed");
    exit();
}
?>