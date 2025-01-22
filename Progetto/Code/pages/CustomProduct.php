<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Recupera l'email dell'utente loggato
/*$user = getLoggedUser(); // Ottiene i dati dell'utente loggato
$email = $user['Email'];*/

include("../includes/header.php");
?>
<style>
    #grid {
        display: grid;
        grid-template-columns: repeat(20, 1fr);
        gap: 5px;
        max-width: 100%;
    }

    .grid-cell {
        width: 100%;
        aspect-ratio: 1;
        background-color: #ffffff; /* Colore di base bianco */
        border: 1px solid #ced4da;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        user-select: none;
    }

    .grid-cell.selected {
        background-color: #007bff; /* Colore blu per le celle selezionate */
        color: white;
    }

    .grid-cell.active {
        background-color: #28a745; /* Colore verde per altre celle attive */
        color: white;
    }

    #submitBtn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }
</style>

<body>
<link rel="stylesheet" href="../CSS/styles.css">
<div class="container my-4">
    <h1 class="text-center">Crea il tuo prodotto personalizzato!</h1>
    <p class="text-center">Seleziona il materiale e il colore del tuo pezzo personalizzato, dagli una forma e dacci una descrizione per darci una linea guida da seguire</p>
    
    <!-- Selezione Materiale -->
    <div class="mb-3">
        <label for="materialSelect" class="form-label">Seleziona Materiale</label>
        <select id="materialSelect" class="form-select">
            <option value="material1">Materiale 1</option>
            <option value="material2">Materiale 2</option>
            <option value="material3">Materiale 3</option>
        </select>
    </div>

    <!-- Selezione Colore -->
    <div class="mb-3">
        <label for="colorSelect" class="form-label">Seleziona Colore</label>
        <select id="colorSelect" class="form-select">
            <option value="#007bff">Blu</option>
            <option value="#ff0000">Rosso</option>
            <option value="#28a745">Verde</option>
            <option value="#ffc107">Giallo</option>
        </select>
    </div>

    <div id="grid" class="mx-auto mb-3"></div>
    <div class="mb-3">
        <label for="description" class="form-label">Descrizione</label>
        <textarea id="description" class="form-control" rows="3">

        </textarea>
    </div>
    <button id="submitBtn" class="btn btn-primary" disabled>Invia</button>
</div>

<!-- JavaScript -->
<script>
    const gridSize = 20; // 10x10
    const grid = document.getElementById('grid');
    const submitBtn = document.getElementById('submitBtn');
    const colorSelect = document.getElementById('colorSelect');
    let selectedCells = [];

    // Funzione per cambiare il colore delle celle in base alla selezione
    function changeCellColor(color) {
        const cells = document.querySelectorAll('.grid-cell');
        clearGrid(); // Resetta il colore delle celle
    }

    // Aggiungi un listener per la selezione del colore
    colorSelect.addEventListener('change', (e) => {
        const selectedColor = e.target.value;
        changeCellColor(selectedColor); // Cambia il colore delle celle in base alla selezione
    });

    // Creazione della griglia dinamica
    for (let row = 0; row < gridSize; row++) {
        for (let col = 0; col < gridSize; col++) {
            const cell = document.createElement('div');
            cell.className = 'grid-cell';
            cell.dataset.row = row;
            cell.dataset.col = col;
            grid.appendChild(cell);
        }
    }

    function clearGrid() {
        const cells = document.querySelectorAll('.grid-cell');
        cells.forEach(cell => {
            cell.classList.remove('selected');
            cell.style.backgroundColor = '#ffffff';
        });
        selectedCells = [];
    }

    // Funzione per abilitare/disabilitare il bottone
    function toggleSubmitButton() {
        if (areCellsAdjacent(selectedCells)) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // Funzione per verificare se le celle selezionate sono adiacenti tramite DFS
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

    // Gestione del click sulle celle
    grid.addEventListener('click', (e) => {
        const cell = e.target;
        if (!cell.classList.contains('grid-cell')) return;

        const row = parseInt(cell.dataset.row);
        const col = parseInt(cell.dataset.col);
        const selectedColor = colorSelect.value; // Ottiene il colore selezionato dalla tendina

        if (cell.classList.contains('selected')) {
            cell.classList.remove('selected');
            selectedCells = selectedCells.filter(([r, c]) => r !== row || c !== col);
            cell.style.backgroundColor = '#ffffff'; // Reset al colore di base
        } else {
            cell.classList.add('selected');
            selectedCells.push([row, col]);
            cell.style.backgroundColor = selectedColor; // Colore della cella selezionata
        }

        toggleSubmitButton();
    });
</script>
</body>

<?php
include("../includes/footer.php");
?>
