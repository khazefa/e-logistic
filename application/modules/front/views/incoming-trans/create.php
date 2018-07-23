<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link " id="nav-supply-tab" data-toggle="tab" href="#nav-supply" role="tab" aria-controls="nav-supply" aria-selected="true">Supply</a>
                        <a class="nav-item nav-link active" id="nav-return-tab" data-toggle="tab" href="#nav-return" role="tab" aria-controls="nav-return" aria-selected="false">Return</a>
                        <a class="nav-item nav-link " id="nav-close-tab" data-toggle="tab" href="#nav-close" role="tab" aria-controls="nav-close" aria-selected="false">Close</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- Begin Content Panel Supply -->
                    <div class="tab-pane fade " id="nav-supply" role="tabpanel" aria-labelledby="nav-supply-tab">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-box">
                                    <div class="card-header bg-primary text-white">
                                        <strong class="card-title">Input Data</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group form-group-sm col-sm-12">
                                                <div class="row">
                                                    <div class="input-group col-sm-12">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                                         </div>
                                                        <input type="text" name="fpartnum_s" id="fpartnum_s" class="form-control" placeholder="Part Number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-sm-12">
                                                <span id="fpartnum_s_notes" class="help-block text-danger"><small></small></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group form-group-sm col-sm-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="number" name="fqty_s" id="fqty_s" class="form-control" value="1" min="0" required="required">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-sm-6">
                                                <div class="row">
                                                    <div class="input-group col-sm-12">
                                                        <button type="button" id="btn_add_s" class="btn btn-warning waves-effect waves-light pull-right">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group form-group-sm col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="text" name="fnotes_s" id="fnotes_s" class="form-control" required="required" placeholder="Delivery Notes">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" id="btn_submit_s" class="btn btn-primary waves-effect waves-light">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-box table-responsive">
                                    <h4 class="m-b-30 header-title">Detail Transaction</h4>
                                    <table id="cart_grid_s" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Part Number</th>
                                            <th>Part Name</th>
                                            <th>Current Stock</th>
                                            <th>Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-9">
                                            Total Quantity: <span id="ttl_qty_s">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content Panel Supply -->
                    
                    <!-- Begin Content Panel Return -->
                    <div class="tab-pane fade show active" id="nav-return" role="tabpanel" aria-labelledby="nav-return-tab">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-header bg-primary text-white">
                                        <strong class="card-title">Retrieve Outgoing Transaction</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="input-group col-sm-12">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                                 </div>
                                                <input type="text" name="ftrans_out" id="ftrans_out" class="form-control" placeholder="Outgoing Trans. No.">
                                            </div>
                                            <div class="input-group col-sm-12">
                                                <span id="ftrans_out_notes" class="help-block text-danger"><small></small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="card bg-light">
                                    <div class="card-header bg-primary text-white">
                                        <strong class="card-title">Verify Data</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="input-group col-sm-6">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                                 </div>
                                                <input type="text" name="fpartnum_r" id="fpartnum_r" class="form-control" placeholder="Part Number">
                                            </div>
                                            <div class="input-group col-sm-6">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                                 </div>
                                                <input type="text" name="fserialnum_r" id="fserialnum_r" class="form-control" placeholder="Serial Number">
                                            </div>
                                            <div class="input-group col-sm-12">
                                                <span id="fverify_r" class="help-block text-danger"><small></small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2"></div>
                                <table id="match_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Part Number</th>
                                        <th>Serial Number</th>
                                        <th>Part Name</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-3 offset-md-9">
                                        Total Quantity: <span id="ttl_qty_r">0</span>
                                    </div>
                                </div>
                                <div class="mt-2"></div>
                                <div class="card-box col-md-6 offset-md-6">
                                    <div class="card-header bg-primary text-white">
                                        <strong class="card-title">Input FE Report</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="input-group col-sm-12">
                                                <input type="text" name="ffe_report" id="ffe_report" class="form-control" placeholder="FE Report Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" id="btn_submit_r" class="btn btn-success waves-effect waves-light">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content Panel Return -->
                    
                    <!-- Begin Content Panel Close -->
                    <div class="tab-pane fade " id="nav-close" role="tabpanel" aria-labelledby="nav-close-tab">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="card bg-light">
                                    <div class="card-header bg-primary text-white">
                                        <strong class="card-title">Close Outgoing Transaction</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="input-group col-sm-6">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                                 </div>
                                                <input type="text" name="ftrans_out_c" id="ftrans_out_c" class="form-control" placeholder="Outgoing Trans. No.">
                                            </div>
                                            <div class="input-group col-sm-6">
                                                <input type="text" name="ffe_report_c" id="ffe_report_c" class="form-control" placeholder="FE Report">
                                            </div>
                                            <div class="input-group col-sm-12">
                                                <div id="ftrans_out_c_notes"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" id="btn_submit_c" class="btn btn-success waves-effect waves-light">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content Panel Close -->
                </div>
            </div>
        </div>
    </div>
    
