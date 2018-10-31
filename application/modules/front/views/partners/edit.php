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
    <div class="col-md-6 offset-md-3">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><?php echo $contentTitle;?></h4>
            <div class="btn-group">
                <button type="button" onclick="location.href='javascript:history.back()'" class="btn btn-sm btn-light waves-effect">
                    <i class="mdi mdi-keyboard-backspace font-18 vertical-middle"></i> Back
                </button>
            </div>
            
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
                <form class="form-horizontal" action="<?php echo base_url($classname.'/modify');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fkey" class="col-3 col-form-label">Partner Alias</label>
                        <div class="col-9">
                            <input type="text" name="fkey" id="fkey" value="<?php echo $fcode; ?>" class="form-control" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-3 col-form-label">Partner Name</label>
                        <div class="col-9">
                            <input type="text" name="fname" id="fname" required value="<?php echo $fname; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fcontact" class="col-3 col-form-label">Partner Contact</label>
                        <div class="col-9">
                            <input type="text" name="fcontact" id="fcontact" required value="<?php echo $fcontact; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="flocation" class="col-3 col-form-label">Partner Location</label>
                        <div class="col-9">
                            <input type="text" name="flocation" id="flocation" value="<?php echo $flocation; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="btn" class="col-3 col-form-label">&nbsp;</label>
                        <div class="col-9">
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