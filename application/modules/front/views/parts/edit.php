<?php
$fpid = '';
$fpartnum = '';
$fname = '';
$fdesc = '';
$fmachine = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $fpid = $r["pid"];
        $fpartnum = $r["partno"];
        $fname = $r["name"];
        $fdesc = $r["desc"];
        $fmachine = $r["machine"];
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
                        <label for="fpartnum" class="col-3 col-form-label">Part Number</label>
                        <div class="col-9">
                            <input type="text" name="fpartnum" id="fpartnum" required value="<?php echo $fpartnum; ?>" class="form-control" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-3 col-form-label">Part Name</label>
                        <div class="col-9">
                            <input type="text" name="fname" id="fname" required value="<?php echo $fname; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fdesc" class="col-3 col-form-label">Part Description</label>
                        <div class="col-9">
                            <textarea name="fdesc" id="fdesc" placeholder="Part Description" class="form-control"><?php echo $fdesc; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fmachine" class="col-3 col-form-label">Machine</label>
                        <div class="col-9">
                            <input type="text" name="fmachine" id="fmachine" required value="<?php echo $fmachine; ?>" class="form-control">
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