<?php            
$fid = '';
$fpurpose = '';
$ftransnum = '';
$fticket = '';
$ftransdate = '';
$feg = '';
$feg_name = '';
$feg_mess = '';
$fpartner = '';
$fcustomer = '';
$flocation = '';
$fssbid = '';
$ffsl = '';
$ffslname = '';

if(!empty($records))
{
    foreach ($records as $r)
    {
        $fid = $r['fid'];
        $fpurpose = $r['fpurpose'];
        $ftransnum = $r['ftransnum'];
        $fticket = $r['fticket'];
        $ftransdate = $r['ftransdate'];
        $feg = $r['feg'];
        $feg_name = $r['feg_name'];
        $feg_mess = $r['feg_mess'];
        $fpartner = $r['fpartner'];
        $fcustomer = $r['fcustomer'];
        $flocation = $r['flocation'];
        $fssbid = $r['fssbid'];
        $ffsl = $r['ffsl'];
        $ffslname = $r['ffslname'];
    }
}
?>
<form action="#" method="POST" class="form-horizontal" role="form">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
    <input type="hidden" name="fid" value="<?php echo $fid;?>">
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <p class="card-text m-b-30">
                    <strong><?php echo $contentTitle.' #'.$ftransnum;?></strong>
                    <a href="#" class="btn btn-outline-danger pull-right" title="Delete this transaction?">
                        <i class="fa fa-trash"></i>
                    </a>
                </p><hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    Transaction Information
                                </div>
                                <div class="card-body">
                                    <div class="text-left">
                                        <p class="font-13"><strong>FSL :</strong> <span class="m-l-10"><?php echo $ffslname;?></span></p>
                                        <p class="font-13"><strong>Purpose :</strong> <span class="m-l-10"><?php echo $fpurpose;?></span></p>
                                        <p class="font-13"><strong>Trans. Date. :</strong> <span class="m-l-10"><?php echo $ftransdate;?></span></p>
                                        <p class="font-13"><strong>Ticket No. :</strong> <span class="m-l-10"><?php echo $fticket;?></span></p>
                                        <p class="font-13"><strong>FSE ID :</strong> <span class="m-l-10"><?php echo $feg;?></span></p>
                                        <p class="font-13"><strong>Assigned FSE :</strong> <span class="m-l-10"><?php echo $feg_name;?></span></p>
                                        <p class="font-13"><strong>Partner :</strong> <span class="m-l-10"><?php echo $fpartner;?></span></p>
                                        <p class="font-13"><strong>FSE Messenger :</strong> <span class="m-l-10"><?php echo $feg_mess;?></span></p>
                                        <p class="font-13"><strong>Customer :</strong> <span class="m-l-10"><?php echo $fcustomer;?></span></p>
                                        <p class="font-13"><strong>Location :</strong> <span class="m-l-10"><?php echo $flocation;?></span></p>
                                        <p class="font-13"><strong>SSB ID :</strong> <span class="m-l-10"><?php echo $fssbid;?></span></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    Transaction Detail
                                </div>
                                <div class="card-body">
                                    <div class="checkbox checkbox-custom">
                                        <input type="checkbox" name="fstock_u" id="fstock_u"> <label for="fstock_u">Required to update stock?</label>
                                    </div>
                                    <div class="col col-md-12">
                                        <table id="detail_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>Part Number</th>
                                                <th>Serial Number</th>
                                                <th>Part Name</th>
                                                <th>Qty</th>
                                                <th>Return Status</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
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
<script type="text/javascript">
    //init table
    function init_table(){
        table = $('#detail_grid').DataTable({
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
                url: "<?= base_url('front/chistorytrans/get_detail_outgoing_datatable/'.$ftransnum); ?>",
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
                { "data": 'qty' },
                { "data": 'return' },
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
                    targets   : 4,
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
        $('#detail_grid tbody').on( 'click', 'button', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fid = data['id'];
            delete_cart(fid);
        });
        
        //function for datatables button
        $('#detail_grid tbody').on( 'keypress', 'input', function (e) {        
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
                .appendTo('#detail_grid_wrapper .col-md-6:eq(0)');
    }
    
    //reload table
    function reload(){
        table.ajax.reload();
    }
    
    function delete_task(id){        
        var url = '<?php echo base_url('front/chistorytrans/delete_task'); ?>';
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
    
    $(document).ready(function() {
        init_table();
    });
</script>