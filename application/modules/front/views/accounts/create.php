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
                <form class="form-horizontal" action="<?php echo base_url('front/cusers/create');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fgroup" class="col-2 col-form-label">User Group</label>
                        <div class="col-3">
                            <select name="fgroup" id="fgroup" class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_group as $g){
                                        echo '<option value="'.$g["id"].'">'.$g["display"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fkey" class="col-2 col-form-label">Username</label>
                        <div class="col-3">
                            <input type="text" name="fkey" id="fkey" required data-parsley-minlength="6" placeholder="Username" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpass" class="col-2 col-form-label">Password</label>
                        <div class="col-3">
                            <input type="password" name="fpass" id="fpass" required data-parsley-minlength="6" placeholder="******" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-2 col-form-label">Full Name</label>
                        <div class="col-4">
                            <input type="text" name="fname" id="fname" required placeholder="Full Name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="femail" class="col-2 col-form-label">Email</label>
                        <div class="col-3">
                            <input type="email" name="femail" id="femail" required parsley-type="email" placeholder="Email" class="form-control">
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
                                        echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fisadm" class="col-2 col-form-label">Is Admin</label>
                        <div class="col-2">
                            <select name="fisadm" id="fisadm" class="selectpicker" title="Please choose.." data-style="btn-light">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
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