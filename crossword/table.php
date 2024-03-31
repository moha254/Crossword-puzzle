<?php
// Include the database connection file
require_once 'Connection.php';

// Function to handle post submission of clues
function handleClueSubmission($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $category = $_POST['category']; // Fetching category (e.g., 'Across' or 'Down')
        $clue_text = $_POST['clue_text'];
        $superscript = $_POST['superscript'];
        $direction = $_POST['direction']; // across or down

        // Retrieve the last crossword_id from the crosswords table
        $sql = "SELECT id FROM crosswords ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $crossword_id = $row['id'];

            // Prepare the SQL statement to insert into clues table
            $sql = "INSERT INTO clues (category, clue_text, superscript, direction, crossword_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            // Bind the parameters
            $stmt->bind_param("sssii", $category, $clue_text, $superscript, $direction, $crossword_id);

            // Execute the statement
            if ($stmt->execute()) {
                return $crossword_id; // Return the crossword_id
            }
        }
        return false; // Clue insertion failed
    }
}

// Handle clue submission
$crossword_id = handleClueSubmission($conn);
if (!$crossword_id) {
    throw new Exception("Clue insertion failed");
}

// Redirect to the crossword page with the crossword_id
header("Location: createcrossword.php?crossword_id=$crossword_id");
exit();
?>
