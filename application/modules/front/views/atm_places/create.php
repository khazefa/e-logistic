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
                <form class="form-horizontal" action="<?php echo base_url('atm/insert');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fssbid" class="col-3 col-form-label">SSB ID <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <input type="text" name="fssbid" id="fssbid" required placeholder="SSB ID" class="form-control" pattern="[a-zA-Z0-9 ]+">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fmachid" class="col-3 col-form-label">Machine ID <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <input type="text" name="fmachid" id="fmachid" required placeholder="Machine ID" class="form-control" pattern="[a-zA-Z0-9 ]+">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-3 col-form-label">Bank Name <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <input type="text" name="fname" id="fname" required placeholder="Bank Name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="floc" class="col-3 col-form-label">Location</label>
                        <div class="col-9">
                            <textarea name="floc" id="floc" placeholder="Location" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="faddress" class="col-3 col-form-label">Address</label>
                        <div class="col-9">
                            <textarea name="faddress" id="faddress" placeholder="Address" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpostcode" class="col-3 col-form-label">Postal Code</label>
                        <div class="col-9">
                            <input type="text" name="fpostcode" id="fpostcode" placeholder="Postal Code" class="form-control" pattern="[0-9]+">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fcity" class="col-3 col-form-label">City</label>
                        <div class="col-9">
                            <input type="fcity" name="fcity" id="fcity" placeholder="City" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fprovince" class="col-3 col-form-label">Province</label>
                        <div class="col-9">
                            <input type="text" name="fprovince" id="fprovince" placeholder="Province" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fisland" class="col-3 col-form-label">Island</label>
                        <div class="col-9">
                            <input type="text" name="fisland" id="fisland" placeholder="Island" class="form-control">
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