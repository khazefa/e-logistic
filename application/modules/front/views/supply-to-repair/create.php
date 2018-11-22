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

                                <?php $input = 'ftransnum';?>
                                <div class="form-group row">
                                    <label for="<?=$input;?>" class="col-sm-3 col-form-label">Transaction Number<span class="text-danger">*</span></label>
                                    <div class="input-group col-sm-9">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                        </div>
                                        <input type="text" name="<?=$input;?>" id="<?=$input;?>" class="form-control" required>
                                    </div>
                                    <div class="col-sm-3"></div><div class="col-sm-3"><span class="text-danger" id="msg_<?=$input;?>"></span></div>
                                </div>
                                
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
                                        <input type="text" name="<?=$input;?>" id="<?=$input;?>" class="form-control" required>
                                    </div>
                                    <div class="col-sm-3"></div><div class="col-sm-3"><span class="text-danger" id="msg_<?=$input;?>"></span></div>
                                </div>

                                <?php $input = 'fserialnum';?>
                                <div class="form-group row">
                                    <label for="<?=$input;?>" class="col-sm-3 col-form-label">Serial Number <span class="text-danger">*</span></label>
                                    <div class="input-group col-sm-9">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                        </div>
                                        <input type="text" name="<?=$input;?>" id="<?=$input;?>" class="form-control" required>
                                    </div>
                                    <div class="col-sm-3"></div><div class="col-sm-3"><span class="text-danger" id="msg_<?=$input;?>"></span></div>
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
                                    <th>Part Number</th>
                                    <th>Part Name</th>
                                    <th>Qty</th>
                                    <th>Status</th>
                                    <th>Action</th>
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

<!-- Modal Request Confirmation -->
<div class="modal fade" id="modal_diff" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="max-width:750px;">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title" id="myModalLabel">Different PN & SN</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="detail_id" value=""/>
                        <input type="hidden" name="action" value =""/>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-3"><label class="col-form-label">OLD</label></div>
                            <div class="col-md-3"><label class="col-form-label">NEW</label></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label class="col-form-label">CHANGE PN :</label></div>
                            <div class="col-md-3"><label class="col-form-label" id="old_pn"></label></div>
                            <div class="col-md-3"><input type="text" name="new_pn" id="new_pn"></div>
                            <div class="col-md-3">
                                <select id="search_part" name="search_part" class="selectpicker" data-live-search="true" data-selected-text-format="values" title="Search Part Name.." data-style="btn-light">
                                    <option value="0">Select Part Name</option>
                                    <?php
                                        foreach($list_part as $p){
                                            echo '<option value="'.$p["partno"].'">'.$p['partno'].' - '.$p["name"].'</option>';
                                        }
                                    ?>
                                </select>   
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label class="col-form-label">CHANGE SN :</label></div>
                            <div class="col-md-3"><label class="col-form-label" id="old_sn"></label></div>
                            <div class="col-md-3"><input type="text" name="new_sn" id="new_sn"></div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="submit_diff">SUBMIT</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Request Confirmation -->


<script type="text/javascript">

var e_transnum = $('#ftransnum');
var e_partname = $('#fpartname');
var e_serialnum = $('#fserialnum');
var e_partnum = $('#fpartnum');
var e_qty = $('#fqty');
var e_total_qty = $('#ttl_qty');
var btn_submit = $('#btn_submit');
var table;
var check_parts_validate = false;

var e_detail_id = $('input[name=detail_id]');
var e_search_part = $('select[name=search_part]');
var e_action = $('input[name=action]');
var e_old_pn = $('#old_pn');
var e_old_sn = $('#old_sn');
var e_new_pn = $('#new_pn');
var e_new_sn = $('#new_sn');
var e_submit_diff = $('#submit_diff');
var e_submit_trans = $('#submit_trans');


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

