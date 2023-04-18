<?php

class Ctrl {
    public function __construct(
        private int $blc_id = 0,
        private int $bl_id = 0,
        private string $ctrl_id = '',
        private string $blc_stat = '',
        private bool $blc_poam = false,
        private string $blc_created = '',
        private string $blc_modified = '',
        private string $blc_comments = '',
    ) {}
    
    public function getBaselineCtrlID() {
        return $this->blc_id;
    }

    public function setBaselineCtrlID(int $value) {
        $this->blc_id = $value;
    }

    public function getBaselineID() {
        return $this->bl_id;
    }

    public function setBaselineID(int $value) {
        $this->bl_id = $value;
    }

    public function getCtrlID() {
        return $this->ctrl_id;
    }

    public function setCtrlID(string $value) {
        $this->ctrl_id = $value;
    }

    public function getStatus() {
        return $this->blc_stat;
    }

    public function setStatus(string $value) {
        $this->blc_stat = $value;
    }

    public function hasPOAM() {
        return $this->blc_poam;
    }

    public function poamStatus() {
        if($this->blc_poam === FALSE) {
            return 'N/A';
        } else {
            return 'Yes';
        }
    }

    public function setPOAM(bool $value) {
        $this->blc_poam = $value;
    }

    public function getStartDate() {
        return $this->blc_created;
    }

    public function setStartDate() {
        $this->blc_created = $value;
    }

    public function getModDate() {
        return $this->blc_modified;
    }

    public function setModDate(string $value) {
        $this->blc_modified = $value;
    }

    public function getComments() {
        return $this->blc_comments;
    }
}
?>