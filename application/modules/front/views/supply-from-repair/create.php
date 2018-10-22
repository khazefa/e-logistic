<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="card-box">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Input Data</strong>
                            </div>
                            <div class="card-body">

                                <?php $input = 'fpartname';?>
                                <div class="form-group row">
                                    <label for="<?=$input;?>" class="col-sm-3 col-form-label">Search Parts </label>
                                    <div class="col-sm-9">
                                        <select id="<?=$input;?>" name="<?=$input;?>" class="selectpicker" data-live-search="true" 
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

                                <?php $input = 'fpartnum';?>
                                <div class="form-group row">
                                    <label for="<?=$input;?>" class="col-sm-3 col-form-label">Part Number <span class="text-danger">*</span></label>
                                    <div class="input-group col-sm-9">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                        </div>
                                        <input type="text" name="<?=$input;?>" id="<?=$input;?>" class="form-control" required><div class="input-group-append"></div><span class="input-group-text text-danger" id="msg_<?=$input;?>"></span>
                                    </div>
                                </div>
                                
                                <?php $input = 'fqty';?>
                                <div class="form-group row">
                                    <label for="<?=$input;?>" class="col-sm-3 col-form-label">Qty <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="number" name="<?=$input;?>" id="<?=$input;?>" class="form-control" min="0" value='1' required><span class="text-danger" id="msg_<?=$input;?>"></span>
                                    </div>
                                </div>

                                <?php $input = 'fdeliverynotes';?>
                                <div class="form-group row">
                                    <label for="<?=$input;?>" class="col-sm-3 col-form-label">Delivery Notes <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="<?=$input;?>" id="<?=$input;?>" class="form-control" required><span class="text-danger" id="msg_<?=$input;?>"></span>
                                    </div>
                                </div>

                                <?php $input = 'freceivedby';?>
                                <div class="form-group row">
                                    <label for="<?=$input;?>" class="col-sm-3 col-form-label">Received By <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="<?=$input;?>" id="<?=$input;?>" class="form-control" required><span class="text-danger" id="msg_<?=$input;?>"></span>
                                    </div>
                                </div>

                                

                                <!-- <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" id="btn_add" class="btn btn-warning waves-effect waves-light">
                                            <i class="fa fa-plus"></i> Add Part
                                        </button>
                                    </div>
                                </div> -->
                                
                                
                            </div>
                            
                        </div>
                    </div>
                <!-- </div>
                <div class="row">   -->
                    <div class="col-md-7">
                        <div class="card-box table-responsive">
                            <h4 class="m-b-30 header-title">Detail Transaction</h4>
                            <table id="cart_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
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
                                    Total Quantity: <span id="ttl_qty">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" id="btn_submit" class="btn btn-primary waves-effect waves-light">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- End Content Panel Supply -->

<script type="text/javascript">

/*
* variable for supply transaction
*/
var e_partname = $('#fpartname');
var e_partnum = $('#fpartnum');
var e_qty = $('#fqty');
var e_received_by = $('#freceivedby');
var e_delivery_notes = $('#fdeliverynotes');
var e_total_qty = $('#ttl_qty');
var btn_submit = $('#btn_submit');
var table;
var check_parts_validate = false;

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

//INIT FORM
function init_form(){
    e_partnum.val('');
    e_qty.val('1');
    e_delivery_notes.val('');
    e_received_by.val('');
    e_total_qty.html('0');
    btn_submit.prop('disabled',true);
    get_total();
    reload();
}

//Create data table
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
            url: '<?php echo base_url('api-'.$alias_controller_name.'-get-cart-table'); ?>',
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
    get_total();
}

//submit supply transaction
function complete_supply(){
    var url = '<?php echo base_url('api-'.$alias_controller_name.'-submit-trans'); ?>';
    var type = 'POST';
    var data = {
        <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
        fqty : parseInt($('#ttl_qty').html()),
        fnotes : e_delivery_notes.val(),
        freceivedby : e_received_by.val(),
    };
    var success = function (jqXHR) {
        if(jqXHR.status == 0){
            $("#error_modal .modal-title").html("Message");
            $("#error_modal .modal-body h4").html(""+jqXHR.message);
            $('#error_modal').modal({
                show: true
            });
            btn_submit.prop('disabled',false);
        }else if(jqXHR.status == 1){
            set_confirm("Transaction Success, is there any other transaction?")
            modalConfirm(function(conf){
                console.log(conf);
                if(conf){
                    init_form();
                }else{
                    window.location = "<?php echo base_url(''.$alias_controller_name.'-trans')?>";
                }
            });

        }
    };
    xhqr(url,type,data,success,error_xhqr);
}

