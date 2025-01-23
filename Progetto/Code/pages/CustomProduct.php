<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

include("../includes/header.php");
?>
<style>
    .color-palette {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .color-option {
        width: 40px;
        height: 40px;
        border: 2px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .color-option.selected {
        transform: scale(1.1);
        border-color: #000;
        box-shadow: 0 0 5px rgba(0,0,0,0.3);
    }

    #grid {
        display: grid;
        grid-template-columns: repeat(20, 1fr);
        gap: 5px;
        max-width: 100%;
    }

    .grid-cell {
        width: 100%;
        aspect-ratio: 1;
        background-color: #ffffff;
        border: 1px solid #ced4da;
        cursor: pointer;
    }

    #submitBtn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .container-wrapper {
        display: flex;
        gap: 30px;
        align-items: start;
    }

    .controls-section {
        min-width: 200px;
        position: sticky;
        top: 20px;
    }

    #description:placeholder-shown {
        border-color: #dc3545;
        background-color: #fff5f5;
    }

    .eraser {
        background-color: #ffffff;
        border: 2px solid #000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .eraser.selected {
        background-color: #f8f9fa;
    }
</style>

<body>
<link rel="stylesheet" href="../CSS/styles.css">
<div class="container my-4">
    <h1 class="text-center">Crea il tuo prodotto personalizzato!</h1>
    <p class="text-center">Seleziona il materiale e il colore del tuo pezzo personalizzato, dagli una forma e dacci una descrizione per darci una linea guida da seguire</p>
    
    <div class="container-wrapper">
        <div class="controls-section">
            <!-- Palette colori -->
            <div class="mb-4">
                <h5>Seleziona Colore</h5>
                <div class="color-palette" id="colorPalette">
                    <div class="color-option" style="background-color: #007bff" data-color="#007bff"></div>
                    <div class="color-option" style="background-color: #dc3545" data-color="#dc3545"></div>
                    <div class="color-option" style="background-color: #28a745" data-color="#28a745"></div>
                    <div class="color-option" style="background-color: #ffc107" data-color="#ffc107"></div>
                    <div class="color-option" style="background-color: #6f42c1" data-color="#6f42c1"></div>
                    <div class="color-option" style="background-color: #fd7e14" data-color="#fd7e14"></div>
                    <div class="color-option" style="background-color: #e83e8c" data-color="#e83e8c"></div>
                    <div class="color-option" style="background-color: #17a2b8" data-color="#17a2b8"></div>
                    <div class="color-option eraser" data-color="#ffffff">🧽</div>
                </div>
            </div>

            <!-- Selezione Materiale -->
            <div class="mb-4">
                <h5>Seleziona Materiale</h5>
                <select id="materialSelect" class="form-select">
                    <option value="material1">Materiale 1</option>
                    <option value="material2">Materiale 2</option>
                    <option value="material3">Materiale 3</option>
                </select>
            </div>

            <!-- Descrizione -->
            <div class="mb-4">
                <h5>Descrizione</h5>
                <textarea id="description" class="form-control" rows="4" placeholder="Descrivi il tuo prodotto (obbligatorio)"></textarea>
            </div>

            <button id="submitBtn" class="btn btn-primary w-100" disabled>Invia</button>
        </div>

        <!-- Griglia -->
        <div id="grid" class="flex-grow-1"></div>
    </div>
</div>

<!-- JavaScript -->
<script>
    const gridSize = 20;
    const grid = document.getElementById('grid');
    const submitBtn = document.getElementById('submitBtn');
    const colorOptions = document.querySelectorAll('.color-option');
    const description = document.getElementById('description');
    let selectedColor = '#007bff';
    let selectedCells = [];

    // Inizializza la palette
    colorOptions[0].classList.add('selected');
    
    // Gestione selezione colore/gomma
    colorOptions.forEach(option => {
        option.addEventListener('click', () => {
            colorOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            selectedColor = option.dataset.color;
        });
    });

    // Crea griglia
    for (let row = 0; row < gridSize; row++) {
        for (let col = 0; col < gridSize; col++) {
            const cell = document.createElement('div');
            cell.className = 'grid-cell';
            cell.dataset.row = row;
            cell.dataset.col = col;
            grid.appendChild(cell);
        }
    }

    // Gestione click celle
    grid.addEventListener('click', (e) => {
        const cell = e.target;
        if (!cell.classList.contains('grid-cell')) return;

        const row = parseInt(cell.dataset.row);
        const col = parseInt(cell.dataset.col);

        if (selectedColor === '#ffffff') {
            // Modalità gomma
            cell.style.backgroundColor = '#ffffff';
            selectedCells = selectedCells.filter(([r, c]) => r !== row || c !== col);
        } else {
            // Modalità colore
            if (cell.style.backgroundColor === selectedColor) {
                cell.style.backgroundColor = '#ffffff';
                selectedCells = selectedCells.filter(([r, c]) => r !== row || c !== col);
            } else {
                cell.style.backgroundColor = selectedColor;
                selectedCells.push([row, col]);
            }
        }

        toggleSubmitButton();
    });

    // Abilita/disabilita bottone
    function toggleSubmitButton() {
        const hasDescription = description.value.trim().length > 0;
        const hasDrawing = selectedCells.length > 0;
        const validShape = areCellsAdjacent(selectedCells);
        
        submitBtn.disabled = !(validShape && hasDescription && hasDrawing);
    }

    // Verifica celle adiacenti
    function areCellsAdjacent(cells) {
        if (cells.length < 2) return true;

        const visited = Array.from({ length: gridSize }, () => Array(gridSize).fill(false));
        const [startRow, startCol] = cells[0];
        dfs(startRow, startCol, visited, cells);

        for (let i = 0; i < cells.length; i++) {
            const [row, col] = cells[i];
            if (!visited[row][col]) {
                return false;
            }
        }

        return true;
    }

    function dfs(row, col, visited, cells) {
        if (row < 0 || col < 0 || row >= gridSize || col >= gridSize || visited[row][col]) {
            return;
        }

        visited[row][col] = true;

        if (!cells.some(([r, c]) => r === row && c === col)) {
            return;
        }

        dfs(row + 1, col, visited, cells);
        dfs(row - 1, col, visited, cells);
        dfs(row, col + 1, visited, cells);
        dfs(row, col - 1, visited, cells);
    }

    // Aggiungi listener per l'input della descrizione
    description.addEventListener('input', toggleSubmitButton);

    // Controllo iniziale
    toggleSubmitButton();
</script>
</body>

<?php
include("../includes/footer.php");
?>