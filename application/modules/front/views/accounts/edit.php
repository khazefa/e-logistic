<?php
$funame = '';
$femail = '';
$fname = '';
$fadm = '';
$fgid = '';
$fgroup = '';
$fgroupname = '';
$ffsl = '';
$fcoverage = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $funame = $r["uname"];
        $femail = $r["email"];
        $fname = $r["fullname"];
        $fadm = $r["adm"];
        $fgid = $r["gid"];
        $fgroup = $r["group"];
        $fgroupname = $r["group_name"];
        $ffsl = $r["fsl"];
        $fcoverage = $r["coverage"];
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
                        <label for="fgroup" class="col-3 col-form-label">User Group</label>
                        <div class="col-9">
                            <select name="fgroup" id="fgroup" class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_group as $g){
                                        if($g["id"] == $fgid){
                                            echo '<option value="'.$g["id"].'" selected>'.$g["display"].'</option>';
                                        }else{
                                            echo '<option value="'.$g["id"].'">'.$g["display"].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fkey" class="col-3 col-form-label">Username</label>
                        <div class="col-9">
                            <input type="text" name="fkey" id="fkey" value="<?php echo $funame;?>" class="form-control" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpass" class="col-3 col-form-label">Change Password</label>
                        <div class="col-9">
                            <input type="password" name="fpass" id="fpass" data-parsley-minlength="3" placeholder="******" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-3 col-form-label">Full Name</label>
                        <div class="col-9">
                            <input type="text" name="fname" id="fname" required value="<?php echo $fname;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="femail" class="col-3 col-form-label">Email</label>
                        <div class="col-9">
                            <input type="email" name="femail" id="femail" parsley-type="email" value="<?php echo $femail;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ffsl" class="col-3 col-form-label">Warehouse</label>
                        <div class="col-9">
                            <select name="ffsl" id="ffsl" required class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    $default_check = "";
                                    if($ffsl == "00"){
                                        $default_check = "selected";
                                    }
                                    echo '<option value="00" '.$default_check.'>WH</option>';
                                    foreach($list_wr as $w){
                                        if($w["code"] == $ffsl){
                                            echo '<option value="'.$w["code"].'" selected>'.$w["name"].'</option>';
                                        }else{
                                            echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row d-none" id="fcoverage">
                        <label for="fcode" class="col-3 col-form-label">Coverage Warehouse</label>
                        <div class="col-9">
                            <div class="checkbox checkbox-info checkbox-circle">
                                <input name="fcode_all" id="fcode_all" type="checkbox" value="*">
                                <label for="fcode_all">
                                    Check All / Uncheck All
                                </label>
                            </div>
                            <div class="input-group col-sm-12">
                                <?php
                                    $col1 = 0;
                                    $col2 = 0;
                                    $col3 = 0;
                                    $col4 = 0;
                                    $arr_col1 = array();
                                    $arr_col2 = array();
                                    $arr_col3 = array();
                                    $arr_col4 = array();
                                    $t_list = count($list_wr);
                                    $t_divide = (int) ceil($t_list/4);

                                    $arr_col1 = array_slice($list_wr, 0, (int) ceil($t_list/4));
                                    $arr_col2 = array_slice($list_wr, count($arr_col1), (int) ceil($t_list/4));
                                    $arr_col3 = array_slice($list_wr, (count($arr_col1) + count($arr_col2)), (int) ceil($t_list/4));
                                    $arr_col4 = array_slice($list_wr, (count($arr_col1) + count($arr_col2) + count($arr_col3)), (int) ceil($t_list/4));

                                    $data_coverage = array();
                                    if(!empty($fcoverage)){
                                        $e_fcoverage = explode(';', $fcoverage);
                                        foreach ($e_fcoverage as $e){
                                            if(!empty($e)){
                                                array_push($data_coverage, trim($e));
                                            }
                                        }
                                    }
                                ?>
                                <div class="col-sm-3">
                                <?php
                                    foreach($arr_col1 as $c1){
                                        $code = filter_var($c1["code"], FILTER_SANITIZE_STRING);
                                        $name = filter_var($c1["name"], FILTER_SANITIZE_STRING);
                                        $checked = "";
                                        
                                        if (in_array($code, $data_coverage)){
                                            $checked = "checked";
                                        }
                                        echo '<div class="col-sm-12">';
                                            echo '<div class="checkbox checkbox-custom">';
                                                echo '<input type="checkbox" id="checkbox'.$col1.'" name="fcode[]" value="'.$code.'" '.$checked.'/>'
                                                        . '<label for="checkbox'.$col1.'">'.$name.'</label>';
                                            echo '</div>';
                                        echo '</div>';
                                        $col1++;
                                    }
                                ?>
                                </div>
                                <div class="col-sm-3">
                                <?php
                                    foreach($arr_col2 as $c2){
                                        $code = filter_var($c2["code"], FILTER_SANITIZE_STRING);
                                        $name = filter_var($c2["name"], FILTER_SANITIZE_STRING);
                                        $col2 = $col1 + $col2;
                                        $checked = "";
                                        
                                        if (in_array($code, $data_coverage)){
                                            $checked = "checked";
                                        }
                                        echo '<div class="col-sm-12">';
                                            echo '<div class="checkbox checkbox-custom">';
                                                echo '<input type="checkbox" id="checkbox'.$col2.'" name="fcode[]" value="'.$code.'" '.$checked.'/>'
                                                        . '<label for="checkbox'.$col2.'">'.$name.'</label>';
                                            echo '</div>';
                                        echo '</div>';
                                        $col2++;
                                    }
                                ?>
                                </div>
                                <div class="col-sm-3">
                                <?php
                                    foreach($arr_col3 as $c3){
                                        $code = filter_var($c3["code"], FILTER_SANITIZE_STRING);
                                        $name = filter_var($c3["name"], FILTER_SANITIZE_STRING);
                                        $col3 = $col2 + $col3;
                                        $checked = "";
                                        
                                        if (in_array($code, $data_coverage)){
                                            $checked = "checked";
                                        }

                                        echo '<div class="col-sm-12">';
                                            echo '<div class="checkbox checkbox-custom">';
                                                echo '<input type="checkbox" id="checkbox'.$col3.'" name="fcode[]" value="'.$code.'" '.$checked.'/>'
                                                        . '<label for="checkbox'.$col3.'">'.$name.'</label>';
                                            echo '</div>';
                                        echo '</div>';
                                        $col3++;
                                    }
                                ?>
                                </div>
                                <div class="col-sm-3">
                                <?php
                                    foreach($arr_col4 as $c4){
                                        $code = filter_var($c4["code"], FILTER_SANITIZE_STRING);
                                        $name = filter_var($c4["name"], FILTER_SANITIZE_STRING);
                                        $col4 = $col3 + $col4;
                                        $checked = "";
                                        
                                        if (in_array($code, $data_coverage)){
                                            $checked = "checked";
                                        }

                                        echo '<div class="col-sm-12">';
                                            echo '<div class="checkbox checkbox-custom">';
                                                echo '<input type="checkbox" id="checkbox'.$col4.'" name="fcode[]" value="'.$code.'" '.$checked.'/>'
                                                        . '<label for="checkbox'.$col4.'">'.$name.'</label>';
                                            echo '</div>';
                                        echo '</div>';
                                        $col4++;
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fisadm" class="col-3 col-form-label">Is Admin</label>
                        <div class="col-9">
                            <select name="fisadm" id="fisadm" class="selectpicker" title="Please choose.." data-style="btn-light">
                                <?php
                                $op_yes = $fadm == 1 ? "selected" : "";
                                $op_no = $fadm == 0 ? "selected" : "";
                                ?>
                                <option value="0" <?php echo $op_no;?>>No</option>
                                <option value="1" <?php echo $op_yes;?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="btn" class="col-3 col-form-label">&nbsp;</label>
                        <div class="col-9">
                            <button id="btn_submit" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var group = "<?php echo BASE_SPV;?>";
        
        if($('#fgroup').val() === group){
            $( "#fcoverage" ).removeClass( "d-none" );
            $('#fcode_all').on("click", function(e){
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
//            $("#btn_submit").on("click", function(e){
//                var checked = $("input[type=checkbox]:checked").length;
//                if(!checked) {
//                  alert("You must check at least one FSL.");
//                  return false;
//                }
//            });
        }
        
        $('#fgroup').on('change', function(e){
            if(this.value === group){
                $( "#fcoverage" ).removeClass( "d-none" );
            }else{
                $( "#fcoverage" ).addClass( "d-none" );
            }
        });
    });
</script>