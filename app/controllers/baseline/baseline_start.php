<?php 
    include 'views/header.php'; 
    ?>

<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="row my-4 justify-content-center">
        <div class="justify-content-center">
        <h1>Start New Baseline</h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="card bg-light ">
            <div class="card-body">
            <form action="." method="post">
            <input type="hidden" name="action" value="create_baseline">
            <input type="hidden" name="adm_id" value="<?php echo $current_admin->getID(); ?>">

            <div class="pl-lg-4">
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="input-group mb-3">
                        <label class="input-group-text" for="co_id">Company</label>
                        <select name="co_id" id="co_id" class="form-control form-control-alternative">
                            <option value="">Choose...</option>
                            <?php foreach($companies as $company) : ?>
                                <option value="<?php echo $company->getID(); ?>" 
                                <?php if(isset($_SESSION['co_id']) && $company->getID() == $_SESSION['co_id']) {
                                    echo 'selected="selected"';
                                } ?>>
                                    <?php echo htmlspecialchars($company->getName()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group mb-3">
                        <label class="input-group-text" for="bl_system">Framework</label>
                        <select name="bl_system" id="bl_system" class="form-control form-control-alternative">
                            <option value="nist80053oscal">Choose...</option>
                            <option value="nist80053oscal">NIST SP 800-53</option>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="input-group mb-1">
                        <label class="input-group-text" for="bl_impact_lvl">Impact Level</label>
                        <select name="bl_impact_lvl" id="bl_impact_lvl" class="form-control form-control-alternative">
                            <option value="low">Choose...</option>
                            <option value="low">Low</option>
                            <option value="mod">Moderate</option>
                            <option value="high">High</option>
                        </select>                    
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="input-group mb-1">
                            <div class="input-group-text">
                                <input type="checkbox" name="hide_unselected" class="form-check-input mt-0">
                                
                            </div>
                            <label class="form-control" for="hide_unselected">Hide Unselected Controls</label>
                    </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="">
                        <div class="input-group mb-3">
                        <label class="input-group-text" for="bl_comments">Notes (Optional)</label>
                        <input type="text" name="bl_comments" id="bl_comments" class="form-control form-control-alternative" value=""/>                        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="input-group mb-3">
                        <input class="btn btn-sm btn-primary" type="submit" value="Create" />
                        </div>
                    </div>
                </div>
            </div>

            </form>

            </div>
        </div>
    </div>
</div>


<?php include 'views/footer.php'; ?>