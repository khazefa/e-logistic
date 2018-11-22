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
                                    <?php
                                        foreach($field_purpose as $key => $val){
                                            echo '<option value="'.$key.'">'.$val.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                Delivery Part From Repair to CWH
                            </div>
                            <div class="card-body">
                                
                                <?php $input = 'ftransnotes';?>
                                <div class="form-group row">
                                    <label for="<?=$input;?>" class="col-sm-3 col-form-label">Notes</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="<?=$input;?>" id="<?=$input;?>" class="form-control"><span class="text-danger" id="msg_<?=$input;?>"></span>
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
                    <div class="col-sm-6">
                        <span class="text-danger" id="msg_fpartnum"></span>
                    </div>
                    
                    <div class="m-b-10">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col col-md-12">

                        <table id="cart_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <?php foreach($field_column as $field_name){ // get field name from controller
                                    echo '<th>'.$field_name.'</th>';
                                }?>
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
    var e_total_qty = $('#ttl_qty')
    
    //deklarasi form part
    var e_switch = $('#fswitch');
    var e_partnum_notes = $('#msg_fpartnum');
    var e_partname = $('#fpartname');
    var e_partnum = $('#fpartnum');
    var e_serialnum = $('#fserialnum');
    
    //variable
    var dataSet = [];
    var status_checkpart = 0;
    var status_checkticket = 0;
    
    var btn_submit = $('#btn_complete');
    

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
        console.error('ERRORS: ' + textStatus + ' - ' + errorThrown );          
    }



//                              Modal confirm Function
////////////////////////////////////////////////////////////////////////////////
    var modalConfirm = function(callback){
        $("#ans_yess").on("click", function(){
            callback(true);
            $("#global_confirm").modal('hide');
        });
        
        $("#ans_no").on("click", function(){
            callback(false);
            $("#global_confirm").modal('hide');
        });
    };

    function set_confirm(message){
        $("#global_confirm .modal-title").html("Confirmation");
        $("#global_confirm .modal-body h4").html(""+message);
        $('#global_confirm').modal("show");
        
    }

