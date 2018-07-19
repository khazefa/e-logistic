<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-supply-tab" data-toggle="tab" href="#nav-supply" role="tab" aria-controls="nav-supply" aria-selected="true">Supply</a>
                        <a class="nav-item nav-link" id="nav-return-tab" data-toggle="tab" href="#nav-return" role="tab" aria-controls="nav-return" aria-selected="false">Return</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- Begin Content Panel Supply -->
                    <div class="tab-pane fade show active" id="nav-supply" role="tabpanel" aria-labelledby="nav-supply-tab">
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
                                                    <div class="input-group col-sm-12">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"> <i class="fa fa-calculator"></i> </span>
                                                         </div>
                                                        <input type="number" name="fqty_s" id="fqty_s" class="form-control" value="1" required="required">
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
                                    <table id="cart_grid_s" class="table table-light dt-responsive nowrap" cellspacing="0" width="100%">
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
                    <div class="tab-pane fade" id="nav-return" role="tabpanel" aria-labelledby="nav-return-tab">
                        
                    </div>
                    <!-- End Content Panel Return -->
                </div>
            </div>
        </div>
    </div>
    
</div>
</form>
<script type="text/javascript">
    var e_partnum_s = $('#fpartnum_s');
    var e_qty_s = $('#fqty_s');
    var e_part_note_s = $('#fpartnum_s_notes');
    
    var dataSet = [];

    //initial form supply
    function init_form_s(){
        e_partnum_s.val("");
        e_qty_s.val(1);
        e_part_note_s.html("");
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
                    e_part_note_s.html(jqXHR.message);
                    e_partnum_s.val("");
                    e_partnum_s.focus();
                    status = 0;
                }else if(jqXHR.status === 1){
                    e_part_note_s.html(jqXHR.message);
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
                    get_total();
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
    
    function get_total() {
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
    
    //add to cart
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
                    get_total();
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
    
    //add to cart
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
                    get_total();
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
            fqty : parseInt($('#ttl_qty_s').html())
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
        get_total();
        
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
    });
</script>