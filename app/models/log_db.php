<?php

class LogDB {

    public static function updateLog(Log $log) {
        $db = Database::getDB();
        $q = 'INSERT INTO access_log
                (acc_id, adm_id, acc_date, acc_data, co_id, bl_id)
                VALUES
                (null, :adm_id, :acc_date, :acc_data, :co_id, :bl_id)';
        try {
            $stmt = $db->prepare($q);
            $stmt->bindValue(':adm_id', $log->getAdmID());
            $stmt->bindValue(':acc_date', $log->getAccDate());
            $stmt->bindValue(':acc_data', $log->getAccData());
            $stmt->bindValue(':co_id', $log->getCompanyID());
            $stmt->bindValue(':bl_id', $log->getBaselineID());
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    private static function loadLog($row) {
        if ($row) {
            return new Log($row['acc_id'], $row['adm_id'], $row['acc_date'], $row['acc_data'], $row['co_id'], $row['bl_id'],);
        } else {
            return NULL;
        }
    }

    public static function getAccessLog() {
        $db = Database::getDB();
        $q = 'SELECT * FROM access_log ORDER BY acc_date DESC LIMIT 20';

        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $stmt->closeCursor();

            $logs = [];
            foreach($rows as $row) {
                $logs[] = self::loadLog($row);
            }
            return $logs;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getPageLog($page = 1, $limit = 25) {
        $start = ($page - 1) * $limit;

        $db = Database::getDB();
        $q = 'SELECT * FROM access_log ORDER BY acc_date DESC LIMIT '.$start.', '.$limit;

        try {
            $stmt = $db->prepare($q);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $stmt->closeCursor();

            $logs = [];
            foreach($rows as $row) {
                $logs[] = self::loadLog($row);
            }
            return $logs;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getTotalRows() {
        $db = Database::getDB();
        $q = 'SELECT COUNT(*) as total FROM access_log';

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

}

?>