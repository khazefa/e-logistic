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
                    <div class="col-md-4 m-b-30">
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
                                                <input type="text" name="ftrans_out" id="ftrans_out" class="form-control" value="<?php echo $transnum; ?>" placeholder="Press [ENTER]" 
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
                    <div class="col-md-8 m-b-30">
                        <div class="card bg-light">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Detail Information</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="column col-md-12">
                                        <div class="text-left">
                                            <p class="font-13"><strong>From FSL :</strong> <span class="m-l-10" id="vfsl">-</span></p>
                                            <p class="font-13"><strong>FSL Code :</strong> <span class="m-l-10" id="vfsl_code">-</span></p>
                                            <p class="font-13"><strong>Purpose :</strong> <span class="m-l-10" id="vpurpose">-</span></p>
                                            <p class="font-13"><strong>Transfer Date. :</strong> <span class="m-l-10" id="vtransdate">-</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-white">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Detail Transaction</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="column col-md-6">
                                        <strong class="text-info">Detail Transfer</strong>
                                        <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Part Number</th>
                                                <th>Serial Number</th>
                                                <th>Qty</th>
                                                <th>Notes</th>
                                                <th>Proceed</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <p class="text-danger">
                                            <strong>Jika ada problem</strong> pada part yang harus dikembalikan, 
                                            maka <strong>Pilih Status Incomplete</strong>.
                                        </p>
                                    </div>
                                    <div class="column col-md-6">
                                        <strong class="text-info">Detail Receive</strong>
                                        <table id="cart_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>Part Number</th>
                                                <th>Serial Number</th>
                                                <th>Qty</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <button type="button" id="btn_clear" class="btn btn-cancel waves-effect waves-light" 
                                                data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Repeat the transaction..">
                                            <i class="fa fa-trash-o"></i> Clear Return
                                        </button>
                                        <div class="mb-4"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1 offset-md-11">
                                        Total: <span id="ttl_qty">0</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3 offset-md-9 text-right">
                                        <button type="button" id="btn_verify" class="btn btn-warning waves-effect waves-light" 
                                                data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Verify Return Quantity..">
                                            Verify
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btn_close" class="btn btn-success waves-effect waves-light" 
                                    data-toggle="tooltip" data-placement="bottom" title="Please do Verify before Submit !" data-original-title="Please do Verify before Submit !">
                                    Complete
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
                <h3 class="modal-title">Proceed Received Parts</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Receive Status</label>
                            <div class="col-6">
                                <select id="dstatus" name="dstatus" class="form-control" data-style="btn-light">
                                    <option value="0">Select Status</option>
                                    <?php
                                        foreach ($status_option as $so => $o){
                                            echo '<option value="'.$so.'">'.$o.'</option>';
                                        }
                                    ?>
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
                                <input name="dserialno" placeholder="Serial Number" class="form-control" type="text" readonly="true" pattern="[a-zA-Z0-9 ]+">
                                <input name="dserialno_old" type="hidden" readonly="true">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Qty</label>
                            <div class="col-4">
                                <input name="dqty" class="form-control" type="number" min="1" readonly="true" 
                                data-toggle="tooltip" data-placement="top" title="" data-original-title="Input Qty and then Press [ENTER]">
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
                <button type="button" id="btnSave" onclick="add_cart()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script type="text/javascript">    
    var e_trans_out = $('#ftrans_out');
    var e_trans_out_notes = $('#ftrans_out_notes');
    var e_fslname = $('#vfsl');
    var e_fslcode = $('#vfsl_code');
    var e_purpose = $('#vpurpose');
    var e_transdate = $('#vtransdate');
    var e_trigger = $('#ftrigger');
    var e_notes = $('#fnotes');
    var trans_purpose = "";
    var total_qty_outgoing = 0, detail_ret_qty = 0, detail_ret_cart = 0;
    var table; 
    var table2;
    var arrStatus = [];
    
    function init_form(){
        var has_transnum = "<?php echo $transnum; ?>";
        if(isEmpty(has_transnum)){
            e_trans_out.val('');
            e_trans_out.prop('readonly', false);
        }else{
            e_trans_out.val(has_transnum);
            e_trans_out.prop('readonly', true);
            check_transfer_reff(has_transnum);
        }
        e_trans_out_notes.html('');
        e_fslcode.html('-');
        e_fslname.html('-');
        e_purpose.html('-');
        e_transdate.html('-');
        e_trigger.prop('checked', false);
        e_notes.prop('disabled', true);
        e_notes.val('');
        $('#btn_close').prop('disabled', true);
    }
    
    //init table
    function init_table(){
        ftransout = e_trans_out.val();

        table = $('#data_grid').DataTable({
            dom: "<'row'<'col-sm-12'><'col-sm-8'><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'><'col-sm-3'>>",
            searching: true,
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
                { "data": 'partnum' },
                { "data": 'serialnum' },
                { "data": 'qty' },
                { "data": 'notes' },
                { "data": 'return' },
            ],
            columnDefs : [
                {
                    targets   : 0,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        var html = '<a href="#" title="'+full.partname+'">'+data+'</a>';
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
                            html = '<a href="javascript:void(0)" title="Change Status" id="btn_edit"><i class="fa fa-pencil"></i> Change Status</a>';
                        }else{
                            if(data === "diff_serialnumber"){
                                html = '<a href="javascript:void(0)" title="Change Status" id="btn_edit"><i class="fa fa-pencil"></i> Different SN</a>';
                            }else{
                                html = '<a href="javascript:void(0)" title="Change Status" id="btn_edit"><i class="fa fa-pencil"></i> '+data.toUpperCase()+'</a>';
                            }
                        }
                        return html;
                    }
                }
            ],
            initComplete: function( settings, json ) {
                if(table2.rows().count() < 1){
                    update_detail_status_all(ftransout, "complete");
                }
            },
            rowCallback: function( row, data, index ) {
                arrStatus.push(data.return);
                fpartnum = data.partnum;
                fserialnum = data.serialnum;
                fqty = data.qty;
                fstatus = data.return;
                fnotes = data.notes;
                
                if(table2.rows().count() < 1){
                    add_cart_direct(ftransout, fpartnum, fserialnum, fqty, fstatus, fnotes);
                }
            },
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-6:eq(0)');
        
        //function for datatables button
        $('#data_grid tbody').on( 'click', '#btn_edit', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fpartno = data['partnum'];
            fserialno = data['serialnum'];
            fqty = data['qty'];
            fnotes = data['notes'];
            fstatus = data['return'];
            ftransno = e_trans_out.val();
            edit_detail_status(ftransno, fpartno, fserialno, fqty, fnotes, fstatus);
        });
    }
    
    //reload table
    function reload(){
        table.ajax.reload();
    }
    
    //init table
    function init_table2(){
        table2 = $('#cart_grid').DataTable({
            dom: "<'row'<'col-sm-12'><'col-sm-8'><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'><'col-sm-3'>>",
            searching: true,
            ordering: false,
            info: false,
            paging: false,
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            lengthChange: false,
            ajax: {
                url: "<?= base_url('cart/incoming/list/'.$cart_postfix); ?>",
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
                { "data": 'partno' },
                { "data": 'serialno' },
                { "data": 'qty' },
                { "data": 'status' },
            ],
            columnDefs : [
                {
                    targets   : 0,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        var html = '<a href="javascript:void(0)" id="btn_delete" title="'+data+'"><i class="fa fa-times"></i></a>'; 
//                        var html = '<button type="button" class="btn btn-danger" id="btn_delete"><i class="fa fa-times"></i></button>'; 
                        if(full.status === "RBP"){
                            return '&nbsp;';
                        }else{
                            return html;
                        }
                    }
                },
                {
                    targets   : -1,
                    orderable : false, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        var html = '';
                        if(data === "incomplete"){
                            html = 'Incomplete';
                        }else if(data === "complete"){
                            html = 'Complete';
                        }else if(data === "diff_serialnumber"){
                            html = 'Different Serial Number';
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
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                $('#ttl_qty').html(totalQty);
            },
            initComplete: function( settings, json ) {
//                $('#ttl_qty').html(table.rows().count());
            }
        });
        
        //function for datatables button
        $('#cart_grid tbody').on( 'click', '#btn_delete', function (e) {        
            var data = table2.row( $(this).parents('tr') ).data();
            ftransout = e_trans_out.val();
            fid = data['id'];
            fpartnum = data['partno'];
            fserialnum = data['serialno'];
            fdstatus = data['status'];
            fstatus = '';
            if(fdstatus === 'RBP'){
                //
            }else{
                delete_cart(fid);
                update_detail_status(ftransout, fpartnum, fserialnum, fstatus);
            }
        });

        table2.buttons().container()
                .appendTo('#cart_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload_cart(){
        table2.ajax.reload();
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
                    init_table2();
                }else if(jqXHR.status === 1){
                    e_trans_out_notes.html('');
                    trans_purpose = jqXHR.purpose;
                    total_qty_outgoing = parseInt(jqXHR.total_qty);
                    if(trans_purpose === "RWH"){
                        e_trans_out.prop('readonly', true);
                        get_transfered_detail(e_trans_out.val());
                        init_table();
                        init_table2();
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
    
    function edit_detail_status(ftransno, fpartno, fserialno, fqty, fnotes, fstatus)
    {        
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        if(!isEmpty(fstatus)){
            $('[name="dstatus"]').val(fstatus);
        }else{
            $('[name="dstatus"]').val('complete');
        }
//        $('[name="dnotes"]').prop('disabled', true);
        $('[name="dpartno_old"]').val(fpartno);
        $('[name="dpartno"]').val(fpartno);
        $('[name="dpartno"]').prop('readonly', true);
        $('[name="dserialno_old"]').val(fserialno);
        $('[name="dserialno"]').val(fserialno);
        $('[name="dserialno"]').prop('readonly', true);
        $('[name="dqty"]').val(fqty);
        $('[name="dqty_old"]').val(fqty);
        $('[name="dqty"]').prop('readonly', true);
        $('[name="dnotes"]').val(fnotes);
        $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
        $('.modal-title').text('Set Status Part'); // Set title to Bootstrap modal title
    }
    
    //add to cart
    function add_cart(){
        var url = '<?php echo base_url('cart/incoming/add/'.$cart_postfix); ?>';
        var type = 'POST';
        var ftransout = e_trans_out.val();
        var fpartnum = $('[name="dpartno"]').val();
        var fserialnum = $('[name="dserialno"]').val();
        var fserialnum_old = $('[name="dserialno_old"]').val();
        var fqty = $('[name="dqty"]').val();
        var fstatus = $('[name="dstatus"]').val();
        var fnotes = $('[name="dnotes"]').val();

        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftransout : ftransout,
            fpartnum : fpartnum,
            fserialnum : fserialnum,
            fserialnum_old : fserialnum_old,
            fqty : fqty,
            fstatus : fstatus,
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
                    update_detail_status(ftransout, fpartnum, fserialnum, fstatus, fnotes);
                    $("#modal_form .close").click();
                    reload();
                    reload_cart();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }

    //add to cart
    function add_cart_direct(ftransout, fpartnum, fserialnum, fqty, fstatus, fnotes){
        var url = '<?php echo base_url('cart/incoming/add/'.$cart_postfix); ?>';
        var type = 'POST';

        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftransout : ftransout,
            fpartnum : fpartnum,
            fserialnum : fserialnum,
            fqty : fqty,
            fstatus : fstatus,
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
                }else if(jqXHR.status === 1){
                    // update_detail_status(ftransout, fpartnum, fserialnum, fstatus, fnotes);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //update detail outgoing status
    function update_detail_status(ftrans_out, fpartnum, fserialnum, fstatus, fnotes){
        var url = '<?php echo base_url($classname_transfer.'/modify-detail'); ?>';
        var type = 'POST';
        
        if(isEmpty(e_trans_out.val())){
            alert('Please input Outgoing Number!');
            e_trans_out.focus();
        }
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : ftrans_out,
            fpartnum : fpartnum,
            fserialnum : fserialnum,
            fstatus : fstatus,
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
                    $("#error_modal .modal-title").html("Message");
                    $("#error_modal .modal-body h4").html(""+jqXHR.message);
                    $('#error_modal').modal({
                        show: true
                    });
                }else if(jqXHR.status === 1){
                    //success
                    arrStatus = [];
                    reload();
                    reload_cart();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
            }
        });
    }
    
    //update detail outgoing status
    function update_detail_status_all(ftrans_out, fstatus){
        var url = '<?php echo base_url($classname_transfer.'/bulk-modify-detail'); ?>';
        var type = 'POST';
        
        if(isEmpty(e_trans_out.val())){
            alert('Please input Outgoing Number!');
            e_trans_out.focus();
        }
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : ftrans_out,
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
                    alert(jqXHR.message);
                }else if(jqXHR.status === 1){
                    //success
                    arrStatus = [];
                    reload();
                    reload_cart();
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
        var url = '<?php echo base_url('cart/incoming/delete'); ?>';
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
                    //success
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
    
    //delete all cart
    function delete_all_cart(ftrans_out){
        var url = '<?php echo base_url('cart/incoming/bulk-delete/'.$cart_postfix); ?>';
        var type = 'POST';
        
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            ftrans_out : ftrans_out
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
                    //success
                    update_detail_status_all(e_trans_out.val(), "");
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
    function update_cart(id, qty){
        var url = '<?php echo base_url('cart/incoming/update'); ?>';
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
                    reload_cart();
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

    function check_pending_state()
    {
        var state = false;
        detail_ret_qty = parseInt(table.data().count());
        detail_ret_cart = parseInt(table2.data().count());

        if(detail_ret_cart !== 0){
            if(detail_ret_cart < detail_ret_qty){
                state = true;
            }else{
                if(inArray("incomplete", arrStatus)){
                    // alert("There are incomplete data");
                    state = true;
                }
            }
        }
        return state;
    }
    
    //submit transaction
    function complete_trans(status){
        var url = '<?php echo base_url($classname.'/insert'); ?>';
        var type = 'POST';
        
        if(isEmpty(e_trans_out.val())){
            alert('Please input Outgoing Number!');
            e_trans_out.focus();
        }
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : e_trans_out.val(),
            fqty : parseInt($("#ttl_qty").html()),
            fcode_from : e_fslcode.html(),
            fnotes : e_notes.val(),
            fstatus : status
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
//                    print_transaction(jqXHR.message);
                    window.location.href = "<?php echo base_url($classname.'/view'); ?>";
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
            init_table2();
        });
        
        e_notes.on("focusout", function(e) {
            $('#btn_close').prop('disabled', false);
	    });
        
        $('[name="dstatus"]').on("change", function(e) {
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
            }else if(val === "incomplete"){
                alert("Please describe the notes for Incomplete part!");
                $('[name="dnotes"]').val('');
                $('[name="dnotes"]').focus();
                $('[name="dserialno"]').prop('readonly', true);
                $('[name="dserialno"]').val(old_sn);
            }else{
                $('[name="dnotes"]').val('');
                $('[name="dserialno"]').prop('readonly', true);
                $('[name="dserialno"]').val(old_sn);
            }
	    });
        
        $('[name="dqty"]').on("keydown", function(e) {
            var val = parseInt(this.value);
            var oldqty = parseInt($('[name="dqty_old"]').val());
            var status = $('[name="dstatus"]').val();
            
            if (e.keyCode == 9 || e.keyCode == 13) {
                if(val <= oldqty){
                    var calc = oldqty - val;
                    if(status === "RGP"){
                        $('[name="dnotes"]').val('CONSUMED = '+calc);
                    }
                }else{
                    alert('the amount you enter exceeds the amount you have');
                    $('[name="dqty"]').val('1');
                    $('[name="dqty"]').focus();
                    $('[name="dnotes"]').val('');
                }
            }
        });
        
        $('#btn_clear').on("click", function(e){
            var ttl_qty = $('#ttl_qty').html();
            if(ttl_qty === "0"){
                alert('You dont have any parts in return cart!');
            }else{
                //clear carts
                delete_all_cart(e_trans_out.val());
                arrStatus = [];
            }
        });
        
        $('#btn_verify').on("click", function(e){
            var ttl_qty = parseInt($('#ttl_qty').html());
            if(ttl_qty === 0){
                alert('You have not return any parts!');
            }else{
                if(check_pending_state()){
                    $('#btn_close').text('Pending');
                    $('#btn_close').prop('disabled', false);
                }else{
                    if(ttl_qty > total_qty_outgoing){
                        alert('Your total returns is greater than your request. Please re-check your returns quantity!');
                        $('#btn_close').text('Complete');
                        $('#btn_close').prop('disabled', true);
                    }else{
                        e_notes.val('');
                        e_notes.prop('disabled', false);
                        e_notes.focus();
                        $('#btn_close').text('Complete');
                        $('#btn_close').prop('disabled', false);
                    }
                }
            }
        });

        $("#btn_close").on("click", function(e){
            var state = $( this ).text().toLowerCase().trim();
            // alert("State:"+state);
            complete_trans(state);
        });
    });
</script>