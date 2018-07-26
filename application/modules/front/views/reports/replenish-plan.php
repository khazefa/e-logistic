<form action="<?php echo base_url('print-replenish-plan');?>" method="POST" class="form-horizontal" role="form" target="_blank">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Report Replenish Plan</strong>
                            </div>
                            <div class="card-body">
                                <?php
                                if(($role == ROLE_WA)){
                                ?>
                                <div class="form-row">
                                    <div class="col-sm-6">
                                        <select name="fcode" id="fcode" required class="selectpicker" data-live-search="true" 
                                                data-selected-text-format="values" title="Please choose FSL" data-style="btn-light">
                                            <?php
                                                foreach($list_wr as $w){
                                                    echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2"></div>
                                <?php
                                }
                                ?>
                                <div class="form-row">
                                    <div class="input-group col-sm-12">
                                        <p>Please select the same date to select report on the same day (Daily)</p>
                                    </div>
                                    <div class="input-group col-sm-6">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                         </div>
                                        <input type="date" name="fdate1" id="fdate1" class="form-control" placeholder="MM/DD/YYYY" required="required">
                                    </div>
                                    <div class="input-group col-sm-6">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                         </div>
                                        <input type="date" name="fdate2" id="fdate2" class="form-control" placeholder="MM/DD/YYYY" required="required">
                                    </div>
                                    <div class="input-group col-sm-12">
                                        <div id="fsearch_notes"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="btn_submit_d" class="btn btn-success waves-effect waves-light">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>