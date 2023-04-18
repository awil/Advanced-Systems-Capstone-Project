<?php

class BaselineDB {
    
    public static function addBaseline(Baseline $baseline) {
        $db = Database::getDB();
        $q = 'INSERT INTO baselines
                    (co_id, bl_system, bl_impact_lvl, bl_stat, bl_created, bl_modified, bl_comments, bl_hideselect)
                    VALUES
                    (:co_id, :bl_system, :bl_impact_lvl, :bl_stat, :bl_created, :bl_modified, :bl_comments, :bl_hideselect)';
        try {
            $stmt = $db->prepare($q);
            $stmt->bindValue(':co_id', $baseline->getBaselineCOID());
            $stmt->bindValue(':bl_system', $baseline->getBaselineSystem());
            $stmt->bindValue(':bl_impact_lvl', $baseline->getImpactLvl());
            $stmt->bindValue(':bl_stat', 'In_Progress');
            $stmt->bindValue(':bl_created', $baseline->getStartDate());
            $stmt->bindValue(':bl_modified', $baseline->getModDate());
            $stmt->bindValue(':bl_comments', $baseline->getComments());
            $stmt->bindValue(':bl_hideselect', $baseline->getHidden());

            $stmt->execute();

            $bl_id = $db->lastInsertId();
            $blsys = $baseline->getBaselineSystem();
            $blimp = $baseline->getImpactLvl();
            $blhid = $baseline->getHidden();
            self::addControls($bl_id, $blsys, $blimp, $blhid);

            $stmt->closeCursor();
            return $bl_id;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    private static function loadBaseline($row) {
        if ($row) {
            return new Baseline($row['bl_id'], $row['co_id'], $row['bl_system'], $row['bl_impact_lvl'], $row['bl_stat'], $row['bl_created'], $row['bl_modified'], $row['bl_comments'], $row['bl_hideselect'],);
        } else {
            return NULL;
        }
    }

    public static function getCtrl($blc_id) {
        $db = Database::getDB();
        $q = 'SELECT * FROM bl_controls WHERE blc_id = '.$blc_id;

        try {
            $stmt = $db->prepare($q);
            $stmt->execute();

            $ct = $stmt->fetch();
            $stmt->closeCursor();

            $ctrl = self::loadCtrl($ct);

            return $ctrl;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    private static function loadCtrl($row) {
        if ($row) {
            return new Ctrl($row['blc_id'], $row['bl_id'], $row['ctrl_id'], $row['blc_stat'], $row['blc_poam'], $row['blc_created'], $row['blc_modified'], $row['blc_comments'],);
        } else {
            return NULL;
        }
    }

    public static function getPageCtrls($bl_id, $page, $limit) {
        $start = ($page - 1) * $limit;

        $db = Database::getDB();
        $q = 'SELECT * FROM bl_controls WHERE bl_id='.$bl_id.' LIMIT '.$start.', '.$limit;

        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $stmt->closeCursor();

            $ctrls = [];
            foreach($rows as $row) {
                $ctrls[] = self::loadCtrl($row);
            }
            return $ctrls;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getTotalRows($bl_id) {
        $db = Database::getDB();
        $q = 'SELECT COUNT(*) as total FROM bl_controls WHERE bl_id='.$bl_id;

        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            $row = $stmt->fetch();
            $stmt->closeCursor();

            return $row['total'];
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }

        return 0;
    }

    public static function getAllBaselines() {
        $db = Database::getDB();
        $q = 'SELECT * FROM baselines ORDER BY bl_modified';

        try {
            $stmt = $db->prepare($q);
            $stmt->execute();

            $rows = $stmt->fetchAll();
            $stmt->closeCursor();

            $baselines = [];
            foreach($rows as $row) {
                $baselines[] = self::loadBaseline($row);
            }
            return $baselines;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getClientBaselines(int $co_id) {
        $db = Database::getDB();
        $q = 'SELECT * FROM baselines WHERE co_id = :co_id ORDER BY bl_modified';

        try {
            $stmt = $db->prepare($q);
            $stmt->bindValue(':co_id', $co_id);

            $stmt->execute();

            $rows = $stmt->fetchAll();
            $stmt->closeCursor();

            $baselines = [];
            foreach($rows as $row) {
                $baselines[] = self::loadBaseline($row);
            }
            return $baselines;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function addControls($bl_id, $bl_system, $bl_impact_lvl, $bl_hideselect) {
        $db = Database::getDB();

        if($bl_hideselect == FALSE) {
            $sql = 'SELECT * FROM '.$bl_system.' JOIN nistbaselines
                    ON ' . $bl_system . '.ctrl_id=nistbaselines.ctrl_id';
        } else {
            $sql = 'SELECT * FROM '.$bl_system.' JOIN nistbaselines
            ON ' . $bl_system . '.ctrl_id=nistbaselines.ctrl_id WHERE ctrl_base_'
            . $bl_impact_lvl .'=\'x\'';
        }
        $statement = $db->prepare($sql);
        $statement->execute();
        $ctrls = $statement->fetchAll();
        $statement->closeCursor();

        try {
            foreach ($ctrls as $row) {
                $ctrl_id = $row['ctrl_id'];
                $q = "INSERT INTO bl_controls (bl_id, ctrl_id, blc_stat, blc_poam, blc_created, blc_modified, blc_comments)
                VALUES ('$bl_id','$ctrl_id','In_Progress', 0, NOW(), NOW(),'')";
                $statement = $db->prepare($q);
                $statement->execute();
                $statement->closeCursor();
            }
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getPOAM($pid) {
        $db = Database::getDB();
        $q = 'SELECT * FROM poam WHERE poam_id = '.$pid;

        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            $po = $stmt->fetch();
            $stmt->closeCursor();

            $stmt->execute();

            $poam = self::loadPoam($po);
            return $poam;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getPOAMbyBLC($blc_id) {
        $db = Database::getDB();
        $q = 'SELECT * FROM poam WHERE blc_id = '.$blc_id;

        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            $po = $stmt->fetch();
            $stmt->closeCursor();

            $stmt->execute();

            $poam = self::loadPoam($po);
            return $poam;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    private static function loadPoam($row) {
        if ($row) {
            return new POAM($row['poam_id'], $row['bl_id'], $row['blc_id'], $row['poam_created'], $row['poam_modified'], $row['poam_item_003'], $row['poam_item_004'], $row['poam_item_005'], $row['poam_item_006'], $row['poam_item_007'], $row['poam_item_008'], $row['poam_item_009'], $row['poam_item_010'], $row['poam_item_011'], $row['poam_item_012'], $row['poam_item_013'], $row['poam_item_014'], $row['poam_item_015'], $row['poam_item_016'], $row['poam_item_017'], $row['poam_item_018'], $row['poam_item_019'], $row['poam_item_020'], $row['poam_item_021'], $row['poam_item_022'], $row['poam_item_023'], $row['poam_item_024'], $row['poam_item_025'], $row['poam_item_026'], $row['poam_item_027'], $row['poam_item_028'], $row['poam_item_029'], $row['poam_item_030'],);
        } else {
            return NULL;
        }
    }

    public static function addPOAM(POAM $p) {
        $db = Database::getDB();

        try {
            $q = 'INSERT INTO poam VALUES
                    (null, :bl_id, :blc_id, :poam_created, :poam_modified, :poam_item_003, :poam_item_004, :poam_item_005, :poam_item_006, :poam_item_007, :poam_item_008, :poam_item_009, :poam_item_010, :poam_item_011, :poam_item_012, :poam_item_013, :poam_item_014, :poam_item_015, :poam_item_016, :poam_item_017, :poam_item_018, :poam_item_019, :poam_item_020, :poam_item_021, :poam_item_022, :poam_item_023, :poam_item_024, :poam_item_025, :poam_item_026, :poam_item_027, :poam_item_028, :poam_item_029, :poam_item_030 )';
            $stmt = $db->prepare($q);
            $stmt->bindValue(':bl_id', $p->getBaselineID());
            $stmt->bindValue(':blc_id', $p->getBLCtrlID());
            $stmt->bindValue(':poam_created', $p->getStartDate());
            $stmt->bindValue(':poam_modified', $p->getModDate());
            $stmt->bindValue(':poam_item_003', $p->getPoamItem003());
            $stmt->bindValue(':poam_item_004', $p->getPoamItem004());
            $stmt->bindValue(':poam_item_005', $p->getPoamItem005());
            $stmt->bindValue(':poam_item_006', $p->getPoamItem006());
            $stmt->bindValue(':poam_item_007', $p->getPoamItem007());
            $stmt->bindValue(':poam_item_008', $p->getPoamItem008());
            $stmt->bindValue(':poam_item_009', $p->getPoamItem009());
            $stmt->bindValue(':poam_item_010', $p->getPoamItem010());
            $stmt->bindValue(':poam_item_011', $p->getPoamItem011());
            $stmt->bindValue(':poam_item_012', $p->getPoamItem012());
            $stmt->bindValue(':poam_item_013', $p->getPoamItem013());
            $stmt->bindValue(':poam_item_014', $p->getPoamItem014());
            $stmt->bindValue(':poam_item_015', $p->getPoamItem015());
            $stmt->bindValue(':poam_item_016', $p->getPoamItem016());
            $stmt->bindValue(':poam_item_017', $p->getPoamItem017());
            $stmt->bindValue(':poam_item_018', $p->getPoamItem018());
            $stmt->bindValue(':poam_item_019', $p->getPoamItem019());
            $stmt->bindValue(':poam_item_020', $p->getPoamItem020());
            $stmt->bindValue(':poam_item_021', $p->getPoamItem021());
            $stmt->bindValue(':poam_item_022', $p->getPoamItem022());
            $stmt->bindValue(':poam_item_023', $p->getPoamItem023());
            $stmt->bindValue(':poam_item_024', $p->getPoamItem024());
            $stmt->bindValue(':poam_item_025', $p->getPoamItem025());
            $stmt->bindValue(':poam_item_026', $p->getPoamItem026());
            $stmt->bindValue(':poam_item_027', $p->getPoamItem027());
            $stmt->bindValue(':poam_item_028', $p->getPoamItem028());
            $stmt->bindValue(':poam_item_029', $p->getPoamItem029());
            $stmt->bindValue(':poam_item_030', $p->getPoamItem030());

            $stmt->execute();
            $poam_id = $db->lastInsertId();
            $stmt->closeCursor();
            self::updateCtrlPoam($p->getBLCtrlID());

            return $poam_id;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function updateCtrlPOAM($blc_id) {
        $db = Database::getDB();
        $q = 'UPDATE bl_controls SET blc_poam = TRUE WHERE blc_id='.$blc_id;
        
        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }
}

function get_controls($framework, $impact, $hide) {
    global $db;
    if($hide == FALSE) {
        $sql = 'SELECT * FROM ' . $framework . ' JOIN nistbaselines
                ON ' . $framework . '.ctrl_id=nistbaselines.ctrl_id LIMIT 50';
    } else {
        $sql = 'SELECT * FROM ' . $framework . ' JOIN nistbaselines
        ON ' . $framework . '.ctrl_id=nistbaselines.ctrl_id WHERE ctrl_base_'
        . $impact .'=\'x\' LIMIT 50';
        // $sql = 'SELECT * FROM ' . $framework . ' JOIN nistbaselines
        //         ON ' . $framework . '.ctrl_id=nistbaselines.ctrl_id WHERE ctrl_base_'
        //         . $impact .'=\'x\' OR ctrl_base_low = \'\' AND ctrl_base_mod = \'\' AND ctrl_base_high = \'\'  LIMIT 50';
    }
    $statement = $db->prepare($sql);
    $statement->execute();
    $controls = $statement->fetchAll();
    $statement->closeCursor();
    return $controls;
}



function save_baseline($clientctrls) {
    global $link;
    foreach ($clientctrls as $row) {
        $cli_id = $row['clientid'];
        $ct_id = $row['cid'];
        $ct_nm = $row['ctrlname'];
        $ct_sel = $row['ctrlsel'];

        if ($ct_sel === "on") {
            $ct_sel = "In_Progress";
        } else {
            $ct_sel = "Not_Applicable";
        }

        $sql = "INSERT INTO baselines (bl_id, co_id, ctrl_id, bl_stat, bl_created, bl_modified, bl_comments)
        VALUES (NULL,'$cli_id','$ct_id','$ct_sel',NOW(), NOW(), '')";
        mysqli_query($link, $sql);
    }
}

function update_baseline($bl_implementation) {
    global $link;
    foreach ($bl_implementation as $row) {
        $co_id = $row['co_id'];
        $ctrl_id = $row['ctrl_id'];
        $bl_stat = $row['bl_stat'];
        $bl_comments = $row['bl_comments'];
        $bl_created = $row['bl_created'];

        $sql = "UPDATE baselines SET bl_stat = '$bl_stat',
        bl_comments = '$bl_comments' WHERE co_id = '$co_id' AND ctrl_id = '$ctrl_id'
        AND bl_created = '$bl_created'";
        mysqli_query($link, $sql);
    }
}

function get_saved_baseline($clientid) {
    global $db;
    $sql = 'SELECT sb.bl_id, sb.co_id, sb.ctrl_id, sb.bl_stat, sb.bl_created, sb.bl_modified, sb.bl_comments, nio.ctrl_name, nio.ctrl_desc
            FROM baselines sb JOIN nist80053oscal nio ON sb.ctrl_id=nio.ctrl_id
            WHERE sb.co_id = :clientid LIMIT 25';
    $statement = $db->prepare($sql);
    $statement->bindValue(':clientid', $clientid);
    $statement->execute();
    $baselines = $statement->fetchAll();
    $statement->closeCursor();
    return $baselines;  
}

function get_saved_baseline_for_client($clientid) {
    global $db;
    $sql = 'SELECT *
            FROM baselines
            WHERE sb.co_id = :clientid LIMIT 25';
    $statement = $db->prepare($sql);
    $statement->bindValue(':clientid', $clientid);
    $statement->execute();
    $baselines = $statement->fetchAll();
    $statement->closeCursor();
    return $baselines;  
}

?>