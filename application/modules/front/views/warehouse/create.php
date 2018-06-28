<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-20"><?php echo $contentTitle;?></h4><hr>
        
        
            <div class="card-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group row">
                        <label for="ffsl" class="col-2 col-form-label">FSL Code</label>
                        <div class="col-3">
                            <input type="text" name="ffsl" id="ffsl" data-parsley-type="number" required placeholder="999" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-2 col-form-label">FSL Name</label>
                        <div class="col-6">
                            <input type="text" name="fname" id="fname" required placeholder="FSL Name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="flocation" class="col-2 col-form-label">Location</label>
                        <div class="col-6">
                            <textarea name="flocation" required class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fnearby" class="col-2 col-form-label">FSL Nearby</label>
                        <div class="col-6">
                            <select name="fnearby" id="fnearby" class="selectpicker" multiple data-live-search="true" 
                                    data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                            </select>
                            <span class="help-block"><small>Nearby Warehouse Location</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpic" class="col-2 col-form-label">Person In Charge (PIC)</label>
                        <div class="col-3">
                            <input type="text" name="fpic" id="fpic" required placeholder="Person In Charge (PIC)" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fphone" class="col-2 col-form-label">Phone</label>
                        <div class="col-3">
                            <input type="text" name="fphone" id="fphone" data-parsley-type="number" required placeholder="Phone" class="form-control">
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
        var options = $("#fnearby");
            $.getJSON("<?= base_url('front/cwarehouse/get_list_data'); ?>", function(response) {
                $.each(response, function() {
                options.append($("<option></option>").val(this.code).text(this.name));
            });
        });
    });
</script>