////////////////////////////////////////////////////////////////////////////////


    //  INIT FUNCTION 
    //==========================================================================
    //initial form state
    function init_form(){
        btn_submit.prop('disabled', true);
        e_purpose.focus();
        e_transnote.prop("readonly", true);
    }
    
    //initial form order state
    function init_form_order(){
        e_partname.attr('disabled', true);
        e_partname.selectpicker('refresh');
        e_partnum.val("");
        e_partnum.prop("readonly", true);
        e_serialnum.val("");
        e_serialnum.prop("readonly", true);
    }
    
    
    //  TABLE FUNCTION
    //==========================================================================
    function init_table(){
        table = $('#cart_grid').DataTable({
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
                url: "<?=$link_datatable_cart;?>",
                type: "POST",
                dataType: "JSON",
                contentType: "application/json",
                data: function(d) {
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                },
            },
            columns: [
                <?php foreach($field_column as $field_id=>$field_name){
                    echo '{ "data" : "'.$field_id.'"},';
                };?>
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
                    targets   : <?=count($field_column)-1;?>,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        if(full.serialno === "NOSN"){
                            return '<input type="number" id="fqty" min="1" max="'+full.qty+'" value="'+full.qty+'" style="width: 100%;">';
                        }else{
                            return data;
                        }
                    }
                }
            ],
            initComplete: function( settings, json ) {
                //$('#ttl_qty').html(table.rows().count());//menjumlahkan jumlah halaman
                get_total();
            }
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
                update_cart(fid, fqty);
                return false;
            }
        });

        table.buttons().container().appendTo('#cart_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload(){
        table.ajax.reload();
        get_total();
    }
    
    //submit proccess
    function complete_submit(){
        var url = "<?=$link_submit;?>";
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftransnote : e_transnote.val(),
            fpurpose : e_purpose.val(),
            fqty : parseInt($('#ttl_qty').html()),
        };

        var success = function (jqXHR) {
            if(jqXHR.status === 0){
                $("#error_modal .modal-title").html("Message");
                $("#error_modal .modal-body h4").html(""+jqXHR.message);
                $('#error_modal').modal({
                    show: true
                });
            }else if(jqXHR.status === 1){
                //print_transaction(jqXHR.message);
                //console.log(jqXHR.message);
                window.location.href = "<?php echo base_url('fsltocwh-trans'); ?>";
            }
        }
        xhqr(url, type, data, success, error_xhqr);
    }

    //  CART FUNCTION
    //==========================================================================
    function check_part(partno,serialnum){        
        var url = "<?=$link_check_part;?>";
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : partno,
            fserialnum : serialnum
        };
        
        var success = function (jqXHR) {
            if(!jqXHR.status){
                e_partnum_notes.html('<span class="help-block text-warning">'+jqXHR.message+'</span>');
                //alert(jqXHR.message);
                e_partnum.prop('readonly', false);
                e_partnum.focus();
                e_serialnum.prop('readonly', true);
                e_serialnum.val('');
            }else{
                //add_cart(partno,serialnum);
                reload();
                e_partnum.prop('readonly', false);
                e_partnum.val('');
                e_partnum.focus();
                e_partnum_notes.html('');
                e_serialnum.prop('readonly', true);
                e_serialnum.val('');
                e_partnum_notes.html('<span class="help-block text-success">'+jqXHR.message+'</span>');
            }
            get_total();
        };

        xhqr(url, type, data, success, error_xhqr);
        
    }
    
    // function add_cart(partno, serialno){            
    //     var url = "<?=$link_cart_add;?>"; 
    //     var type = 'POST';

    //     var data = {
    //         <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
    //         fpartnum : partno,
    //         fserialnum : serialno
    //     };

    //     var success = function (jqXHR) {
    //         if(jqXHR.status){
    //             reload();
    //             e_partnum.prop('readonly', false);
    //             e_partnum.val('');
    //             e_partnum.focus();
    //             e_partnum_notes.html('');
    //             e_serialnum.prop('readonly', true);
    //             e_serialnum.val('');
    //             alert(jqXHR.message);
    //         }else{
    //             alert(jqXHR.message);
    //             e_partnum.prop('readonly', false);
    //             e_partnum.focus();
    //             reload();
    //             get_total();
    //         }
            
    //     };
    //     xhqr(url, type, data, success, error_xhqr);   
    // }
    
    function get_total(){
        var json = table.ajax.json();
        var is = 0;
        if(json !== undefined && json.data.length > 0){
            json.data.forEach(function(v,k){
                is += parseInt(v.qty);
            });
        }
        e_total_qty.html(is);
    }
    
    //update cart
    function update_cart(id, qty){        
        var url = "<?=$link_cart_update;?>";
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : id,
            fqty : qty
        };

        var success = function (jqXHR) {
            if(jqXHR.status === 1){
                reload();
                get_total();
            }else if(jqXHR.status === 0){
                alert(jqXHR.message);
            }
        }
        xhqr(url, type, data, success, error_xhqr);   
    }
    
    //delete cart
    function delete_cart(id){        
        var url = "<?=$link_cart_delete;?>";
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : id
        };

        var success = function (jqXHR) {
            if(jqXHR.status){
                reload();
                get_total();
            }else{
                alert(jqXHR.message);
            }
        };
        xhqr(url, type, data, success, error_xhqr); 
        
    }
    
    
    //  PRINT FUNCTION
    //==========================================================================
    function print_transaction(ftransno){
        var param = ftransno;
        var url = '<?php echo base_url('print-fsltocwh-trans/'); ?>'+param;
        var newWindow=window.open(url);
    }
    
    function falert(fobj,val){
        var msg = $('#msg_'+fobj.attr('id'));
        if(!val){
            msg.html('* Required');
        }else{
            msg.html('');
        }
        return val;
    }
    
    function validation(){
        var check = {e_purpose}; //form object who wants to validate check
        var ks = true;
        Object.keys(check).forEach(function(k){
            if(check[k].val() === ''){
                falert(check[k],false);
                ks=false;
            }else{
                falert(check[k],true);
            }
        });

        if(ks){
            btn_submit.prop('disabled',false);
        }else{
            btn_submit.prop('disabled',true);
        }
        return ks;
    }

    $(document).ready(function() {
        init_form();
        init_form_order();
        init_table();
        
        e_purpose.on('focusout', function(evt){
            validation();
        });

        e_purpose.on("change", function(e) {
            var valpurpose = $(this).val();
            
            if(valpurpose === "0"){
                alert( "Please choose purpose!" );
                init_form();
            
            }else{
                e_transnote.val("Supply stock Repair Center to Warehouse Pusat");
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
                        //check_part(e_partnum.val());
                            //fill serial number
                            e_partnum.prop('readonly', true);
                            e_serialnum.prop('readonly', false);
                            e_serialnum.val('');
                            e_serialnum.focus();
                    
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
                    check_part(e_partnum.val(),e_serialnum.val());
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
        
        btn_submit.on("click", function(e){        
            //$(this).prop('disabled', true);
            var total_qty = parseInt($('#ttl_qty').html());
            
            if(e_purpose.val() === "0"){
                $("#error_modal .modal-title").html("Message");
                $("#error_modal .modal-body h4").html("Please select your purpose!");
                $('#error_modal').modal({
                    show: true
                });
            }else{
               
                if(total_qty > 0){
                    complete_submit();
                         
                }else{
                    $("#error_modal .modal-title").html("Message");
                    $("#error_modal .modal-body h4").html("You dont have any detail of transaction");
                    $('#error_modal').modal({
                        show: true
                    });
                    e_partnum.prop('readonly', false);
                    e_partnum.focus();
                    get_total();
                }
                
                
            }
        });
    });
    
</script>