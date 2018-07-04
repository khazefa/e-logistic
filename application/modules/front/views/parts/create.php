<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <a href="javascript:history.back()"><i class="fa fa-reply"></i> Back</a>
            <h4 class="header-title m-b-30 pull-right"><?php echo $contentTitle;?></h4><hr>
            
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
                <form class="form-horizontal" action="<?php echo base_url('front/cparts/create');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fpartnum" class="col-2 col-form-label">Part Number</label>
                        <div class="col-4">
                            <input type="text" name="fpartnum" id="fpartnum" required placeholder="Part Number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-2 col-form-label">Part Name</label>
                        <div class="col-6">
                            <input type="text" name="fname" id="fname" required placeholder="Part Name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fdesc" class="col-2 col-form-label">Part Description</label>
                        <div class="col-6">
                            <textarea name="fdesc" id="fdesc" placeholder="Part Description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fmachine" class="col-2 col-form-label">Machine</label>
                        <div class="col-6">
                            <input type="text" name="fmachine" id="fmachine" required placeholder="Machine" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="btn" class="col-2 col-form-label">&nbsp;</label>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        
    });
</script>