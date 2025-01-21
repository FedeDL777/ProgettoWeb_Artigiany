<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
/*if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}*/

// Recupera l'email dell'utente loggato
/*$user = getLoggedUser(); // Ottiene i dati dell'utente loggato
$email = $user['Email'];*/

include("../includes/header.php");
?>
 <style>
        #grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            /* 10 colonne */
            gap: 5px;
            /* Spaziatura tra le celle */
            max-width: 100%;
            /* Adattamento responsive */
        }

        .grid-cell {
            width: 100%;
            aspect-ratio: 1;
            /* Cella quadrata */
            background-color: #f8f9fa;
            /* Sfondo chiaro */
            border: 1px solid #ced4da;
            /* Bordo */
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            user-select: none;
        }

        .grid-cell.selected {
            background-color: #007bff;
            /* Colore blu per le celle selezionate */
            color: white;
        }

        .grid-cell.active {
            background-color: #28a745;
            /* Colore verde */
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
        <h1 class="text-center">Griglia con Selezione Adiacente</h1>
        <p class="text-center">Seleziona celle adiacenti e premi "Invia" se sono tutte adiacenti.</p>
        <div id="grid" class="mx-auto mb-3"></div>
        <button id="submitBtn" class="btn btn-primary" disabled>Invia</button>
    </div>

    <!-- JavaScript -->
    <script>
        const gridSize = 10; // 10x10
        const grid = document.getElementById('grid');
        const submitBtn = document.getElementById('submitBtn');
        let selectedCells = [];

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
// Funzione per abilitare/disabilitare il bottone
function toggleSubmitButton() {
    // Mostra il bottone solo se le celle selezionate sono adiacenti
    if (areCellsAdjacent(selectedCells)) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Funzione per verificare se le celle selezionate sono adiacenti tramite DFS
function areCellsAdjacent(cells) {
    if (cells.length < 2) return true; // Se c'è solo una cella, è automaticamente adiacente a se stessa

    // Crea una matrice per tracciare le celle visitate
    const visited = Array.from({ length: gridSize }, () => Array(gridSize).fill(false));
    
    // Seleziona la prima cella come punto di partenza per la DFS
    const [startRow, startCol] = cells[0];
    
    // Avvia la DFS a partire dalla prima cella
    dfs(startRow, startCol, visited, cells);

    // Controlla se tutte le celle selezionate sono state visitate
    for (let i = 0; i < cells.length; i++) {
        const [row, col] = cells[i];
        if (!visited[row][col]) {
            return false; // Se anche una cella non è stata visitata, non sono tutte adiacenti
        }
    }

    return true;
}

// Funzione DFS per esplorare le celle adiacenti
function dfs(row, col, visited, cells) {
    // Verifica se la cella è fuori dai limiti o già visitata
    if (row < 0 || col < 0 || row >= gridSize || col >= gridSize || visited[row][col]) {
        return;
    }

    // Segna la cella come visitata
    visited[row][col] = true;

    // Verifica se la cella è nella lista delle celle selezionate
    if (!cells.some(([r, c]) => r === row && c === col)) {
        return;
    }

    // Esplora le celle adiacenti (su, giù, sinistra, destra)
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

    // Aggiungi o rimuovi la cella dalla selezione
    if (cell.classList.contains('selected')) {
        cell.classList.remove('selected');
        selectedCells = selectedCells.filter(([r, c]) => r !== row || c !== col);
    } else {
        cell.classList.add('selected');
        selectedCells.push([row, col]);
    }

    // Controlla se le celle selezionate sono adiacenti e aggiorna il bottone
    toggleSubmitButton();
});
    </script>
</body>

<?php
    include("../includes/footer.php");
?>