<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><?php echo $contentTitle;?></h4>
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
                                                <input type="text" name="ftrans_out" id="ftrans_out" class="form-control" placeholder="Press [ENTER]" 
                                                       data-toggle="tooltip" data-placement="top" title="" data-original-title="Input Reff No and then Press [ENTER]">
                                            </div>
                                            <div class="input-group col-sm-12">
                                                <span id="ftrans_out_notes" class="help-block text-danger"><small></small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btn_reset" class="btn btn-danger waves-effect waves-light" 
                                        data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Refind Reff No..">
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
                                            <th>#</th>
                                            <th>Part Number</th>
                                            <th>Part Name</th>
                                            <th>Serial Number</th>
                                            <th>Qty</th>
                                            <th>Status</th>
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
                                <!--
                                <div class="mt-2"><hr></div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="checkbox" name="ftrigger" id="ftrigger"> is the amount of spare parts transferred equal to the transfer document?
                                        <label>Notes (<small>Please enter a note if the actual number of parts is different compared to the data request</small>)</label>
                                        <textarea name="fnotes" id="fnotes" class="form-control"></textarea>
                                    </div>
                                </div>
                                -->
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btn_close" class="btn btn-success waves-effect waves-light">
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

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Proceed Incomplete</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" name="dtid" id="dtid"/> 
                    <div class="form-body">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Caused By</label>
                            <div class="col-6">
                                <select id="dcaused" name="dcaused" class="form-control" data-style="btn-light">
                                    <option value="0">Select Caused</option>
                                    <option value="diff_serialnumber">Different Serial Number</option>
                                    <option value="no_physic">No Physic</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Part Number</label>
                            <div class="col-6">
                                <input name="dpartno" placeholder="Part Number" class="form-control" type="text" readonly="true">
                                <input name="dpartno_old" type="hidden" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Serial Number</label>
                            <div class="col-6">
                                <input name="dserialno" placeholder="Serial Number" class="form-control" type="text" readonly="true" pattern="[a-zA-Z0-9 ]+" 
                                       data-toggle="tooltip" data-placement="top" title="" data-original-title="Press [TAB] or [ENTER] after Input this text">
                                <input name="dserialno_old" type="hidden" readonly="true">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Qty</label>
                            <div class="col-4">
                                <input name="dqty" class="form-control" type="number" min="1" readonly="true">
                                <input name="dqty_old" type="hidden" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Notes</label>
                            <div class="col-6">
                                <textarea name="dnotes" class="form-control" placeholder="Notes"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="update_detail_notes()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script type="text/javascript">
    var table;
    var e_trans_out = $('#ftrans_out');
    var e_trans_out_notes = $('#ftrans_out_notes');
    var e_fslname = $('#vfsl');
    var e_fslcode = $('#vfsl_code');
    var e_purpose = $('#vpurpose');
    var e_transdate = $('#vtransdate');
    var e_trigger = $('#ftrigger');
    var e_notes = $('#fnotes');
    var trans_purpose = "";
    var total_qty_outgoing = 0;
    
    function init_form(){
        e_trans_out.val('');
        e_trans_out.prop("readonly", false);
        e_trans_out_notes.html('');
        e_fslcode.html('-');
        e_fslname.html('-');
        e_purpose.html('-');
        e_transdate.html('-');
        e_trigger.prop('checked', false);
        e_notes.prop('disabled', false);
        e_notes.val('');
//        $('#btn_close').prop('disabled', true);
    }
    
    //init table
    function init_table(){
        table = $('#data_grid').DataTable({
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
                url: "<?= base_url($classname_transfer.'/list_detail'); ?>",
                type: 'GET',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.ftrans_out = e_trans_out.val();
                },
            },
            columns: [
                { "data": 'id' },
                { "data": 'partnum' },
                { "data": 'partname' },
                { "data": 'serialnum' },
                { "data": 'qty' },
                { "data": 'notes' },
            ],
            columnDefs : [
                {
                    targets   : 0,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        var html = '#';
                        return html;
                    }
                },
                {
                    targets   : -1,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        var html = '';
                        if(isEmpty(data)){
                            html = '<select name="dstatus" class="form-control">';
                                html += '<option value="1">COMPLETE</option>';
                                html += '<option value="0">INCOMPLETE</option>';
                            html += '</select>'
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
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                $('#ttl_qty').html(totalQty);
            },
//            rowCallback: function( row, data, index ) {
//                if ( data.deleted === "Y" ) {
//                    $('td:eq(0)', row).html( '<span style="text-decoration: line-through;">'+data.partnum+'</span>' );
//                    $('td:eq(1)', row).html( '<span style="text-decoration: line-through;">'+data.partname+'</span>' );
//                    $('td:eq(2)', row).html( '<span style="text-decoration: line-through;">'+data.serialnum+'</span>' );
//                    $('td:eq(3)', row).html( '<span style="text-decoration: line-through;">'+data.qty+'</span>' );
//                }
//            },
            initComplete: function( settings, json ) {
//                $('#ttl_qty').html(table.rows().count());
            }
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-6:eq(0)');
        
        //function for datatables button //not used
        $('#data_grid tbody').on( 'change', 'select', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            ftransno = e_trans_out.val();
            fid = data['id'];
            fpartno = data['partnum'];
            fserialno = data['serialnum'];
            fqty = data['qty'];
            fstatus = this.value;
            
            if(fstatus === "0"){
                edit_detail_status(fid, ftransno, fpartno, fserialno, fqty);
            }
        });
    }
    
    //reload table
    function reload(){
        table.ajax.reload();
    }
    
    function edit_detail_status(fid, ftransno, fpartno, fserialno, fqty)
    {        
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        $('[name="dtid"]').val(fid);
        $('[name="dpartno_old"]').val(fpartno);
        $('[name="dpartno"]').val(fpartno);
        $('[name="dpartno"]').prop('readonly', true);
        $('[name="dserialno_old"]').val(fserialno);
        $('[name="dserialno"]').val(fserialno);
        $('[name="dserialno"]').prop('readonly', true);
        $('[name="dqty"]').val(fqty);
        $('[name="dqty_old"]').val(fqty);
        $('[name="dqty"]').prop('readonly', true);
        $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
        $('.modal-title').text('Proceed Incomplete'); // Set title to Bootstrap modal title
    }
    
    //check outgoing transaction
    function check_transfer_reff(transnum){
        var url = '<?php echo base_url($classname_transfer.'/check-transaction'); ?>';
        var type = 'GET';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : transnum
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
                    e_trans_out_notes.html('<span class="help-block text-danger">'+jqXHR.message+'</span>');
                    e_trans_out.prop("readonly", false);
                    e_trans_out.val('');
                    e_trans_out.focus();
                    e_fslcode.html('-');
                    e_fslname.html('-');
                    e_purpose.html('-');
                    e_transdate.html('-');
                    e_notes.val('');
                    init_table();
                }else if(jqXHR.status === 1){
                    e_trans_out_notes.html('');
                    trans_purpose = jqXHR.purpose;
                    total_qty_outgoing = parseInt(jqXHR.total_qty);
                    if(trans_purpose === "RWH"){
                        e_trans_out.prop('readonly', true);
                        get_transfered_detail(e_trans_out.val());
                        init_table();
                    }else{
                        alert('This feature is only working on Transfer Stock Transaction!');
                        init_form();
                        e_trans_out.focus();
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //get outgoing information
    function get_transfered_detail(transnum){
        var url = '<?php echo base_url($classname_transfer.'/detail'); ?>';
        var type = 'GET';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : transnum
        };
        
        $.ajax({
            type: type,
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            dataType: 'JSON',
            contentType:"application/json",
            data: data,
            success: function (jqXHR) {
                $.each(jqXHR, function(index, element) {
                    $.each(element, function(property, data) {
                        $('#vfsl').html(data.fslname);
                        $('#vfsl_code').html(data.fsl);
                        $('#vpurpose').html(data.purpose);
                        $('#vtransdate').html(data.transdate);
                    });
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //update detail outgoing status
    function update_detail_notes(){
        var url = '<?php echo base_url($classname_transfer.'/modify-detail'); ?>';
        var type = 'POST';
        var fid = $('[name="dtid"]').val();
        var fcaused = $('[name="dcaused"]').val();
        var fpartnum = $('[name="dpartno"]').val();
        var fserialnum = null;
        var fqty = $('[name="dqty"]').val();
        if(fcaused === "diff_serialnumber"){
            fserialnum = $('[name="dserialno"]').val();
        }else if(fcaused === "no_physic"){
            fserialnum = $('[name="dserialno_old"]').val();
            total_qty_outgoing = total_qty_outgoing - parseInt(fqty);
        }
        var fnotes = $('[name="dnotes"]').val();
        
        //belum selesai
        if(isEmpty(e_trans_out.val())){
            alert('Please input Reff No!');
            e_trans_out.focus();
        }
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            fid : fid,
            fpartnum : fpartnum,
            fserialnum : fserialnum,
            fnotes : fnotes
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
                    $("#modal_form .close").click();
                }else if(jqXHR.status === 1){
                    //success
                    $("#modal_form .close").click();
                    reload();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //submit transaction
    function complete_trans(fstatus){
        var url = '<?php echo base_url($classname.'/insert'); ?>';
        var type = 'POST';
        
        if(isEmpty(e_trans_out.val())){
            alert('Please input Reff No!');
            e_trans_out.focus();
        }
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : e_trans_out.val(),
            fstatus : fstatus
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
                    window.location.href = "<?php echo base_url('supply-fsl-to-fsl/view'); ?>";
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
        
        e_trans_out.on("keyup", function(e) {
            $(this).val($(this).val().toUpperCase());
	});
        
        e_trans_out.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_trans_out.val())){
                    alert('Please input Reff No!');
                    e_trans_out.focus();
                }else{
                    check_transfer_reff(e_trans_out.val());
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
                alert('Please input Reff No!');
                e_trigger.prop('checked', false);
            }else{
                if(e_trigger.is(':checked')){
                    e_notes.val('');
                    e_notes.prop('disabled', true);
                    $('#btn_close').prop('disabled', false);
                }else{
                    alert('Please enter a note if the actual number of parts is different compared to the data request.');
                    e_notes.val('');
                    e_notes.prop('disabled', false);
                    $('#btn_close').prop('disabled', true);
                }
            }
	});
        
        $('[name="dcaused"]').on("change", function(e) {
            var val = this.value;
            var sn = $('[name="dserialno"]').val();
            var old_sn = $('[name="dserialno_old"]').val();
            
            if(val === "diff_serialnumber"){
                $('[name="dnotes"]').val('');
                if(sn === "nosn".toUpperCase() || sn === "no sn".toUpperCase()){
                    //skip
                }else{
                    alert("Please change Serial Number.");
                    $('[name="dserialno"]').prop('readonly', false);
                    $('[name="dserialno"]').val('');
                    $('[name="dserialno"]').focus();
                    $('[name="dserialno"]').prop('required', true);
                }
            }else if(val === "no_physic"){
                $('[name="dnotes"]').val('');
                $('[name="dserialno"]').prop('readonly', true);
                $('[name="dserialno"]').val(old_sn);
                $('[name="dnotes"]').val('NO PHYSIC');
            }else{
                $('[name="dnotes"]').val('');
            }
	});
        
        $('[name="dserialno"]').on("keydown", function(e) {
            var val = this.value;
            var oldsn = $('[name="dserialno_old"]').val();
            
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(isEmpty(val)){
                    alert("Please input new serial number!");
                }else{
                    $('[name="dnotes"]').val('Old Serial Number = '+oldsn);
                }
            }
        });
        
        e_notes.on("focusout", function(e) {
            $('#btn_close').prop('disabled', false);
	});
        
        $("#btn_close").on("click", function(e){
            var total_qty = parseInt($('#ttl_qty').html());
            
            if(total_qty === 0){
                alert('Please process the transaction!');
            }else{
                if(total_qty_outgoing < total_qty){
                    $("#global_confirm .modal-title").html("Confirmation");
                    $("#global_confirm .modal-body h4").html("Your transaction would be <strong>PENDING</strong>, is that okay?");
                    $('#global_confirm').modal({
                        show: true
                    });
                    $('#ans_yess').click(function () {
                        //continue close transaction
                        complete_trans('pending');
                    });
                    $('#ans_no').click(function () {
                        //hold close transaction
                    });
                }else{
                    complete_trans('complete');
                }
            }
            /**
            if(e_trigger.is(':checked')) {
                complete_trans('complete');
            }else{
                if(!isEmpty(e_notes.val())){
                    $("#global_confirm .modal-title").html("Confirmation");
                    $("#global_confirm .modal-body h4").html("It seems that you write some notes, do you want to continue this transaction?");
                    $('#global_confirm').modal({
                        show: true
                    });
                    $('#ans_yess').click(function () {
                        //continue close transaction
                        complete_trans('complete');
                    });
                    $('#ans_no').click(function () {
                        //hold close transaction
                        e_notes.focus();
                    });
                }
            }
            **/
        });
    });
</script>