<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Recupera l'email dell'utente loggato
$user = getLoggedUser(); // Ottiene i dati dell'utente loggato
$email = $user['Email'];

include("../includes/header.php");
?>

<main>
<body>
    <div class="grid" id="grid"></div>
    <button id="submitButton" disabled>Invia</button>

    <script>
        const grid = document.getElementById('grid');
        const submitButton = document.getElementById('submitButton');
        const gridSize = 10;
        const selectedCells = new Set();

        // Funzione per verificare se tutte le celle selezionate sono adiacenti
        function areCellsAdjacent(selectedCells) {
            if (selectedCells.size === 0) return false;

            const directions = [-1, 1, -gridSize, gridSize];
            const visited = new Set();
            const selectedArray = Array.from(selectedCells).map(Number);

            function dfs(cell) {
                visited.add(cell);
                for (const dir of directions) {
                    const neighbor = cell + dir;

                    // Controlla se il vicino è valido e appartiene alle celle selezionate
                    if (
                        selectedCells.has(neighbor) &&
                        !visited.has(neighbor) &&
                        isValidNeighbor(cell, neighbor)
                    ) {
                        dfs(neighbor);
                    }
                }
            }

            function isValidNeighbor(cell, neighbor) {
                // Controlla se il vicino è nella stessa riga o colonna corretta
                if (Math.abs(cell - neighbor) === 1) {
                    return Math.floor(cell / gridSize) === Math.floor(neighbor / gridSize);
                }
                return true;
            }

            dfs(selectedArray[0]); // Avvia DFS dalla prima cella selezionata

            // Verifica se tutte le celle selezionate sono state visitate
            return visited.size === selectedCells.size;
        }

        // Genera la griglia 10x10
        for (let i = 0; i < gridSize * gridSize; i++) {
            const cell = document.createElement('div');
            cell.className = 'cell';
            cell.dataset.index = i;
            grid.appendChild(cell);
        }

        // Gestione del clic sulle celle
        grid.addEventListener('click', (event) => {
            const cell = event.target;
            if (!cell.classList.contains('cell')) return;

            const index = Number(cell.dataset.index);

            if (cell.classList.contains('active')) {
                cell.classList.remove('active');
                selectedCells.delete(index);
            } else {
                cell.classList.add('active');
                selectedCells.add(index);
            }

            // Verifica l'adiacenza e abilita/disabilita il bottone
            if (areCellsAdjacent(selectedCells)) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        });
    </script>
</body>

    </main>
<?php
    include("../includes/footer.php");
?>