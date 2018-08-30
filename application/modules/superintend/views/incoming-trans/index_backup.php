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
                    <div class="input-group col-lg-12">
                        <small>Please start typing 'c' that refef to FSL Code, to find FSL Coverage Data</small>
                        <input type="text" name="fcoverage" id="fcoverage" class="typeahead form-control" data-role="tagsinput">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-12">
                        <select name="fpurpose" id="fpurpose" class="form-control" placeholder="By Purpose">
                            <option value="">By Purpose</option>
                            <option value="RG">Return Good</option>
                            <option value="S">Supply</option>
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
            <h4 class="header-title"><?php echo $contentTitle;?></h4><hr>
            
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
                                <th>Date</th>
                                <th>FSL</th>
                                <th>Purpose</th>
                                <th>Outgoing No.</th>
                                <th>Qty</th>
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
    var e_date1 = $('#fdate1');
    var e_date2 = $('#fdate2');
    var e_coverage = $('#fcoverage');
    var e_purpose = $('#fpurpose');
    
    function init_form(){
        e_date1.val('');
        e_date2.val('');
        e_coverage.val('');
        e_coverage.tagsinput('removeAll');
        e_purpose.val('');
    }
    
    $(document).ready(function() {
        var fslnames = new Bloodhound({
            datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.name); },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: '<?php echo base_url('superintend/coutgoing/get_list_coverage'); ?>',
                filter: function(list) {
                    // This should not be required, but I have left it incase you still need some sort of filtering on your server response
                    return $.map(list, function(data) { 
                        return { name: data.name, code: data.code }; 
                    });
                }
            }
        });
        fslnames.initialize();

        $('#fcoverage').tagsinput({
            itemValue: 'code',
            itemText: 'name',
            typeaheadjs: {
                name: 'fslnames',
//                displayKey: 'name',
                displayKey: function(data) {
                   return data.name;
                },
//                valueKey: 'code',
                source: fslnames.ttAdapter(),
                classNames: {
                    input: 'Typeahead-input',
                    hint: 'Typeahead-hint',
                    selectable: 'Typeahead-selectable'
                },
                templates: {
                    suggestion: function (fsl) {
                        return '<strong>' + fsl.name + '</strong>';
                    }
                }
            }
        });
        fslnames.clearPrefetchCache();
        $('#fcoverage').on('itemAdded', function(event) {
            // event.item: contains the item
            if(event.item.name === "FSL All"){
                //event after select All
                alert("If you select All for FSL Coverage, then you don\'t have to select another FSL Coverage.");
                e_purpose.focus();
            }else{
                //event after select exclude All
            }
        });
        
        $('#fcoverage').on('change', function(e) {
//            alert('Val:'+this.value);
        });
        
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
                url: '<?php echo base_url('superintend/cincoming/get_list_view_datatable'); ?>',
                type: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    d.fdate1 = e_date1.val();
                    d.fdate2 = e_date2.val();
                    d.fcoverage = e_coverage.val();
                    d.fpurpose = e_purpose.val();
                }
            },
            columns: [
                { "data": 'transnum' },
                { "data": 'transdate' },
                { "data": 'fsl' },
                { "data": 'purpose' },
                { "data": 'transout' },
                { "data": 'qty' },
                { "data": 'button' },
            ],
            order: [[ 0, "desc" ]],
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