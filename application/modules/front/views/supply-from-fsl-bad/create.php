<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title"><?php echo $contentHeader;?></strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group form-group-sm col-sm-12">
                                        <div class="row">
                                            <div class="input-group col-sm-12">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                                 </div>
                                                <input type="text" name="ftrans_out" id="ftrans_out" class="form-control" placeholder="Press [ENTER] after input Outgoing No.">
                                            </div>
                                            <div class="input-group col-sm-12">
                                                <span id="ftrans_out_notes" class="help-block text-danger"><small></small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btn_reset" class="btn btn-danger waves-effect waves-light">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-white">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Detail Transaction</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="column col-md-6">
                                        <div class="text-left">
                                            <p class="font-13"><strong>From FSL :</strong> <span class="m-l-10" id="vfsl">-</span></p>
                                            <p class="font-13"><strong>FSL Code :</strong> <span class="m-l-10" id="vfsl_code">-</span></p>
                                            <p class="font-13"><strong>Purpose :</strong> <span class="m-l-10" id="vpurpose">-</span></p>
                                            <p class="font-13"><strong>Transfer Date. :</strong> <span class="m-l-10" id="vtransdate">-</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Part Number</th>
                                            <th>Part Name</th>
                                            <th>Serial Number</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-9">
                                        Total Quantity: <span id="ttl_qty">0</span>
                                    </div>
                                </div>
                                <div class="mt-2"><hr></div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="checkbox checkbox-primary"><input type="checkbox" name="ftrigger" id="ftrigger"><label for="ftrigger"> I agree, the number of incoming parts is appropriate.</label></div>
                                        <label>Notes (<small>Please enter a note if the actual number of parts is different compared to the data request</small>)</label>
                                        <textarea name="fnotes" id="fnotes" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="submit_trans" class="btn btn-success waves-effect waves-light">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

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
    var e_trans_out = $('#ftrans_out');
    var e_trans_out_notes = $('#ftrans_out_notes');
    var e_fslname = $('#vfsl');
    var e_fslcode = $('#vfsl_code');
    var e_purpose = $('#vpurpose');
    var e_transdate = $('#vtransdate');
    var e_trigger = $('#ftrigger');
    var e_notes = $('#fnotes');

    var e_detail_id = $('input[name=detail_id]');
    var e_search_part = $('select[name=search_part]');
    var e_action = $('input[name=action]');
    var e_old_pn = $('#old_pn');
    var e_old_sn = $('#old_sn');
    var e_new_pn = $('#new_pn');
    var e_new_sn = $('#new_sn');
    var e_submit_diff = $('#submit_diff');
    var e_submit_trans = $('#submit_trans');

    var trans_purpose = "";
    var total_qty_outgoing = 0;
    var data_cart;
    var table;
    


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

