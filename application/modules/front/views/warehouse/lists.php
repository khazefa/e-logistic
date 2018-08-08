<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <button type="button" onclick="location.href='<?php echo base_url("add-warehouses");?>'" class="btn btn-custom btn-rounded w-md waves-effect waves-light">
                <i class="fa fa-plus"></i> Add New
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
                <div class="row">
                    <div class="table-responsive">
                        <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>FSL Code</th>
                                <th>Warehouse</th>
                                <th>Location</th>
                                <th>Nearby</th>
                                <th>PIC</th>
                                <th>Phone</th>
                                <th>Supervisor</th>
                                <th>Action</th>
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
        // Responsive Datatable with Buttons
        var table = $('#data_grid').DataTable({
            dom: "<'row'<'col-sm-10'B><'col-sm-2'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'p><'col-sm-3'i>>",
            language: {
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            },
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            lengthChange: true,
            buttons: [
                {
                    extend: 'copy',
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    footer:true
                }, 
                {
                    extend: 'excel',
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    footer:true
                },
                {
                    extend: 'pdf',
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    footer:true
                }
            ],
            ajax: {                
                url: "<?= base_url('front/cwarehouse/get_m_list_datatable'); ?>",
                type: "POST",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify( {
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                } ),
            },
            columns: [
                { "data": 'code' },
                { "data": 'name' },
                { "data": 'location' },
                { "data": 'nearby' },
                { "data": 'pic' },
                { "data": 'phone' },
                { "data": 'spv' },
                { "data": 'button' },
            ],
            order: [[ 0, "asc" ]],
//            columnDefs: [{ 
//                orderable: false,
//                targets: [ -1 ]
//            }],
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-12:eq(0)');
    });
</script>