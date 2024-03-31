<?php
require_once 'Connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category']; // fetching category (e.g., 'Across' or 'Down')
    $clue_text = $_POST['clue_text'];

    $sql = "INSERT INTO word (category, clue_text) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $category, $clue_text);

    if ($stmt->execute()) {
        header("Location: createword.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mycss.css"> 
	<link rel="stylesheet" href="responsive.css"> 
    <title>Create Clue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
/* Styles for the sections */
section {
    margin-bottom: 20px;
}

h2 {
    font-size: 1.5em;
    color: #333;
}

ol {
    list-style-type: decimal;
    margin-left: 20px;
}

li {
    margin-bottom: 5px;
}

/* Styles for the form */
form {
    margin-top: 20px;
    border: 1px solid #ccc;
    padding: 20px;
    width: 70%;
    margin-left: auto;
    margin-right: auto;
}

label {
    font-weight: bold;
}

textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}


    </style>
  
</head>
<body>
<header role="banner">
  <h1>Admin Panel</h1>
  <ul class="utilities">
    <br>
    <li class="users"><a href="#">My Account</a></li>
    <a href="pc_cword.php" style="text-decoration: none; color: #212529;">
    <button type="submit" name="Logout" style="background: none;color: #212529; border: none; cursor: pointer;font-size:26px;">
        Logout
    </button>
</a>
  </ul>
</header>

<nav role='navigation'>
  <ul class="main">
    <li class="dashboard"><a href="AdminPanel.php">Dashboard</a></li>
    <li class="edit"><a href="createcrossword.php">Crosswords</a></li>
    <li class="write"><a href="index.php">Website puzzle</a></li>
    <li class="edit"><a href="creategrid.php">Table Grid</a></li>
    <li class="write"><a href="index3.php">Crosswords Grid</a></li>
    <li class="edit"><a href="createword.php">CWords</a></li>
    <li class="write"><a href="index2.php">Word puzzle</a></li>
    <li class="comments"><a href="#">Ads</a></li>
    <li class="users"><a href="#">Manage Users</a></li>
  </ul>
</nav>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>create Word</title>
    <!-- Add any CSS links or stylesheets here -->
</head>
<body>
<section>
    <h2>Across</h2>
    <ol id="across">
        <?php
        // Fetch clue data from a database or any other source
        // For demonstration purposes, here we assume the clue data is fetched from a PHP array
        if (isset($_POST['across_clues']) && is_array($_POST['across_clues'])) {
            foreach ($_POST['across_clues'] as $clue) {
                echo '<li>' . htmlspecialchars($clue) . '</li>';
            }
        }
        ?>
    </ol>
</section>
<section>
    <h2>Down</h2>
    <ol id="down">
        <?php
        // Fetch clue data from a database or any other source
        // For demonstration purposes, here we assume the clue data is fetched from a PHP array
        if (isset($_POST['down_clues']) && is_array($_POST['down_clues'])) {
            foreach ($_POST['down_clues'] as $clue) {
                echo '<li>' . htmlspecialchars($clue) . '</li>';
            }
        }
        ?>
    </ol>
</section>

<!-- Form for the admin to provide the words -->
<!-- Form for the admin to provide the words -->
<form method="post">
    <h3>Provide Clues</h3>
    <div>
        <label for="category">Category:</label><br>
        <input type="text" name="category" required>
    </div>
    <div>
        <label for="clue_text">Clue Text:</label><br>
        <textarea name="clue_text" rows="5" cols="50"></textarea>
    </div>
    <div>
        <input type="submit" value="Submit">
    </div>
</form>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
