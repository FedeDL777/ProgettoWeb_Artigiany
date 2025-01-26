<?php
class DatabaseHelper
{
    private $db;
    private $emailLength = 100;
    public function __construct($servername, $username, $password, $dbname, $port)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }
    public function getEmailLength(){
        return $this->emailLength;
    }

    public function beginTransaction() {
        return $this->db->begin_transaction();
    }
    
    public function commit() {
        return $this->db->commit();
    }
    
    public function rollback() {
        return $this->db->rollback();
    }
    //SELECT QUERY

    public function getUserAddress($email)
{
    $query = "SELECT luogo FROM users WHERE email = ?";
    $stmt = $this->db->prepare($query);
    if ($stmt === false) {
        error_log("Errore nella preparazione dello statement: " . $this->db->error);
        return false;
    }
    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        error_log("Errore nell'esecuzione dello statement: " . $stmt->error);
        return false;
    }
    $result = $stmt->get_result();
    return $result->fetch_assoc()['luogo'] ?? null;
}

public function saveUserAddress($email, $address) {
    $query = "UPDATE users SET luogo = ? WHERE email = ?";
    $stmt = $this->db->prepare($query);
    
    if ($stmt === false) {
        error_log("Errore nella preparazione dello statement: " . $this->db->error);
        return false;
    }
    
    $stmt->bind_param('ss', $address, $email);
    
    if (!$stmt->execute()) {
        error_log("Errore nell'esecuzione dello statement: " . $stmt->error);
        return false;
    }
    
    return true;
}
    // Recupera i materiali per custom product 
    public function getMaterials()
    {
        $query = "SELECT * FROM MATERIALE 
        WHERE NOT CostoXquadretto = 0
        ORDER BY CostoXquadretto ASC";	
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getProductFromCategory($category)
    {
        $query = "SELECT productID, Nome, Descrizione, Costo, PathImmagine FROM PRODOTTO WHERE Categoria = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $category);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    // Metodo per cercare prodotti
    public function searchProducts($searchQuery)
    {
        $query = "SELECT productID, Nome, Descrizione, Costo, PathImmagine FROM PRODOTTO WHERE Nome LIKE ? OR Descrizione LIKE ?";
        $stmt = $this->db->prepare($query);

        $likeQuery = "%" . $searchQuery . "%";
        $stmt->bind_param("ss", $likeQuery, $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Restituisce tuttle carte attualemte valide di un utente
    public function getUserCards($email) {
        $query = "SELECT C.* 
                  FROM carta_di_credito AS C 
                  WHERE C.Email = ? 
                  AND C.Scadenza >= CURDATE()"; // Controllo diretto sulla data
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Verifica se un'email è già registrata
    public function checkUsermail($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function checkLogin($email, $password, $adminClient)
    {
        $query = "SELECT * FROM users WHERE email = ? AND Pw = ? AND AdminClient = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('ssi', $email, $password, $adminClient);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    

    // Recupera il carrello di un cliente
    public function searchClientCart($email)
    {
        $query_cart = "SELECT * 
                        FROM `carrello` 
                        WHERE Email = ? AND Used = 0
                        ORDER BY Ora DESC LIMIT 1";
        $stmt = $this->db->prepare($query_cart);

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $cart = $stmt->get_result();
        return $cart->fetch_all(MYSQLI_ASSOC);
    }
    public function searchClientUsedCarts($email)
    {
        $query_cart = "SELECT * FROM `carrello` WHERE Email = ? AND Used = 1 ORDER BY Ora DESC";
        $stmt = $this->db->prepare($query_cart);

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $cart = $stmt->get_result();
        return $cart->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera i prodotti nel carrello
    public function searchCartProducts($cart_id)
    {
        $query_items = "SELECT p.Nome AS product_name, 
                           p.Descrizione AS description,
                           p.Costo AS price, 
                           c.Quantity as quantity, 
                           c.productID AS product_id, 
                           p.PathImmagine AS PathImmagine
                    FROM COMPOSIZIONE_CARRELLO c
                    INNER JOIN PRODOTTO p ON c.productID = p.productID
                    WHERE c.cartID = ?";
    
        $stmt = $this->db->prepare($query_items);
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        $cart_items = $stmt->get_result();
        return $cart_items->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getLastProductID()
    {
        $query = "SELECT productID, Nome, PathImmagine, Costo FROM PRODOTTO ORDER BY productID DESC LIMIT 1;";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getTopSelledProduct()
{
    $query = "SELECT p.productID, p.Nome, p.PathImmagine, p.Costo
              FROM composizione_carrello cc
              INNER JOIN PRODOTTO p ON cc.productID = p.productID
              GROUP BY p.productID, p.Nome, p.PathImmagine, p.Costo
              ORDER BY SUM(cc.Quantity) DESC
              LIMIT 1;";
    $result = $this->db->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}




    public function getCategoryById($categoryID) {
        $query = "SELECT * FROM CATEGORIE WHERE categoryID = ?";
        $stmt = $this->db->prepare($query);
        
        $stmt->bind_param('i', $categoryID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Restituisce una singola riga
    }
    
    public function getProductsByCategory($categoryID) {
        $query = "SELECT * FROM PRODOTTO WHERE categoryID = ?";
        $stmt = $this->db->prepare($query);
        
        $stmt->bind_param('i', $categoryID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Restituisce tutte le righe
    }
    public function getProductInCart($cart_id, $product_id)
    {
        $query = "SELECT * FROM COMPOSIZIONE_CARRELLO WHERE cartID = ? AND productID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $cart_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        
    }

    public function getNotificationsByEmail($email) {
        $query = "SELECT * FROM NOTIFICHE WHERE Email = ? ORDER BY Data_ DESC";
        $stmt = $this->db->prepare($query);
        
        if ($stmt === false) {
            die("Errore preparazione statement: " . $this->db->error);
        }
        
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getOrderByCardandEmail($email, $card) {
        $query = "SELECT orderID as ID
                        FROM ORDINI 
                        WHERE Email = ? AND Numero = ?
                        ORDER BY Data_ DESC
                        LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $email, $card);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //INSERT QUERY
    // Inserimento di un nuovo cliente
    public function insertClient($email, $password)
    {
        $query = "INSERT INTO users (email, Pw, AdminClient) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $client = 0;
        $stmt->bind_param('ssi', $email, $password, $client);
        return $stmt->execute();
    }

    // Inserimento di un nuovo admin
    public function insertAdmin($email, $password)
    {
        $query = "INSERT INTO users (email, Pw, AdminClient) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $admin = 1;
        $stmt->bind_param('ssi', $email, $password, $admin);
        return $stmt->execute();
    }
    // Inserimento di una nuova carta di credito
    public function insertCreditCard($email, $nome, $cognome, $numero, $scadenza)
    {
        $query = "INSERT INTO `carta_di_credito`(`Email`, `Nome`, `Cognome`, `Numero`, `Scadenza`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('sssis', $email,  $nome, $cognome, $numero, $scadenza);
        return $stmt->execute();
    }

    public function insertProduct($nome, $descrizione, $costo, $pathImmagine, $categoryID)
{
    $query = "INSERT INTO PRODOTTO (Nome, Descrizione, Costo, PathImmagine, categoryID, Email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($query);
    
    // Ottieni l'email dell'utente loggato dalla sessione
    $email = $_SESSION['email'] ?? null;
    
    if (!$email) {
        throw new Exception("Utente non autenticato");
    }

    $stmt->bind_param('ssdssi', $nome, $descrizione, $costo, $pathImmagine, $categoryID, $email);
    
    if ($stmt->execute()) {
        return $this->db->insert_id;
    }
    
    throw new Exception("Errore nell'inserimento del prodotto: " . $stmt->error);
}

    public function insertCart($email)
    {
        $query = "INSERT INTO carrello (Ora, Email, Used) VALUES (NOW(), ?, ?)";
        $stmt = $this->db->prepare($query);
        $used = 0;
        $stmt->bind_param('si', $email, $used);
        return $stmt->execute();
    }

    public function insertProductInCart($cart_id, $product_id, $quantity)
    {
        $query = "INSERT INTO COMPOSIZIONE_CARRELLO (cartID, productID, Quantity) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $cart_id, $product_id, $quantity);
        return $stmt->execute();
    }

    public function insertProductMaterial($productId, $materiale) {
        $query = "INSERT INTO PRODOTTOMATERIALE (productID, Nome) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('is', $productId, $materiale);
        return $stmt->execute();
    }
    public function insertOrder($cart_id, $luogo, $numero, $email, $totale)
    {
        $query = "INSERT INTO ORDINI (cartID, Data_, Luogo, Numero, Email, Totale) VALUES (?, NOW(), ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('isssd', $cart_id, $luogo, $numero, $email, $totale);
        return $stmt->execute();
    }
    public function insertNotification($email, $message, $orderID, $read = 0) {
        $query = "INSERT INTO NOTIFICHE (Email, Testo, Data_, Letto, orderID) VALUES (?, ?, NOW(), ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssii', $email, $message, $read, $orderID);
        return $stmt->execute();
    }
    //DELETE QUERY

    // Elimina un cliente
    public function deleteClient($email)
    {
        $query = "DELETE FROM client WHERE email = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('s', $email);
        return $stmt->execute();
    }

    // Eliminazione di una carta di credito
    public function deleteCreditCard($email, $numero) {
        $query = "DELETE FROM CARTA_DI_CREDITO 
                WHERE Email = ? AND Numero = ?";
        $stmt = $this->db->prepare($query);
    
        if ($stmt === false) {
            error_log("Errore preparazione statement: " . $this->db->error);
            return false;
        }
    
        $stmt->bind_param('ss', $email, $numero);
        $result = $stmt->execute();
    
        if (!$result) {
            error_log("Errore eliminazione carta: " . $stmt->error);
        }
    
        return $result;
    }   
    public function deleteProductFromCart($cart_id, $product_id)
    {
        $query = "DELETE FROM COMPOSIZIONE_CARRELLO WHERE cartID = ? AND productID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $cart_id, $product_id);
        return $stmt->execute();
    }

    public function deleteNotification($email, $data) {
        $query = "DELETE FROM NOTIFICHE WHERE Email = ? AND Data_ = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $email, $data);
        return $stmt->execute();
    }

    //UPDATE QUERY
    // Aggiorna la password di un cliente
    public function updateClientPassword($email, $password)
    {
        $query = "UPDATE client SET Pw = ? WHERE email = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('ss', $password, $email);
        return $stmt->execute();
    }
    public function AddCartProductQuantity($cart_id, $product_id, $quantity)
    {
        $query = "UPDATE COMPOSIZIONE_CARRELLO SET Quantity = Quantity + ? WHERE cartID = ? AND productID = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('iii', $quantity, $cart_id, $product_id);
        return $stmt->execute();
    }
    public function decreaseCartProductQuantity($cart_id, $product_id, $quantity)
    {
        if($quantity > 0){
            return;
        }
        $result = $this->getProductInCart($cart_id, $product_id);
        if(!empty($result) && $result[0]['Quantity'] + $quantity <= 0 ){
            return $this->deleteProductFromCart($cart_id, $product_id);
        }
        else if(!empty($result)){
            $query = "UPDATE COMPOSIZIONE_CARRELLO SET Quantity = Quantity + ? WHERE cartID = ? AND productID = ?";
            $stmt = $this->db->prepare($query);
    
            $stmt->bind_param('iii', $quantity, $cart_id, $product_id);
            return $stmt->execute();
        }

    }
    public function useCart($cart_id)
    {
        $query = "UPDATE carrello SET Used = 1 WHERE cartID = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('i',$cart_id);
        return $stmt->execute();
    }


    // Recupera la password hashata di un admin
    public function getHashedPasswordAdmin($email)
    {
        $query = "SELECT Pw FROM users WHERE email = ? AND AdminClient = 1";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera la Pw hashata di un cliente
    public function getHashedPasswordClient($email)
    {
        $query = "SELECT Pw FROM users WHERE email = ? AND AdminClient = 0";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function markAllNotificationsAsRead($email) {
        $query = "UPDATE NOTIFICHE SET Letto = 1 WHERE Email = ? AND Letto = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        return $stmt->execute();
    }

    public function getProductById($productID)
{
    $query = "SELECT * FROM PRODOTTO WHERE productID = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('i', $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // Cambiato da fetch_all a fetch_assoc
}

    public function getAllUsers() 
{
    $query = "SELECT Email, AdminClient, luogo FROM USERS";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


    //permette a un admin di aggiungere una categoria
    public function addCategory($category)
    {
        $query = "INSERT INTO CATEGORIE (Nome) VALUES (?)";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $stmt->bind_param('s', $category);
        return $stmt->execute();
    }

    //ottiene tutte le categoria da visualizzare nella home
    public function getCategories()
    {
        $query = "SELECT * FROM CATEGORIE";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countUnreadNotifications($email) {
        $query = "SELECT COUNT(*) AS unread_count FROM NOTIFICHE WHERE Email = ? AND Letto = 0";
        $stmt = $this->db->prepare($query);
        
        if ($stmt === false) {
            die("Errore preparazione statement: " . $this->db->error);
        }
        
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['unread_count'];
    }

    public function getAllProducts() {
        $query = "SELECT * FROM PRODOTTO";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}



?>