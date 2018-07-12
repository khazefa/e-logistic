<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <label for="fpurpose" class="col-sm-3 col-form-label">Purpose</label>
                            <div class="col-sm-6">
                                <select name="fpurpose" id="fpurpose" class="form-control" placeholder="Select Purpose">
                                    <option value="0">Select Purpose</option>
                                    <option value="SP">Sales/Project</option>
                                    <option value="W">Warranty</option>
                                    <option value="M">Maintenance</option>
                                    <option value="I">Investment</option>
                                    <option value="B">Borrowing</option>
                                    <option value="RWH">Return to WH</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <label for="fticketnum" class="col-sm-3 col-form-label">Ticket Number</label>
                            <div class="col-sm-6">
                                <input type="text" name="fticketnum" id="fticketnum" class="form-control" required="required" pattern="[a-zA-Z0-9\s]+">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <label for="fengineer_id" class="col-sm-3 col-form-label">FE ID Number</label>
                            <div class="col-sm-6">
                                <input type="text" name="fengineer_id" id="fengineer_id" class="form-control" required="required">
                                <span id="feg_notes" class="help-block"><small></small></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <label for="fnotes" class="col-sm-3 col-form-label">Delivery Notes</label>
                            <div class="col-sm-6">
                                <input type="text" name="fnotes" id="fnotes" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <label for="fpartner" class="col-sm-3 col-form-label">Service Partner</label>
                            <div class="col-sm-6">
                                <input type="text" name="fpartner" id="fpartner" class="form-control" readonly="readonly">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <label for="fpurpose_notes" id="fpurpose_notes" class="col-sm-9 col-form-label"></label>                            
                        </div>
                    </div>
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <label for="fengineer_name" class="col-sm-3 col-form-label">Assigned FE</label>
                            <div class="col-sm-6">
                                <input type="text" name="fengineer_name" id="fengineer_name" class="form-control" readonly="readonly">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card-box">
            <div class="card-header bg-primary text-white">
                <strong class="card-title">Detail Orders</strong>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                 </div>
                                <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="Part Number" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                 </div>
                                <input type="text" name="fserialnum" id="fserialnum" class="form-control" placeholder="Serial Number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm col-sm-12">
                        <span id="fpartnum_notes" class="help-block text-danger"><small></small></span>
                    </div>
                    <div class="m-b-10">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col col-md-12">

                            <table id="cart_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Part Name</th>
                                    <th>Stock</th>
                                    <th>Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        <div class="mt-2"></div>

                        <div class="row">
                            <div class="col-md-2 offset-md-10">
                                Total Quantity: <span id="ttl_qty">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-list">
                    <div class="mt-2"></div>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box table-responsive">
            <h4 class="m-b-30 header-title">Part Subtitusi</h4>
            <table id="subtitute_grid" class="table table-light dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Part Number</th>
                    <th>Stock</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        
        <div class="card-box table-responsive">
            <h4 class="m-b-30 header-title">Part In Nearby Warehouse</h4>
            <table id="wh_grid" class="table table-light dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>FSL</th>
                    <th>Part Number</th>
                    <th>Stock</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    
