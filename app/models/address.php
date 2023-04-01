<?php
class Address {
    public function __construct(
        private int $add_id = 0,
        private string $add_line1 = '',
        private string $add_line2 = '',
        private string $add_city = '',
        private string $add_state = '',
        private string $add_zipCode = '',
    ) { }

    public function getID() {
        return $this->add_id;
    }

    public function setID(int $value) {
        $this->add_id = $value;
    }
    
    public function getLine1() {
        return $this->add_line1;
    }

    public function setLine1(string $value) {
        $this->add_line1 = $value;
    }
    
    public function hasLine2() {
        return strlen($this->add_line2) > 0;
    }
    
    public function getLine2() {
        return $this->add_line2;
    }

    public function setLine2(string $value) {
        $this->add_line2 = $value;
    }

    public function getCity() {
        return $this->add_city;
    }

    public function setCity(string $value) {
        $this->add_city = $value;
    }
    
    public function getState() {
        return $this->add_state;
    }
    
    public function getStateUpper() {
        return strtoupper($this->add_state);
    }

    public function setState(string $value) {
        $this->add_state = $value;
    }
    
    public function getZipCode() {
        return $this->add_zipCode;
    }

    public function setZipCode(string $value) {
        $this->add_zipCode = $value;
    }
    
    public function getFullAddress() {
        return $this->add_city . ', ' . $this->add_state . ' ' . $this->add_zipCode;
    }
}
?>