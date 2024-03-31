<?php
// Include the database connection file
require_once 'Connection.php';

// Function to handle post submission of clues
function handleClueSubmission($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $category = $_POST['category']; // Fetching category (e.g., 'Across' or 'Down')
        $clue_text = $_POST['clue_text'];

        // Prepare the SQL statement
        $sql = "CALL InsertCrosswordAndClue(?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $category, $clue_text);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Clue insertion successful
        } else {
            return false; // Clue insertion failed
        }
    }
}











// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the crossword grid values from the form
    $crossword_values = $_POST['value'];
    $superscript_nums = $_POST['superscript_num'];

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Insert the crossword into the crosswords table
        $insert_crossword_sql = "INSERT INTO crosswords () VALUES ()";
        $conn->query($insert_crossword_sql);

        // Fetch the auto-generated crossword ID
        $crossword_id = $conn->insert_id;

        // Iterate through the grid values and insert them into the database
        foreach ($crossword_values as $row_index => $columns) {
            foreach ($columns as $column_index => $value) {
                // Retrieve the superscript number for this cell
                $superscript_num = $superscript_nums[$row_index][$column_index];

                // Prepare and execute the SQL statement to insert new crossword cells
                $insert_cell_sql = "INSERT INTO cword_cells (crossword_id, row_number, col_number, value, superscript_num) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_cell_sql);

                $stmt->bind_param("iiisi", $crossword_id, $row_index, $column_index, $value, $superscript_num);
                $stmt->execute();

                // Check if the insertion was successful
                if ($stmt->affected_rows <= 0) {
                    throw new Exception("Cell insertion failed");
                }
            }
        }

        // Commit the transaction
        $conn->commit();

        // Calculate score
        $score = 0;

        // Fetch correct values from the database for comparison
        $select_correct_values_sql = "SELECT value, superscript_num FROM cword_cells WHERE crossword_id = ?";
        $stmt = $conn->prepare($select_correct_values_sql);
        $stmt->bind_param("i", $crossword_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Compare user inputs with correct values
        while ($row = $result->fetch_assoc()) {
            $correct_value = $row['value'];
            $correct_superscript_num = $row['superscript_num'];
            
            // Check if user input matches correct value
            if (isset($crossword_values[$row_index][$column_index]) &&
                $crossword_values[$row_index][$column_index] === $correct_value &&
                $superscript_nums[$row_index][$column_index] === $correct_superscript_num) {
                $score++;
            }
        }

        // Handle clue submission
        if (!handleClueSubmission($conn)) {
            throw new Exception("Clue insertion failed");
        }

        // Redirect after successful submission
        header("Location: createcrossword.php");
        exit();
        
    } catch (Exception $e) {
        // Rollback the transaction
        $conn->rollback();
        
        // Redirect back to the interface with error message
        header("Location: creategrid.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
