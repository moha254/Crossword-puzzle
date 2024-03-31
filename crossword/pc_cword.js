document.addEventListener('DOMContentLoaded', function () {
    const cells = document.querySelectorAll('input.crossword-input');
    
    cells.forEach(function (cell, index) {
        cell.addEventListener('input', function () {
            if (this.value.length === 1) {
                if (index < cells.length - 1) {
                    cells[index + 1].focus();
                }
            }
        });

        cell.addEventListener('keydown', function (event) {
            if (event.keyCode === 8 && this.value === '') {
                if (index > 0) {
                    cells[index - 1].focus();
                }
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const cells = document.querySelectorAll('input.super-input');
    
    cells.forEach(function (cell, index) {
        cell.addEventListener('input', function () {
            if (this.value.length === 1) {
                if (index < cells.length - 1) {
                    cells[index + 1].focus();
                }
            }
        });

        cell.addEventListener('keydown', function (event) {
            if (event.keyCode === 8 && this.value === '') {
                if (index > 0) {
                    cells[index - 1].focus();
                }
            }
        });
    });
});


document.getElementById('checkScoreBtn').addEventListener('click', function() {
    // Get all crossword input elements
    var inputs = document.querySelectorAll('.crossword-input');
    
    // Initialize variables for score calculation
    var score = 0;

    // Loop through each input element
    inputs.forEach(function(input) {
        var inputValue = input.value.toUpperCase();
        var correctValue = input.getAttribute('data-letter');

        // Check if input value matches the correct letter
        if (inputValue === correctValue) {
            score++;
        }
    });

    // Update score display
    document.getElementById('currentScore').textContent = 'Score: ' + score;

    // Update high score if necessary
    var highScore = parseInt(document.getElementById('highScore').textContent.split(': ')[1]);
    if (score > highScore) {
        document.getElementById('highScore').textContent = 'High Score: ' + score;
    }
});

document.getElementById('showErrorsBtn').addEventListener('click', function() {
    // Get all crossword input elements
    var inputs = document.querySelectorAll('.crossword-input');
    
    // Loop through each input element
    inputs.forEach(function(input) {
        var inputValue = input.value.toUpperCase();
        var correctValue = input.getAttribute('data-letter');

        // Check if input value matches the correct letter
        if(inputValue !== ""){
            if (inputValue === correctValue) {
                input.classList.remove('error');
            } else {
                input.classList.add('error');
            }
        }
    });
});

document.getElementById('resetButton').addEventListener('click', function() {
    // Get all crossword input elements
    var inputs = document.querySelectorAll('.crossword-input');
    
    // Loop through each input element
    inputs.forEach(function(input) {
        // Clear input value
        input.value = '';
        
        // Remove error class
        input.classList.remove('error');
    });
    
    // Reset score display
    document.getElementById('currentScore').textContent = 'Score: 0';
    document.getElementById('highScore').textContent = 'High Score: 0';
});


