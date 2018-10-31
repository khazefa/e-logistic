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
                <form class="form-horizontal" action="<?php echo base_url($classname.'/insert');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fpartner" class="col-3 col-form-label">Service Partner</label>
                        <div class="col-9">
                            <select name="fpartner" id="fpartner" class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_partner as $p){
                                        echo '<option value="'.$p["id"].'">'.$p["name"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fkey" class="col-3 col-form-label">FE ID</label>
                        <div class="col-9">
                            <input type="text" name="fkey" id="fkey" required data-parsley-minlength="1" placeholder="FE ID" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpass" class="col-3 col-form-label">Password</label>
                        <div class="col-9">
                            <input type="password" name="fpass" id="fpass" required data-parsley-minlength="6" value="<?php echo $default_pass; ?>" class="form-control">
                            <span class="help-block"><small>default password already created, please change this text if you considered.</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-3 col-form-label">Full Name</label>
                        <div class="col-9">
                            <input type="text" name="fname" id="fname" required placeholder="Full Name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ftitle" class="col-3 col-form-label">Title</label>
                        <div class="col-9">
                            <input type="text" name="ftitle" id="ftitle" required placeholder="Title" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="femail" class="col-3 col-form-label">Email</label>
                        <div class="col-9">
                            <input type="email" name="femail" id="femail" required parsley-type="email" placeholder="Email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fphone" class="col-3 col-form-label">Phone</label>
                        <div class="col-9">
                            <input type="text" name="fphone" id="fphone" placeholder="Phone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="farea" class="col-3 col-form-label">Service Area</label>
                        <div class="col-9">
                            <input type="text" name="farea" id="farea" required placeholder="Service Area" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fspv" class="col-3 col-form-label">FSSPV CODE</label>
                        <div class="col-9">
                            <input type="text" name="fspv" id="fspv" required placeholder="FSSPV CODE" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fcode" class="col-3 col-form-label">Warehouse</label>
                        <div class="col-9">
                            <select name="fcode" id="fcode" required class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_wr as $w){
                                        echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                    }
                                ?>
                            </select>
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