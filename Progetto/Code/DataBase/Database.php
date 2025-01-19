<?php

require_once 'Seller.php';


class Database
{
    private $db;
    public $seller;
    

    public function __construct($servername, $username, $password, $dbname, $port)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        $this->seller = new Seller($this);
        //$this->Artigiany = new Artigiany($this);
    }

    public function query($query, $param_types, ...$params)
    {
        $stmt = $this->db->prepare($query);
        if ($param_types !== '') {
            $stmt->bind_param($param_types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function query2($query, $param_types, ...$params)
    {
        $stmt = $this->db->prepare($query);
        if ($param_types !== '') {
            $stmt->bind_param($param_types, ...$params);
        }
        $stmt->bind_param($param_types, ...$params);
        $stmt->execute();
    }

    /*
    public function isProductInCart($email, $id_product)
    {
        $query = "SELECT * FROM CART WHERE email = ? AND id_product = ?";
        return count($this->query($query, 'si', $email, $id_product)) > 0;
    }

    public function addProductToCart($email, $id_product, $quantity)
    {
        $query = "INSERT INTO CART (email, id_product, quantity) VALUES (?, ?, ?)";
        $this->query2($query, 'sii', $email, $id_product, $quantity);
    }

    public function getUsersWithProductInCart($id_product)
    {
        $query = "SELECT email FROM CART WHERE id_product = ?";
        return $this->query($query, 'i', $id_product);
    }

    public function getUsersWithProductInCartMoreThan($id_product, $quantity)
    {
        $query = "SELECT email FROM CART WHERE id_product = ? AND quantity > ?";
        return $this->query($query, 'ii', $id_product, $quantity);
    }

    public function getUsersWithProductInWishlist($id_product)
    {
        $query = "SELECT email FROM WISHES WHERE id_product = ?";
        return $this->query($query, 'i', $id_product);
    }   

    public function removeProductFromCart($email, $id_product)
    {
        $query = "DELETE FROM CART WHERE email = ? AND id_product = ?";
        $this->query2($query, 'si', $email, $id_product);
    }

    public function emptyCart($email)
    {
        $query = "DELETE FROM CART WHERE email = ?";
        $this->query2($query, 's', $email);
    }

    public function getCart($email)
    {
        return $this->query("SELECT * FROM CART where email = ?", 's', $email);
    }

    public function getCartProduct($email, $id_product)
    {
        $query = "SELECT * FROM CART WHERE email = ? AND id_product = ?";
        return $this->query($query, 'si', $email, $id_product);
    }
    
    public function getMaxPrice(){
        $query = "SELECT MAX(price) as max_price FROM PRODUCT";
        return $this->query($query, '');
    }

    public function updateQuantityInCart($email, $id_product, $quantity)
    {
        $query = "UPDATE CART SET quantity = ? WHERE id_product = ? AND email = ?";
        $this->query2($query, 'iis', $quantity, $id_product, $email);
    }
    public function getProduct($id)
    {
        $query = "SELECT * FROM PRODUCT p WHERE id_product = ?";
        return $this->query($query, 'i', $id);
    }

    public function filteredSearchProduct($name = null, $minPrice = null, $maxPrice = null, $category = null, $is_discount = false)
    {
        $query = "SELECT p.id_product 
        FROM PRODUCT p 
        WHERE p.removed = 0";

        $params = [];
        $types = '';

        if (!empty($name)) {
            $query .= " AND p.name LIKE ?";
            $params[] = "%$name%";
            $types .= 's';
        }

        if (!empty($minPrice)) {
            $query .= " AND p.price >= ?";
            $params[] = $minPrice;
            $types .= 'i';
        }

        if (!empty($maxPrice)) {
            $query .= " AND p.price <= ?";
            $params[] = $maxPrice;
            $types .= 'i';
        }

        if ($is_discount) {
            $query .= " AND p.discount > 0";
        }

        if (!empty($category)) {
            $query .= " AND p.category_tag = ?";
            $params[] = $category;
            $types .= 's';
        }

        return $this->query($query, $types, ...$params);
    }

    public function searchProductByName($productName)
    {
        $query = "
            SELECT p.id_product
            FROM PRODUCT p
            WHERE p.name LIKE ?
            AND p.removed = 0
            ORDER BY 
                CASE 
                    WHEN p.name = ? THEN 1 -- Nomi esatti
                    WHEN p.name LIKE ? THEN 2 -- Nomi simili
                END,
                p.id_product
        ";

        return $this->query($query, 'sss', "%$productName%", $productName, "%$productName%");
    }

    public function getUser($email)
    {
        return $this->query("SELECT * FROM USER WHERE email = ?", 's', $email);
    }

    public function checkLogin($email, $password)
    {
        $users = $this->getUser($email);
        if (count($users) == 0) {
            return [];
        }
        $user = $users[0];
        if (password_verify($password, $user['password'])) {
            return [$user];
        }
        return [];
    }
    public function registerUser()
    {
        $query = "INSERT INTO USER (name, email, password, seller) VALUES (?, ?, ?, ?)";
        $this->query2($query, 'sssi', $_POST['name'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), 0);
    }

    public function isProductInWishlist($email, $id_product)
    {
        $query = "SELECT * FROM WISHES WHERE id_product = ? AND email = ?";
        return count($this->query($query, 'is', $id_product, $email)) > 0;
    }

    public function addProductToWishlist($email, $id_product)
    {
        $query = "INSERT INTO WISHES (id_product, email) VALUES (?, ?)";
        $this->query2($query, 'is', $id_product, $email);
    }

    public function removeProductFromWishlist($email, $id_product)
    {
        $query = "DELETE FROM WISHES WHERE id_product = ? AND email = ?";
        $this->query2($query, 'is', $id_product, $email);
    }

    public function getWishlist($email)
    {
        return $this->query("SELECT p.id_product FROM WISHES w JOIN PRODUCT p ON w.id_product = p.id_product WHERE w.email = ?", 's', $email);
    }

    public function addOrder($email, $products, $price)
    {
        $query1 = "INSERT INTO ORDERS (day, email, price) VALUES (CURDATE(), ?, ?)";
        $this->query2($query1, 'si', $email, $price);
        $id_order = $this->db->insert_id;
        $query2 = "INSERT INTO ORDERS_ITEM (id_product, id_order, quantity) VALUES (?, ?, ?)";
        foreach ($products as $product) {
            $this->query2($query2, 'iii', $product['id_product'], $id_order, $product['quantity']);
        }
    }

    public function getOrders($email)
    {
        return $this->query("SELECT * FROM ORDERS WHERE email = ?", 's', $email);
    }

    public function getOrderProducts($id_order)
    {
        return $this->query("SELECT * FROM ORDERS_ITEM oi WHERE oi.id_order = ?", 'i', $id_order);
    }

    public function addCreditCard($email, $number, $name, $surname, $expiration)
    {
        $query = "INSERT INTO CREDIT_CARD (email, number, name, surname, expiration) VALUES (?, ?, ?, ?, ?)";
        $this->query2($query, 'sssss', $email, $number, $name, $surname, $expiration);
    }

    public function removeCreditCard($email, $number)
    {
        $query = "DELETE FROM CREDIT_CARD WHERE email = ? AND number = ?";
        $this->query2($query, 'ss', $email, $number);
    }

    public function getCreditCards($email)
    {
        return $this->query("SELECT * FROM CREDIT_CARD WHERE email = ?", 's', $email);
    }

    public function getAllCategories()
    {
        return $this->query("SELECT category_tag FROM CATEGORY", '');
    }
    public function getNotifications($email)
    {
        return $this->query("SELECT * FROM NOTIFICATION WHERE email = ?", 's', $email);
    }

    public function hasUnreadNotifications($email)
    {
        $query = "SELECT * FROM NOTIFICATION WHERE email = ? AND seen = 0";
        return count($this->query($query, 's', $email)) > 0;
    }

    public function markNotificationAsRead($id_notification)
    {
        $query = "UPDATE NOTIFICATION SET seen = 1 WHERE id_notification = ?";
        $this->query2($query, 'i', $id_notification);
    }

    public function deleteNotification($id_notification)
    {
        $query = "DELETE FROM NOTIFICATION WHERE id_notification = ?";
        $this->query2($query, 'i', $id_notification);
    }

    public function addNotification($email, $title, $description)
    {
        $query = "INSERT INTO NOTIFICATION (title, day, seen, description, email) VALUES (?, CURDATE(), ?, ?, ?)";
        $this->query2($query, 'siss', $title, 0, $description, $email);
    }

    public function addNotificationProductUpdated($id_product)
    {
        $interested_users = $this->query("SELECT email FROM WISHES WHERE id_product = ?", 'i', $id_product);
        foreach ($interested_users as $user) {
            $this->addNotification($user['email'], "Prodotto aggiornato", "Il venditore ha aggiornato un prodotto della tua lista dei desideri");
        }
    }

    public function getMostSoldProducts()
    {
        $query = "SELECT id_product FROM ORDERS_ITEM GROUP BY id_product ORDER BY COUNT(id_product) DESC LIMIT 3";
        return $this->query($query, '');
    }
}*/



}
   