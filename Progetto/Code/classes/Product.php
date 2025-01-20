<?php

class Product
{
    private $id;
    private $name;
    private $price;
    private $description;
    private $img;
    private $seller_email;
    private $category_tag;
    private $availability;
    private $removed;

    public function __construct($id_product, $name, $price, $description, $email, $category_tag, $availability, $removed = 0)
    {
        $this->id = $id_product;
        $this->name = $name;
        $this->price = $price / 100;
        $this->description = $description;
        $this->img = $image;
        $this->category_tag = $category_tag;
        $this->discount = $discount;
        $this->availability = $availability;
        $this->removed = $removed;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function getSellerEmail()
    {
        return $this->seller_email;
    }

    public function getCategoryTag()
    {
        return $this->category_tag;
    }


    public function getAvailability()
    {
        return $this->availability;
    }

    public function isRemoved()
    {
        return $this->removed;
    }

}