</div>
</form>
<script type="text/javascript">
    var e_purpose = $('#fpurpose');
    var e_ticketnum = $('#fticketnum');
    var e_engineer_id = $('#fengineer_id');
    var e_engineer_notes = $('#feg_notes');
    var e_notes = $('#fnotes');
    var e_partner = $('#fpartner');
    var e_engineer_name = $('#fengineer_name');
    var e_purpose_notes = $('#fpurpose_notes');
    
    var e_partnum = $('#fpartnum');
    var e_partnum_notes = $('#fpartnum_notes');
    var e_serialnum = $('#fserialnum');
    
    var fpurpose = "";
    var fticketnum = "";
    var fengineer_id = "";
    var fnotes = "";
    var fpartner = "";
    var fengineer_name = "";
    var fpartnum = "";
    var fserialnum = "";
    
    var dataSet = [];
        
    //initial form state
    function init_form(){
        e_ticketnum.prop("readonly", true);
        e_ticketnum.val("");
        e_engineer_id.prop("readonly", true);
        e_engineer_id.val("");
        e_engineer_notes.html("");
        e_notes.prop("readonly", true);
        e_notes.val("");
        e_purpose_notes.html("Purpose Notes");
    }
    
    //initial form order state
    function init_form_order(){
        e_partnum.val("");
        e_partnum.focus();
        e_partnum_notes.html("");
        e_serialnum.val("");
    }
    
    //get detail eg
    function get_eg_detail(eg_id){
        var url = '<?php echo base_url('front/coutgoing/info_eg'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fkey : e_engineer_id.val()
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 1){
                    e_partner.val(jqXHR.pr_name);
                    e_engineer_name.val(jqXHR.eg_name);
                    e_engineer_notes.html("");
                }else if(jqXHR.status == 0){
                    e_engineer_notes.html(jqXHR.message);
//                    alert(jqXHR.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //init table
    function init_table(){
        table = $('#cart_grid').DataTable({
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
                url: "<?= base_url('front/coutgoing/get_list_cart_datatable'); ?>",
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
                { "data": 'serialno' },
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
                {
                    targets   : 5,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
//                        console.log('data: '+full.serial_number);
                        if(full.serialno === "NOSN"){
                            return '<input type="text" id="fqty" value="'+full.qty+'" data-parsley-type="number" size="2" pattern="[0-9]">';
                        }else{
                            return data;
                        }
                    }
                }
            ]
        });
        
        //function for datatables button
        $('#cart_grid tbody').on( 'click', 'button', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            delete_cart(fid);
        });
        
        //function for datatables button
        $('#cart_grid tbody').on( 'keypress', 'input', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            fqty = this.value;
            if (e.keyCode == 13) {
                //update cart by cart id
                update_cart(fid, fqty);
                return false;
            }
        });

        table.buttons().container()
                .appendTo('#cart_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload(){
        table.ajax.reload();
    }
    
    //init table
    function init_table2(){
        table2 = $('#subtitute_grid').DataTable({
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
                { "title": "", "class": "center" },
                { "title": "Part Number", "class": "center" },
                { "title": "Stock", "class": "center" },
            ],
            columnDefs : [{
                targets   : 0,
                orderable : false, //set not orderable
                data      : null,
                render    : function ( data, type, full, meta ) {
                    return '<button type="button" class="btn btn-warning" id="btn_add_sub"><i class="fa fa-cart-plus"></i></button>';
            }}]
        });
        
        //function for datatables button
        $('#subtitute_grid tbody').on( 'click', 'button', function (e) {        
            var data = table2.row( $(this).parents('tr') ).data();
            fpartnum = data[0];
            fstock = data[2];
            
            if(fstock < 1){
                alert('Out of stock!');
            }else{
                add_part_sub(fpartnum);
            }
        });

        table2.buttons().container()
                .appendTo('#subtitute_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload2(){
        table2.ajax.reload();
    }
    
    //init table
    function init_table3(){
        table3 = $('#wh_grid').DataTable({
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
                { "title": "FSL", "class": "center" },
                { "title": "Part Number", "class": "center" },
                { "title": "Stock", "class": "center" },
            ]
        });

        table3.buttons().container()
                .appendTo('#wh_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload3(){
        table3.ajax.reload();
    }
    
    //get part replacement
    function get_part_sub(partno){
        var url = '<?php echo base_url('front/coutgoing/get_list_part_sub'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : partno
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 1){
                    //load parts replacement
                    table2.clear().draw();
                    $.each(jqXHR.data, function(i, object) {
                        $.each(object, function(property, data) {
                            $.each(data, function(property2, detail_data) {
                                if(detail_data.stock === "0"){
                                    
                                }else{
                                    table2.row.add(
                                        [detail_data.partno, detail_data.partno, detail_data.stock]
                                    ).draw();
                                }
                            });
                        });
                    });
                    e_partnum_notes.html(jqXHR.message);
//                    e_partnum.val('');
                }else if(jqXHR.status == 0){
                    e_partnum_notes.html(jqXHR.message);
//                    e_partnum.val('');
                    e_partnum.focus();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //get part replacement
    function get_nearby_wh(partno){
        var url = '<?php echo base_url('front/coutgoing/get_part_nearby'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : partno
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 1){
                    //load part from nearby warehouse
                    table3.clear().draw();
                    $.each(jqXHR.data, function(i, object) {
                        $.each(object, function(property, data) {
                            $.each(data, function(property2, detail_data) {
                                if(detail_data.stock === "0"){
                                    //if stock is 0 then hide part information
                                }else{
                                    table3.row.add(
                                        [detail_data.code, detail_data.partno, detail_data.stock]
                                    ).draw();
                                }
                            });
                        });
                    });
                }else if(jqXHR.status == 0){
                    e_partnum_notes.html(jqXHR.message);
                    e_partnum.val('');
                    e_partnum.focus();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //check part stock
    function check_part(partno){
        var status = 0;
        var url = '<?php echo base_url('front/coutgoing/check_part'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : e_partnum.val()
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
                    e_partnum_notes.html(jqXHR.message);
                    status = 0;
                }else if(jqXHR.status == 1){
                    e_partnum_notes.html(jqXHR.message);
                    table2.clear().draw();
                    //fill serial number
                    e_serialnum.focus();
                    status = 1;
                }else if(jqXHR.status == 2){
                    //load part from nearby warehouse
                    e_partnum_notes.html(jqXHR.message);
                    table2.clear().draw();
                    status = 2;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
        return status;
    }
    
    //add part sub number to part number field
    function add_part_sub(partno){
        e_partnum.val('');
        e_partnum.val(partno);
        if(check_part(partno) === 0){
            //load data part replacement
            get_part_sub(partno);
        }else if(check_part(partno) === 1){
            //fill in serial number
            table2.clear().draw();
        }else if(check_part(partno) === 2){
            //load part from nearby warehouse
            get_nearby_wh(partno);
        }
//        e_partnum.focus;
    }
    
    //add to cart
    function add_cart(partno, serialno){
//        alert('Cart add part number:'+partno+' - serial number:'+serialno);
        
        var url = '<?php echo base_url('front/coutgoing/add_cart'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : partno,
            fserialnum : serialno
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                if(jqXHR.status == 1){
                    get_total();
                }else if(jqXHR.status == 0){
                    alert(jqXHR.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    function get_total() {
        var url = '<?php echo base_url('front/coutgoing/get_total_cart'); ?>';
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
                    $('#ttl_qty').html(jqXHR.ttl_cart);
                }else{
                    $('#ttl_qty').html(jqXHR.ttl_cart);
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
        var url = '<?php echo base_url('front/coutgoing/update_cart'); ?>';
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
        var url = '<?php echo base_url('front/coutgoing/delete_cart'); ?>';
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
    
    $(document).ready(function() {
        init_form();
        init_form_order();
        
        e_ticketnum.on("keyup", function(e) {
            $(this).val($(this).val().toUpperCase());
	});
        
        e_engineer_id.on("keyup", function(e) {
            $(this).val($(this).val().toUpperCase());
	});
        
        /*
        e_notes.on("keyup", function(e) {
            $(this).val($(this).val().toUpperCase());
	});
        */
       
        init_table();
        init_table2();
        init_table3();
        get_total();
            
        e_purpose.on("change", function(e) {
            var valpurpose = e_purpose.val();
            
            if(valpurpose === "0"){
                alert( "Please choose purpose!" );
                init_form();
            }else if(valpurpose === "RWH"){
                e_ticketnum.prop("readonly", true);
                e_ticketnum.val("");
                e_engineer_id.prop("readonly", true);
                e_engineer_id.prop("value", "");
                e_notes.prop("readonly", false);
                e_notes.focus();
                e_purpose_notes.html("Return Sparepart");
            }else{
                e_ticketnum.prop("readonly", false);
                e_ticketnum.focus();
                e_engineer_id.prop("readonly", false);
                e_notes.prop("readonly", true);
                e_notes.val("");
                e_purpose_notes.html("Sparepart to engineer");
            }
        });
        
        e_engineer_id.on("keypress", function(e) {
            if (e.keyCode == 13) {
                get_eg_detail(e_engineer_id.val());
                return false;
            }
        });
        
        e_partnum.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_partnum.val())){
                    alert('Please fill in this field!');
                    e_partnum.focus();
                }else{
                    if(check_part(e_partnum.val()) === 0){
                        //load data part replacement
                        get_part_sub(e_partnum.val());
                    }else if(check_part(e_partnum.val()) === 1){
                        //fill in serial number
                    }else if(check_part(e_partnum.val()) === 2){
                        //load part from nearby warehouse
                        get_nearby_wh(e_partnum.val());
                    }
                }
                return false;
            }
        });
        
        e_serialnum.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_partnum.val()) || isEmpty(e_serialnum.val())){
                    alert('Please fill in required field!');
                    e_serialnum.focus();
                }else{
                    if(check_part(e_partnum.val()) === 0){
                        init_form_order();
                    }else if(check_part(e_partnum.val()) === 0){
                        //add cart with serial number logic
                        add_cart(e_partnum.val(), e_serialnum.val());
                        reload();
                        init_form_order();
                    }else{
                        init_form_order();
                    }
                }
                return false;
            }
        });
    });
</script>