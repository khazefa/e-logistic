<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-30 pull-right"><?php echo $contentTitle;?></h4><br><hr>
            <p>Please choose warehouse that you want to manage it's stock parts.</p>
            
            <p class="text-success text-center">
                <?php
                $error = $this->session->flashdata('error');
                if($error)
                {
                ?>
                <div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $error; ?>                    
                </div>
                <?php
                }
                $success = $this->session->flashdata('success');
                if($success)
                {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $success; ?>                    
                </div>
                <?php } ?>
            </p>
            
            <div class="card-body">
                <div class="row">
                    <?php
                        foreach ($list_data_wh as $w){
                            $wh_code = strtolower($w["code"]);
                    ?>
                        <div class="col-md-2">
                            <h4 class="card-header text-center">FSL</h4>
                            <div class="card m-b-30 card-body">
                                <h2 class="card-title text-center">
                                    <a href="<?php echo base_url("oversee/detail-spareparts-stock/").$wh_code; ?>" class="btn btn-custom waves-effect waves-light"><?php echo $w["code"]; ?></a>
                                </h2>
                                <p class="card-text text-center"><?php echo $w["name"]; ?></p>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>