</div>
</form>
<script type="text/javascript">
   /*
    * variable for supply transaction
    */
    var e_partnum_s = $('#fpartnum_s');
    var e_qty_s = $('#fqty_s');
    var e_part_note_s = $('#fpartnum_s_notes');
    var e_note_s = $('#fnotes_s');
   /*
    * end variable for supply transaction
    */
   
   /*
    * variable for return transaction
    */
    var e_trans_out = $('#ftrans_out');
    var e_trans_out_notes = $('#ftrans_out_notes');
    var e_partnum_r = $('#fpartnum_r');
    var e_serialnum_r = $('#fserialnum_r');
    var e_verify_note_r = $('#fverify_r');
    var e_fe_report = $('#ffe_report');
   /*
    * end variable for return transaction
    */
    
   /*
    * variable for close transaction
    */
    var e_trans_out_c = $('#ftrans_out_c');
    var e_trans_out_c_notes = $('#ftrans_out_c_notes');
    var e_fe_report_c = $('#ffe_report_c');
   /*
    * end variable for close transaction
    */
    
    var dataSet = [];
    
    var outgoing_qty = 0;

    //initial form supply
    function init_form_s(){
        e_partnum_s.val("");
        e_qty_s.val(1);
        e_part_note_s.html("");
        e_note_s.val("");
    }
    
    function init_form_r(){
        e_partnum_r.val("");
        e_partnum_r.prop("readonly", false);
        e_serialnum_r.val("");
        e_verify_note_r.html("");
    }
    
    function init_form_c(){
        e_trans_out_c.val('');
        e_trans_out_c_notes.html('');
        e_fe_report_c.val('');
    }
    
    //init table
    function init_table_s(){
        table = $('#cart_grid_s').DataTable({
//            select: {
//                style: 'multi'
//            },
//            scrollY: '50vh',
//            scrollCollapse: true,
            searching: false,
            ordering: false,
            info: false,
            paging: false,
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            lengthChange: false,
            ajax: {
                url: "<?= base_url('front/cincoming/get_list_cart_datatable'); ?>",
                type: "POST",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify( {
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                } ),
            },
            columns: [
                { "data": 'id' },
                { "data": 'partno' },
                { "data": 'name' },
                { "data": 'stock' },
                { "data": 'qty' },
            ],
            columnDefs : [
                {
                    targets   : 0,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        return '<button type="button" class="btn btn-danger" id="btn_delete"><i class="fa fa-trash"></i></button>';
                    }
                },
            ]
        });
        
        //function for datatables button
        $('#cart_grid_s tbody').on( 'click', 'button', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            delete_cart(fid);
        });

        table.buttons().container()
                .appendTo('#cart_grid_s_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload(){
        table.ajax.reload();
    }
    
    //init table
    function init_table_r(){        
        table_match = $('#match_grid').DataTable({
            searching: false,
            ordering: false,
            info: false,
            paging: false,
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            lengthChange: false,
            data: dataSet,
            columns: [
                { "title": "Part Number", "class": "left" },
                { "title": "Serial Number", "class": "left" },
                { "title": "Part Name", "class": "left" },
                { "title": "Qty", "class": "left" },
                { "title": "Status", "class": "left" },
            ]
        });

        table_match.buttons().container()
                .appendTo('#match_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload2(){
        table_match.ajax.reload();
    }
    
    //check part
    function check_part(partno){
        var status = 0;
        
        var url = '<?php echo base_url('front/cincoming/check_part'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : e_partnum_s.val()
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status === 0){
                    e_part_note_s.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    e_partnum_s.val("");
                    e_partnum_s.focus();
                    status = 0;
                }else if(jqXHR.status === 1){
                    e_part_note_s.html('<span class="help-block text-success">'+jqXHR.message+'</span>');
                    e_qty_s.focus();
                    status = 1;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
        return status;
    }
    
    //add to cart
    function add_cart(){
        var total_qty = table.rows().count();
        
        var url = '<?php echo base_url('front/cincoming/add_cart'); ?>';
        var type = 'POST';

        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : e_partnum_s.val(),
            fqty : e_qty_s.val()
        };

        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 0){
                    alert(jqXHR.message);
                }else if(jqXHR.status == 1){
                    reload();
                    get_total_s();
                }else if(jqXHR.status == 2){
                    alert(jqXHR.message);
                    init_form_s();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //get total cart supply
    function get_total_s() {
        var url = '<?php echo base_url('front/cincoming/get_total_cart'); ?>';
        var type = 'POST';
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            success:function(jqXHR)
            {
                if(jqXHR.status === 1){
                    $('#ttl_qty_s').html(jqXHR.ttl_cart);
                }else{
                    $('#ttl_qty_s').html(jqXHR.ttl_cart);
                }
            },
            cache: false,
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //get total cart return
    function get_total_r() {
        var url = '<?php echo base_url('front/cincoming/get_total_cart_return'); ?>';
        var type = 'POST';
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            success:function(jqXHR)
            {
                if(jqXHR.status === 1){
                    $('#ttl_qty_r').html(jqXHR.ttl_cart);
                }else{
                    $('#ttl_qty_r').html(jqXHR.ttl_cart);
                }
            },
            cache: false,
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //update cart
    function update_cart(id, qty){        
        var url = '<?php echo base_url('front/cincoming/update_cart'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : id,
            fqty : qty
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status === 1){
                    reload();
                    get_total_s();
                }else if(jqXHR.status === 0){
                    alert(jqXHR.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //delete cart
    function delete_cart(id){        
        var url = '<?php echo base_url('front/cincoming/delete_cart'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : id
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status === 1){
                    reload();
                    get_total_s();
                }else if(jqXHR.status === 0){
                    alert(jqXHR.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //submit supply transaction
    function complete_supply(){
        var url = '<?php echo base_url('front/cincoming/submit_trans_supply'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fqty : parseInt($('#ttl_qty_s').html()),
            fnotes : e_note_s.val()
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 0){
                    $("#error_modal .modal-title").html("Message");
                    $("#error_modal .modal-body h4").html(""+jqXHR.message);
                    $('#error_modal').modal({
                        show: true
                    });
                }else if(jqXHR.status == 1){
//                    print_transaction(jqXHR.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //check outgoing transaction
    function check_trans_out(transnum){
        var status = 0;
        
        var url = '<?php echo base_url('front/cincoming/check_outgoing'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : transnum
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status === 0){
                    e_trans_out_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    e_trans_out.prop("readonly", false);
                    e_trans_out.val("");
                    e_trans_out.focus();
                    status = 0;
                }else if(jqXHR.status === 1){
                    e_trans_out_notes.html("");
                    outgoing_qty = jqXHR.total_qty;
                    status = 1;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
        return status;
    }
    
    //check data to outgoing data
    function verify_data(){
        var total_qty = table_match.rows().count();
        
        var url = '<?php echo base_url('front/cincoming/verify_outgoing'); ?>';
        var type = 'POST';

        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : e_trans_out.val(),
            fpartnum : e_partnum_r.val(),
            fserialnum : e_serialnum_r.val()
        };

        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 0){
                    e_verify_note_r.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                }else if(jqXHR.status == 1){
                    init_form_r();
                    //cek total qty outgoing jika verifikasi melebihi qotal qty tolak verifikasi selanjutnya, 
                    //jika kurang dari total qty maka admin harus input FE Report
                    //jika sama maka tidak harus input FE Report
                    
                    //load part from nearby warehouse
                    $.each(jqXHR.data, function(i, object) {
                        table_match.row.add(
                            [object.partno, object.serialno, object.partname, object.qty, object.status]
                        ).draw();
//                        $.each(object, function(property, data) {
//                            table_match.row.add(
//                                [data.partno, data.serialno, data.partname, data.qty, data.status]
//                            ).draw();
//                        });
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //clear cart
    function clear_cart(){        
        var url = '<?php echo base_url('front/cincoming/clear_cart'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>"
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status === 1){
                    //done
                }else if(jqXHR.status === 0){
                    alert(jqXHR.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //submit return transaction
    function complete_return(){
        var url = '<?php echo base_url('front/cincoming/submit_trans_return'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            ftrans_out : e_trans_out.val(),
            ffe_report : e_fe_report.val()
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 0){
                    $("#error_modal .modal-title").html("Message");
                    $("#error_modal .modal-body h4").html(""+jqXHR.message);
                    $('#error_modal').modal({
                        show: true
                    });
                }else if(jqXHR.status == 1){
//                    print_transaction(jqXHR.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //check outgoing transaction
    function check_trans_out_c(transnum){
        var status = 0;
        
        var url = '<?php echo base_url('front/cincoming/check_outgoing'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : transnum
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status === 0){
                    e_trans_out_c_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    e_trans_out_c.prop("readonly", false);
                    e_trans_out_c.val("");
                    e_trans_out_c.focus();
                    status = 0;
                }else if(jqXHR.status === 1){
                    e_trans_out_c_notes.html("");
                    e_fe_report_c.focus();
                    status = 1;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
        return status;
    }
    
    //submit close transaction
    function complete_close(){
        var url = '<?php echo base_url('front/cincoming/submit_trans_close'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            ftrans_out : e_trans_out_c.val(),
            ffe_report : e_fe_report_c.val()
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 0){
                    $("#error_modal .modal-title").html("Message");
                    $("#error_modal .modal-body h4").html(""+jqXHR.message);
                    $('#error_modal').modal({
                        show: true
                    });
                }else if(jqXHR.status == 1){
//                    print_transaction(jqXHR.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    $(document).ready(function() {
        init_table_s();
        get_total_s();
        
        init_table_r();
        clear_cart();
        get_total_r();
        table_match.clear().draw();
        
        init_form_c();
        
        e_partnum_s.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_partnum_s.val())){
                    alert('Please fill in this field!');
                    e_partnum_s.focus();
                }else{
                    check_part(e_partnum_s.val());
                }
                return false;
            }
        });
        
        e_qty_s.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_partnum_s.val())){
                    alert('Please fill in required field!');
                    e_partnum_s.focus();
                }else{
                    add_cart();
                    reload();
                    init_form_s();
                    e_partnum_s.focus();
                }
                return false;
            }
        });
        
        $("#btn_add_s").on("click", function(e){
            if(isEmpty(e_partnum_s.val())){
                alert('Please fill in required field!');
                e_partnum_s.focus();
            }else{
                add_cart();
                reload();
                init_form_s();
                e_partnum_s.focus();
            }
        });
        
        $("#btn_submit_s").on("click", function(e){
            var total_qty = parseInt($('#ttl_qty_s').html());
            
            if(total_qty > 0){
                $('#confirmation').modal({
                    show: true
                });
                $('#opt_yess').click(function () {
                    complete_supply();
                    window.location.href = "<?php echo base_url('new-incoming-trans'); ?>";
                });
                $('#opt_no').click(function () {
                    complete_supply();
                    window.location.href = "<?php echo base_url('incoming-trans'); ?>";
                });
            }else{
                $("#error_modal .modal-title").html("Message");
                $("#error_modal .modal-body h4").html("You have not filled out the data");
                $('#error_modal').modal({
                    show: true
                });
            }
        });
        
        e_trans_out.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_trans_out.val())){
                    alert('Please fill in this field!');
                    e_trans_out.focus();
                }else{
                    check_trans_out(e_trans_out.val());
                    e_trans_out.prop("readonly", true);
                    e_partnum_r.focus();
                }
                return false;
            }
        });
        
        e_partnum_r.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_partnum_r.val())){
                    alert('Please fill in this field!');
                    e_partnum_r.focus();
                }else{
                    e_partnum_r.prop("readonly", true);
                    e_serialnum_r.val("");
                    e_serialnum_r.focus();
                }
                return false;
            }
        });
        
        e_serialnum_r.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_trans_out.val())){
                    alert('Please fill in outgoing transaction number!');
                    e_trans_out.focus();
                }else{
                    if(isEmpty(e_partnum_r.val()) || isEmpty(e_serialnum_r.val())){
                        alert('Please fill in required field!');
                        e_serialnum_r.focus();
                    }else{
                        verify_data();
                        get_total_r();
                        init_form_r();
                        e_partnum_r.focus();
                    }
                }
                return false;
            }
        });
        
        $("#btn_submit_r").on("click", function(e){
//            var total_qty = parseInt($('#ttl_qty_s').html());
            
//            if(total_qty > 0){
                $('#confirmation').modal({
                    show: true
                });
                $('#opt_yess').click(function () {
                    complete_return();
                    window.location.href = "<?php echo base_url('new-incoming-trans'); ?>";
                });
                $('#opt_no').click(function () {
                    complete_return();
                    window.location.href = "<?php echo base_url('incoming-trans'); ?>";
                });
//            }else{
//                $("#error_modal .modal-title").html("Message");
//                $("#error_modal .modal-body h4").html("You have not filled out the data");
//                $('#error_modal').modal({
//                    show: true
//                });
//            }
        });
        
        e_trans_out_c.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_trans_out_c.val())){
                    alert('Please fill in this field!');
                    e_trans_out_c.focus();
                }else{
                    check_trans_out_c(e_trans_out_c.val());
                    e_trans_out_c.prop("readonly", true);
                    e_fe_report_c.focus();
                }
                return false;
            }
        });
        
        $("#btn_submit_c").on("click", function(e){     
            $('#confirmation').modal({
                show: true
            });
            $('#opt_yess').click(function () {
                complete_close();
                window.location.href = "<?php echo base_url('new-incoming-trans'); ?>";
            });
            $('#opt_no').click(function () {
                complete_close();
                window.location.href = "<?php echo base_url('incoming-trans'); ?>";
            });
        });
    });
</script>