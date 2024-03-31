<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_rows = $_POST['num_rows'];
    $num_columns = $_POST['num_columns'];
?>

<div class="crossword-container">
    <table id="crossword">
        <caption>Today's Crossword</caption>
        <tr>
            <td colspan="<?php echo $num_columns; ?>" id="highScore">High Score: 0</td>
        </tr>
        <tr>
            <td colspan="<?php echo $num_columns; ?>" id="currentScore">Score: 0</td>
        </tr>

        <form method="post" action="process_add_cells.php">
            <?php for ($row = 1; $row <= $num_rows; $row++) : ?>
                <tr>
                    <?php for ($col = 1; $col <= $num_columns; $col++) : ?>
                        <td class="crossword-cell">
                            <input type="text" class="crossword-input" data-letter="" name="value[<?php echo $row; ?>][<?php echo $col; ?>]" value="">
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
    </table>
</div>

<?php } ?>
