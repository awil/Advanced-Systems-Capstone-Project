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

    public static function getAllBaselines(int $co_id) {
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
            // $sql = 'SELECT * FROM ' . $framework . ' JOIN nistbaselines
            //         ON ' . $framework . '.ctrl_id=nistbaselines.ctrl_id WHERE ctrl_base_'
            //         . $impact .'=\'x\' OR ctrl_base_low = \'\' AND ctrl_base_mod = \'\' AND ctrl_base_high = \'\'  LIMIT 50';
        }
        $statement = $db->prepare($sql);
        $statement->execute();
        $clientctrls = $statement->fetchAll();
        $statement->closeCursor();

        try {
            foreach ($clientctrls as $row) {
                $ctrl_id = $row['ctrl_id'];
                $q = "INSERT INTO bl_controls (bl_id, ctrl_id, blc_stat, blc_created, blc_modified, blc_comments)
                VALUES ('$bl_id','$ctrl_id','In_Progress',NOW(), NOW(),'')";
                $statement = $db->prepare($q);
                $statement->execute();
                $statement->closeCursor();
            }
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


function get_clients() {
    global $db;
    $sql = 'SELECT * FROM clients';
    $statement = $db->prepare($sql);
    $statement->execute();
    $clients = $statement->fetchAll();
    $statement->closeCursor();
    return $clients;   
}

function get_client_id($id) {
    global $db;
    $sql = 'SELECT * FROM clients WHERE cl_id = :clientid';
    $statement = $db->prepare($sql);
    $statement->bindValue(':clientid', $id);
    $statement->execute();
    $client = $statement->fetch();
    $GLOBALS['curr_client'] = $client;
    $statement->closeCursor();
    return $client;   
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