//add part to cart
function add_cart(){
    var url = '<?php echo base_url('api-'.$alias_controller_name.'-add-cart'); ?>';
    var type = 'POST';
    var data = {
        <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
        fqty : e_qty.val(),
        fpartnum : e_partnum.val()
    };
    var success = function (jqXHR) {
        if(jqXHR.status == 0){
            $("#error_modal .modal-title").html("Message");
            $("#error_modal .modal-body h4").html(""+jqXHR.message);
            $('#error_modal').modal({
                show: true
            });
        }else if(jqXHR.status == 1){
            reload();
        }
    };
    if(check_parts_validate){
        xhqr(url,type,data,success,error_xhqr);
        return true;
    }else{
        check_parts();
        return false;
    }
    
}

//delete cart
function delete_cart(id){
    var url = '<?php echo base_url('api-'.$alias_controller_name.'-delete-cart'); ?>';
    var type = 'POST';
    var data = {
        <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
        fid : id
    };
    var success = function (jqXHR) {
        if(jqXHR.status == 1){
            reload();
        }else if(jqXHR.status == 0){
            alert(jqXHR.message);
        }
    };
    xhqr(url,type,data,success,error_xhqr);
}

//function get total of table cart
function get_total(){
    var url = '<?php echo base_url('api-'.$alias_controller_name.'-get-total-cart'); ?>';
    var type = 'POST';
    var data = {
        <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
    };
    var success = function (jqXHR) {
        if(jqXHR.status === 1){
            $('#ttl_qty').html(jqXHR.ttl_cart);
        }else{
            $('#ttl_qty').html("0");
        }
    };
    xhqr(url,type,data,success,error_xhqr);
}

//check part number
function check_parts(){
    var url = '<?php echo base_url('api-'.$alias_controller_name.'-check-partnum'); ?>';
    var type = 'POST';
    var data = {
        <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
        fpartnum : e_partnum.val()
    };
    var success = function (jqXHR) {
        if(jqXHR.status == 0){
            check_parts_validate = false;
            falerts(e_partnum,check_parts_validate,jqXHR.message);
            $(this).focus();
        }else if(jqXHR.status == 1){
            check_parts_validate = true;
            falerts(e_partnum,check_parts_validate,jqXHR.message);
            e_qty.focus();
        }
    };
    xhqr(url,type,data,success,error_xhqr);
    
}

//validation alert
function falert(fobj,val){
    var msg = $('#msg_'+fobj.attr('id'));
    if(!val){
        msg.html('* Required');
    }else{
        msg.html('');
    }
    return val;
}

//alert with dynamic message
function falerts(fobj,val,msg){
    var msgs = $('#msg_'+fobj.attr('id'));
    if(val){
        msgs.html('');
    }else{
        msgs.html(msg);
    }
}

//function validation form
function validation(){
    var ret = false;
    // ret1 = (e_partnum.val()=='')?falert(e_partnum,false):falert(e_partnum,true);
    ret2 = (e_received_by.val()=='')?falert(e_received_by,false):falert(e_received_by,true);
    ret3 = (e_delivery_notes.val()=='')?falert(e_delivery_notes,false):falert(e_delivery_notes,true);
    if(ret3 && ret2){
        ret = true;
        btn_submit.prop('disabled',false);
    }else{
        ret = false;
        btn_submit.prop('disabled',true);
    }
    return ret;
}

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


////////////////////////////////////////////////////////////
// when document ready to execute 
////////////////////////////////////////////////////////////
$(document).ready(function(ex){
    init_table();
    init_form();
    

    $('input[type=text]').on('focusout',function(e){
        validation();
        if($(this).attr('id') == 'fpartnum'){
            check_parts();
        }
    });
    $('input[type=text], input[type=number]').on('keypress',function(e){
        
        if($(this).attr('id') == 'fpartnum'){
            if(e.which == 13) {
                check_parts();
                validation();
            }
        }

        if($(this).attr('id') == 'fqty'){
            if(e.which == 13) {
                if($(this).val() > 0){
                    if(add_cart()){
                        set_confirm("is there any other parts?");
                        modalConfirm(function(conf){
                            if(conf){
                                e_qty.val('1');
                                e_partnum.val('');
                                e_partnum.focus();
                            }else{
                                e_delivery_notes.focus();
                            }
                        });
                    }
                }else{
                    falerts($(this),false,'* Min Value is 1.');
                }
                validation();
                
            }
        }   
        
        if($(this).attr('id') == 'freceivedby'){
            if(e.which == 13) {
                validation();
            }
        }

        if($(this).attr('id') == 'fdeliverynotes'){
            if(e.which == 13) {
                e_received_by.focus();
                validation();
            }
        }

    });

    e_partname.on('change', function() {
        var selectedText = $(this).find("option:selected").val();
        e_partnum.prop('readonly', false);
        e_partnum.val(selectedText);
        e_partnum.focus();
    });

    btn_submit.on('click',function(e){
        $(this).prop('disabled',true);
        complete_supply();
    });
    
});



</script>