<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Crossword Puzzles</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            border: 1px solid #ccc;
            width: 30px;
            height: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Crossword Puzzle</h1>
        <table>
            <?php
            require_once 'Connection.php'; // Assuming Connection.php contains the database connection

            $column_number = 0; // Initialize $column_number variable

            // Retrieve puzzle from the database
            $sql = "SELECT * FROM crossword_puzzles WHERE puzzle_id = 1"; // Assuming you're retrieving a specific puzzle by its ID
            $result = $conn->query($sql);

            // Display puzzle
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $row_number = $row["row_number"];
                $column_number = $row["column_number"];
                $puzzle_data = isset($row["puzzle_data"]) ? $row["puzzle_data"] : ''; // Check if puzzle_data is set

                if (!empty($puzzle_data)) {
                    // Convert puzzle_data string to a 2D array
                    $puzzle_array = str_split($puzzle_data, $column_number);

                    // Loop through the 2D array to create table cells
                    for ($i = 0; $i < $row_number; $i++) {
                        echo '<tr>';
                        for ($j = 0; $j < $column_number; $j++) {
                            echo '<td>' . (isset($puzzle_array[$i][$j]) ? $puzzle_array[$i][$j] : '') . '</td>'; // Check if array element is set
                        }
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="' . $column_number . '">No puzzle data found.</td></tr>';
                }
            } else {
                echo '<tr><td colspan="' . $column_number . '">No puzzle found.</td></tr>';
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
