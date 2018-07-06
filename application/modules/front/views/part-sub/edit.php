<?php
$fid = '';
$fpartnum = '';
$fpartsub = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $fid = $r["id"];
        $fpartnum = $r["partno"];
        $fpartsub = $r["partnosub"];
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
                <form class="form-horizontal" action="<?php echo base_url('front/cpartsub/update');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <input type="hidden" name="fid" value="<?php echo $fid; ?>" readonly="readonly">
                    <div class="form-group row">
                        <label for="fpartnum" class="col-2 col-form-label">Part Number</label>
                        <div class="col-3">
                            <input type="text" name="fpartnum" id="fpartnum" value="<?php echo $fpartnum; ?>" class="form-control" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpartsub" class="col-2 col-form-label">Part Number Subtitute</label>
                        <div class="col-10">
                            <select name="fpartsub[]" id="fpartsub" class="selectpicker" multiple data-live-search="true" 
                                    data-selected-text-format="count > 5" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_data_part as $p){
                                        $selected = "";
                                        if(!empty($fpartsub)){
                                            $multi_val = explode(";", $fpartsub);
                                            foreach($multi_val as $i){
                                                if($p["partno"] == $i){
                                                    $selected = "selected";
                                                }
                                            }
                                        }
                                        echo '<option value="'.$p["partno"].'" '.$selected.'>'.$p["partno"].'</option>';
                                    }
                                ?>
                            </select>
                            <span class="help-block"><small>List Part Number for Subtitution</small></span>
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