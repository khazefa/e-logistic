<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><?php echo $contentTitle;?></h4>
            <?php 
                if(!$readonly){
            ?>
                <div class="btn-group">
                    <button type="button" onclick="location.href='<?php echo base_url($classname."/add");?>'" class="btn btn-sm btn-light waves-effect">
                        <i class="mdi mdi-plus-circle font-18 vertical-middle"></i> Add New
                    </button>
                </div>
            <?php 
                }
                if($hashub){
            ?>
                <div class="col-md-6">
                    <label for="fcode" class="col-3 col-form-label">Warehouse</label>
                    <div class="col-9">
                        <select name="fcode" id="fcode" required class="selectpicker" data-live-search="true" 
                                data-selected-text-format="values" title="Select FSL.." data-style="btn-light">
                            <option value="">-</option>
                            <?php
                                foreach($list_warehouse as $w){
                                    echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
            
            <p class="text-success text-center">
                <?php
                $error = $this->session->flashdata('error');
                if($error)
                {
                ?>
                <div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $error; ?>                    
                </div>
                <?php
                }
                $success = $this->session->flashdata('success');
                if($success)
                {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $success; ?>                    
                </div>
                <?php } ?>
            </p>
            
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">All Stock</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- Begin Content Panel All Stock -->
                    <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box">
                                    <div class="table-responsive">
                                        <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>FSL</th>
                                                <th>Part Number</th>
                                                <th>Part Name</th>
                                                <th>Min Stock</th>
                                                <th>On hand FSE</th>
                                                <th>Last Stock</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content Panel All Stock -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Data Detail -->
<div class="modal fade" id="viewdetail" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="max-width: 1200px!important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Parts On Engineer's Hand</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box table-responsive">
                            <table id="detail_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Trans No</th>
                                    <th>Date</th>
                                    <th>Ticket No</th>
                                    <th>Requested by</th>
                                    <th>Take by</th>
                                    <th>Part No</th>
                                    <th>Serial No</th>
                                    <th>Qty</th>
                                    <th>Purpose</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-2 offset-md-10">
                                    Total Quantity: <span id="ttl_dqty">0</span>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Data Detail -->

<script type="text/javascript">
    var e_code = $("#fcode");
    var tabel;
    var tabel_d;
    
    function init_table(){        
        // Responsive Datatable with Buttons
        table = $('#data_grid').DataTable({
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
//                        columns: ':visible:not(:last-child)',
                        modifier: {
                            page: 'current'
                        }
                    },
                    footer:false
                },
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i>',
                    titleAttr: 'Copy All',
                    footer:false
                }
            ],
            ajax: {                
                url: "<?php echo $url_list;?>",
                type: "GET",
                dataType: "JSON",
                contentType: "application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.fcode = e_code.val();
                }
            },
            columns: [
                { "data": 'code' },
                { "data": 'partno' },
                { "data": 'partname' },
                { "data": 'minstock' },
                { "data": 'onhand' },
//                { "data": 'initstock' },
                { "data": 'stock' },
            ],
            columnDefs : [
                {
                    targets   : 4,
                    orderable : true, //set not orderable
                    data      : null,
                    render    : function ( data, type, full, meta ) {
                        if(data === "0"){
                            return data;
                        }else{
                            return '<a href="#" id="show_detail"><i class="fa fa-info-circle"></i> '+data+'</a>';
                        }
                    }
                }
            ],
            initComplete: function() {
                e_code.prop('disabled', false);
                e_code.selectpicker('refresh');
            }
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-12:eq(0)');
                
        //function for datatables button
        $('#data_grid tbody').on('click', '#show_detail', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fcode = data['code'];
            fpartnum = data['partno'];
            viewdetail(fcode, fpartnum);
        });
    }
    
    function init_detail(fcode, fpartnum){
        tabel_d = $('#detail_grid').DataTable({
            dom: "<'row'<'col-sm-12'B><'col-sm-10'l><'col-sm-2'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'p><'col-sm-3'i>>",
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i>',
                    title: 'Parts '+fpartnum+' On Engineer Hand',
                    titleAttr: 'Copy',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    },
                    footer:false
                }, 
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    title: 'Parts '+fpartnum+' On Engineer Hand',
                    titleAttr: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    },
                    footer:false
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i> All Page',
                    title: 'All Parts '+fpartnum+' On Engineer Hand',
                    titleAttr: 'Excel All Page',
                    footer:false
                }
            ],
            ajax: {                
                url: '<?php echo $url_list_detail;?>',
                type: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.fcode = fcode;
                    d.fpartnum = fpartnum;
                }
            },
            columns: [
                { "data": 'transnum' },
                { "data": 'transdate' },
                { "data": 'ticket' },
                { "data": 'reqby' },
                { "data": 'takeby' },
                { "data": 'partnum' },
                { "data": 'serialnum' },
                { "data": 'qty' },
                { "data": 'purpose' },
            ],
            order: [[ 1, "desc" ]],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;

                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ? i : 0;
                };
                var totalQty = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                $('#ttl_dqty').html(totalQty);
            },
        });
    }

    function reload2(){
        table_d.ajax.reload();
    }
    
    function viewdetail(fcode, fpartnum){
        $('#viewdetail').modal({
            show: true
        });
        init_detail(fcode, fpartnum);
    }
    
    $(document).ready(function() {
        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            searching: true,
            paginate: true,
            autoWidth: false,
            columnDefs: [{ 
                orderable: false,
                targets: [ 0 ]
            }],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            language: {
                search: '<span>Search:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            }
        });
        
        init_table();
        
        e_code.on('change', function() {
            var selectedText = $(this).find("option:selected").val();
            e_code.prop('disabled', true);
            e_code.selectpicker('refresh');
            init_table();
        });
    });
</script>