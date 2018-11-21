<div class="row justify-content-md-center">
    <div class="col-md-9">
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
                <div class="row">
                    <div class="col-md-12">
                        <form action="#" method="POST" class="form-horizontal" role="form">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <div class="form-group row">
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                        </div>
                                        <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="Part Number" 
                                            data-toggle="tooltip" data-placement="top" title="" data-original-title="Input Part Number and then Press [ENTER]">
                                    </div>
                                    <p id="fpartnum_notes"></p>
                                </div>
                                <div class="col-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calculator"></i> </span>
                                        </div>
                                        <input type="number" name="fqty" id="fqty" class="form-control" placeholder="Init Stock" min="0"
                                            data-toggle="tooltip" data-placement="top" title="" data-original-title="Input Init Stock and then Press [ENTER]">
                                    </div>
                                    <p id="fpartnum_notes"></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>FSL</th>
                                    <th>Part Number</th>
                                    <th>Part Name</th>
                                    <th>Min Stock</th>
                                    <th>Last Stock</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var e_partnum = $("#fpartnum");
    var e_partnum_notes = $("#fpartnum_notes");
    var e_qty = $("#fqty");
    var table;
    var isExist = false;

    function init_form(){
        e_partnum.val('');
        e_partnum.focus();
        e_partnum_notes.html('');
        e_qty.val('0');
    }

    //check part stock
    function check_part(partno){
        var url = '<?php echo $url_check_part;?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : partno
        };

        var throw_ajax_success = function (jqXHR) {
            if(jqXHR.status === 0){
                e_partnum_notes.html('<span class="help-block text-warning">'+jqXHR.message+'</span>');
                e_qty.focus();
                isExist = false;
            }else if(jqXHR.status === 1){
                e_partnum_notes.html('<span class="help-block text-success">'+jqXHR.message+'</span>');
                e_qty.focus();
                isExist = true;
            }else if(jqXHR.status === 2){
                e_partnum_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                isExist = false;
            }
        };
        
        throw_ajax(url, type, data, throw_ajax_success, throw_ajax_err);        
    }

    function init_table(){
        // Responsive Datatable with Buttons
        table = $('#data_grid').DataTable({
            dom: "<'row'<'col-sm-9'l><'col-sm-3'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'p><'col-sm-3'i>>",
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            ajax: {                
                url: "<?php echo $url_list;?>",
                type: "GET",
                dataType: "JSON",
                contentType: "application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                }
            },
            columns: [
                { "data": 'code' },
                { "data": 'partno' },
                { "data": 'partname' },
                { "data": 'minstock' },
                { "data": 'stock' },
            ],
            initComplete: function() {
                e_partnum.focus();
            }
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-12:eq(0)');
    }

    //save transaction
    function save(){
        if(isEmpty(e_partnum.val())){
            alert('Please input Part Number!');
            e_partnum.focus();
        }else{
            var url = '<?php echo base_url($classname.'/insert'); ?>';
            var type = 'POST';
            var data = {
                <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
                fpartnum : e_partnum.val(),
                fqty : e_qty.val()
            };

            var throw_ajax_success = function (jqXHR) {
                if(jqXHR.status === 0){
                    alert(jqXHR.message);
                }else if(jqXHR.status === 1){
                    table.ajax.reload();
                    init_form();
                }
            };

            throw_ajax(url, type, data, throw_ajax_success, throw_ajax_err);
        }
    }

    //save transaction
    function update(){
        if(isEmpty(e_partnum.val())){
            alert('Please input Part Number!');
            e_partnum.focus();
        }else{
            var url = '<?php echo base_url($classname.'/modify'); ?>';
            var type = 'POST';
            var data = {
                <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
                fpartnum : e_partnum.val(),
                fqty : e_qty.val()
            };

            var throw_ajax_success = function (jqXHR) {
                if(jqXHR.status === 0){
                    alert(jqXHR.message);
                }else if(jqXHR.status === 1){
                    table.ajax.reload();
                    init_form();
                }
            };

            throw_ajax(url, type, data, throw_ajax_success, throw_ajax_err);
        }
    }

    $(document).ready(function() {
        init_form();
        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            searching: true,
            paginate: true,
            autoWidth: false,
            columnDefs: [{ 
                orderable: false,
                targets: [ 0 ]
            }],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            language: {
                search: '<span>Search:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            }
        });
        init_table();

        e_partnum.on("keydown", function (e) {
            var val = this.value;
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(isEmpty(val)){
                    alert("Please input Part Number!");
                }else{
                    check_part(val);
                }
                return false;
            }
        });

        e_qty.on("keydown", function (e) {
            var val = parseInt(this.value);
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(isEmpty(val) || val === 0){
                    alert("Please input Quantity!");
                }else{
                    //action insert
                    if(isExist){
                        // alert("Data "+e_partnum.val()+" already exist!");
                        //update
                        update();
                    }else{
                        // alert("Data "+e_partnum.val()+" is not exist!");
                        //insert
                        save();
                    }
                }
                return false;
            }
        });
    });
</script>