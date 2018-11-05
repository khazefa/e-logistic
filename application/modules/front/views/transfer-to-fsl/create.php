<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><?php echo $contentTitle;?></h4>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fdest_fsl" class="col-form-label">FSL Destination <span class="text-danger">*</span></label>
                                <select name="fdest_fsl" id="fdest_fsl" class="selectpicker" data-live-search="true" 
                                        data-selected-text-format="values" title="Select FSL Destination.." data-style="btn-light">
                                    <?php
                                        foreach($list_warehouse as $f){
                                            echo '<option value="'.$f["code"].'">'.$f["name"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fdelivery" class="col-form-label">Delivery Notes</label>
                                <input type="text" name="fdelivery" id="fdelivery" class="form-control" placeholder="(Optional)">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fnotes" class="col-form-label">Transaction Notes</label>
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
                <strong class="card-title pull-right">Detail Orders</strong>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
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
                                    <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="Part Number">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                     </div>
                                    <input type="text" name="fserialnum" id="fserialnum" class="form-control" placeholder="Serial Number">
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
                    <button type="button" id="btn_complete" class="btn btn-success waves-effect waves-light">Submit</button>
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

<script type="text/javascript">
    var e_dest_fsl = $('#fdest_fsl');
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
        
    //initial form state
    function init_form(){
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
            delete_cart(fid);
        });
        
        //function for datatables button
        $('#cart_grid tbody').on( 'keypress', 'input', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            fstock = parseInt(data['stock']);
            fqty = parseInt(this.value);
            if (e.keyCode == 13) {
                if(fqty > fstock){
                    alert('The quantity amount exceeds the sparepart stock !');
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
    
    //add part sub number to part number field
    function add_part_sub(partno){
        e_partnum.prop("readonly", false);
        e_partnum.val('');
        e_partnum.val(partno);
        alert('Please press [ENTER] to your subtitution part number');
    }
    
    //add to cart
    function add_cart(partno, serialno){
        var total_qty = table.rows().count();
        
//        if(total_qty >= 3){
//            $("#error_modal .modal-title").html("Message");
//            $("#error_modal .modal-body h4").html("Cannot add request more than 3 items!");
//            $('#error_modal').modal({
//                show: true
//            });
//        }else{
            var url = '<?php echo base_url('cart/outgoing/add/'.$cart_postfix); ?>';
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
//        }
    }
    
    //update cart
    function update_cart(id, qty){        
        var url = '<?php echo base_url('cart/outgoing/update'); ?>';
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
        var url = '<?php echo base_url('cart/outgoing/delete'); ?>';
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
            fqty : parseInt($('#ttl_qty').html()),
            fdest_fsl : e_dest_fsl.val(),
            fdelivery : e_delivery.val(),
            fnotes : e_notes.val()
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
        
        e_serialnum.on("keyup", function(e) {
            var sn = $(this).val();
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
        
        e_dest_fsl.on('change', function() {
            var selectedText = $(this).find("option:selected").text();
            e_notes.val("Transfer stock to "+selectedText);
            if(!isEmpty(e_notes.val())){
                e_delivery.prop("readonly", false);
                e_delivery.focus();
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
        
        e_partnum.on('keypress', function(e){
            if (e.keyCode == 13) {
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
        
        e_serialnum.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_partnum.val())){
                    alert('Please fill out spare part number!');
                    e_partnum.focus();
                }else if(isEmpty(e_serialnum.val())){
                    alert('Please fill out serial number!');
                    e_serialnum.focus();
                }else{
                    check_part(e_partnum.val());
//                    alert(status_checkpart);
                    if(status_checkpart === 1){
                        add_cart(e_partnum.val(), e_serialnum.val());
                        reload();
//                        init_form_order();
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
        
        $("#btn_complete").on("click", function(e){        
            var total_qty = parseInt($('#ttl_qty').html());
            
            if(e_dest_fsl.val() === "0"){
                $("#error_modal .modal-title").html("Message");
                $("#error_modal .modal-body h4").html("Please select your FSL Destination!");
                $('#error_modal').modal({
                    show: true
                });
            }else{
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
        });
    });
</script>