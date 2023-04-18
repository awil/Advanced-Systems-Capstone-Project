<?php

class Baseline {
    public function __construct(
        private int $bl_id = 0,
        private int $co_id = 0,
        private string $bl_system = '',
        private string $bl_impact_lvl = '',
        private string $bl_stat = '',
        private string $bl_created = '',
        private string $bl_modified = '',
        private string $bl_comments = '',
        private bool $bl_hideselect = false,
    ) {}

    public function getBaselineID() {
        return $this->bl_id;
    }

    public function setBaselineID(int $value) {
        $this->bl_id = $value;
    }

    public function getBaselineCOID() {
        return $this->co_id;
    }

    public function setBaselineCOID(int $value) {
        $this->co_id = $value;
    }

    public function getBaselineSystem() {
        return $this->bl_system;
    }

    public function setBaselineSystem(string $value) {
        $this->bl_system = $value;
    }

    public function getImpactLvl() {
        return $this->bl_impact_lvl;
    }

    public function setImpactLvl(string $value) {
        $this->bl_impact_lvl = $value;
    }

    public function getBaselineStat() {
        return $this->bl_stat;
    }

    public function setBaselineStat(string $value) {
        $this->bl_stat = $value;
    }

    public function getStartDate() {
        return $this->bl_created;
    }

    public function setStartDate(string $value) {
        $this->bl_created = $value;
    }

    public function getModDate() {
        return $this->bl_modified;
    }

    public function setModDate(string $value) {
        $this->bl_modified = $value;
    }

    public function getComments() {
        return $this->bl_comments;
    }

    public function setComments(string $value) {
        $this->bl_comments = $value;
    }

    public function getShort() {
        $db = Database::getDB();
        $q = 'SELECT co_short FROM companies WHERE co_id = :co_id';
        try {
            $stmt = $db->prepare($q);
            $stmt->bindValue(':co_id', $this->co_id);
            $stmt->execute();
            
            $short = $stmt->fetch();
            $stmt->closeCursor();

            $short = $short['co_short'];
            return $short;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public function getHidden() {
        return $this->bl_hideselect;
    }

    public function setHidden(bool $value) {
        $this->bl_hideselect = $value;
    }
}

?>