<form action="<?php echo base_url('print-replenish-plan');?>" method="POST" class="form-horizontal" role="form" target="_blank">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Report Replenish Plan</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="input-group col-sm-12">
                                        <p>Please select the same date to select report on the same day (Daily)</p>
                                    </div>
                                    <div class="input-group col-sm-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                         </div>
                                        <input type="date" name="fdate1" id="fdate1" class="form-control" placeholder="MM/DD/YYYY" required="required">
                                    </div>
                                    <div class="input-group col-sm-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                         </div>
                                        <input type="date" name="fdate2" id="fdate2" class="form-control" placeholder="MM/DD/YYYY" required="required">
                                    </div>
                                    <div class="input-group col-sm-12">
                                        <div id="fsearch_notes"></div>
                                    </div>
                                </div>
                                <?php
                                if(($role == ROLE_WA)){
                                    $show_fsl = TRUE;
                                ?>
                                <div class="mt-4"></div>
                                <div class="form-row">
                                    <div class="col-sm-12">
<!--                                        <select name="fcode[]" id="fcode" required class="selectpicker" multiple data-live-search="true" 
                                                data-selected-text-format="values" title="Please choose FSL" data-style="btn-light">
                                            <option value="*">All FSL</option>-->
                                        <div class="checkbox checkbox-info checkbox-circle">
                                            <input name="fcode_all" id="fcode_all" type="checkbox" value="*">
                                            <label for="fcode_all">
                                                Check All / Uncheck All
                                            </label>
                                        </div>
                                        <table border="0" class="table-sm table-borderless table-responsive">
                                            <?php
                                                $a = 0;
                                                foreach($list_wr as $w){
//                                                    echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                                    if($a++ %6 == 0) echo "<tr>";
                                                        echo '<td align="center">'
                                                            . '<input type="checkbox" name="fcode[]" value="'.$w["code"].'"/>'
                                                            . '</td>';
                                                        echo '<td style="text-align:left"><strong>'.$w["name"].'</strong></td>';
                                                    if($a %6 == 0) echo "</tr>";
                                                }
                                            ?>
                                        </table>
                                        <!--</select>-->
                                    </div>
                                </div>
                                <?php
                                }else{
                                    $show_fsl = FALSE;
                                }
                                ?>
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="btn_generate_plan" class="btn btn-success waves-effect waves-light">
                                    Generate
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#fcode_all').on("click", function(e){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        var show_fsl = <?php echo $show_fsl; ?>;
        $("#btn_generate_plan").on("click", function(e){
            if(show_fsl){
                var checked = $("input[type=checkbox]:checked").length;
                if(!checked) {
                  alert("You must check at least one FSL.");
                  return false;
                }
            }else{
                return true;
            }
        });
    });
</script>