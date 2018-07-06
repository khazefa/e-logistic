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
                <form class="form-horizontal" id="frm_import" name="frm_import" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <div class="col-6" id="uploader">
                            <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// Initialize the widget when the DOM is ready
    $(document).ready(function() {
        // Setup html5 version
        $("#uploader").pluploadQueue({
            // General settings
            runtimes : 'html5,flash,silverlight,html4',
            url : "<?php echo base_url('front/cparts/import_csv_adv');?>",

            chunk_size : '1mb',
            rename : true,
            dragdrop: true,
            multiple_queues: true,

            filters : {
                // Maximum file size
                max_file_size : '25mb',
                // User can upload no more then 10000 files in one go (sets multiple_queues to false)
    //            max_file_count: 10000,
                // Specify what files to browse for
                mime_types: [
                    {title : "Document files", extensions : "csv"},
                ]
            },

            // Resize images on clientside if we can
            resize: {
                width : 200,
                height : 200,
                quality : 90,
                crop: true // crop to exact dimensions
            },


            // Flash settings
            flash_swf_url : '<?php echo base_url(); ?>assets/public/plugins/plupload/Moxie.swf',

            // Silverlight settings
            silverlight_xap_url : '<?php echo base_url(); ?>assets/public/plugins/plupload/Moxie.xap'
        });

        // Handle the case when form was submitted before uploading has finished
        $('#frm_import').submit(function(e) {
            // Files in queue upload them first
            if ($('#uploader').plupload('getFiles').length > 0) {

                    // When all files are uploaded submit form
                    $('#uploader').on('complete', function() {
                            $('#frm_import')[0].submit();
                    });

                    $('#uploader').plupload('start');
            } else {
                    alert("You must have at least one file in the queue.");
            }
            return false; // Keep the form from submitting
        });
    });
</script>