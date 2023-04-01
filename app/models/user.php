<?php
class User {
    public function __construct(
        private int $u_id = 0,
        private string $u_first = '',
        private string $u_last = '',
        private string $u_title = '',
        private string $u_email = '',
        private string $u_password = '',
    ) { }

    public function getID() {
        return $this->u_id;
    }

    public function setID(int $value) {
        $this->u_id = $value;
    }

    public function getFirstName() {
        return $this->u_first;
    }

    public function setFirstName(string $value) {
        $this->u_first = $value;
    }
    
    public function getLastName() {
        return $this->u_last;
    }

    public function setLastName(string $value) {
        $this->u_last = $value;
    }
    
    public function setTitle(string $value) {
        $this->u_title = $value;
    }
    
    public function getTitle() {
        return $this->u_title;
    }

    public function getName() {
        return "$this->u_first $this->u_last";
    }
    
    public function getEmail() {
        return $this->u_email;
    }

    public function setEmail(string $value) {
        $this->u_email = $value;
    }
    
    public function getPassword() {
        return $this->u_password;
    }

    public function setPassword(string $value) {
        $this->u_password = $value;
    }
    
    public function hasPassword() {
        return !empty($this->u_password);
    }

    public function getPhone() {
        return $this->u_phone;
    }

    public function setPhone(string $value) {
        $this->u_phone = $value;
    }

}
?>