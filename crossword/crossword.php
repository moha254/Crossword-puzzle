<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="mycss.css"> 
	<link rel="stylesheet" href="responsive.css"> 
    <title>Admin Panel</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    width: 80%;
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
}

input[type="text"] {
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}
</style>
    </style>
</head>
<body>
    
<header role="banner">
  <h1>Admin Panel</h1>
  <ul class="utilities">
    <br>
    <li class="users"><a href="#">My Account</a></li>
    <a href="scholarpage.php" style="text-decoration: none; color: #212529;">
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

<body>


<div class="container">
    <h2>Admin Interface - Crossword Puzzle</h2>
    <form action="save_crossword.php" method="post">
        <label for="input_id">Input ID:</label>
        <input type="text" id="input_id" name="input_id" required>
        
        <label for="letter">Letter:</label>
        <input type="text" id="letter" name="letter" maxlength="1" required>
        
        <label for="data_right">Data Right:</label>
        <input type="text" id="data_right" name="data_right">
        
        <label for="data_left">Data Left:</label>
        <input type="text" id="data_left" name="data_left">
        
        <label for="data_down">Data Down:</label>
        <input type="text" id="data_down" name="data_down">
        
        <label for="data_up">Data Up:</label>
        <input type="text" id="data_up" name="data_up">
        
        <label for="data_clue_a">Data Clue A:</label>
        <input type="text" id="data_clue_a" name="data_clue_a">
        
        <label for="data_clue_d">Data Clue D:</label>
        <input type="text" id="data_clue_d" name="data_clue_d">
        
        <label for="sup">Sup:</label>
        <input type="number" id="sup" name="sup">
        
        <button type="submit">Save</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>