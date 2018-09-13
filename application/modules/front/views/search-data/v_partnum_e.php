<div class="row">
    <div class="col-md-4">
        <div class="card-box">
            <div class="card-header bg-primary text-white">
                <strong class="card-title">Search By</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <select id="fpartname" name="fpartname" class="selectpicker form-control" data-live-search="true" 
                            data-selected-text-format="values" title="Search Part Name.." data-style="btn-light">
                            <?php
                                foreach($list_part as $p){
                                    echo '<option value="'.$p["partno"].'">'.$p['partno'].' - '.$p["name"].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                         </div>
                        <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="Part Number">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <select name="fcoverage[]" id="fcoverage" class="selectpicker form-control" multiple data-actions-box="true" 
                                data-live-search="true" data-selected-text-format="count > 3" title="FSL Location" data-style="btn-light">
                            <?php
                                foreach($list_coverage as $w){
                                    echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" id="btn_search" class="btn btn-primary waves-effect waves-light">
                    Search
                </button>
                <button type="button" id="btn_reset" class="btn btn-danger waves-effect waves-light">
                    Reset
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-8">
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
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Engineer</th>
                                    <th>Partner</th>
                                    <th>Messenger</th>
                                    <th>Trans. Date</th>
                                    <th>Ticket No.</th>
                                    <th>FSL</th>
                                    <th>Trans. No.</th>
                                    <th>Qty</th>
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
</div>

<script type="text/javascript">
    var e_partname = $('#fpartname');
    var e_partnum = $('#fpartnum');
    var e_coverage = $('#fcoverage');
    
    function init_form(){
        e_partname.val('');
        e_partname.selectpicker('refresh');
        e_partnum.val('');
        e_coverage.val('');
        e_coverage.selectpicker('refresh');
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
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
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
                    titleAttr: 'Excel',
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    exportOptions: {
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
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
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
                    titleAttr: 'Excel All Page',
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    footer:false
                }
            ],
            ajax: {                
                url: '<?php echo base_url('front/csearch/get_list_datatable_eg'); ?>',
                type: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.fcoverage = e_coverage.val();
                    d.fpartnum = e_partnum.val();
                }
            },
            columns: [
                { "data": 'fullname' },
                { "data": 'partner' },
                { "data": 'fullname_2' },
                { "data": 'transdate' },
                { "data": 'ticket' },
                { "data": 'fsl' },
                { "data": 'transnum' },
                { "data": 'qty' },
            ],
            order: [[ 1, "asc" ]],
            columnDefs: [{ 
                orderable: false,
                targets: [ -1 ]
            }],
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-12:eq(0)');
                
        e_partname.on('change', function() {
            var selectedText = $(this).find("option:selected").val();
            e_partnum.prop('readonly', false);
            e_partnum.val(selectedText);
            e_partnum.focus();
        });
                
        $("#btn_search").on("click", function(e){
            e.preventDefault();
            table.ajax.reload();
        });
        $("#btn_reset").on("click", function(e){
            e.preventDefault();
            init_form();
            table.ajax.reload();
        });
    });
</script>