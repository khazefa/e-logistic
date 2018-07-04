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
                        <label for="fcode" class="col-2 col-form-label">FSL/Warehouse Code</label>
                        <div class="col-3">
                            <select name="fcode" id="fcode" class="selectpicker" required data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_data_wh as $w){
                                        echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                    }
                                ?>
                            </select>
                            <span class="help-block"><small>Warehouse Location</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fserialnum" class="col-2 col-form-label">Serial Number</label>
                        <div class="col-4">
                            <input type="text" name="fserialnum" id="fserialnum" required placeholder="Serial Number" class="form-control">
                            <span class="help-block"><small>Serial Number cannot be null</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpartnum" class="col-2 col-form-label">Part Number</label>
                        <div class="col-4">
                            <input type="text" name="fpartnum" id="fpartnum" required placeholder="Part Number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fparentid" class="col-2 col-form-label">Main Part</label>
                        <div class="col-4">
                            <select name="fparentid" id="fparentid" class="selectpicker" required data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_data as $d){
                                        echo '<option value="'.$d["pid"].'">'.$d["serialno"].' - '.$d["name"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fnearby" class="col-2 col-form-label">Part Subtitution</label>
                        <div class="col-10">
                            <select name="fpartidsub[]" id="fpartidsub" class="selectpicker" required multiple data-live-search="true" 
                                    data-selected-text-format="count > 2" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_data as $d1){
                                        echo '<option value="'.$d1["pid"].'">'.$d1["serialno"].' - '.$d1["name"].'</option>';
                                    }
                                ?>
                            </select>
                            <span class="help-block"><small>List serial number</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fparttype" class="col-2 col-form-label">Part Type</label>
                        <div class="col-3">
                            <select name="fparttype" id="fparttype" class="selectpicker" required data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <option value="0">Type 1</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpartsupply" class="col-2 col-form-label">Part Supplier</label>
                        <div class="col-3">
                            <select name="fpartsupply" id="fpartsupply" class="selectpicker" required data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <option value="0">Supplier 1</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-2 col-form-label">Part Name</label>
                        <div class="col-6">
                            <input type="text" name="fname" id="fname" required placeholder="Part Name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fstock" class="col-2 col-form-label">Part Stock</label>
                        <div class="col-2">
                            <input data-parsley-type="number" type="number" name="fstock" id="fstock" required value="1" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fdesc" class="col-2 col-form-label">Part Description</label>
                        <div class="col-6">
                            <textarea name="fdesc" id="fdesc" placeholder="Part Description" class="form-control"></textarea>
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