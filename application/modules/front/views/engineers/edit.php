<?php
$fkey = '';
$femail = '';
$fname = '';
$ftitle = '';
$fpartner = '';
$fphone = '';
$farea = '';
$fspv = '';
$fcode = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $fkey = $r["feid"];
        $femail = $r["email"];
        $fname = $r["name"];
        $ftitle = $r["title"];
        $fpartner = $r["partner"];
        $fphone = $r["phone"];
        $farea = $r["area"];
        $fspv = $r["spv"];
        $fcode = $r["code"];
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
                <form class="form-horizontal" action="<?php echo base_url('front/cengineers/update');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fpartner" class="col-2 col-form-label">Service Partner</label>
                        <div class="col-3">
                            <select name="fpartner" id="fpartner" class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_partner as $p){
                                        if($p["id"] == $fpartner){
                                            echo '<option value="'.$p["id"].'" selected>'.$p["name"].'</option>';
                                        }else{
                                            echo '<option value="'.$p["id"].'">'.$p["name"].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fkey" class="col-2 col-form-label">FE ID</label>
                        <div class="col-3">
                            <input type="text" name="fkey" id="fkey" value="<?php echo $fkey;?>" class="form-control" readonly="readonly">
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
                        <label for="ftitle" class="col-2 col-form-label">Title</label>
                        <div class="col-4">
                            <input type="text" name="ftitle" id="ftitle" required value="<?php echo $ftitle;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="femail" class="col-2 col-form-label">Email</label>
                        <div class="col-3">
                            <input type="email" name="femail" id="femail" required parsley-type="email" value="<?php echo $femail;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fphone" class="col-2 col-form-label">Phone</label>
                        <div class="col-4">
                            <input type="text" name="fphone" id="fphone" value="<?php echo $fphone;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="farea" class="col-2 col-form-label">Service Area</label>
                        <div class="col-4">
                            <input type="text" name="farea" id="farea" required value="<?php echo $farea;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fspv" class="col-2 col-form-label">FSSPV CODE</label>
                        <div class="col-4">
                            <input type="text" name="fspv" id="fspv" required value="<?php echo $fspv;?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fcode" class="col-2 col-form-label">Warehouse</label>
                        <div class="col-3">
                            <select name="fcode" id="fcode" required class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_wr as $w){
                                        if($w["code"] == $fcode){
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