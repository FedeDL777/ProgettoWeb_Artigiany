<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}
$materialiCustom = $dbh->getMaterials();
include("../includes/header.php");
?>
<style>
 body {
    font-size: 18px; /* Font leggermente più grande */
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    color: #212529;
}

.container {
    display: flex;
    flex-direction: column; /* Disposizione verticale */
    align-items: center; /* Centrare tutti gli elementi */
    gap: 20px; /* Spaziatura tra gli elementi */
    max-width: 1000px; /* Larghezza massima del contenitore */
    margin: 20px auto; /* Centrare il contenitore nella pagina */
    padding: 10px;
}

h1, p {
    text-align: center; /* Allinea il testo al centro */
    margin: 10px 0;
}

.color-palette {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(40px, 1fr)); /* Disposizione adattiva */
    gap: 10px;
    margin-bottom: 20px;
    width: 100%; /* Adatta alla larghezza */
    max-width: 600px; /* Larghezza massima */
}

.color-option {
    width: 40px;
    height: 40px;
    border: 2px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.color-option.selected {
    transform: scale(1.1);
    border-color: #000;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
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

#materialSelect {
    width: 100%;
    max-width: 600px;
    margin-bottom: 20px;
}

#grid {
    display: grid;
    grid-template-columns: repeat(20, 1fr); /* 20 colonne uguali */
    gap: 2px;
    width: 100%;
    max-width: 800px;
}

.grid-cell {
    width: 100%;
    aspect-ratio: 1; /* Crea celle quadrate */
    background-color: #ffffff;
    border: 1px solid #ced4da;
    cursor: pointer;
}

#description {
    width: 100%;
    max-width: 600px;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    resize: vertical; /* Permette di ridimensionare l'area di testo verticalmente */
    margin-bottom: 20px;
}

#description:placeholder-shown {
    border-color: #dc3545;
    background-color: #fff5f5;
}

#submitBtn {
    width: 100%;
    max-width: 600px;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.2s, box-shadow 0.2s;
}

#submitBtn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
    box-shadow: none;
}

#submitBtn:hover:not(:disabled) {
    background-color: #0056b3;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

@media (min-width: 992px) {
    body {
        font-size: 20px; /* Font più grande su schermi grandi */
    }

    #grid {
        gap: 5px; /* Spaziatura maggiore tra celle */
        grid-template-columns: repeat(20, 30px); /* Celle leggermente più grandi */
    }

    .grid-cell {
        width: auto;
        height: auto;
    }
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
                    <div class="color-option" style="background-color:rgb(66, 193, 72)" data-color="#6f42c1"></div>
                    <div class="color-option" style="background-color:rgb(189, 189, 186)" data-color="#28a745"></div>
                    <div class="color-option" style="background-color:rgb(248, 187, 5)" data-color="#ffc107"></div>
                    <div class="color-option" style="background-color:rgb(42, 19, 0)" data-color="#fd7e14"></div>
                    <div class="color-option" style="background-color:rgb(155, 70, 0)" data-color="#e83e8c"></div>
                    <div class="color-option" style="background-color:rgb(255, 197, 142)" data-color="#17a2b8"></div>
                    <div class="color-option eraser" data-color="#ffffff">🧽</div>
                </div>
            </div>

            <!-- Selezione Materiale -->
                <?php
                    if ($materialiCustom->num_rows > 0) {
                    echo '<div class="mb-4">';
                    echo '<h5>Seleziona Materiale</h5>';
                    echo '<select id="materialSelect" class="form-select">';
                    // Itera attraverso i risultati e crea le opzioni del <select>
                    while ($row = $materialiCustom->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['Nome']) . '">' . htmlspecialchars($row['Nome']) . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                } else {
                    echo '<div class="mb-4">';
                    echo '<h5>Seleziona Materiale</h5>';
                    echo '<select id="materialSelect" class="form-select">';
                    echo '<option value="">Nessun materiale disponibile</option>';
                    echo '</select>';
                    echo '</div>';
                }
                ?>


        <!-- Griglia -->
        <div class="col-lg-9 col-md-12 mx-auto mb-4 text-center"> 
            <div>
                <h5>Crea la tua forma</h5>
                <div id="grid" class="grid-container"></div>
            </div>
        </div>

        <!-- Descrizione e bottone -->
        <div class="col-lg-8 col-md-12 mx-auto mb-4 text-center">  
            <div class="mb-4">
                <h5>Descrizione</h5>
                <textarea id="description" class="form-control" rows="4" placeholder="Descrivi il tuo prodotto (obbligatorio)"></textarea>
            </div>

            <button id="submitBtn" class="btn btn-primary w-100" disabled>Invia</button>
        </div>
    </div>
</div>

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
