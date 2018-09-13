<form action="#" method="POST" class="form-horizontal" role="form">
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <h4 class="header-title m-b-20"><?php echo $contentTitle; ?></h4><hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="fsearch" class="col-sm-2 col-form-label">list Search</label>
                                <div class="col-sm-6">
                                    <textarea name="fsearch" id ="fsearch" class="form-control " rows="20"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-6">
                                    <button type="button" id="btn_search" class="btn btn-success waves-effect waves-light">Submit</button>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            
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
                                <th>Part No</th>
                                <th>Description</th>
                                <th>Stock</th>
                                <th>Note</th>
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
    var e_search = $('#fsearch');
    var b_search = $('#btn_search');
    var table;
    
    $(document).ready(function() {
        // Responsive Datatable with Buttons
        table = $('#data_grid').DataTable({
            dom: "<'row'<'col-sm-10'l><'col-sm-2'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'p><'col-sm-3'i>>",
            language: {
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            },
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            lengthChange: true,
            ajax: {                
                url: "<?= base_url('front/csearchparts/get_list_view_datatable'); ?>",
                type: "POST",
                dataType: "JSON",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                contentType: "application/json",
                data: function(p){
                    p.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    p.fsearch = e_search.val();
                }
            },
            columns: [
                { "data": 'part_number' },
                { "data": 'part_name' },
                { "data": 'stock_last_value' },
                { "data": 'note' },
            ],
            order: [[ 0, "desc" ]],
            columnDefs: [{ 
                orderable: false,
                targets: [ -1 ]
            }],
        });
        table.buttons().container().appendTo('#data_grid_wrapper .col-md-12:eq(0)');
        
        b_search.on('click',function(){
            search();
        });
    });
    
    function search(){
        table.ajax.reload();
    }
</script>