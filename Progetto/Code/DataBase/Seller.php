<?php
class Seller
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insertProduct($name, $description, $price, $category_tag, $image, $email, $discount, $availability)
    {
        $query = "INSERT INTO PRODUCT (name, price, description, category_tag, image, email, discount, availability, removed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->query2($query, 'sissssiii', $name, $price, $description, $category_tag, $image, $email, $discount, $availability, 0);
    }

    public function getProductsSoldBy($email)
    {
        $query = "SELECT id_product FROM PRODUCT WHERE email = ? AND removed = 0";
        return $this->db->query($query, 's', $email);
    }

    public function deleteProduct($id_product)
    {
        $query = "UPDATE PRODUCT SET removed = 1 WHERE id_product = ?";
        $this->db->query2($query, 'i', $id_product);
    }

    public function updateProduct($id_product, $name = null, $description = null, $price = null, $img = null, $category = null, $discount = -1, $availability = null)
    {
        $query = "UPDATE PRODUCT SET id_product = id_product";

        $params = [];
        $types = '';

        if ($name != null) {
            $query .= ", name = ?";
            $params[] = $name;
            $types .= 's';
        }

        if ($description != null) {
            $query .= ", description = ?";
            $params[] = $description;
            $types .= 's';
        }

        if ($price != null) {
            $query .= ", price = ?";
            $params[] = $price;
            $types .= 'i';
        }

        if ($img != null) {
            $query .= ", image = ?";
            $params[] = $img;
            $types .= 's';
        }

        if ($category != null) {
            $query .= ", category_tag = ?";
            $params[] = $category;
            $types .= 's';
        }

        if ($discount != -1) {
            $query .= ", discount = ?";
            $params[] = $discount;
            $types .= 'i';
        }

        if ($availability != null) {
            $query .= ", availability = ?";
            $params[] = $availability;
            $types .= 'i';
        }

        $query .= " WHERE id_product = ?";
        $params[] = $id_product;
        $types .= 'i';

        $this->db->query2($query, $types, ...$params);
    }
}