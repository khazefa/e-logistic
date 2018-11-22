<div class="row">
    <div class="col-md-3">
        <div class="card-box">
            <div class="card-header bg-primary text-white">
                <strong class="card-title">Search By</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <p>Please select the same date to select report on the same day (Daily)</p>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                         </div>
                        <input type="date" name="fdate1" id="fdate1" class="form-control" placeholder="MM/DD/YYYY" required="required">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                         </div>
                        <input type="date" name="fdate2" id="fdate2" class="form-control" placeholder="MM/DD/YYYY" required="required">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <select name="fcoverage[]" id="fcoverage" class="selectpicker form-control" multiple data-actions-box="true" 
                                data-live-search="true" data-selected-text-format="count > 3" title="Transfered From" data-style="btn-light">
                            <?php
                                foreach($list_coverage as $w){
                                    echo '<option value="'.$w["code"].'">'.$w["name"].'</option>';
                                }
                            ?>
                        </select>
                        <!--<input type="text" name="fcoverage" id="fcoverage" class="typeahead form-control" data-role="tagsinput">-->
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <select name="fstatus" id="fstatus" class="selectpicker form-control" data-actions-box="true" 
                                data-live-search="true" data-selected-text-format="count > 3" title="Status" data-style="btn-light">
                            <option value="">- Please Chose -</option>
                            <option value="open">Open</option>
                            <option value="pending">Pending</option>
                            <option value="closed">Close</option>
                        </select>
                        <!--<input type="text" name="fcoverage" id="fcoverage" class="typeahead form-control" data-role="tagsinput">-->
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <select name="fpurpose[]" id="fpurpose" class="selectpicker form-control" multiple data-actions-box="true" 
                                data-live-search="true" data-selected-text-format="count > 3" title="Please choose Purpose" data-style="btn-light">
                            <?php
                                foreach($field_purpose as $k=>$v){
                                    echo '<option value="'.$k.'">'.$v.'</option>';
                                }
                            ?>
                        </select>
                        <!--<input type="text" name="fcoverage" id="fcoverage" class="typeahead form-control" data-role="tagsinput">-->
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
    <div class="col-md-9">
        <div class="card-box">
            <button type="button" onclick="location.href='<?php echo base_url("new-supply-to-repair-trans");?>'" class="btn btn-custom btn-rounded w-md waves-effect waves-light">
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
                                <th>Trans No</th>
                                <th>Date Create</th>
                                <!-- <th>Outgoing No.</th> -->
                                <th>Qty</th>
                                <th>Transfered From</th>
                                <th>Status</th>
                                <!--<th>Notes</th>-->
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


<!-- Modal Form Detail -->
<div class="modal fade" id="viewdetail" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="max-width:750px;">
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
                            <div class="col-md-6"><label class="col-form-label"><?=$rv?></label></div>
                            <div class="col-md-6" id="<?=$rk?>"></div>
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
                                    <th>Notes</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Form Detail -->

<script type="text/javascript">
    var e_date1 = $('#fdate1');
    var e_date2 = $('#fdate2');
    var e_coverage = $('#fcoverage');
    var e_status = $('#fstatus');
    var e_purpose = $('#fpurpose');
    var table1;
    var transnum = '';
    
    function init_form(){
        e_date1.val('');
        e_date2.val('');
        e_status.val('');
        e_status.selectpicker('refresh');
        e_coverage.val('');
        e_coverage.selectpicker('refresh');
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
                { "data": 'dt_notes'},
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

    function error_xhqr(jqXHR, textStatus, errorThrown){
        // Handle errors here
        console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );          
    }

    function viewdetail(transnum_e){
        transnum = transnum_e;
        var url = '<?=$link_modal ?>';
        var type = 'POST';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            ftransnum : transnum,
        };
        var success = function (jqXHR) {
                var rs = jqXHR[0];
                <?php foreach($field_modal_js as $fk => $fv){?>
                    $('#<?=$fk?>').html(jqXHR.<?=$fv?>);
                <?php }?>

                $('#viewdetail').modal({
                    show: true
                });
                reload2();
            
        };
        xhqr(url, type, data, success, error_xhqr);
    }
    
    $(document).ready(function() {
        init_table();
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
                    exportOptions: {
                        columns: ':visible:not(:last-child)',
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
                        columns: ':visible:not(:last-child)',
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
                        columns: ':visible:not(:last-child)',
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
                        columns: ':visible:not(:last-child)'
                    },
                    footer:false
                }
            ],
            ajax: {                
                url: '<?=$link_get_data; ?>',
                type: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.fdate1 = e_date1.val();
                    d.fdate2 = e_date2.val();
                    d.fcoverage = e_coverage.val();
                    d.fstatus = e_status.val();
                    d.fpurpose = e_purpose.val();
                }
            },
            columns: [
                { "data": 'transnum' },
                { "data": 'transdate' },
                // { "data": 'transout' },
                { "data": 'qty' },
                { "data": 'fsl_code' },
                { "data": 'status' },
//                { "data": 'notes' },
                { "data": 'button' },
            ],
            order: [[ 1, "desc" ]],
            columnDefs: [{ 
                orderable: false,
                targets: [ -1 ]
            }],
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-12:eq(0)');
                
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