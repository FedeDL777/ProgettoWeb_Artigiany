<?php
class DatabaseHelper
{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    // Metodo per cercare prodotti
    public function searchProducts($searchQuery)
    {
        $query = "SELECT productID, Nome, Descrizione, Costo, PathImmagine FROM PRODOTTO WHERE Nome LIKE ? OR Descrizione LIKE ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $likeQuery = "%" . $searchQuery . "%";
        $stmt->bind_param("ss", $likeQuery, $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Inserimento di un nuovo cliente
    public function insertClient($email, $password)
    {
        $query = "INSERT INTO users (email, Password, AdminClient) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $client = 0;
        $stmt->bind_param('ssi', $email, $password, $client);
        return $stmt->execute();
    }

    // Inserimento di un nuovo admin
    public function insertAdmin($email, $password)
    {
        $query = "INSERT INTO users (email, Password, AdminClient) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $admin = 1;
        $stmt->bind_param('ssi', $email, $password, $admin);
        return $stmt->execute();
    }

    // Verifica se un'email è già registrata
    public function checkUsermail($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera il carrello di un cliente
    public function searchClientCart($email)
    {
        $query_cart = "SELECT * FROM `carrello` WHERE Email = ? ORDER BY Ora DESC LIMIT 1";
        $stmt = $this->db->prepare($query_cart);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
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
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        $cart_items = $stmt->get_result();
        return $cart_items->fetch_all(MYSQLI_ASSOC);
    }

    // Elimina un cliente
    public function deleteClient($email)
    {
        $query = "DELETE FROM client WHERE email = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $stmt->bind_param('s', $email);
        return $stmt->execute();
    }

    // Aggiorna la password di un cliente
    public function updateClientPassword($email, $password)
    {
        $query = "UPDATE client SET Password = ? WHERE email = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $stmt->bind_param('ss', $password, $email);
        return $stmt->execute();
    }

    // Recupera la password hashata di un admin
    public function getHashedPasswordAdmin($email)
    {
        $query = "SELECT Password FROM users WHERE email = ? AND AdminClient = 1";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera la password hashata di un cliente
    public function getHashedPasswordClient($email)
    {
        $query = "SELECT Password FROM users WHERE email = ? AND AdminClient = 0";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Errore nella preparazione della query: " . $this->db->error);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>