//                              Init Form
////////////////////////////////////////////////////////////////////////////////
function init_table(){
    table = $('#cart_grid').DataTable({
        searching: false, ordering: false, info: false, paging: false,
        destroy: true, stateSave: false, deferRender: true, processing: true, lengthChange: false,
        ajax: {
            url: '<?= $link_datatable; ?>',
            type: "POST",
            dataType: "JSON",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            contentType: "application/json",
            data: function(d){
                d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>",
                d.ftransnum = e_transnum.val()
            },
        },
        columns: [
            //{ "data": 'id' },
            { "data": 'partnum' },
            { "data": 'partname' },
            { "data": 'qty' },
            { "data": 'status' },
            { "data": 'btn_diff' },
        ],
        
    });
    
    //function for datatables button
    $('#cart_grid tbody').on( 'click', 'button', function (e) {        
        var data = table.row( $(this).parents('tr') ).data();
        fid = data['id'];
    });

    $('#cart_grid').on('draw.dt',function(){
        init_select_picker();
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

function init_form(){
    e_transnum.val('');
    e_partnum.val('');
    e_serialnum.val('');    
    e_total_qty.html('0');
    btn_submit.prop('disabled',true);
    reload();
    get_total();
}

function init_new_part(){
    e_partnum.val('');
    e_serialnum.val('');
}

function init_select_picker(){
    $('.selectpicker').selectpicker(); //initialize select picker
    $("select.selectpicker:not([name=search_part])").on('change',function(){ // get selectpicker which name isn't search_part
        init_form_modal();
        var selected = $(this).val();
        var element_id = $(this).attr('id');
        var ix = element_id.substring(10,element_id.length);
        e_detail_id.val(ix);
        e_action.val(selected);
        switch(selected){
            case 'diff_partnumber':
                $("#modal_diff").modal('show');
                e_search_part.prop('disabled', false);
                e_search_part.selectpicker('refresh');
                e_new_pn.prop('disabled', false);
                e_new_sn.prop('disabled', false);
                $("#old_pn").html(data_cart[ix].partnum);
                $("#old_sn").html(data_cart[ix].serialnum);
                break;
            case 'diff_serialnumber':
                $("#modal_diff").modal('show');
                e_search_part.prop('disabled', true);
                e_search_part.selectpicker('refresh');
                e_new_pn.prop('disabled', true);
                e_new_sn.prop('disabled', false);
                $("#old_pn").html(data_cart[ix].partnum);
                $("#old_sn").html(data_cart[ix].serialnum);
                break;
            case 'no_physic' :
                set_confirm("Are you sure, does it exist physically?");
                modalConfirm(function(conf){
                    //console.log(conf);
                    if(conf){
                        //init_form();
                        submit_part_notexist();
                    }
                });
                break;
        }
    });
    
}

//                              Checking Data baru sampai di sini
////////////////////////////////////////////////////////////////////////////////

function check_parts(){
    var url = '<?=$link_check_parts;?>';
    var type = 'POST';
    var data = {
        <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
        fpartnum : parseInt($('#ttl_qty').html()),
        fserialnum : e_delivery_notes.val(),
    };
    var success = function (jqXHR) {
        if(!jqXHR.status){
            $("#error_modal .modal-title").html("Message");
            $("#error_modal .modal-body h4").html(""+jqXHR.message);
            $('#error_modal').modal({
                show: true
            });
            btn_submit.prop('disabled',false);
        }
    };
    xhqr(url,type,data,success,error_xhqr);
}

function check_transaction(){
    
}

function get_total(){
    var json = table.ajax.json();
    var is = 0;
    if(json !== undefined && json.length > 0){
        json.data.forEach(function(v,k){
            is += v.qty;
        });
    }
    e_total_qty.html(is);
}


//                              Transaction function
////////////////////////////////////////////////////////////////////////////////

//reload table
function reload(){
    table.ajax.reload();
    get_total();
}

//submit supply transaction
function complete_supply(){
    var url = '<?=$link_submit;?>';
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
                    window.location = "<?=$link_trans?>";
                }
            });

        }
    };
    xhqr(url,type,data,success,error_xhqr);
}

function verify_supply(){
    var url = '<?=$link_verify?>';
    var type = 'POST';
    var data = {
        <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
        ftransnum : e_transnum.val();
        fserialnum : e_serialnum.val();
        fpartnum : e_partnum.val();
    };
    var success = function (jqXHR) {
        if(jqXHR.status){
            reload();
            init_new_part();
        }else{
            $("#error_modal .modal-title").html("Message");
            $("#error_modal .modal-body h4").html(""+jqXHR.message);
            $('#error_modal').modal({
                show: true
            });
        }
    };
    xhqr(url,type,data,success,error_xhqr);
}

//add part to cart
function add_cart(){
    var url = '';
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
    var check = {e_transnum}; //form object who wants to validate check
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



//                               Windows On Load
////////////////////////////////////////////////////////////////////////////////
$(document).ready(function(ex){
    init_table();
    init_form();
    
    $('input[type=text]').on('focusout',function(e){
        validation();
    });

    $('input[type=text], input[type=number]').on('keypress',function(e){
        var e_id = $(this).attr('id');

        if(e_id === 'ftransnum'){
            if(e.which === 13){
                reload();
            }
        }

        if(e_id === 'fpartnum'){
            if(e.which === 13) {
                validation();
            }
        }

        if(e_id === 'fserialnum'){
            if(e.which === 13) {
                check_parts();
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