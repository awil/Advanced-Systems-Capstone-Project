<?php
class Company {
    public function __construct(
        private int $co_id = 0,
        private string $co_name = '',
        private string $co_short = '',
        private int $co_add_id = 0,
    ) { }

    public function getID() {
        return $this->co_id;
    }

    public function setID(int $value) {
        $this->co_id = $value;
    }

    public function getName() {
        return $this->co_name;
    }

    public function setName(string $value) {
        $this->co_name = $value;
    }

    public function getShort() {
        return $this->co_short;
    }

    public function setShort(string $value) {
        $this->co_short = $value;
    }

    public function getAddress() {
        return $this->co_add_id;
    }

    public function setAddress(int $value) {
        $this->co_add_id = $value;
    }

}
?>