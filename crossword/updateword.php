<?php
require_once 'Connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Retrieve form data
    $id = $_POST['id'];
    $category = $_POST['category'];
    $clue_text = $_POST['clue_text'];

    // Update the clue in the database
    $sql = "UPDATE word SET category=?, clue_text=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $category, $clue_text, $id);
    $stmt->execute();

    // Redirect back to the index.php page after updating
    header("Location: index2.php?success=clue_updated");
    exit();
}
?>
