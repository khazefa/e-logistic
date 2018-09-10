<?php
$funame = '';
$femail = '';
$fname = '';
$warehouse = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $funame = $r["ukey"];
        $femail = $r["email"];
        $fname = $r["fullname"];
        $warehouse = $r["warehouse"];
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
                <form class="form-horizontal" action="<?php echo base_url('superintend/cprofile/update');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
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
                            <span class="help-block text-info"><small>Please use strong passwords for your data security.</small></span>
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
                            <label for="fwarehouse" class="col-12 col-form-label"><?php echo $warehouse; ?></label>
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