<?php
require_once('../../config/util.php');

require_once('../../models/user.php');
require_once('../../models/log.php');
require_once('../../models/log_db.php');
require_once('../../models/admin.php');
require_once('../../models/admin_db.php');
require_once('../../models/baseline_db.php');
require_once('../../models/baseline.php');
require_once('../../models/company.php');
require_once('../../models/company_db.php');
require_once('../../models/poam.php');
require_once('../../models/ctrl.php');
require_once('../../models/fields.php');
require_once('../../models/validate.php');


$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {        
        $action = 'view_dashboard';
    }
}

switch ($action) {
    case 'start_baseline':
        $companies = CompanyDB::getAllCompanies();
        $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
        $current_client = CompanyDB::getCompany($_SESSION['co_id']);
        
        include 'baseline_start.php';
        break;
    case 'create_baseline':
        $companies = CompanyDB::getAllCompanies();
        $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
        $current_client = CompanyDB::getCompany($_SESSION['co_id']);

        $baseline = new Baseline();
        $baseline->setBaselineCOID(filter_input(INPUT_POST, 'co_id'));
        $baseline->setBaselineSystem(filter_input(INPUT_POST, 'bl_system'));
        $baseline->setImpactLvl(filter_input(INPUT_POST, 'bl_impact_lvl'));
        $baseline->setStartDate(date("Y-m-d H:i:s"));
        $baseline->setModDate(date("Y-m-d H:i:s"));
        $baseline->setComments(filter_input(INPUT_POST, 'bl_comments'));

        if (isset($_POST['hide_unselected'])) {
            $hide = TRUE;
        } else {
            $hide = FALSE;
        }

        $baseline->setHidden($hide);
        $bl_id = BaselineDB::addBaseline($baseline);
        $baselines = BaselineDB::getAllBaselines($_SESSION['co_id']);

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setCompanyID($_SESSION['co_id']);
        $l->setBaselineID($bl_id);
        $l->setAccData($current_admin->getName().' created Baseline ID: '.$bl_id.' for '.$current_client->getName().'.');
        LogDB::updateLog($l);

        include 'baseline_view.php';
        break;
    case 'view_baselines':
        $companies = CompanyDB::getAllCompanies();
        $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
        $current_client = CompanyDB::getCompany($_SESSION['co_id']);
        $baselines = BaselineDB::getAllBaselines($_SESSION['co_id']);

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setCompanyID($_SESSION['co_id']);
        $l->setAccData($current_admin->getName().' viewed baseline database.');
        LogDB::updateLog($l);

        include 'baseline_view.php';
        break;
    case 'logout':
        $_SESSION = [];
        include('../index.php');
        break;
    default:
        display_error("Unknown account action: " . $action);
        break;
}