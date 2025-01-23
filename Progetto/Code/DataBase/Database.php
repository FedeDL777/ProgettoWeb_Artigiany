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
    //SELECT QUERY

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
    public function getUserCards($email)
    {
        $query = "SELECT C.*
                FROM carta_di_credito AS C, users AS U
                WHERE C.Email = U.Email AND DATE(C.Scadenza) > CURRENT_DATE AND U.Email = ?";
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
        $query_cart = "SELECT * FROM `carrello` WHERE Email = ? ORDER BY Ora DESC LIMIT 1";
        $stmt = $this->db->prepare($query_cart);

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $cart = $stmt->get_result();
        return $cart->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera i prodotti nel carrello
    public function searchCartProducts($cart_id)
    {
        $query_items = "SELECT p.Nome AS product_name, p.Costo AS price, c.productID AS product_id 
                        FROM COMPOSIZIONE_CARRELLO c
                        INNER JOIN PRODOTTO p ON c.productID = p.productID
                        WHERE c.cartID = ?";
        $stmt = $this->db->prepare($query_items);

        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        $cart_items = $stmt->get_result();
        return $cart_items->fetch_all(MYSQLI_ASSOC);
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

    //DELETE QUERY

    // Elimina un cliente
    public function deleteClient($email)
    {
        $query = "DELETE FROM client WHERE email = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('s', $email);
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
}
?>