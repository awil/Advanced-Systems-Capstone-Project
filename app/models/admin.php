<?php
class Admin extends User {
    public function __construct(
        // Pass this info to superclass
        int $adm_id = 0,
        string $adm_first = '',
        string $adm_last = '',
        string $adm_title = '',
        string $adm_email = '',
        string $adm_password = '',
        private int $adm_access = 0,
    ) { 
        // Call User constructor
        parent::__construct($adm_id, $adm_first, $adm_last, $adm_title, $adm_email, $adm_password);
    }

    public function getAccess() {
        return $this->adm_access;
    }

    public function setAccess(int $value) {
        $this->adm_access = $value;
    }
}
?>