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

    public function setID(int $value) {
        $this->poam_id = $value;
    }

    public function getBaselineID() {
        return $this->bl_id;
    }

    public function setBaselineID(int $value) {
        $this->bl_id = $value;
    }

    public function getBLCtrlID() {
        return $this->blc_id;
    }

    public function setBLCtrlID(int $value) {
        $this->blc_id = $value;
    }

    public function getStartDate() {
        return $this->poam_created;
    }

    public function setStartDate(string $value) {
        $this->poam_created = $value;
    }

    public function getModDate() {
        return $this->poam_modified;
    }

    public function setModDate(string $value) {
        $this->poam_modified = $value;
    }

    public function setPoamItem003(string $value) {
        $this->poam_item_003 = $value;
    }

    public function setPoamItem004(string $value) {
        $this->poam_item_004 = $value;
    }

    public function setPoamItem005(string $value) {
        $this->poam_item_005 = $value;
    }

    public function setPoamItem006(string $value) {
        $this->poam_item_006 = $value;
    }

    public function setPoamItem007(string $value) {
        $this->poam_item_007 = $value;
    }

    public function setPoamItem008(string $value) {
        $this->poam_item_008 = $value;
    }

    public function setPoamItem009(string $value) {
        $this->poam_item_009 = $value;
    }

    public function setPoamItem010(string $value) {
        $this->poam_item_010 = $value;
    }

    public function setPoamItem011(string $value) {
        $this->poam_item_011 = $value;
    }

    public function setPoamItem012(string $value) {
        $this->poam_item_012 = $value;
    }

    public function setPoamItem013(string $value) {
        $this->poam_item_013 = $value;
    }

    public function setPoamItem014(string $value) {
        $this->poam_item_014 = $value;
    }

    public function setPoamItem015(string $value) {
        $this->poam_item_015 = $value;
    }

    public function setPoamItem016(string $value) {
        $this->poam_item_016 = $value;
    }

    public function setPoamItem017(string $value) {
        $this->poam_item_017 = $value;
    }

    public function setPoamItem018(string $value) {
        $this->poam_item_018 = $value;
    }

    public function setPoamItem019(string $value) {
        $this->poam_item_019 = $value;
    }

    public function setPoamItem020(string $value) {
        $this->poam_item_020 = $value;
    }

    public function setPoamItem021(string $value) {
        $this->poam_item_021 = $value;
    }

    public function setPoamItem022(string $value) {
        $this->poam_item_022 = $value;
    }

    public function setPoamItem023(string $value) {
        $this->poam_item_023 = $value;
    }

    public function setPoamItem024(string $value) {
        $this->poam_item_024 = $value;
    }

    public function setPoamItem025(string $value) {
        $this->poam_item_025 = $value;
    }

    public function setPoamItem026(string $value) {
        $this->poam_item_026 = $value;
    }

    public function setPoamItem027(string $value) {
        $this->poam_item_027 = $value;
    }

    public function setPoamItem028(string $value) {
        $this->poam_item_028 = $value;
    }

    public function setPoamItem029(string $value) {
        $this->poam_item_029 = $value;
    }

    public function setPoamItem030(string $value) {
        $this->poam_item_030 = $value;
    }

    public function getPoamItem003() {
        return $this->poam_item_003;
    }

    public function getPoamItem004() {
        return $this->poam_item_004;
    }

    public function getPoamItem005() {
        return $this->poam_item_005;
    }

    public function getPoamItem006() {
        return $this->poam_item_006;
    }

    public function getPoamItem007() {
        return $this->poam_item_007;
    }

    public function getPoamItem008() {
        return $this->poam_item_008;
    }

    public function getPoamItem009() {
        return $this->poam_item_009;
    }

    public function getPoamItem010() {
        return $this->poam_item_010;
    }

    public function getPoamItem011() {
        return $this->poam_item_011;
    }

    public function getPoamItem012() {
        return $this->poam_item_012;
    }

    public function getPoamItem013() {
        return $this->poam_item_013;
    }

    public function getPoamItem014() {
        return $this->poam_item_014;
    }

    public function getPoamItem015() {
        return $this->poam_item_015;
    }

    public function getPoamItem016() {
        return $this->poam_item_016;
    }

    public function getPoamItem017() {
        return $this->poam_item_017;
    }

    public function getPoamItem018() {
        return $this->poam_item_018;
    }

    public function getPoamItem019() {
        return $this->poam_item_019;
    }

    public function getPoamItem020() {
        return $this->poam_item_020;
    }

    public function getPoamItem021() {
        return $this->poam_item_021;
    }

    public function getPoamItem022() {
        return $this->poam_item_022;
    }

    public function getPoamItem023() {
        return $this->poam_item_023;
    }

    public function getPoamItem024() {
        return $this->poam_item_024;
    }

    public function getPoamItem025() {
        return $this->poam_item_025;
    }

    public function getPoamItem026() {
        return $this->poam_item_026;
    }

    public function getPoamItem027() {
        return $this->poam_item_027;
    }

    public function getPoamItem028() {
        return $this->poam_item_028;
    }

    public function getPoamItem029() {
        return $this->poam_item_029;
    }

    public function getPoamItem030() {
        return $this->poam_item_030;
    }
}
?>