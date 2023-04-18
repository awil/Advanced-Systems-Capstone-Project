<?php

class Log {
    public function __construct(
        private int $acc_id = 0,
        private int $adm_id = 0,
        private string $acc_date = '',
        private string $acc_data = '',
        private int $co_id = 0,
        private int $bl_id = 0,
    ){}

    public function getID() {
        return $this->acc_id;
    }

    public function setID(int $value) {
        $this->acc_id = $value;
    }

    public function getAdmID() {
        return $this->adm_id;
    }

    public function setAdmID(int $value) {
        $this->adm_id = $value;
    }

    public function getAccDate() {
        return $this->acc_date;
    }

    public function setAccDate(string $value) {
        $this->acc_date = $value;
    }

    public function getAccData() {
        return $this->acc_data;
    }

    public function setAccData(string $value) {
        $this->acc_data = $value;
    }

    public function getCompanyID() {
        return $this->co_id;
    }

    public function setCompanyID(int $value) {
        $this->co_id = $value;
    }

    public function getBaselineID() {
        return $this->bl_id;
    }

    public function setBaselineID(int $value) {
        $this->bl_id = $value;
    }

}

?>