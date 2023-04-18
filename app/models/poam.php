<?php
class POAM {
    public function __construct(
        private int $poam_id = 0,
        private int $bl_id = 0,
        private int $blc_id = 0,
        private string $poam_created = '',
        private string $poam_modified = '',
        private string $poam_item_003 = '',
        private string $poam_item_004 = '',
        private string $poam_item_005 = '',
        private string $poam_item_006 = '',
        private string $poam_item_007 = '',
        private string $poam_item_008 = '',
        private string $poam_item_009 = '',
        private string $poam_item_010 = '',
        private string $poam_item_011 = '',
        private string $poam_item_012 = '',
        private string $poam_item_013 = '',
        private string $poam_item_014 = '',
        private string $poam_item_015 = '',
        private string $poam_item_016 = '',
        private string $poam_item_017 = '',
        private string $poam_item_018 = '',
        private string $poam_item_019 = '',
        private string $poam_item_020 = '',
        private string $poam_item_021 = '',
        private string $poam_item_022 = '',
        private string $poam_item_023 = '',
        private string $poam_item_024 = '',
        private string $poam_item_025 = '',
        private string $poam_item_026 = '',
        private string $poam_item_027 = '',
        private string $poam_item_028 = '',
        private string $poam_item_029 = '',
        private string $poam_item_030 = '',
    ){}

    public function getID() {
        return $this->poam_id;
    }

    public function getBaselineID() {
        return $this->bl_id;
    }

    public function getBLCtrlID() {
        return $this->blc_id;
    }

    public function getStartDate() {
        return $this->poam_created;
    }

    public function getLastUpdated() {
        return $this->poam_modified;
    }

    public function setLastUpdated(string $value) {
        $this->poam_modified = $value;
    }
}
?>