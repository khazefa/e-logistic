<?php
$fcode = '';
$fname = '';
$flocation = '';
$fcontact = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $fcode = $r["code"];
        $fname = $r["name"];
        $flocation = $r["location"];
        $fcontact = $r["contact"];
    }
}
?>
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
                <form class="form-horizontal" action="<?php echo base_url('front/cpartners/update');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fkey" class="col-2 col-form-label">Partner Alias</label>
                        <div class="col-2">
                            <input type="text" name="fkey" id="fkey" value="<?php echo $fcode; ?>" class="form-control" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-2 col-form-label">Partner Name</label>
                        <div class="col-4">
                            <input type="text" name="fname" id="fname" required value="<?php echo $fname; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fcontact" class="col-2 col-form-label">Partner Contact</label>
                        <div class="col-4">
                            <input type="text" name="fcontact" id="fcontact" required value="<?php echo $fcontact; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="flocation" class="col-2 col-form-label">Partner Location</label>
                        <div class="col-4">
                            <input type="text" name="flocation" id="flocation" value="<?php echo $flocation; ?>" class="form-control">
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