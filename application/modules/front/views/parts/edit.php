<?php
$fpid = '';
$fcode = '';
$fpartnum = '';
$fserialnum = '';
$fparentid = '';
$fpartidsub = '';
$fparttype = '';
$fpartsupply = '';
$fname = '';
$fdesc = '';
$fstock = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $fpid = $r["pid"];
        $fcode = $r["code"];
        $fpartnum = $r["partno"];
        $fserialnum = $r["serialno"];
        $fparentid = $r["parentid"];
        $fpartidsub = $r["partidsub"];
        $fparttype = $r["typeid"];
        $fpartsupply = $r["supplyid"];
        $fname = $r["name"];
        $fdesc = $r["desc"];
        $fstock = $r["stock"];
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
                <form class="form-horizontal" action="<?php echo base_url('front/cparts/update');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fcode" class="col-2 col-form-label">FSL/Warehouse Code</label>
                        <div class="col-3">
                            <select name="fcode" id="fcode" class="selectpicker" required data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_data_wh as $w){
                                        $selected = $w["code"] == $fcode ? "selected" : "";
                                        echo '<option value="'.$w["code"].'" '.$selected.'>'.$w["name"].'</option>';
                                    }
                                ?>
                            </select>
                            <span class="help-block"><small>Warehouse Location</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fserialnum" class="col-2 col-form-label">Serial Number</label>
                        <div class="col-4">
                            <!--<input type="text" name="fserialnum" id="fserialnum" value="<?php echo $fserialnum; ?>" class="form-control" readonly="true">-->
                            <input type="text" name="fserialnum" id="fserialnum" value="<?php echo $fserialnum; ?>" class="form-control" required="true">
                            <span class="help-block"><small>Serial Number cannot be null</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpartnum" class="col-2 col-form-label">Sparepart Number</label>
                        <div class="col-4">
                            <input type="text" name="fpartnum" id="fpartnum" required value="<?php echo $fpartnum; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fparentid" class="col-2 col-form-label">Main Sparepart</label>
                        <div class="col-4">
                            <select name="fparentid" id="fparentid" class="selectpicker" required data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_data as $d){
                                        $selected = $fpid != 0 ? ($d["pid"] == $fpid ? "selected" : "") : "";
                                        echo '<option value="'.$d["pid"].'" '.$selected.'>'.$d["serialno"].' - '.$d["name"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fnearby" class="col-2 col-form-label">Sparepart Subtitution</label>
                        <div class="col-10">
                            <select name="fpartidsub[]" id="fpartidsub" class="selectpicker" required multiple data-live-search="true" 
                                    data-selected-text-format="count > 2" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_data as $d1){
                                        $selected = "";
                                        if(!empty($fpartidsub)){
                                            $i_partsub = explode(";", $fpartidsub);
                                            foreach($i_partsub as $i){
                                                if($d1["pid"] == $i){
                                                    $selected = "selected";
                                                }
                                            }
                                        }
                                        
                                        echo '<option value="'.$d1["pid"].'" '.$selected.'>'.$d1["serialno"].' - '.$d1["name"].'</option>';
                                    }
                                ?>
                            </select>
                            <span class="help-block"><small>List serial number</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fparttype" class="col-2 col-form-label">Sparepart Type</label>
                        <div class="col-3">
                            <select name="fparttype" id="fparttype" class="selectpicker" required data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <option value="0">Type 1</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpartsupply" class="col-2 col-form-label">Sparepart Supplier</label>
                        <div class="col-3">
                            <select name="fpartsupply" id="fpartsupply" class="selectpicker" required data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <option value="0">Supplier 1</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-2 col-form-label">Sparepart Name</label>
                        <div class="col-6">
                            <input type="text" name="fname" id="fname" required value="<?php echo $fname; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fstock" class="col-2 col-form-label">Sparepart Stock</label>
                        <div class="col-2">
                            <input data-parsley-type="number" type="number" name="fstock" id="fstock" required value="<?php echo $fstock; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fdesc" class="col-2 col-form-label">Sparepart Description</label>
                        <div class="col-6">
                            <textarea name="fdesc" id="fdesc" placeholder="Sparepart Description" class="form-control"><?php echo $fdesc; ?></textarea>
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