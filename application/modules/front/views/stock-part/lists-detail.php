<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <button type="button" onclick="location.href='<?php echo base_url("import-spareparts-stock");?>'" class="btn btn-success btn-rounded w-md waves-effect waves-light">
                <i class="fa fa-download"></i> Import
            </button>
            <h4 class="header-title m-b-30 pull-right"><?php echo $contentTitle;?></h4><br><hr>
            
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
                        <a class="nav-item nav-link " id="nav-subtitute-tab" data-toggle="tab" href="#nav-subtitute" role="tab" aria-controls="nav-subtitute" aria-selected="false">Subtitution Stock</a>
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
                                                <th>Init Stock</th>
                                                <th>Min Stock</th>
                                                <th>On hand FSE</th>
                                                <th>Last Stock</th>
                                                <!--<th>Action</th>-->
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
                    
                    <!-- Begin Content Panel Subtitute Stock -->
                    <div class="tab-pane fade " id="nav-subtitute" role="tabpanel" aria-labelledby="nav-subtitute-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box">
                                    <div class="table-responsive">
                                        <table id="datasub_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>FSL</th>
                                                <th>Part Number</th>
                                                <th>Part Name</th>
                                                <th>Last Stock</th>
                                                <th>Part Subtitute</th>
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
                    <!-- End Content Panel Subtitute Stock -->
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
    var tabel_d;
    
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
                    title: 'Parts '+fpartnum+' On Engineer Hand',
                    titleAttr: 'Excel All Page',
                    footer:false
                }
            ],
            ajax: {                
                url: '<?=base_url('list-detail-spareparts-stock');?>',
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
        // Responsive Datatable with Buttons
        var table = $('#data_grid').DataTable({
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
                url: "<?= base_url('front/cstockpart/get_m_list_datatable/').$code; ?>",
                type: "POST",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify( {
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                } ),
            },
            columns: [
                { "data": 'code' },
                { "data": 'partno' },
                { "data": 'partname' },
                { "data": 'initstock' },
                { "data": 'minstock' },
                { "data": 'onhand' },
                { "data": 'stock' },
//                { "data": 'button' },
            ],
            columnDefs : [
                {
                    targets   : 5,
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

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            if ((target == '#nav-subtitute')) {
                // Responsive Datatable with Buttons
                var tablesub = $('#datasub_grid').DataTable({
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
                        url: "<?= base_url('front/cstockpart/get_list_partsub_datatable/').$code; ?>",
                        type: "POST",
                        dataType: "JSON",
                        contentType: "application/json",
                        data: JSON.stringify( {
                            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                        } ),
                    },
                    columns: [
                        { "data": 'code' },
                        { "data": 'partno' },
                        { "data": 'partname' },
                        { "data": 'stock' },
                        { "data": 'partnosub' },
                    ],
                    order: [[ 3, "desc" ]],
                    columnDefs: [{ 
                        orderable: false,
                        targets: [ 0 ]
                    }],
                });

                tablesub.buttons().container()
                        .appendTo('#datasub_grid_wrapper .col-md-12:eq(0)');

//                tablesub.column(1).data().unique();
            } else {
                //
            }
        });

    });
</script>