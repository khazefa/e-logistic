<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle; ?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="fpurpose" class="col-sm-2 col-form-label">Purpose</label>
                            <div class="col-sm-6">
                                <select name="fpurpose" id="fpurpose" class="form-control" placeholder="Select Purpose">
                                    <option value="0">Select Purpose</option>
                                    <option value="RBP">Return Bad Part</option>
                                    <option value="RBS">Return Bad Stock</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Delivery Sparepart to FSL
                            </div>
                            <div class="card-body">
                                <p>Please use this form section <strong>for purpose Supply Stock</strong></p>
                                <div class="form-group row">
                                    <label for="fairwaybill" class="col-sm-3 col-form-label">No Airwaybill</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="fairwaybill" id="fairwaybill" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ftransnotes" class="col-sm-3 col-form-label">Transaction Notes</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="ftransnotes" id="ftransnotes" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fservice" class="col-sm-3 col-form-label">Service</label>
                                    <div class="col-sm-8">
                                        <select name="fservice" id="fservice" class="selectpicker" data-live-search="true" 
                                                data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                            <option value="0">Select Service</option>
                                            <option value="REG">Regular</option>
                                            <option value="EXPRESS">Overnight / Express</option>
                                            <option value="INTCOURIER">Internal Courier</option>
                                            <option value="SAMEDAY">Sameday Service</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fdeliveryby" class="col-sm-3 col-form-label">Delivery By</label>
                                    <div class="col-sm-8">
                                        <select name="fdeliveryby" id="fdeliveryby" class="selectpicker" data-live-search="true" 
                                                data-selected-text-format="values" title="Please choose.." data-style="btn-light">
                                            <option value="0">Select Service</option>
                                            <option value="DHL">DHL</option>
                                            <option value="POS">POS</option>
                                            <option value="JNE">JNE</option>
                                            <option value="YCH">YCH</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="feta" class="col-sm-3 col-form-label">ETA</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="feta" id="feta" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="card-box">
            <div class="card-header bg-primary text-white">
                <input type="checkbox" name="fswitch" id="fswitch" data-plugin="switchery" data-color="#f1b53d"/>
                <strong class="card-title pull-right">Detail Orders</strong>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group form-group-sm col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <select id="fpartname" name="fpartname" class="selectpicker" data-live-search="true" 
                                    data-selected-text-format="values" title="Search Part Name.." data-style="btn-light">
                                    <option value="0">Select Part Name</option>
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
</div>
</form>
<script type="text/javascript">
////////////////////////////////////////////////////////////////////////////////

    //deklarasi form fsl
    var e_purpose = $('#fpurpose');
    var e_transnote = $('#ftransnotes');
    var e_service = $('#fservice');
    var e_airwaybill = $('#fairwaybill');
    var e_eta = $('#feta');
    var e_deliveryby = $('#fdeliveryby');
    
    //deklarasi form part
    var e_switch = $('#fswitch');
    var e_partname = $('#fpartname');
    var e_partnum = $('#fpartnum');
    var e_partnum_notes = $('#fpartnum_notes');
    var e_serialnum = $('#fserialnum');
    
    //variable
    var dataSet = [];
    var status_checkpart = 0;
    var status_checkticket = 0;
    var link = function(link){return '<?=base_url('front/cfsltocwh/');?>'+link;};
    
    $(document).ready(function() {
        
        //inisiasi awal
        init_form();
        init_form_order();
        init_table();
        init_table2();
        get_total();
        
        e_purpose.on("change", function(e) {
            var valpurpose = $(this).val();
            
            if(valpurpose === "0"){
                alert( "Please choose purpose!" );
                init_form();
            
            }else{
                e_airwaybill.prop('disabled', false);
                e_airwaybill.val('');
                e_transnote.val("Supply stock to Warehouse Pusat");
                e_airwaybill.prop("readonly", false);
                e_service.prop("disabled", false);
                e_service.selectpicker('refresh');
                e_deliveryby.prop("disabled", false);
                e_deliveryby.selectpicker('refresh');
            }
        });
        
        
        e_airwaybill.on("change", function(){
            var v = $(this).length;
            
            if(v === 0){
                //init_form();
                e_airwaybill.focus();
            }else{
               e_service.prop('disabled', false);
            }
        });//belum bisa
        
        e_service.on('change', function() {
            var selectedText = $(this).find("option:selected").text();
            var v = $(this).val();
            
            if(v === "0"){
                alert( "Please choose Service!" );
                e_service.focus();
                //init_form();
            }else{
                get_eta_time();
            }
        });
        
        e_deliveryby.on('change', function() {
            var selectedText = $(this).find("option:selected").text();
            var v = $(this).val();
            
            if(v === "0"){
                alert( "Please choose delivery By!" );
                //init_form();
                e_deliveryby.focus();
            }else{
                get_eta_time();
            }
        });
        
        e_partname.on('change', function() {
            var selectedText = $(this).find("option:selected").val();
            e_partnum.prop('readonly', false);
            e_partnum.val(selectedText);
            e_partnum.focus();
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
        
        e_switch.on('change', function(){
            if(this.checked === false){
                init_form_order();
            }else{
                e_partname.attr('disabled', false);
                e_partname.selectpicker('refresh');
                e_partnum.prop('readonly', false);
                e_partnum.val('');
                e_partnum.focus();
            }
        });
        
        $("#btn_complete").on("click", function(e){        
            $(this).prop('disabled', true);
            var total_qty = parseInt($('#ttl_qty').html());
            
            if(e_purpose.val() === "0"){
                $("#error_modal .modal-title").html("Message");
                $("#error_modal .modal-body h4").html("Please select your purpose!");
                $('#error_modal').modal({
                    show: true
                });
            }else{
               
                 
                if(total_qty > 0){
                    complete_request();
//                           
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
    
    
////////////////////////////////////////////////////////////////////////////////
//                              AJAX Function
////////////////////////////////////////////////////////////////////////////////
    function xhqr(url, type, data, successT, errorT){
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: successT,
            error: errorT
        });
    }
    
    function error_xhqr(jqXHR, textStatus, errorThrown){
        // Handle errors here
        console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );          
    }

////////////////////////////////////////////////////////////////////////////////


    //  INIT FUNCTION 
    //==========================================================================
    //initial form state
    function init_form(){
        e_purpose.focus();
        e_airwaybill.prop("readonly", true);
        e_transnote.prop("readonly", true);
        e_service.prop("disabled", true);
        e_service.selectpicker('refresh');
        e_deliveryby.prop("disabled", true);
        e_deliveryby.selectpicker('refresh');
        e_eta.prop("readonly", true);
    }
    
    //initial form order state
    function init_form_order(){
        e_partname.attr('disabled', true);
        e_partname.selectpicker('refresh');
        e_partnum.val("");
        e_partnum.prop("readonly", true);
        e_partnum_notes.html("");
        e_serialnum.val("");
        e_serialnum.prop("readonly", true);
    }
    
    
    //  TABLE FUNCTION
    //==========================================================================
    //TABLE Parts
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
                url: link('get_list_cart_datatable'),
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
    
    //TABLE Subtitute
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
                e_serialnum.prop('readonly', false);
                e_serialnum.val('');
                e_serialnum.focus();
            }
        });

        table2.buttons().container()
                .appendTo('#subtitute_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload2(){
        table2.ajax.reload();
    }
    
    //  FORM FUNCTION 
    //==========================================================================
    //load fsl destination
    function load_fsl_dest(){
        let e_dest_fsl = $('#fdest_fsl');
        e_dest_fsl.empty();

        e_dest_fsl.append('<option selected="true" disabled>Please choose..</option>');
        e_dest_fsl.prop('selectedIndex', 0);

        const url = link('get_list_warehouse');

        // Populate dropdown with list of provinces
        $.getJSON(url, function (data) {
          $.each(data, function (key, entry) {
            e_dest_fsl.append($('<option></option>').attr('value', entry.code).text(entry.name));
          })
        });
    }
    
    //get ETA time
    function get_eta_time(){
        var url = link("get_eta");
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fdelivery_type : e_service.val(),
            fdelivery_by : e_deliveryby.val()
        };
        var type = 'POST';
        
        var success = function(jqXHR){
            if(jqXHR.status === 0){
                console.log("ERROR : GET_ETA "+ jqXHR.message);
            }else if(jqXHR.status === 1){
                e_eta.val(jqXHR.ETA);
            }
        };
        
        xhqr(url, type, data, success, error_xhqr);
    }

    //submit proccess
    function complete_request(){
        var url = link('submit_trans');
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
           
            fairwaybill : e_airwaybill.val(),
            ftransnote : e_transnote.val(),
            fservice : e_service.val(),
            feta : e_eta.val(),
            //fdest_fsl : e_dest_fsl.val(),
            fpurpose : e_purpose.val(),
            fdeliveryby: e_deliveryby.val(),
            fqty : parseInt($('#ttl_qty').html()),
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
                    $("#error_modal .modal-title").html("Message");
                    $("#error_modal .modal-body h4").html(""+jqXHR.message);
                    $('#error_modal').modal({
                        show: true
                    });
                }else if(jqXHR.status === 1){
                    print_transaction(jqXHR.message);
                    //console.log(jqXHR.message);
                    window.location.href = "<?php echo base_url('fsltocwh-trans'); ?>";
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }


    //  CART FUNCTION
    //==========================================================================
    //check part stock
    function check_part(partno){        
        var url = link('check_part');
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
                    status_checkpart = 0;
                }else if(jqXHR.status === 1){
                    e_partnum_notes.html('<span class="help-block text-success">'+jqXHR.message+'</span>');
                    e_partnum.prop("readonly", true);
                    status_checkpart = 1;
                }else if(jqXHR.status === 2){
                    e_partnum_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    status_checkpart = 2;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
        
    }
    
    
    //add to cart
    function add_cart(partno, serialno){
        var total_qty = table.rows().count();
        
        if(e_purpose.val() === "RWH"){
            if(total_qty >= 15){
                
            }
            var url = link('add_cart'); 
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
            
                var url = link('add_cart'); 
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
    
    //get total part int temp
    function get_total() {
        var url = link('get_total_cart'); 
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
    
    //update cart
    function update_cart(id, qty){        
        var url = link('update_cart');
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
    
    //delete cart
    function delete_cart(id){        
        var url = link('delete_cart');
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
    
    //  FUNCTION PART SUBTITUSI
    //==========================================================================
    //add part sub to folder
    function add_part_sub(partno){
        e_partnum.prop("readonly", false);
        e_partnum.val('');
        e_partnum.val(partno);
//        e_partnum.prop("readonly", true);
        alert('Please press [ENTER] to your subtitution part number');
        
//        check_part(partno);
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
    
    //  PRINT FUNCTION
    //==========================================================================
    //print to pdf
    function print_transaction(ftransno){
        var param = ftransno;
        var url = '<?php echo base_url('print-fsltocwh-trans/'); ?>'+param;
        var newWindow=window.open(url);
//        window.location.assign(url);
    }
    
</script>