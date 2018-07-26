<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                          <label for="fpurpose" class="col-sm-2 col-form-label">Purpose</label>
                          <div class="col-sm-6">
                                <select name="fpurpose" id="fpurpose" class="form-control" placeholder="Select Purpose">
                                    <option value="0">Select Purpose</option>
                                    <option value="SP">Sales/Project</option>
                                    <option value="W">Warranty</option>
                                    <option value="M">Maintenance</option>
                                    <option value="I">Investment</option>
                                    <option value="B">Borrowing</option>
                                    <option value="RWH">Transfer Stock</option>
                                </select>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Transfer Sparepart to Engineer
                            </div>
                            <div class="card-body">
                                <p>Please use this form section <strong>for purpose other than Transfer Stock</strong></p>
                                <div class="form-group row">
                                    <label for="fticketnum" class="col-sm-3 col-form-label">Ticket Number</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="fticketnum" id="fticketnum" class="form-control" required="required" pattern="[a-zA-Z0-9\s]+">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fengineer_id" class="col-sm-3 col-form-label">Assigned FSE</label>
                                    <div class="col-sm-8">
                                        <select name="fengineer_id" id="fengineer_id" class="selectpicker" required data-live-search="true" 
                                                data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                            <option value="0">Select FSE</option>
                                            <?php
                                                foreach($list_eg as $e){
                                                    echo '<option value="'.$e["feid"].'">'.$e['feid'].' - '.$e["fullname"].' - '.$e["partner"].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fengineer2_id" class="col-sm-3 col-form-label">FSE Messenger</label>
                                    <div class="col-sm-8">
                                        <select name="fengineer2_id" id="fengineer2_id" class="selectpicker" data-live-search="true" 
                                                data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                            <option value="0">Select FSE</option>
                                            <?php
                                                foreach($list_eg as $e2){
                                                    echo '<option value="'.$e2["feid"].'">'.$e2['feid'].' - '.$e2["fullname"].' - '.$e2["partner"].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fdelivery" class="col-sm-3 col-form-label">Delivery Notes</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="fdelivery" id="fdelivery" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fnotes1" class="col-sm-3 col-form-label">Transaction Notes</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="fnotes1" id="fnotes1" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Transfer Sparepart to another FSL
                            </div>
                            <div class="card-body">
                                <p>Please use this form section <strong>for purpose Transfer Stock</strong></p>
                                <div class="form-group row">
                                    <label for="fdest_fsl" class="col-sm-3 col-form-label">FSL Destination</label>
                                    <div class="col-sm-8">
                                        <select name="fdest_fsl" id="fdest_fsl" class="selectpicker" data-live-search="true" 
                                                data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                            <option value="0">Select FSL Destination</option>
                                            <?php
                                                foreach($list_fsl as $f){
                                                    echo '<option value="'.$f["code"].'">'.$f["name"].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fdelivery2" class="col-sm-3 col-form-label">Delivery Notes</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="fdelivery2" id="fdelivery2" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fnotes2" class="col-sm-3 col-form-label">Transaction Notes</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="fnotes2" id="fnotes2" class="form-control">
                                    </div>
                                </div>
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
                <strong class="card-title pull-right">Detail Orders</strong>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group form-group-sm col-sm-6">
                        <div class="row">
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                 </div>
                                <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="Part Number">
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
                        <div id="fpartnum_notes"></div>
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
                            <div class="col-md-3 offset-md-9">
                                Total Quantity: <span id="ttl_qty">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-list">
                    <div class="mt-2"></div>
                    <button type="button" id="btn_complete" class="btn btn-success waves-effect waves-light">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-box table-responsive">
            <h4 class="m-b-30 header-title">Part Subtitusi</h4>
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
        
        <div class="card-box table-responsive">
            <h4 class="m-b-30 header-title">Part In Nearby Warehouse</h4>
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
</form>
<script type="text/javascript">
    var e_purpose = $('#fpurpose');
    var e_ticketnum = $('#fticketnum');
    var e_engineer_id = $('#fengineer_id');
    var e_engineer2_id = $('#fengineer2_id');
    var e_engineer_notes = $('#feg_notes');
    var e_engineer2_notes = $('#feg2_notes');
    var e_delivery1 = $('#fdelivery');
    var e_notes1 = $('#fnotes1');
    
    var e_dest_fsl = $('#fdest_fsl');
    var e_delivery2 = $('#fdelivery2');
    var e_notes2 = $('#fnotes2');
    
    var e_switch = $('#fswitch');
    var e_partnum = $('#fpartnum');
    var e_partnum_notes = $('#fpartnum_notes');
    var e_serialnum = $('#fserialnum');
    
    var dataSet = [];
        
    //initial form state
    function init_form(){
        e_purpose.focus();
        
        e_ticketnum.val("");
        e_ticketnum.prop("readonly", true);
        e_engineer_id.attr('disabled', true);
        e_engineer2_id.attr('disabled', true);
        e_delivery1.val("");
        e_delivery1.prop("readonly", true);
        e_notes1.val("");
        e_notes1.prop("readonly", true);
        
        e_dest_fsl.attr('disabled', true);
        e_delivery2.val("");
        e_delivery2.prop("readonly", true);
        e_notes2.val("");
        e_notes2.prop("readonly", true);
    }
    
    //initial form order state
    function init_form_order(){
//        e_switch.prop('checked', false);
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
                            return '<input type="number" id="fqty" min="0" value="'+full.qty+'" style="width: 100%;">';
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
            fstock = data['stock'];
            fqty = this.value;
            if (e.keyCode == 13) {
                if(fqty > fstock){
                    alert('The quantity amount exceeds the sparepart stock!');
                    this.focus;
                }else{
                    //update cart by cart id
                    update_cart(fid, fqty);
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
    
    //load fsl destination
    function load_fsl_dest(){
        let e_dest_fsl = $('#fdest_fsl');
        e_dest_fsl.empty();

        e_dest_fsl.append('<option selected="true" disabled>Please choose..</option>');
        e_dest_fsl.prop('selectedIndex', 0);

        const url = '<?php echo base_url('front/coutgoing/get_list_warehouse'); ?>';

        // Populate dropdown with list of provinces
        $.getJSON(url, function (data) {
          $.each(data, function (key, entry) {
            e_dest_fsl.append($('<option></option>').attr('value', entry.code).text(entry.name));
          })
        });
    }
    
    //get part replacement
    function get_part_sub(partno){
        var status = 0;
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
//                                }
                            });
                        });
                    });
//                    e_partnum_notes.html('<span class="help-block text-success">'+jqXHR.message+'</span>');
                    table3.clear().draw();
                    status = 1;
                }else if(jqXHR.status === 0){
                    e_partnum_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    e_partnum.focus();
                    table2.clear().draw();
                    get_nearby_wh(partno);
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
        var status = 0;
        
        var url = '<?php echo base_url('front/coutgoing/check_part'); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : partno
        };
        
        var resAjax = $.ajax({
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
                    return 0;
                }else if(jqXHR.status === 1){
                    e_partnum_notes.html('<span class="help-block text-success">'+jqXHR.message+'</span>');
                    table2.clear().draw();
                    table3.clear().draw();
                    
                    e_partnum.prop("readonly", true);
                    //fill serial number
                    e_serialnum.focus();
                    return 1;
                }else if(jqXHR.status === 2){
                    e_partnum_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    table2.clear().draw();
                    table3.clear().draw();
                    return 2;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
        return resAjax.status;
    }
    
    //check ticket
    function check_ticket(ticketno){
        var status = 0;
        
        var url = '<?php echo base_url('front/coutgoing/check_ticket'); ?>';
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
                    window.location.href = "<?php echo base_url('outgoing-trans'); ?>";
                    status = 0;
                }else if(jqXHR.status === 1){
                    alert(jqXHR.message);
                    e_engineer_id.focus();
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
    
    //add part sub number to part number field
    function add_part_sub(partno){
        e_partnum.prop("readonly", false);
        e_partnum.val('');
        e_partnum.val(partno);
        e_partnum.prop("readonly", true);
        check_part(partno);
    }
    
    //add to cart
    function add_cart(partno, serialno){
        var total_qty = table.rows().count();
        
        if(e_purpose.val() === "RWH"){
            if(total_qty >= 15){
                
            }
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
                    if(jqXHR.status == 0){
                        alert(jqXHR.message);
                    }else if(jqXHR.status == 1){
                        reload();
                        get_total();
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
        }else{
            if(total_qty >= 3){
                $("#error_modal .modal-title").html("Message");
                $("#error_modal .modal-body h4").html("Cannot add request more than 3 items!");
                $('#error_modal').modal({
                    show: true
                });
            }else{
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
                        if(jqXHR.status == 0){
                            alert(jqXHR.message);
                        }else if(jqXHR.status == 1){
                            reload();
                            get_total();
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
                if(jqXHR.status == 1){
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
    
    //submit transaction
    function complete_request(){
        var url = '<?php echo base_url('front/coutgoing/submit_trans'); ?>';
        var type = 'POST';
        
        var delivery = "";
        if(isEmpty(e_delivery1.val())){
            delivery = e_delivery2.val();
        }else{
            delivery = e_delivery1.val();
        }
        var notes = "";
        if(isEmpty(e_notes1.val())){
            notes = e_notes2.val();
        }else{
            notes = e_notes1.val();
        }
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fticket : e_ticketnum.val(),
            fengineer_id : e_engineer_id.val(),
            fengineer2_id : e_engineer2_id.val(),
            fpurpose : e_purpose.val(),
            fqty : parseInt($('#ttl_qty').html()),
            fnotes : notes,
            fdelivery : delivery
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
                    print_transaction(jqXHR.message);
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
        var url = '<?php echo base_url('print-outgoing-trans/'); ?>'+param;
        var newWindow=window.open(url);
//        window.location.assign(url);
    }
    
    function setMessage(){
        var message = "Hello";
        
        return message;
    }
    
    $(document).ready(function() {
        init_form();
        init_form_order();
        
        e_ticketnum.on("keyup", function(e) {
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
            var valpurpose = $(this).val();
            
            if(valpurpose === "0"){
                alert( "Please choose purpose!" );
                init_form();
            }else if(valpurpose === "RWH"){
                e_ticketnum.prop("readonly", true);
                e_ticketnum.val("");
                e_delivery1.prop("readonly", true);
                e_delivery1.val("");
                e_notes1.prop("readonly", true);
                e_notes1.val("");
                
                e_engineer_id.prop('disabled', true);
                e_engineer_id.val('default');
                e_engineer_id.selectpicker('refresh');
                
                e_engineer2_id.prop('disabled', true);
                e_engineer2_id.val('default');
                e_engineer2_id.selectpicker('refresh');
                
                e_dest_fsl.prop('disabled', false);
                e_dest_fsl.selectpicker('refresh');
//                load_fsl_dest();
                e_dest_fsl.focus();
                e_notes2.prop("readonly", false);
                e_delivery2.prop("readonly", false);
            }else{
                e_ticketnum.prop("readonly", false);
                e_ticketnum.focus();
                
                e_engineer_id.prop('disabled', false);
                e_engineer_id.val('default');
                e_engineer_id.selectpicker('refresh');
                
                e_notes1.val("");
                e_notes1.prop("readonly", true);
                e_delivery1.val("");
                e_delivery1.prop("readonly", true);
                
                e_dest_fsl.prop('disabled', true);
                e_dest_fsl.val('default');
                e_dest_fsl.selectpicker('refresh');
                
                e_notes2.val("");
                e_notes2.prop("readonly", true);
                e_delivery2.val("");
                e_delivery2.prop("readonly", true);
            }
        });
        
        e_ticketnum.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_ticketnum.val())){
                    alert('Please fill out the ticket number!');
                    e_ticketnum.focus();
                }else{
                    check_ticket($(this).val());
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
                $("#global_confirm .modal-body h4").html("Is the person who ask for sparepart are not the concerned FSE?");
                $('#global_confirm').modal({
                    show: true
                });
                $('#ans_yess').click(function () {
                    e_engineer2_id.prop('disabled', false);
                    e_engineer2_id.selectpicker('refresh');
                    e_engineer2_id.focus();
                    e_notes1.prop("readonly", false);
                    e_delivery1.prop("readonly", false);
                });
                $('#ans_no').click(function () {
                    e_delivery1.prop("readonly", false);
                    e_delivery1.focus();
                    e_notes1.prop("readonly", false);
                });
            }
        });
        
        e_engineer2_id.on('change', function() {
            e_delivery1.prop("readonly", false);
            e_delivery1.focus();
            e_notes1.prop("readonly", false);
//            e_partnum.focus();
        });
        
        e_dest_fsl.on('change', function() {
            var selectedText = $(this).find("option:selected").text();
            e_notes2.val("Transfer stock to "+selectedText);
        });
        
        e_switch.on('change', function(){
            if(this.checked === false){
                init_form_order();
            }else{
                e_partnum.prop('readonly', false);
                e_partnum.val('');
                e_partnum.focus();
                e_serialnum.prop('readonly', false);
                e_serialnum.val('');
            }
        });
        
        e_partnum.on('keypress', function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_partnum.val())){
                    alert('Please fill in this field!');
                    e_partnum.focus();
                }else{
                    if(check_part(e_partnum.val()) === 0){
                        
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
                if(isEmpty(e_partnum.val())){
                    alert('Please fill out spare part number!');
                    e_partnum.focus();
                }else if(isEmpty(e_serialnum.val())){
                    alert('Please fill out serial number!');
                    e_serialnum.focus();
                }else{
//                    alert(setMessage());
                    alert(check_part(e_partnum.val()));
//                    if(check_part(e_partnum.val()) === 1){
//                        add_cart(e_partnum.val(), e_serialnum.val());
//                        reload();
////                        init_form_order();
//                        e_partnum.prop('readonly', false);
//                        e_partnum.val('');
//                        e_partnum.focus();
//                    }else{
//                        alert('Please check your spare part number again');
//                        e_partnum.prop('readonly', false);
//                        e_partnum.focus();
//                    }
                }
                return false;
            }
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
                if(e_purpose.val() === "RWH"){
                    if(isEmpty(e_dest_fsl.val())){
                        alert('Please select your FSL Destination!');
                        e_dest_fsl.focus();
                    }else if(isEmpty(e_delivery2.val())){
                        $("#confirmation .modal-title").html("Message");
                        $("#confirmation .modal-body h4").html("Do you have RW Bill or another Delivery Notes?");
                        $('#confirmation').modal({
                            show: true
                        });
                        $('#opt_yess').click(function () {
                            e_delivery2.prop('readonly', false);
                            e_delivery2.focus();
                        });
                        $('#opt_no').click(function () {
                            e_notes2.prop('readonly', false);
                            e_notes2.focus();
                        });
                    }
                }else{
                    if(isEmpty(e_ticketnum.val())){
                        alert('Please fill out the ticket number!');
                        e_ticketnum.focus();
                    }else if(isEmpty(e_engineer_id.val()) || e_engineer_id.val() === 0){
                        alert('Please select the FSE data!');
                        e_engineer_id.focus();
                    }else if(isEmpty(e_delivery1.val())){
                        $("#global_confirm .modal-title").html("Message");
                        $("#global_confirm .modal-body h4").html("Do you have RW Bill or another Delivery Notes?");
                        $('#global_confirm').modal({
                            show: true
                        });
                        $('#ans_yess').click(function () {
                            e_delivery1.prop('readonly', false);
                            e_delivery1.focus();
                        });
                        $('#ans_no').click(function () {
                            e_notes1.prop('readonly', false);
                            e_notes1.focus();
                        });
                    }else{
                        if(total_qty > 0){
                            $('#confirmation').modal({
                                show: true
                            });
                            $('#opt_yess').click(function () {
                                if(check_ticket(e_ticketnum.val()) === 1){
                                    complete_request();
                                    window.location.href = "<?php echo base_url('new-outgoing-trans'); ?>";
                                }
                            });
                            $('#opt_no').click(function () {
                                if(check_ticket(e_ticketnum.val()) === 1){
                                    complete_request();
                                    window.location.href = "<?php echo base_url('outgoing-trans'); ?>";
                                }
                            });
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
            }
        });
    });
</script>