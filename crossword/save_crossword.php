<?php
require_once 'Connection.php'; // Assuming Connection.php contains the database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    $input_id = $_POST["input_id"];
    $letter = $_POST["letter"];
    $data_right = $_POST["data_right"];
    $data_left = $_POST["data_left"];
    $data_down = $_POST["data_down"];
    $data_up = $_POST["data_up"];
    $data_clue_a = $_POST["data_clue_a"];
    $data_clue_d = $_POST["data_clue_d"];
    $sup = $_POST["sup"];

    // Insert data into database
    $sql = "INSERT INTO crossword_puzzle (input_id, letter, data_right, data_left, data_down, data_up, data_clue_a, data_clue_d, sup) 
            VALUES ('$input_id', '$letter', '$data_right', '$data_left', '$data_down', '$data_up', '$data_clue_a', '$data_clue_d', '$sup')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Record added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Redirect to the admin interface if the form is not submitted
    header("Location: admin_interface.html");
    exit();
}
?>
