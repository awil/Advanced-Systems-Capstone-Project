<?php
class Client extends User {

    public function __construct(
        // Pass this info to superclass
        int $u_id = 0,
        string $u_first = '',
        string $u_last = '',
        string $u_title = '',
        string $u_email = '',
        string $u_password = '',
        // Client properties
        private string $cl_phone = '',
        private int $add_id = 0,
        private int $co_id = 0,
    ) { 
        // Call User constructor
        parent::__construct($u_id, $u_first, $u_last, $u_title, $u_email, $u_password);
    }

    public function getPhone() {
        return $this->cl_phone;
    }

    public function setPhone(string $value) {
        $this->cl_phone = $value;
    }

    public function getClientAddress() {
        return $this->add_id;
    }

    public function setClientAddress(int $value) {
        $this->add_id = $value;
    }

    public function getCompany() {
        return $this->co_id;
    }

    public function setCompany(int $value) {
        $this->co_id = $value;
    }
}
?>