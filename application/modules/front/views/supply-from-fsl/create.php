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
                                        <input type="checkbox" name="ftrigger" id="ftrigger"> is the amount of spare parts transferred equal to the transfer document?
                                        <label>Notes (<small>Please enter a note if the actual number of parts is different compared to the data request</small>)</label>
                                        <textarea name="fnotes" id="fnotes" class="form-control"></textarea>
                                    </div>
                                </div>
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
        $('#btn_close').prop('disabled', true);
    }
    
    //init table
    function init_table(){
        var table = $('#data_grid').DataTable({
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
                url: "<?= base_url('front/coutgoing/get_view_outgoing_detail'); ?>",
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
                { "data": 'partname' },
                { "data": 'serialnum' },
                { "data": 'qty' },
            ],
//            rowCallback: function( row, data, index ) {
//                if ( data.deleted === "Y" ) {
//                    $('td:eq(0)', row).html( '<span style="text-decoration: line-through;">'+data.partnum+'</span>' );
//                    $('td:eq(1)', row).html( '<span style="text-decoration: line-through;">'+data.partname+'</span>' );
//                    $('td:eq(2)', row).html( '<span style="text-decoration: line-through;">'+data.serialnum+'</span>' );
//                    $('td:eq(3)', row).html( '<span style="text-decoration: line-through;">'+data.qty+'</span>' );
//                }
//            },
            initComplete: function( settings, json ) {
                $('#ttl_qty').html(table.rows().count());
            }
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
        var url = '<?php echo base_url('front/csupplyfromfsl/check_outgoing_trf'); ?>';
        var type = 'POST';
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
                    total_qty_outgoing = jqXHR.total_qty;
                    if(trans_purpose === "RWH"){
                        e_trans_out.prop('readonly', true);
                        get_outgoing_info(e_trans_out.val());
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
    function get_outgoing_info(transnum){
        var url = '<?php echo base_url('front/coutgoing/get_view_outgoing'); ?>';
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
    
    //submit transaction
    function complete_trans(){
        var url = '<?php echo base_url('front/csupplyfromfsl/submit_trans_close_transfer'); ?>';
        var type = 'POST';
        
        if(isEmpty(e_trans_out.val())){
            alert('Please input Outgoing Number!');
            e_trans_out.focus();
        }
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",  
            ftrans_out : e_trans_out.val(),
            fqty : $("ttl_qty").html(),
            fcode_from : e_fslcode.html(),
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
                    $("#error_modal .modal-title").html("Message");
                    $("#error_modal .modal-body h4").html(""+jqXHR.message);
                    $('#error_modal').modal({
                        show: true
                    });
                }else if(jqXHR.status === 1){
//                    print_transaction(jqXHR.message);
//                    alert('Close Transfer Stock Success!');
                    window.location.href = "<?php echo base_url('supply-from-fsl'); ?>";
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
        
        e_notes.on("focusout", function(e) {
            $('#btn_close').prop('disabled', false);
	});
        
        $("#btn_close").on("click", function(e){
            if(e_trigger.is(':checked')) {
                complete_trans();
            }else{
                if(!isEmpty(e_notes.val())){
                    $("#global_confirm .modal-title").html("Confirmation");
                    $("#global_confirm .modal-body h4").html("It seems that you write some notes, do you want to continue this transaction?");
                    $('#global_confirm').modal({
                        show: true
                    });
                    $('#ans_yess').click(function () {
                        //continue close transaction
                        complete_trans();
                    });
                    $('#ans_no').click(function () {
                        //hold close transaction
                        e_notes.focus();
                    });
                }
            }
        });
    });
</script>