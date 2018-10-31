<?php
$fid = '';
$fname = '';
$fdisplay = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $fid = $r["id"];
        $fname = $r["name"];
        $fdisplay = $r["display"];
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
                    <input type="hidden" name="fid" value="<?php echo $fid; ?>">
                    <div class="form-group row">
                        <label for="fname" class="col-3 col-form-label">Group Name</label>
                        <div class="col-9">
                            <input type="text" name="fname" id="fname" required data-parsley-maxlength="3" value="<?php echo $fname; ?>" class="form-control" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fdisplay" class="col-3 col-form-label">Group Display</label>
                        <div class="col-9">
                            <input type="text" name="fdisplay" id="fdisplay" required value="<?php echo $fdisplay; ?>" class="form-control">
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