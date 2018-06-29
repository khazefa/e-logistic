<?php
$funame = '';
$femail = '';
$fname = '';
$fadm = '';
$fgid = '';
$fgroup = '';
$fgroupname = '';
$ffsl = '';

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
                <form class="form-horizontal" action="<?php echo base_url('front/cusers/update');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fgroup" class="col-2 col-form-label">User Group</label>
                        <div class="col-3">
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
                        <label for="fkey" class="col-2 col-form-label">Username</label>
                        <div class="col-3">
                            <input type="text" name="fkey" id="fkey" value="<?php echo $funame;?>" class="form-control" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpass" class="col-2 col-form-label">Change Password</label>
                        <div class="col-3">
                            <input type="password" name="fpass" id="fpass" data-parsley-minlength="6" placeholder="******" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-2 col-form-label">Full Name</label>
                        <div class="col-4">
                            <input type="text" name="fname" id="fname" required value="<?php echo $fname;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="femail" class="col-2 col-form-label">Email</label>
                        <div class="col-3">
                            <input type="email" name="femail" id="femail" required parsley-type="email" value="<?php echo $femail;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ffsl" class="col-2 col-form-label">Warehouse</label>
                        <div class="col-3">
                            <select name="ffsl" id="ffsl" required class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <option value="00">HQ</option>
                                <?php
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
                    <div class="form-group row">
                        <label for="fisadm" class="col-2 col-form-label">Is Admin</label>
                        <div class="col-2">
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