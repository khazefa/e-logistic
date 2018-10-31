<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><?php echo $contentTitle;?></h4>
            <?php 
                if(!$readonly){
            ?>
                <div class="btn-group">
                    <button type="button" onclick="location.href='<?php echo base_url($classname.'/add');?>'" class="btn btn-sm btn-light waves-effect">
                        <i class="mdi mdi-plus-circle font-18 vertical-middle"></i> Add New
                    </button>
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
                <div class="row">
                    <div class="table-responsive">
                        <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>FSL Code</th>
                                <th>Warehouse</th>
                                <!--<th>Location</th>-->
                                <th>Nearby</th>
                                <th>PIC</th>
                                <th>Phone</th>
                                <th>Supervisor</th>
                                <th>Sort Order</th>
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

<!-- Modal Request Confirmation -->
<div class="modal fade" id="viewdetail" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel">
    <div class="modal-dialog"  style="max-width:750px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">View Detail</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <?php foreach($field_modal_popup as $rk => $rv){?>
                        <div class="row">
                            <div class="col-md-4"><label class="col-form-label"><?=$rv?></label></div>
                            <div class="col-md-6" id="<?=$rk?>"></div>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Request Confirmation -->

<script type="text/javascript">
    var table;
    
    function init_table(){
        // Responsive Datatable with Buttons
        table = $('#data_grid').DataTable({
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
                    text: '<i class="fa fa-copy"></i>',
                    titleAttr: 'Copy',
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        },
//                        columns: ':visible:not(:last-child)'
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    },
                    footer:false
                }, 
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    title: 'Export <?php echo $contentHeader; ?>',
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        },
//                        columns: ':visible:not(:last-child)'
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    },
                    footer:false
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: 'Export <?php echo $contentHeader; ?>',
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        },
//                        columns: ':visible:not(:last-child)'
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    },
                    footer:false
                }, 
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i> All Page',
                    titleAttr: 'Excel All Page',
                    title: 'Export All <?php echo $contentHeader; ?>',
                    exportOptions: { 
//                        columns: ':visible:not(:last-child)'
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }, //last column has the action types detail/edit/delete
                    footer:false
                }
            ],
            ajax: {                
                url: "<?php echo $url_list;?>",
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
//                { "data": 'location' },
                { "data": 'nearby' },
                { "data": 'pic' },
                { "data": 'phone' },
                { "data": 'spv' },
                { "data": 'sort' },
                { "data": 'button' },
            ],
            order: [[ 0, "asc" ]],
            columnDefs: [{ 
                orderable: false,
                targets: [ -1 ]
            }],
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-12:eq(0)');
    }
    
    function viewdetail(fcode){
        var url = '<?php echo $url_modal;?>';
        var type = 'GET';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            fcode : fcode,
        };
        var success = function (jqXHR) {
            var rs = jqXHR.data[0];
            <?php foreach($field_modal_js as $fk => $fv){?>
                $('#<?=$fk?>').html(rs.<?=$fv?>);
            <?php }?>

            $('#viewdetail').modal({
                show: true
            });
        };
        throw_ajax(url, type, data, success, throw_ajax_err);
    }
    
    $(document).ready(function() {
        init_table();
    });
</script>