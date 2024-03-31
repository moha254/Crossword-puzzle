
<?php
// Connect to the database
include "Connection.php";

// Get the id of the link from the POST request
$id = $_POST["id"];

// Delete the link with the given id
$sql = "DELETE FROM word WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

// Redirect back to the links page
header("Location: index2.php");
exit();
?>