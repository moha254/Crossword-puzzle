<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crossword Puzzle Generator</title>
    <style>
        .crossword-grid {
            border-collapse: collapse;
        }
        .crossword-cell {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .black-cell {
            background-color: #000;
        }
    </style>
</head>
<body>
    <h1>Crossword Puzzle Generator</h1>
    <table id="crossword-grid" class="crossword-grid">
        <!-- PHP Loop to generate initial grid -->
        <?php foreach ($cellData as $cell): ?>
            <?php if ($cell['column_index'] == 1): ?>
                <tr>
            <?php endif; ?>
            <td class="crossword-cell <?php echo $cell['is_black'] ? 'black-cell' : ''; ?>">
                <?php echo $cell['value']; ?>
                <?php if ($cell['superscript_number']): ?>
                    <sup><?php echo $cell['superscript_number']; ?></sup>
                <?php endif; ?>
            </td>
            <?php if ($cell['column_index'] == max(array_column($cellData, 'column_index'))): ?>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
    <br>
    <button onclick="addRow()">Add Row</button>
    <button onclick="addColumn()">Add Column</button>

    <script>
        function addRow() {
            var grid = document.getElementById('crossword-grid');
            var newRow = grid.insertRow();
            for (var i = 0; i < grid.rows[0].cells.length; i++) {
                var newCell = newRow.insertCell();
                newCell.className = 'crossword-cell';
            }
        }

        function addColumn() {
            var grid = document.getElementById('crossword-grid');
            for (var i = 0; i < grid.rows.length; i++) {
                var newCell = grid.rows[i].insertCell();
                newCell.className = 'crossword-cell';
            }
        }
    </script>
</body>
</html>
