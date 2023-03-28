<?php
class Admin extends User {
    public function __construct(
        // Pass this info to superclass
        private int $adm_id = 0,
        private string $adm_first = '',
        private string $adm_last = '',
        private string $adm_title = '',
        private string $adm_alias = '',
        private string $adm_email = '',
        private string $adm_password = '',
    ) { 
        // Call User constructor
        parent::__construct($adm_id, $adm_first, $adm_last, $adm_title, $adm_alias, $adm_email, $adm_password);
    }
}
?>