<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-4">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle; ?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Close Transaction</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group row col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                        </div>
                                        <input type="text" name="ftransnum" id="ftransnum" class="form-control" placeholder="Trans. No."><span class="text-danger" id="msg_transnum"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class=" row col-sm-12">
                                        <input type="text" name="fnotes" id="fnotes" class="form-control" placeholder="Notes"><span class="text-danger" id="msg_fnotes"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="checkbox checkbox-primary">
                                            <input name="fagree" id="fagree" value="agree" type="checkbox">
                                            <label for="fagree">
                                                I agree, the contents of the Transaction are appropriate.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btn_submit" class="btn btn-success waves-effect waves-light">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card-box">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">                   
                        <div class="card bg-light"> 
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Close Transaction</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php foreach($field_modal_popup as $rk => $rv){?>
                                        <div class="row">
                                            <div class="col-md-6"><label class="col-form-label"><?=$rv?></label></div>
                                            <div class="col-md-6" id="<?=$rk?>"> :</div>
                                        </div>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-box table-responsive">
                                            <h4 class="m-b-30 header-title">Detail Transaction</h4>
                                            <table id="cart_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Part Number</th>
                                                    <th>Part Name</th>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
<script>
    var e_trans_out_c = $('#ftransnum');
    var e_notes = $('#fnotes');
    var e_check = $('#fagree');
    var transnum = e_trans_out_c.val();
    var msg_transnum = $('#msg_ftransnum');
    var btn_submit = $('#btn_submit');
    var table1;
    var step = 0;
    
    function init_form(){
        step = 0;
        e_trans_out_c.val('');
        transnum = '';
        msg_transnum.html('');
        btn_submit.prop('disabled',true);
        reload2();
   }
    
    function init_table(){
        // Table 1
        table1 = $('#cart_grid').DataTable({
            dom: "<'row'<'col-sm-12'B><'col-sm-10'l><'col-sm-2'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'p><'col-sm-3'i>>",
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i>',
                    titleAttr: 'Copy',
                    exportOptions: {
                        //columns: ':visible:not(:last-child)',
                        modifier: {
                            page: 'current'
                        }
                    },
                    footer:false
                }, 
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    exportOptions: {
                        //columns: ':visible:not(:last-child)',
                        modifier: {
                            page: 'current'
                        }
                    },
                    footer:false
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    exportOptions: {
                        //columns: ':visible:not(:last-child)',
                        modifier: {
                            page: 'current'
                        }
                    },
                    footer:false
                }, 
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i> All Page',
                    titleAttr: 'Excel All Page',
                    exportOptions: {
                        //columns: ':visible:not(:last-child)'
                    },
                    footer:false
                }
            ],
            ajax: {                
                url: '<?=$link_modal_detail;?>',
                type: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.ftransnum = transnum;

                }
            },
            columns: [
                { "data": 'part_number' },
                { "data": 'part_name' },
                { "data": 'dt_fsltocwh_qty' },
            ],
            order: [[ 0, "desc" ]],
            columnDefs: [{ 
                orderable: false,
                targets: [ -1 ]
            }],
        });
    }

    function reload2(){
        table1.ajax.reload();
    }

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
        var ret = false;
        ret1 = (e_trans_out_c.val()=='')?falert(e_trans_out_c,false):falert(e_trans_out_c,true);
        ret2 = (e_notes.val()=='')?falert(e_notes,false):falert(e_notes,true);
        ret3 = (e_check.val()=='0'|| e_check.val()=='')?falert(e_check,false):falert(e_check,true);
        
        if(ret1 && ret2 && ret3 && ret4 && ret5){
            btn_submit.prop('disabled',false);
        }else{
            btn_submit.prop('disabled',true);
        }

    }

    function error_xhqr(jqXHR, textStatus, errorThrown){
        // Handle errors here
        console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );          
    }

    function closing_trans(){
        
    }
    
    function check_transnum(transnum_e){
        transnum = transnum_e;
        var url = '<?=$link_check_transnum?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            ftransnum : transnum,
        }
        var success = function (jqXHR) {
            var rs = jqXHR;
            if(!rs.status){
                msg_transnum.html(rs.message);
            }else{
                viewdetail();
            }
        }
        xhqr(url, type, data, success, error_xhqr);
    }

    function viewdetail(){
        var url = '<?=$link_modal ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            ftransnum : transnum,
        };
        var success = function (jqXHR) {
                var rs = jqXHR[0];
                <?php foreach($field_modal_js as $fk => $fv){?>
                    $('#<?=$fk?>').html(" : " + jqXHR.<?=$fv?>);
                <?php }?>

                reload2();
            
        };
        xhqr(url, type, data, success, error_xhqr);
    }

    $(document).ready(function() {
        init_table();
        init_form();
        e_trans_out_c.on("keypress", function(e){
            if (e.keyCode == 13) {
                if(isEmpty(e_trans_out_c.val())){
                    alert('Please fill in this field!');
                    e_trans_out_c.focus();
                }else{
                    
                    check_transnum($(this).val());
                    //e_trans_out_c.prop("readonly", true);
                    //e_fe_report_c.focus();
                }
                return false;
            }
        });
    });
</script>