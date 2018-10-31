<?php
$fcode = '';
$fname = '';
$flocation = '';
$fnearby = '';
$fpic = '';
$fphone = '';
$fspv = '';
$fsort = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $fcode = $r["code"];
        $fname = $r["name"];
        $flocation = $r["location"];
        $fnearby = $r["nearby"];
        $flocation = $r["location"];
        $fpic = $r["pic"];
        $fphone = $r["phone"];
        $fspv = $r["spv"];
        $fsort = $r["sort_order"];
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
                        <label for="fcode" class="col-3 col-form-label">FSL Code</label>
                        <div class="col-9">
                            <input type="text" name="fcode" id="fcode" value="<?php echo $fcode; ?>" class="form-control" readonly="true">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-3 col-form-label">FSL Name</label>
                        <div class="col-9">
                            <input type="text" name="fname" id="fname" required value="<?php echo $fname; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="flocation" class="col-3 col-form-label">Location</label>
                        <div class="col-9">
                            <textarea name="flocation" required class="form-control"><?php echo $flocation; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fnearby" class="col-3 col-form-label">FSL Nearby</label>
                        <div class="col-9">                            
                            <select name="fnearby[]" id="fnearby" class="selectpicker" multiple data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_wr as $w){
                                        $selected = "";
                                        if(!empty($fnearby)){
                                            $i_nearby = explode(";", $fnearby);
                                            foreach($i_nearby as $i){
                                                if($w["code"] == $i){
                                                    $selected = "selected";
                                                }
                                            }
                                        }
                                        echo '<option value="'.$w["code"].'" '.$selected.'>'.$w["name"].'</option>';
                                    }
                                ?>
                            </select>
                            <span class="help-block"><small>Nearby Warehouse Location</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpic" class="col-3 col-form-label">Person In Charge (PIC)</label>
                        <div class="col-9">
                            <input type="text" name="fpic" id="fpic" required value="<?php echo $fpic; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fphone" class="col-3 col-form-label">Phone</label>
                        <div class="col-9">
                            <input type="text" name="fphone" id="fphone" data-parsley-type="number" required value="<?php echo $fphone; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fspv" class="col-3 col-form-label">Supervisor</label>
                        <div class="col-9">                            
                            <select name="fspv[]" id="fspv" class="selectpicker" multiple data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_spv as $s){
                                        $selected = "";
                                        if(!empty($fspv)){
                                            $i_spv = explode(";", $fspv);
                                            foreach($i_spv as $v){
                                                if($s["uname"] == $v){
                                                    $selected = "selected";
                                                }
                                            }
                                        }
                                        echo '<option value="'.$s["uname"].'" '.$selected.'>'.$s["fullname"].'</option>';
                                    }
                                ?>
                            </select>
                            <span class="help-block"><small>Warehouse Supervisors</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="forder" class="col-3 col-form-label">Sort Order</label>
                        <div class="col-9">
                            <input type="number" name="forder" id="forder" min="0" data-parsley-type="number" required value="<?php echo $fsort; ?>" class="form-control">
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