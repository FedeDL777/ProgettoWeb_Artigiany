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

    // INSERT SECTION
    /*
    public function insertUser($name, $surname, $username, $email, $password, $birthday)
    {
        $query = "INSERT INTO user (name, surname, username, email, password, birthday) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssss', $name, $surname, $username, $email, $password, $birthday);
        $stmt->execute();
    }
*/
    public function insertClient($email, $password)
    {
        $query = "INSERT INTO users (email, password_, AdminClient) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $client = 0;
        $stmt->bind_param('ssi', $email, $password, $client);
        return $stmt->execute();

    }
    public function insertAdmin($email, $password)
    {
        $query = "INSERT INTO users (email, password_, AdminClient) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $client = 0;
        $stmt->bind_param('ssi', $email, $password, $client);
        return $stmt->execute();

    }
    public function checkUsermail($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //SEARCH SECTION
    public function searchClientCart($email)
    {
        $query_cart = "SELECT * 
                        FROM `carrello`
                        WHERE Email = ?
                        ORDER BY Ora DESC
                        LIMIT 1";
        $stmt = $this->db->prepare($query_cart);
        $stmt->execute([$email]);
        $cart = $stmt->get_result();
        return $cart->fetch_all(MYSQLI_ASSOC);
    }
    public function searchCartProducts($cart_id)
    {
        //Query generata
        $query_items = "SELECT 
                            p.Nome AS product_name, 
                            p.Costo AS price, 
                            c.productID AS product_id 
                        FROM COMPOSIZIONE_CARRELLO c
                        INNER JOIN PRODOTTO p ON c.productID = p.productID
                        WHERE c.cartID = ?";
        $stmt = $this->db->prepare($query_items);
        $stmt->execute([$cart_id]);
        $cart_items = $stmt->get_result();
        return $cart_items->fetch_all(MYSQLI_ASSOC);
    }







    
    // DELETE SECTION
/*
    public function deleteCartProduct($username, $id_product)
    {
        $query = "DELETE FROM wishes WHERE username = ? AND id_product = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $username, $id_product);
        $stmt->execute();
    }
*/
    public function deleteClient($email)
    {
        $query = "DELETE FROM client WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
    }

    // UPDATE SECTION
/*
    public function updateUser($name, $surname, $username, $email, $birthday, $cardNumber, $password)
    {
        $query = "UPDATE user SET name = ?, surname = ?, email = ?, birthday = ?, card_number = ?, password = ? WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssssss', $name, $surname, $email, $birthday, $cardNumber, $password, $username);
        $stmt->execute();
    }
*/
    public function updateClientPassword($email, $password)
    {
        $query = "UPDATE client SET password = ? WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $password, $email);
        $stmt->execute();
    }

    // SELECT SECTION
}
?>