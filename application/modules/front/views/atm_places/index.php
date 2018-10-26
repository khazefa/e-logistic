<div class="row">
    <div class="col-md-3">
        <div class="card-box">
            <div class="card-header bg-primary text-white">
                <strong class="card-title">Search By</strong>
            </div>
            <div class="card-body">
                <?php
                    $banks = array();
                    $banks_c = array();
                    $cities = array();
                    $cities_c = array();
                    foreach($arr_data_u as $w){
                        $banks[] = $w["bank"];
                        $cities[] = $w["city"];
                    }
                ?>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <select name="fbank" id="fbank" class="selectpicker form-control" data-actions-box="true" 
                                data-live-search="true" title="Bank Name" data-style="btn-light">
                            <?php
                                $banks_c = array_unique($banks);
                                foreach($banks_c as $b){
                                    echo '<option value="'.$b.'">'.$b.'</option>';
                                }
                            ?>
                        </select>
<!--                        <select name="fbank" id="fbank" class="selectpicker form-control" data-actions-box="true" 
                                data-live-search="true" title="Bank Name" data-style="btn-light">
                        </select>-->
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <select name="fcity" id="fcity" class="selectpicker form-control" data-actions-box="true" 
                                data-live-search="true" title="City Name" data-style="btn-light">
                            <?php
                                $cities_c = array_unique($cities);
                                foreach($cities_c as $c){
                                    echo '<option value="'.$c.'">'.$c.'</option>';
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
    <div class="col-md-9">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><?php echo $contentTitle;?></h4>
            <?php 
                if(!$readonly){
            ?>
                <div class="btn-group">
                    <button type="button" onclick="location.href='<?php echo base_url("atm/add");?>'" class="btn btn-sm btn-light waves-effect">
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
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>SSB ID</th>
                                    <th>Machine ID</th>
                                    <th>Bank Name</th>
                                    <th>Location</th>
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
    var e_bank = $('#fbank');
    var e_city = $('#fcity');
    var jbanks = [];
    var jbanks_c = [];
    var jcity = [];
    var jcity_c = [];
    var table;
    
    function init_form(){
        e_bank.val('');
        e_bank.selectpicker('refresh');
        e_city.val('');
        e_city.selectpicker('refresh');
    }
    
    /*
    function load_select_values(){
        var dataAPI = "<?php echo $arr_data_u; ?>";
        $.getJSON( dataAPI, {
            fname: "",
            fcity: "",
        })
        .done(function( json ) {
            $.each(json.data, function(key, val) {
                //get values from ajax
                jbanks.push(val.bank);
                jcity.push(val.city);
            });
            //remove duplicate values
            jbanks_c = removeDups(jbanks);
            //set select values
            e_bank.empty();
            jbanks_c.forEach(function(item){
                e_bank.append($('<option>').text(item).attr('value', item));
            });
            e_bank.selectpicker('refresh');
            
            //remove duplicate values
            jcity_c = removeDups(jcity);
            //set select values
            e_city.empty();
            jcity_c.forEach(function(item){
                e_city.append($('<option>').text(item).attr('value', item));
            });
            e_city.selectpicker('refresh');
        })
        .fail(function( jqxhr, textStatus, error ) {
            var err = textStatus + ", " + error;
            console.log( "Error Retrieve: " + err );
        });        
    }
    */
    
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
//                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        },
                        columns: ':visible:not(:last-child)'
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
                        columns: ':visible:not(:last-child)'
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
                        columns: ':visible:not(:last-child)'
                    },
                    footer:false
                }, 
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i> All Page',
                    titleAttr: 'Excel All Page',
                    title: 'Export All <?php echo $contentHeader; ?>',
                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                    footer:false
                }
            ],
            ajax: {                
                url: '<?php echo $arr_data;?>',
                type: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.fname = e_bank.val();
                    d.fcity = e_city.val();
                }
            },
            columns: [
                { "data": 'serial_no' },
                { "data": 'machine' },
                { "data": 'bank' },
                { "data": 'location' },
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
    }
    
    function viewdetail(fid){
        var url = '<?php echo $url_modal;?>';
        var type = 'GET';
        var data = {
            <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",
            fid : fid,
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
        init_form();
//        load_select_values();
        
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