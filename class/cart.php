<?php
class cart {
    private String $id;
    private String $departure;
    private String $arrival;
    private String $ship;
    private int $quantity;

    public function __construct(String $id, String $departure, String $arrival, String $ship, int $quantity) {
        $this->id = $id;
        $this->departure = $departure;
        $this->arrival = $arrival;
        $this->ship = $ship;
        $this->quantity = $quantity;
    }

    public function getId(): String
    { return $this->id; }

    public function getDeparture(): String
    { return $this->departure; }

    public function getArrival(): String
    { return $this->arrival; }

    public function getShip(): String
    { return $this->ship; }

    public function getQuantity(): int
    { return $this->quantity; }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public function addQuantity() {
        $this->quantity++;
    }
}