//                              Message Confirm
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


    function init_form(){
        e_trans_out.val('');
        e_trans_out.prop("readonly", false);
        e_trans_out_notes.html('');
        e_fslcode.html('-');
        e_fslname.html('-');
        e_purpose.html('-');
        e_transdate.html('-');
        e_trigger.prop('checked', false);
        e_notes.prop('disabled', true);
        e_notes.val('');
        e_submit_trans.prop('disabled', true);
    }
    
    function init_form_modal(){
        e_detail_id.val('');
        e_new_pn.val('');
        e_old_pn.html('');
        e_new_sn.val('');
        e_old_sn.html('');
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
                    e_new_sn.prop('disabled', true);
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
                case 'diff_pn_and_sn' :
                    $("#modal_diff").modal('show');
                    e_search_part.prop('disabled', false);
                    e_search_part.selectpicker('refresh');
                    e_new_pn.prop('disabled', false);
                    e_new_sn.prop('disabled', false);
                    $("#old_pn").html(data_cart[ix].partnum);
                    $("#old_sn").html(data_cart[ix].serialnum);
                    break;
                case 'doesnt_exist' :
                    set_confirm("Are you sure, the parts is not exist?");
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

    //init table
    function init_table(){
        table = $('#data_grid').DataTable({
            searching:false, ordering:false, info:false, paging:false, destroy:true, stateSave:false, deferRender:true, processing:true,
            lengthChange:false,
            ajax: {
                url: "<?= base_url('front/csupplyfromfslbad/get_trans_detail'); ?>",
                type: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.ftransnum = e_trans_out.val();
                },
            },
            columns: [
                { "data": 'partnum' },
                { "data": 'partname' },
                { "data": 'serialnum' },
                { "data": 'qty' },
                { "data": 'select_dt_notes' },
            ],
            
            initComplete: function( settings, json ) {
                $('#ttl_qty').html(table.rows().count());//menjumlahkan jumlah halaman
                
                var jd = {};
                for(var i = 0;i<json.data.length;i++){
                    jd[json.data[i].transid] = json.data[i];
                }
                data_cart = jd;
            }
        });
        
        $('#data_grid').on('draw.dt',function(){
            init_select_picker();
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-6:eq(0)');
                
    }
    
    //reload table
    function reload(){
        table.ajax.reload();
    }
    
    //check outgoing transaction
    function check_trans_out(transnum){
        var url = '<?php echo base_url('front/cfsltocwh/check_trans'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftransnum : transnum
        };
        var success = function (jqXHR) {
            if(jqXHR.status === false){
                init_form();
                e_trans_out_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                init_table();
            }else if(jqXHR.status === true){
                e_trans_out_notes.html('');
                trans_purpose = jqXHR.purpose;
                total_qty_outgoing = jqXHR.total_qty;
                e_notes.prop('disabled', false);
                e_trans_out.prop('readonly', true);
                get_outgoing_info(e_trans_out.val());
                init_table();
            }
        };
        xhqr(url, type, data, success, error_xhqr); //ajax calling
    }

    //check partnumber
    function check_part_number(partnumber,callback){
        var url = '<?php echo base_url('front/cfsltocwh/check_part'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fpartnum : e_new_pn.val()
        };
        var success = function (jqXHR){
            if(jqXHR.status === 1){
                callback(true);
            }else{
                callback(false);
            }
        }
        xhqr(url, type, data, success, error_xhqr); //ajax calling
    }
    
    //get outgoing information
    function get_outgoing_info(transnum){
        var url = '<?php echo base_url('front/cfsltocwh/get_trans'); ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftransnum : transnum
        };
        var success = function (jqXHR) {
            $('#vfsl').html(jqXHR.fsl_name);
            $('#vfsl_code').html(jqXHR.fsl_code);
            $('#vpurpose').html(jqXHR.fsltocwh_purpose);
            $('#vtransdate').html(jqXHR.date);
        };
        xhqr(url, type, data, success, error_xhqr); //ajax calling
    }
    
    //submit transaction
    function complete_trans(){
        if(isEmpty(e_trans_out.val())){
            alert('Please input Outgoing Number!');
            e_trans_out.focus();
        }else{
            var url = '<?php echo base_url('front/csupplyfromfslbad/close_trans'); ?>';
            var type = 'POST';
            var data = {
                <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
                ftransnum : e_trans_out.val(),
                fqty : $("ttl_qty").html(),
                fcode_from : e_fslcode.html(),
                fnotes : e_notes.val()
            };
            var success = function (jqXHR) {
                if(jqXHR.status !== false){
                    set_confirm("Transaction Success, Are you want input other transaction?");
                    modalConfirm(function(conf){
                        if(conf){
                            init_form();
                            init_table();
                        }else{
                            window.location = "<?php echo base_url('supply-from-fsl-bad'); ?>";
                        }
                    });
                    
                }else{
                    $("#error_modal .modal-title").html("Message");
                    $("#error_modal .modal-body h4").html(""+jqXHR.message);
                    $('#error_modal').modal({
                        show: true
                    });
                }
            };
            xhqr(url, type, data, success, error_xhqr); //ajax calling
        }
    }

    //validation diff value
    function validate_diff(){
        var selected = e_action.val();var isValidate = false;
        var ic = false;
        var ic_message = '';
        var pn = e_new_pn.val();
        var sn = e_new_sn.val();
        

        if(selected === 'diff_partnumber'){
            if(pn!=='' && typeof pn === 'string'){
                check_part_number(pn,function(e){
                    if(!e){
                        alert('Part number not found / failed to check part.');
                        return false;
                    }else{submit_diff();}
                });
            }else{
                alert('Part Number should be more than 1 character.');
                return false;
            }
        }
        else if(selected === 'diff_serialnumber'){
            if(sn!=='' && typeof sn === 'string'){
                submit_diff();
            }else{
                alert('Serial Number should be more than 1 character.');
                return false;
            }
        }
        else if(selected === 'diff_pn_and_sn'){
            if((sn!=='' && typeof sn === 'string') && (pn!=='' && typeof pn === 'string')){
                return check_part_number(pn,function(e){
                    if(!e){
                        alert('Part number not found / failed to check part.');
                        return false;
                    }else{submit_diff();}
                });
            }else{
                alert('Serial Number / Part Number should be more than 1 character.');
                return false;
            }
        }
        
    }

    function submit_diff(){
        var url = '<?=base_url('front/csupplyfromfslbad/update_diff');?>';
        var type = "POST";
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : e_detail_id.val(),
            fnewpn : e_new_pn.val(),
            fnewsn : e_new_sn.val(),
            faction: e_action.val()
        };
        var success = function(jqXHR){
            if(jqXHR.status){
                reload();
                alert(jqXHR.message);
                $('#modal_diff').modal('hide');
            }else{
                alert(jqXHR.message);
            }
        }
        xhqr(url, type, data, success, error_xhqr) //ajax calling
    
    }

    function submit_part_notexist(){
        var url = '<?=base_url('front/csupplyfromfslbad/update_notexist');?>';
        var type = "POST";
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : e_detail_id.val(),
            faction: e_action.val()
        }
        var success = function(jqXHR){
            if(jqXHR.status){
                //reload();
                console.log(jqXHR.message);
                //$('#modal_diff').modal('hide');
            }else{
                alert(jqXHR.message);
            }
        }
        xhqr(url, type, data, success, error_xhqr)
    }
    
    $(document).ready(function() {
        init_form();
        
        e_trans_out.on("keyup", function(e) {
            $(this).val($(this).val().toUpperCase());
	    });
        
        e_trans_out.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_trans_out.val())){
                    alert('Please input Outgoing Number!');
                    e_trans_out.focus();
                }else{
                    check_trans_out(e_trans_out.val());
                }
                return false;
            }
        });
        
        $("#btn_reset").on("click", function(e){
            init_form();
            init_table();
        });
        
        e_trigger.on("click", function(e) {
            if(isEmpty(e_trans_out.val())){
                alert('Please input Outgoing Number!');
                e_trigger.prop('checked', false);
            }else if(data_cart === undefined && data_cart === null){
                alert('Outgoing Number don\'t have a data!');
            }else{
                if(e_trigger.is(':checked')){
                    e_notes.val('');
                    e_notes.prop('disabled', true);
                    e_submit_trans.prop('disabled', false);
                }else{
                    alert('Please enter a note if the actual number of parts is different compared to the data request.');
                    e_notes.val('');
                    e_notes.prop('disabled', false);
                    e_submit_trans.prop('disabled', true);
                }
            }
	    });
        
        e_notes.on("focusout", function(e) {
            if(e_notes.val() === '' && !e_trigger.is(':checked')){
                e_submit_trans.prop('disabled', true);
            }else{
                e_submit_trans.prop('disabled', false);
            }
	    });

        e_notes.on("keyup", function(e) {
            if(e_notes.val() === '' && !e_trigger.is(':checked')){
                e_submit_trans.prop('disabled', true);
            }else{
                e_submit_trans.prop('disabled', false);
            }
	    });
        
        e_search_part.on("change",function(e){
            var key = $(this).find("option:selected").val();
            e_new_pn.val(key);
        });

        e_submit_diff.on('click',function(e){
            validate_diff();
        });

        $('.modal').on('hidden.bs.modal', function () {
            reload();
        });

        e_submit_trans.on("click", function(e){
            if(e_trigger.is(':checked')) {
                complete_trans();
            }else{
                if(!isEmpty(e_notes.val())){
                    set_confirm("It seems that you write some notes, do you want to continue this transaction?");
                    modalConfirm(function(conf){
                        if(conf){
                            complete_trans();
                        }else{
                            e_notes.focus();
                        }
                    });
                }
            }
        });
    });
</script>