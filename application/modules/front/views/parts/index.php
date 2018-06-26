<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <button type="button" class="btn btn-custom btn-rounded w-md waves-effect waves-light">
                <i class="fa fa-plus"></i> Add New
            </button>
            <h4 class="header-title m-b-30 pull-right"><?php echo $contentTitle;?></h4><hr>
            
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Sparepart Number</th>
                                <th>Serial Number</th>
                                <th>Sparepart Name</th>
                                <th>Stock</th>
                                <th>Sparepart Type</th>
                                <th>FSL</th>
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
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var fticket = $('#fticket').val();
        var fpartnum = $('#fpartnum').val();

        // Responsive Datatable with Buttons examples
        var table = $('#data_grid').DataTable({
//            select: {
//                style: 'multi'
//            },
            dom: "<'row'<'col-sm-10'B><'col-sm-2'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-10'i><'col-sm-2'p>>",
            language: {
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            },
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            lengthChange: true,
            buttons: ['copy', 'excel', 'pdf'],
            ajax: {
                url: "<?= base_url('json/list_part.json') ?>",
                type: "POST",
                dataType: "JSON",
                contentType: "application/json",
//                data: JSON.stringify( { "fticket": fticket, "fpartnum": fpartnum } ),
//                data: function(d){
//                    d.fticket = fticket;
//                    d.fpartnum = fpartnum;
//                    d.fqty = fqty;
//                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
//                }
            },
            columns: [
                { "data": 'part_number' },
                { "data": 'serial_number' },
                { "data": 'part_name' },
                { "data": 'part_stock' },
                { "data": 'part_type' },
                { "data": 'fsl_code' },
            ],
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-6:eq(0)');
    });
</script>