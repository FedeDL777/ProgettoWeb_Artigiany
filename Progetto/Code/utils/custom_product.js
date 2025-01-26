// Inizializza variabili
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
            selectedCells.push([row, col, selectedColor]); // Aggiungi colore
        }
    }

    toggleSubmitButton();
});

// Abilita/disabilita bottone
function toggleSubmitButton() {
    const hasDescription = description.value.trim().length > 0;
    const hasDrawing = selectedCells.length > 0; // Verifica che almeno una cella sia selezionata
    const validShape = areCellsAdjacent(selectedCells);

    // Il bottone sarà abilitato solo se:
    // - la descrizione è presente,
    // - almeno una cella è disegnata,
    // - le celle selezionate sono adiacenti.
    submitBtn.disabled = !(validShape && hasDescription && hasDrawing);
}

// Verifica celle adiacenti
function areCellsAdjacent(cells) {
    if (cells.length < 2) return true; // Se ci sono meno di due celle, si considerano adiacenti

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
    if (row < 0 || col < 0 || row >= gridSize || col >= gridSize) return;
    if (visited[row][col]) return;

    visited[row][col] = true;
    const neighbors = [
        [row + 1, col], [row - 1, col], [row, col + 1], [row, col - 1]
    ];

    for (const [nRow, nCol] of neighbors) {
        if (cells.some(cell => cell[0] === nRow && cell[1] === nCol)) {
            dfs(nRow, nCol, visited, cells);
        }
    }
}

submitBtn.addEventListener('click', () => {
    const materialSelect = document.getElementById('materialSelect');
    const material = materialSelect.options[materialSelect.selectedIndex].text.split(" - ")[0];
    const pricePerQuadretto = parseFloat(materialSelect.options[materialSelect.selectedIndex].text.split("€")[1].trim().replace(',', '.'));

    const totalQuadretti = selectedCells.length;
    const totalPrice = (pricePerQuadretto * totalQuadretti).toFixed(2);

    // Formatta selectedCells con colore incluso
    const formattedSelectedCells = selectedCells.map(cell => ({
        row: cell[0],
        col: cell[1],
        material: material,
        color: cell[2] // Aggiunto il colore alla cella
    }));

    // Compila i campi del modulo
    document.getElementById('formMaterial').value = material;
    document.getElementById('formDescription').value = description.value;
    document.getElementById('formSelectedCells').value = JSON.stringify(formattedSelectedCells);
    document.getElementById('formTotalPrice').value = totalPrice;

    // Invia il modulo
    document.getElementById('customProductForm').submit();
});
