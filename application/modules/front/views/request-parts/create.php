<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><?php echo $contentTitle;?></h4>
            <div class="btn-group">
                <button type="button" onclick="location.href='javascript:history.back()'" class="btn btn-sm btn-light waves-effect">
                    <i class="mdi mdi-keyboard-backspace font-18 vertical-middle"></i> Back
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="fpurpose" class="col-form-label">Purpose</label>
                                <select name="fpurpose" id="fpurpose" class="form-control" placeholder="Select Purpose">
                                    <option value="0">Select Purpose</option>
                                    <option value="SP">Sales/Project</option>
                                    <option value="W">Warranty</option>
                                    <option value="M">Maintenance</option>
                                    <option value="I">Investment</option>
                                    <option value="B">Borrowing</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="fticketnum" class="col-form-label">Ticket Number <span class="text-danger">*</span></label>
                                <input type="text" name="fticketnum" id="fticketnum" class="form-control" required="required" data-parsley-minlength="8" 
                                    data-parsley-maxlength="8" pattern="[a-zA-Z0-9\s]+" placeholder="(Required)" 
                                    data-toggle="tooltip" data-placement="top" title="" data-original-title="Input Ticket No and then Press [ENTER]">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fengineer_id" class="col-form-label">Assigned FSE <span class="text-danger">*</span></label>
                                <select name="fengineer_id" id="fengineer_id" class="selectpicker" required data-live-search="true" 
                                        data-selected-text-format="values" title="Select FSE.." data-style="btn-light">
                                    <?php
                                        foreach($list_engineer as $e){
                                            echo '<option value="'.$e["feid"].'">'.$e['feid'].' - '.$e["fullname"].' - '.$e["partner"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fengineer2_id" class="col-form-label">FSE Messenger</label>
                                <select name="fengineer2_id" id="fengineer2_id" class="selectpicker" data-live-search="true" 
                                        data-selected-text-format="values" title="Select Messenger.." data-style="btn-light">
                                    <?php
                                        foreach($list_engineer as $e2){
                                            echo '<option value="'.$e2["feid"].'">'.$e2['feid'].' - '.$e2["fullname"].' - '.$e2["partner"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="fssb_id" class="col-form-label">SSB/ID <span class="text-danger">*</span></label>
                                <input type="text" name="fssb_id" id="fssb_id" class="form-control" required="true" placeholder="(Required)" 
                                    data-toggle="tooltip" data-placement="top" title="" data-original-title="Input SSB/ID and then Press [ENTER]">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fcust" class="col-form-label">Customer <span class="text-danger">*</span></label>
                                <input type="text" name="fcust" id="fcust" class="form-control" required="true" placeholder="(Required)">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="floc" class="col-form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="floc" id="floc" class="form-control" required="true" placeholder="(Required)">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fdelivery" class="col-form-label">Delivery Notes</label>
                                <input type="text" name="fdelivery" id="fdelivery" class="form-control" placeholder="(Optional)">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fnotes" class="col-form-label">Request Notes</label>
                                <input type="text" name="fnotes" id="fnotes" class="form-control" placeholder="(Optional)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card-box">
            <div class="card-header bg-primary text-white">
                <input type="checkbox" name="fswitch" id="fswitch" data-plugin="switchery" data-color="#f1b53d"/>
                <strong class="card-title">Input your detail orders</strong>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-3 offset-md-9">
                                <a class="btn btn-default waves-effect" href="#" role="button" id="btn_stucked_cart"
                                   aria-haspopup="true" aria-expanded="false" 
                                   data-toggle="tooltip" data-placement="bottom" title="" data-original-title="View Data Stucked Cart">
                                    <i class="mdi mdi-basket-fill noti-icon"></i> Stucked Cart
                                    <span class="badge badge-danger badge-pill noti-icon-badge" id="ttl_stucked_cart">0</span>
                                </a>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <select id="fpartname" name="fpartname" class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Search Part Name.." data-style="btn-light">
                                    <?php
                                        foreach($list_part as $p){
                                            echo '<option value="'.$p["partno"].'">'.$p['partno'].' - '.$p["name"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                     </div>
                                    <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="Part Number" 
                                        data-toggle="tooltip" data-placement="top" title="" data-original-title="Input Part No and then Press [ENTER]">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                     </div>
                                    <input type="text" name="fserialnum" id="fserialnum" class="form-control" placeholder="Serial Number" 
                                        data-toggle="tooltip" data-placement="top" title="" data-original-title="Input Serial No and then Press [ENTER]">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div id="fpartnum_notes"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
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
                        </div>
                        <div class="col-md-3 offset-md-9">
                            Total Quantity: <span id="ttl_qty">0</span>
                        </div>
                    </div>
                </div>
                <div class="button-list">
                    <button type="button" id="btn_complete" class="btn btn-success waves-effect waves-light" 
                        data-toggle="tooltip" data-placement="top" title="" data-original-title="Please re-confirm the data you have input is appropriate">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-box">
            <h4 class="m-b-30 header-title">Part Subtitusi</h4>
            <div class="table-responsive">
                <table id="subtitute_grid" class="table table-light dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Part Number</th>
                        <th>Part Name</th>
                        <th>Stock</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-box">
            <h4 class="m-b-30 header-title">Part In Nearby Warehouse</h4>
            <div class="table-responsive">
                <table id="fsl_grid" class="table table-light dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>FSL</th>
                        <th>Part Number</th>
                        <th>Part Name</th>
                        <th>Stock</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
</form>

<!-- Modal View Detail Information -->
<div class="modal fade" id="viewstucked" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel">
    <div class="modal-dialog"  style="max-width:900px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">View Stucked Cart</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box table-responsive">
                            <p>Harap hapus data cart yang tersangkut di sistem ini, 
                            agar jumlah dari Part yang ada dapat dikembalikan ke data 
                            stok masing-masing oleh sistem.</p>
                            <table id="stucked_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Part Name</th>
                                    <th>Qty</th>
                                    <th>Cart Date</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal View Detail Information -->

<script type="text/javascript">
    var e_purpose = $('#fpurpose');
    var e_ticketnum = $('#fticketnum');
    var e_engineer_id = $('#fengineer_id');
    var e_engineer2_id = $('#fengineer2_id');
    var e_engineer_notes = $('#feg_notes');
    var e_engineer2_notes = $('#feg2_notes');
    var e_ssb_id = $('#fssb_id');
    var e_cust = $('#fcust');
    var e_loc = $('#floc');
    var e_delivery = $('#fdelivery');
    var e_notes = $('#fnotes');
    
    var e_switch = $('#fswitch');
    var e_partname = $('#fpartname');
    var e_partnum = $('#fpartnum');
    var e_partnum_notes = $('#fpartnum_notes');
    var e_serialnum = $('#fserialnum');
    
    var dataSet = [];
    var status_checkpart = 0;
    var status_checkticket = 0;
    var table;
    var table2;
    var table3;
    var table_s;
        
    //initial form state
    function init_form(){
        e_purpose.focus();
        
        e_ticketnum.val("");
        e_ticketnum.prop("readonly", true);
        e_engineer_id.attr('disabled', true);
        e_engineer2_id.attr('disabled', true);
        e_ssb_id.val("");
        e_ssb_id.prop("readonly", true);
        e_cust.val("");
        e_cust.prop("readonly", true);
        e_loc.val("");
        e_loc.prop("readonly", true);
        e_delivery.val("");
        e_delivery.prop("readonly", true);
        e_notes.val("");
        e_notes.prop("readonly", true);
    }
    
    function init_form_order(){
//        e_switch.prop('checked', false);
        e_partname.attr('disabled', true);
        e_partname.val('');
        e_partname.selectpicker('refresh');
        e_partnum.val("");
        e_partnum.prop("readonly", true);
        e_partnum_notes.html("");
        e_serialnum.val("");
        e_serialnum.prop("readonly", true);
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
                url: "<?php echo $url_list_cart;?>",
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
                { "data": 'partname' },
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
                    targets   : 2,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        var html = '';
                        if(data === "NOSN"){
                            html = data;
                        }else{
                            html = '<input type="text" id="dserialnum" value="'+data+'" class="form-control" title="Change Serial No and then Press [ENTER]">';
                        }
                        return html;
                    }
                },
                {
                    targets   : 4,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        var html = '';
                        if(data === "0"){
                            html = '<span class="text-danger font-weight-bold">0</span>';
                        }else{
                            html = data;
                        }
                        return html;
                    }
                },
                {
                    targets   : 5,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
//                        console.log('data: '+full.serial_number);
                        var html = '';
                        if(full.serialno === "NOSN"){
                            html = '<input type="number" id="dqty" min="0" value="'+full.qty+'" style="width: 100%;">';
                        }else{
                            html = data;
                        }
                        return html;
                    }
                }
            ],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;

                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ? i : 0;
                };
                var totalQty = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                $('#ttl_qty').html(totalQty);
            },
        });
        
        //function for datatables button
        $('#cart_grid tbody').on( 'click', 'button', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            fpartnum = data['partno'];
            fqty = parseInt(data['qty']);
            delete_cart(fid, fpartnum, fqty);
        });

        //function for datatables button
        $('#cart_grid tbody').on( 'keydown', '#dserialnum', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            fserialnum = this.value;
            if (e.keyCode == 9 || e.keyCode == 13) {
                //update cart by cart id
                update_cart_info(fid, fserialnum);
                return false;
            }
        });
        
        //function for datatables button
        $('#cart_grid tbody').on( 'keydown', '#dqty', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            fpartnum = data['partno'];
            fstock = parseInt(data['stock']);
            fqty = parseInt(this.value);
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(fqty > fstock){
                    alert('The quantity amount exceeds the sparepart stock !');
                    this.focus;
                }else{
                    //update cart by cart id
                    update_cart_qty(fid, fpartnum, fqty);
                }
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

    function total_stucked(){
        var url = '<?php echo base_url('cart/outgoing/total-stucked'); ?>';
        var type = 'GET';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                $('#ttl_stucked_cart').html(jqXHR.total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }

    //init table
    function init_table_stucked(){
        table_s = $('#stucked_grid').DataTable({
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
                url: '<?php echo base_url('cart/outgoing/list-stucked'); ?>',
                type: "GET",
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
                { "data": 'partname' },
                { "data": 'qty' },
                { "data": 'cart_date' },
            ],
            columnDefs : [
                {
                    targets   : 0,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        return '<button type="button" class="btn btn-danger" id="btn_delete_s"><i class="fa fa-trash"></i></button>';
                    }
                }
            ],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;

                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ? i : 0;
                };
                var totalQty = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                $('#ttl_qty_s').html(totalQty);
            },
        });
        
        //function for datatables button
        $('#stucked_grid tbody').on( 'click', '#btn_delete_s', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            fpartnum = data['partno'];
            fqty = parseInt(data['qty']);
            delete_cart(fid, fpartnum, fqty);
        });

        table_s.buttons().container()
                .appendTo('#stucked_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload_stucked(){
        table_s.ajax.reload();
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
                { "title": "Part Name", "class": "center" },
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
                table2.clear().draw();
                e_partnum.focus();
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
        table3 = $('#fsl_grid').DataTable({
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
                { "title": "Warehouse", "class": "center" },
                { "title": "Part Number", "class": "center" },
                { "title": "Part Name", "class": "center" },
                { "title": "Stock", "class": "center" },
            ]
        });

        table3.buttons().container()
                .appendTo('#fsl_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload3(){
        table3.ajax.reload();
    }
    
    //get part replacement
    function get_part_sub(partno){
        var status = 0;
        var total_stock_sub = 0;
        var url = '<?php echo $url_part_sub;?>';
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
                if(jqXHR.status === 1){
                    //load parts replacement
                    table2.clear().draw();
                    $.each(jqXHR.data, function(i, object) {
                        $.each(object, function(property, data) {
                            $.each(data, function(property2, detail_data) {
//                                if(detail_data.stock === "0"){
//                                    //if stock is 0 then hide information list
//                                }else{
                                    table2.row.add(
                                        [detail_data.partno, detail_data.partno, detail_data.part, detail_data.stock]
                                    ).draw();
                                    total_stock_sub = total_stock_sub + parseInt(detail_data.stock);
//                                }
                            });
                        });
                    });
//                    e_partnum_notes.html('<span class="help-block text-success">'+jqXHR.message+'</span>');
                    if(total_stock_sub === 0){
                        get_nearby_wh(partno);
                    }else{
                        table3.clear().draw();
                    }
                    status = 1;
                }else if(jqXHR.status === 0){
                    e_partnum_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    e_partnum.focus();
                    table2.clear().draw();
                    status = 0;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
        return status;
    }
    
    //get part replacement
    function get_nearby_wh(partno){
        var url = '<?php echo $url_part_nearby;?>';
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
//                                if(detail_data.stock === "0"){
//                                    //if stock is 0 then hide information list
//                                }else{
                                    table3.row.add(
                                        [detail_data.warehouse, detail_data.partno, detail_data.part, detail_data.stock]
                                    ).draw();
//                                }
                            });
                        });
                    });
                }else if(jqXHR.status == 0){
                    e_partnum_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
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
        var url = '<?php echo $url_check_part;?>';
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
            async: false,
            success: function (jqXHR) {
                if(jqXHR.status === 0){
                    e_partnum_notes.html('<span class="help-block text-warning">'+jqXHR.message+'</span>');
                    //load data part replacement
                    get_part_sub(partno);
                    status_checkpart = 0;
                }else if(jqXHR.status === 1){
                    e_partnum_notes.html('<span class="help-block text-success">'+jqXHR.message+'</span>');
                    table2.clear().draw();
                    table3.clear().draw();
                    
                    e_partnum.prop("readonly", true);
                    status_checkpart = 1;
                }else if(jqXHR.status === 2){
                    e_partnum_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    table2.clear().draw();
                    table3.clear().draw();
                    status_checkpart = 2;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
        
    }
    
    //check ticket
    function check_ticket(ticketno){        
        var url = '<?php echo base_url($classname.'/check-ticket'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fticket : ticketno
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
                    alert(jqXHR.message);
                    window.location.href = "<?php echo base_url($classname.'/view'); ?>";
                    status_checkticket = 0;
                }else if(jqXHR.status === 1){
                    alert(jqXHR.message);
                    status_checkticket = 1;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
//        return status;
    }
    
    function get_atm_loc(ssbid){
        var url = '<?php echo $url_detail_atm;?>';
        var type = 'GET';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            fssbid : ssbid,
        };
        var success = function (jqXHR) {
            var rs = jqXHR.data[0];
            if(isEmpty(rs)){
                alert('System cannot find the data specified, please input by manual.');
                e_cust.val("");
                e_cust.prop("readonly", false);
                e_cust.focus();
                e_loc.val("");
                e_loc.prop("readonly", false);
            }else{
                e_cust.val(rs.bank);
                e_cust.prop("readonly", true);
                e_loc.val(rs.location);
                e_loc.prop("readonly", true);
                e_delivery.focus();
            }
        };
        throw_ajax(url, type, data, success, throw_ajax_err);
    }
    
    //add part sub number to part number field
    function add_part_sub(partno){
        e_partnum.prop("readonly", false);
        e_partnum.val('');
        e_partnum.val(partno);
        alert('Please press [ENTER] to your subtitution part number');
    }
    
    //add to cart
    function add_cart(partno, serialno, qty){
        var total_qty = table.rows().count();
        
        if(total_qty >= 3){
            $("#error_modal .modal-title").html("Message");
            $("#error_modal .modal-body h4").html("Cannot add request more than 3 items!");
            $('#error_modal').modal({
                show: true
            });
        }else{
            var url = '<?php echo base_url('cart/outgoing/add/'.$cart_postfix); ?>';
            var type = 'POST';

            var data = {
                <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
                fpartnum : partno,
                fserialnum : serialno,
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
                    if(jqXHR.status == 0){
                        alert(jqXHR.message);
                    }else if(jqXHR.status == 1){
                        reload();
                    }else if(jqXHR.status == 2){
                        alert(jqXHR.message);
                        init_form_order();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
                }
            });
        }
    }
    
    //update cart
    function update_cart_info(id, serialnum){
        var url = '<?php echo base_url('cart/outgoing/update'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : id,
            fpartnum : "",
            fserialnum : serialnum,
            fqty : 0
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

    //update cart
    function update_cart_qty(id, partnum, qty){
        var url = '<?php echo base_url('cart/outgoing/update'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : id,
            fpartnum : partnum,
            fserialnum : "",
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
    function delete_cart(id, partnum, qty){        
        var url = '<?php echo base_url('cart/outgoing/delete'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : id,
            fpartnum : partnum,
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
    
    //submit transaction
    function complete_request(){
        var url = '<?php echo base_url($classname.'/insert'); ?>';
        var type = 'POST';

        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fticket : e_ticketnum.val(),
            fengineer_id : e_engineer_id.val(),
            fengineer2_id : e_engineer2_id.val(),
            fpurpose : e_purpose.val(),
            fqty : parseInt($('#ttl_qty').html()),
            fdelivery : e_delivery.val(),
            fnotes : e_notes.val(),
            fcust : e_cust.val(),
            floc : e_loc.val(),
            fssb_id : e_ssb_id.val()
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
                    alert(jqXHR.message);
                }else if(jqXHR.status === 1){
                    print_transaction(jqXHR.message);
                    window.location.href = "<?php echo base_url($classname.'/add'); ?>";
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    function print_transaction(ftransno)
    {
        var param = ftransno;
        var url = '<?php echo base_url($classname.'/print/'); ?>'+param;
        var newWindow=window.open(url);
//        window.location.assign(url);
    }
    
    $(document).ready(function() {
        init_form();
        init_form_order();
        total_stucked();
        
        e_ticketnum.on("keyup", function(e) {
            $(this).val($(this).val().toUpperCase());
	    });
        
        e_serialnum.on("keyup", function(e) {
            var sn = $(this).val().trim();
            if(sn.toUpperCase() == "NO SN"){
                $(this).val("NOSN");
            }else if(sn == "nosn"){
                $(this).val("NOSN");
            }else if(sn == "no sn"){
                $(this).val("NOSN");
            }
	    });
       
        init_table();
        init_table2();
        init_table3();
            
        e_purpose.on("change", function(e) {
            var valpurpose = $(this).val();
            
            if(valpurpose === "0"){
                alert( "Please choose purpose!" );
                init_form();
            }else{
                e_ticketnum.prop("readonly", false);
                e_ticketnum.focus();
                
                e_engineer_id.prop('disabled', false);
                e_engineer_id.val('default');
                e_engineer_id.selectpicker('refresh');
                
                e_notes.val("");
                e_notes.prop("readonly", true);
                e_delivery.val("");
                e_delivery.prop("readonly", true);
                e_cust.val("");
                e_cust.prop("readonly", true);
                e_loc.val("");
                e_loc.prop("readonly", true);
                e_ssb_id.val("");
                e_ssb_id.prop("readonly", true);
            }
        });
        
        e_ticketnum.on("change", function(e){
            var len = e_ticketnum.val().length;

        });
        
        e_ticketnum.on("keydown", function(e){
            var len = e_ticketnum.val().length;
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(isEmpty(e_ticketnum.val())){
                    alert('Please fill out the ticket number!');
                    e_ticketnum.focus();
                }else{
                    if (e_ticketnum.val().match("^5")) {
                        if(len !== 8){
                            alert('Ticket number must be 8 digits');
                            e_ticketnum.val('');
                            e_ticketnum.focus();
                        }else{
                            check_ticket(e_ticketnum.val());
                            if(status_checkticket === 1){
                                e_engineer_id.focus();
                            }
                        }
                    }else{
                        alert('Ticket number not accepted! start typing by number 5!');
                        e_ticketnum.val('');
                        e_ticketnum.focus();
                    }
                }
                return false;
            }
        });
        
        e_engineer_id.on('change', function() {
            if(isEmpty(e_ticketnum.val())){
                alert('Please fill out the ticket number!');
                e_ticketnum.focus();
                e_engineer_id.selectpicker('refresh');
            }else{
                $("#global_confirm .modal-title").html("Confirmation");
                $("#global_confirm .modal-body h4").html("Add FSE Messenger?");
                $('#global_confirm').modal({
                    show: true
                });
                $('#ans_yess').click(function () {
                    e_engineer2_id.prop('disabled', false);
                    e_engineer2_id.selectpicker('refresh');
                    e_engineer2_id.focus();
                    e_notes.prop("readonly", false);
                    e_delivery.prop("readonly", false);
                    e_ssb_id.prop("readonly", false);
                });
                $('#ans_no').click(function () {
                    e_delivery.prop("readonly", false);
                    e_delivery.focus();
                    e_notes.prop("readonly", false);
                    e_ssb_id.prop("readonly", false);
                });
            }
        });
        
        e_engineer2_id.on('change', function() {
            e_ssb_id.focus();
        });
        
        e_ssb_id.on('keydown', function(e){
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(isEmpty(this.value)){
                    alert('Please fill in the text box!');
                }else{
                    get_atm_loc(this.value);
                }
                return false;
            }
        });
        
        e_switch.on('change', function(){
            if(this.checked === false){
                init_form_order();
            }else{
                e_partname.attr('disabled', false);
                e_partname.val('');
                e_partname.selectpicker('refresh');
                e_partnum.prop('readonly', false);
                e_partnum.val('');
                e_partnum.focus();
            }
        });
        
        e_partname.on('change', function() {
            var selectedText = $(this).find("option:selected").val();
            e_partnum.prop('readonly', false);
            e_partnum.val(selectedText);
            e_partnum.focus();
            e_partname.val('');
            e_partname.selectpicker('refresh');
        });
        
        e_partnum.on('keydown', function(e){
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(isEmpty(e_partnum.val())){
                    alert('Please fill in this field!');
                    e_partnum.focus();
                }else{
                    if (/^[0-9A-Za-z]+$/.test(e_partnum.val())){
                        check_part(e_partnum.val());
                        if(status_checkpart === 1){
                            //fill serial number
                            e_serialnum.prop('readonly', false);
                            e_serialnum.val('');
                            e_serialnum.focus();
                        }
                    }else{
                        alert('Spare part number contained by unknown characters!');
                        e_partnum.val('');
                        e_partnum.focus();
                    }
                }
                return false;
            }
        });
        
        e_serialnum.on("keydown", function(e){
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(isEmpty(e_partnum.val())){
                    alert('Please fill out spare part number!');
                    e_partnum.focus();
                }else if(isEmpty(e_serialnum.val())){
                    alert('Please fill out serial number!');
                    e_serialnum.focus();
                }else{
                    check_part(e_partnum.val());
                    if(status_checkpart === 1){
                        if(e_serialnum.val() === "NOSN"){
                            var qty = parseInt(prompt("Enter quantity for NOSN Serial Number"));
                            add_cart(e_partnum.val(), e_serialnum.val(), qty);
                        }else{
                            var qty = 1;
                            add_cart(e_partnum.val(), e_serialnum.val(), qty);
                        }
                        reload();
                        e_partnum.prop('readonly', false);
                        e_partnum.val('');
                        e_partnum.focus();
                        e_partnum_notes.html('');
                        e_serialnum.prop('readonly', true);
                        e_serialnum.val('');
                    }else{
                        alert('Please check your spare part number again');
                        e_partnum.prop('readonly', false);
                        e_partnum.focus();
                    }
                }
                return false;
            }
        });

        $("#btn_stucked_cart").on("click", function(e){
            $('#viewstucked').modal({
                show: true
            });
            init_table_stucked();
        });
        
        $("#btn_complete").on("click", function(e){        
            var total_qty = parseInt($('#ttl_qty').html());
            
            if(e_purpose.val() === "0"){
                $("#error_modal .modal-title").html("Message");
                $("#error_modal .modal-body h4").html("Please select your purpose!");
                $('#error_modal').modal({
                    show: true
                });
            }else{
                if(isEmpty(e_ticketnum.val())){
                    alert('Please fill out the ticket number!');
                    e_ticketnum.focus();
                }else if(isEmpty(e_engineer_id.val()) || e_engineer_id.val() === "0"){
                    alert('Please select the FSE data!');
                    e_engineer_id.focus();
                }
                else{
                    if(total_qty > 0){
                        complete_request();
                    }else{
                        $("#error_modal .modal-title").html("Message");
                        $("#error_modal .modal-body h4").html("You dont have any detail of transaction");
                        $('#error_modal').modal({
                            show: true
                        });
                        e_partnum.prop('readonly', false);
                        e_partnum.focus();
                    }
                }
            }
        });
    });
</script>