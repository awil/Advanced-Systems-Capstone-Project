<?php
require_once('../../config/util.php');

require_once('../../models/user.php');
require_once('../../models/log.php');
require_once('../../models/log_db.php');
require_once('../../models/admin.php');
require_once('../../models/admin_db.php');
require_once('../../models/baseline.php');
require_once('../../models/baseline_db.php');
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

if (isset($_SESSION['adm_id'])) {
    $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
}
if (isset($_SESSION['co_id'])) {
    $current_client = CompanyDB::getCompany($_SESSION['co_id']);
}

switch ($action) {
    case 'start_baseline':
        $companies = CompanyDB::getAllCompanies();
        // $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
        // $current_client = CompanyDB::getCompany($_SESSION['co_id']);
        
        include 'baseline_start.php';
        break;
    case 'create_baseline':
        $companies = CompanyDB::getAllCompanies();
        // $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
        // $current_client = CompanyDB::getCompany($_SESSION['co_id']);

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
        $_SESSION['bl_id'] = $bl_id;
        $baselines = BaselineDB::getAllBaselines($_SESSION['co_id']);

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setCompanyID($_SESSION['co_id']);
        $l->setBaselineID($bl_id);
        $l->setAccData($current_admin->getName().' created Baseline ID: '.$bl_id.' for '.$current_client->getName().'.');
        LogDB::updateLog($l);

        $bl_head = $current_client->getShort().'\'s';

        include 'baseline_view.php';
        break;
    case 'view_baselines':
        $companies = CompanyDB::getAllCompanies();
        // $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
        // $current_client = CompanyDB::getCompany($_SESSION['co_id']);
        $baselines = BaselineDB::getAllBaselines();

        $bl_head = 'Current';

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setAccData($current_admin->getName().' accessed baseline database');
        LogDB::updateLog($l);

        include 'baseline_view.php';
        break;
    case 'view_co_baselines':
        $companies = CompanyDB::getAllCompanies();
        // $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
        // $current_client = CompanyDB::getCompany($_SESSION['co_id']);
        $baselines = BaselineDB::getClientBaselines($_SESSION['co_id']);

        $bl_head = $current_client->getShort().'\'s';

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setCompanyID($_SESSION['co_id']);
        $l->setAccData($current_admin->getName().' accessed '.$current_client->getShort().' baselines');
        LogDB::updateLog($l);

        include 'baseline_view.php';
        break;
    case 'edit_baseline':
        $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
        $current_client = CompanyDB::getCompany($_SESSION['co_id']);
        $bl_head = $current_client->getShort().'\'s';
        
        if(!isset($_SESSION['bl_id'])) {
            $_SESSION['bl_id'] = filter_input(INPUT_POST, 'bl_id');
        }

        $bl_id = $_SESSION['bl_id'];

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit = 10;

        $ctrls = BaselineDB::getPageCtrls($bl_id, $page, $limit);
        $tot_row = BaselineDB::getTotalRows($bl_id);
        $tot_pg = ceil($tot_row / $limit);

        include 'baseline_edit.php';
        break;
    case 'mod_poam':
        $blc_id = filter_input(INPUT_POST, 'blc_id');
        $bl_id = filter_input(INPUT_POST, 'bl_id');
        $ctrl = BaselineDB::getCtrl($blc_id);

        if ($_POST['submit'] === 'Start') {
            // Handle start 

            include 'poam_start.php';
        } else if ($_POST['submit'] === 'Edit') {
            // Handle edit 

            $poam = BaselineDB::getPOAMbyBLC($blc_id);

            include 'poam_edit.php';
        } else if ($_POST['submit'] === 'View') {
            // Handle view

            include 'poam_view.php';
        }
        
        break;
    case 'create_poam':
        $current_admin = AdminDB::getAdmin(filter_input(INPUT_POST, 'adm_id'));
        $current_client = CompanyDB::getCompany(filter_input(INPUT_POST, 'co_id'));
        $blc_id = filter_input(INPUT_POST, 'blc_id');
        $bl_id = filter_input(INPUT_POST, 'bl_id');

        $np = new POAM();
        
        $np->setBaselineID($bl_id);
        $np->setBLCtrlID($blc_id);
        $np->setStartDate(date("Y-m-d H:i:s"));
        $np->setModDate(date("Y-m-d H:i:s"));
        $np->setPoamItem003(filter_input(INPUT_POST, 'poam_item_003'));
        $np->setPoamItem004(filter_input(INPUT_POST, 'poam_item_004'));
        $np->setPoamItem005(filter_input(INPUT_POST, 'poam_item_005'));
        $np->setPoamItem006(filter_input(INPUT_POST, 'poam_item_006'));
        $np->setPoamItem007(filter_input(INPUT_POST, 'poam_item_007'));
        $np->setPoamItem008(filter_input(INPUT_POST, 'poam_item_008'));
        $np->setPoamItem009(filter_input(INPUT_POST, 'poam_item_009'));
        $np->setPoamItem010(filter_input(INPUT_POST, 'poam_item_010'));
        $np->setPoamItem011(filter_input(INPUT_POST, 'poam_item_011'));
        $np->setPoamItem012(filter_input(INPUT_POST, 'poam_item_012'));
        $np->setPoamItem013(filter_input(INPUT_POST, 'poam_item_013'));
        $np->setPoamItem014(filter_input(INPUT_POST, 'poam_item_014'));
        $np->setPoamItem015(filter_input(INPUT_POST, 'poam_item_015'));
        $np->setPoamItem016(filter_input(INPUT_POST, 'poam_item_016'));
        $np->setPoamItem017(filter_input(INPUT_POST, 'poam_item_017'));
        $np->setPoamItem018(filter_input(INPUT_POST, 'poam_item_018'));
        $np->setPoamItem019(filter_input(INPUT_POST, 'poam_item_019'));
        $np->setPoamItem020(filter_input(INPUT_POST, 'poam_item_020'));
        $np->setPoamItem021(filter_input(INPUT_POST, 'poam_item_021'));
        $np->setPoamItem022(filter_input(INPUT_POST, 'poam_item_022'));
        $np->setPoamItem023(filter_input(INPUT_POST, 'poam_item_023'));
        $np->setPoamItem024(filter_input(INPUT_POST, 'poam_item_024'));
        $np->setPoamItem025(filter_input(INPUT_POST, 'poam_item_025'));
        $np->setPoamItem026(filter_input(INPUT_POST, 'poam_item_026'));
        $np->setPoamItem027(filter_input(INPUT_POST, 'poam_item_027'));
        $np->setPoamItem028(filter_input(INPUT_POST, 'poam_item_028'));
        $np->setPoamItem029(filter_input(INPUT_POST, 'poam_item_029'));
        $np->setPoamItem030(filter_input(INPUT_POST, 'poam_item_030'));

        $new_poam_id = BaselineDB::addPOAM($np);
        $poam = BaselineDB::getPOAM($new_poam_id);
        $ctrl = BaselineDB::getCtrl($poam->getBLCtrlID());
        $ctrl->setPOAM(TRUE);

        include 'poam_edit.php';
        
        break;
    case 'logout':
        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setAccData($current_admin->getName().' logged out');
        LogDB::updateLog($l);

        $_SESSION = [];
        include('../index.php');
        break;
    default:
        display_error("Unknown account action: " . $action);
        break;
}