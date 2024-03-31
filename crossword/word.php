<!DOCTYPE html>
<html lang="en">
<head>
   <title>Daily Crossword</title>
   <meta charset="utf-8" />
   <link href="pc_styles.css" rel="stylesheet" />
   <link href="word.css" rel="stylesheet" />
  
</head>

<body>
   <header>
      <h1>The <b>Star</b> NewsPaper</h1>
      <nav id="top">
         <ul>
            <li><a href="#">news</a></li>
            <li><a href="#">sports</a></li>
            <li><a href="#">weather</a></li>
            <li><a href="#">entertainment</a></li>
            <li><a href="#">classifieds</a></li>
            <li><a href="Adminlogin.php">Logout</a></li>
         </ul>
      </nav>
   </header>

   <aside id="Clues">
      <h1>Clues</h1>
      <div>
         
         <section>
            
            <h2></h2>
            <ol>
               <?php
               require_once 'Connection.php';
               // Fetch 'across' clues from the database
               $sql = "SELECT SUBSTRING_INDEX(clue_text, ':', -1) AS clue_text FROM word WHERE category = 'Across'";
               $result = $conn->query($sql);

               if ($result->num_rows > 0) {
                  // Loop through each row in the result set
                  while ($row = $result->fetch_assoc()) {
                     // Display the clue text as a list item
                     echo '<li>' . htmlspecialchars($row['clue_text']) . '</li>';
                  }
               }
               ?>
            </ol>
         </section>

         <section>
            <h2></h2>
            <ol>
               <?php
               // Fetch 'down' clues from the database
               $sql = "SELECT SUBSTRING_INDEX(clue_text, ':', -1) AS clue_text FROM word WHERE category = 'Down'";
               $result = $conn->query($sql);

               if ($result->num_rows > 0) {
                  // Loop through each row in the result set
                  while ($row = $result->fetch_assoc()) {
                     // Display the clue text as a list item
                     echo '<li>' . htmlspecialchars($row['clue_text']) . '</li>';
                  }
               }
               ?>
            </ol>
         </section><script type="text/javascript">
var justDid='';
window.onload = function(){
    // words[i] correlates to clues[i]
    var words = ["DOG", "CAT", "BAT", "ELEPHANT", "KANGAROO"];
    var clues = ["Man's best friend", "Likes to chase mice", "Flying mammal", "Has a trunk", "Large marsupial"];

    // Create crossword object with the words and clues
    var cw = new Crossword(words, clues);

    // create the crossword grid (try to make it have a 1:1 width to height ratio in 10 tries)
    var tries = 10; 
    var grid = cw.getSquareGrid(tries);

    // report a problem with the words in the crossword
    if(grid == null){
        var bad_words = cw.getBadWords();
        var str = [];
        for(var i = 0; i < bad_words.length; i++){
            str.push(bad_words[i].word);
        }
        alert("Shoot! A grid could not be created with these words:\n" + str.join("\n"));
        return;
    }

    // turn the crossword grid into HTML
    var show_answers = true;
    document.getElementById("crossword").innerHTML = CrosswordUtils.toHtml(grid, show_answers);

    // Event listener for mouse down on crossword cells
    var isMouseDown = false;
    var isHighlighted;
    var crosswordCells = document.querySelectorAll("#our_table td");
    crosswordCells.forEach(function(cell) {
      cell.addEventListener("mousedown", function() {
        isMouseDown = true;
        cell.classList.toggle("highlighted");
        isHighlighted = cell.classList.contains("highlighted");
        return false; // prevent text selection
      });
    });

    // Event listener for mouse over on crossword cells
    crosswordCells.forEach(function(cell) {
      cell.addEventListener("mouseover", function() {
        if (isMouseDown) {
          cell.classList.toggle("highlighted", isHighlighted);
        }
      });
    });

    // Event listener for mouse up anywhere on the document
    document.addEventListener("mouseup", function() {
      isMouseDown = false;
    });

    // Event listener for check button click
    document.getElementById("checkButton").addEventListener("click", function() {
      var highlightedWords = getHighlightedWords();
      checkWords(highlightedWords);
    });

    // Function to get all highlighted words
    function getHighlightedWords() {
      var highlightedWords = [];
      var highlightedCells = document.querySelectorAll("#our_table td.highlighted");
      highlightedCells.forEach(function(cell) {
        highlightedWords.push(cell.textContent);
      });
      return highlightedWords;
    }

    // Function to check if the highlighted words match any of the correct words
    function checkWords(words) {
      var correctWords = ["DOG", "CAT", "BAT", "ELEPHANT", "KANGAROO"]; // List of correct words
      var correctHighlightedWords = words.filter(function(word) {
        return correctWords.includes(word);
      });
      if (correctHighlightedWords.length > 0) {
        // Display a popup indicating the correct highlighted words
        alert('CORRECT: ' + correctHighlightedWords.join(', '));
      } else {
        alert('No correct words highlighted.');
      }
    }
};
</script>

<button id="checkButton">Check</button>
<div id="crossword"></div>

      </div>
   </aside>
   

   <footer>
      <nav id="bottom">
         <ul>
            <li><a href="#"><img id="fbicon" src="pc_fbicon.png" alt="" />facebook</a></li>
            <li><a href="#"><img id="twittericon" src="pc_twittericon.png" alt="" />twitter</a></li>
            <li><a href="#">Publisher's Desk</a></li>
         </ul>
         <ul>
            <li><a href="#">News</a></li>
            <li><a href="#">Sports</a></li>
            <li><a href="#">Weather</a></li>
         </ul>
         <ul>
            <li><a href="#">Puzzles and Games</a></li>
            <li><a href="#">Editorials</a></li>
            <li><a href="#">Classifieds</a></li>
         </ul>
         <ul>
            <li><a href="#">Archives</a></li>
            <li><a href="#">Customer Service</a></li>
            <li><a href="#">Contact Us</a></li>
         </ul>
      </nav>
      <p>The Star NewsPaper &copy; 2024 All Rights Reserved</p>
   </footer>

   <div id="crossword"></div>
   

   <script src="word.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
      var justDid = '';
      $(function () {
         var isMouseDown = false,
            isHighlighted;
         $("table td")
            .mousedown(function () {
               isMouseDown = true;
               $(this).toggleClass("highlighted");
               isHighlighted = $(this).hasClass("highlighted");
               if (isHighlighted) {
                  justDid += $(this).text();
               }
               return false; // prevent text selection
            })
            .mouseover(function () {
               if (isMouseDown) {
                  $(this).toggleClass("highlighted", isHighlighted);
                  if (isHighlighted) {
                     justDid += $(this).text();
                  }
               }
            });

         $(document).mouseup(function () {
            isMouseDown = false;
            console.log("Whole : " + encodeURIComponent(justDid.replace(/(\r\n|\n|\r)/gm, "")));
         });
      });
   </script>
</body>
</html>
