<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <strong class="card-title">Tickets</strong><hr>
            <div class="card-body">
                
                <div class="row">
                    <div class="col col-md-6">
                        <div class="form-group row">
                            <label for="fticketnum" class="col-3 col-form-label">Ticket Number</label>
                            <div class="col-6">
                                <input type="text" name="fticketnum" id="fticketnum" class="form-control" placeholder="99999999" required="required" pattern="\d*">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="f_idfe" class="col-3 col-form-label">FE ID Number</label>
                            <div class="col-6">
                                <input type="text" name="f_idfe" id="f_idfe" class="form-control" placeholder="99999999">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fpurpose" class="col-3 col-form-label">Purpose</label>
                            <div class="col-6">
                                <select name="fpurpose" id="fpurpose" class="form-control" placeholder="Select Purpose">
                                    <option value="0">Select Purpose</option>
                                    <option value="Sales/Project">Sales/Project</option>
                                    <option value="Warranty">Warranty</option>
                                    <option value="Maintenance">Maintenance</option>
                                    <option value="Investment">Investment</option>
                                    <option value="Borrowing">Borrowing</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="form-group row">
                            <label for="fpartner" class="col-3 col-form-label">Service Partner</label>
                            <div class="col-6">
                                <input type="text" name="fpartner" id="fpartner" class="form-control" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="f_asfe" class="col-3 col-form-label">Assigned FE</label>
                            <div class="col-6">
                                <input type="text" name="f_asfe" id="f_asfe" class="form-control" readonly="readonly">
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
<div class="row">
    <div class="col-md-9">
        <div class="card-box">
            <div class="card-header bg-primary text-white">
                <strong class="card-title">Order Summary</strong>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col col-md-12">
                        <div class="row form-group">
                            <div class="col col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                     </div>
                                    <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="Part Number">
                                </div>
                            </div>
                            <div class="col col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                     </div>
                                    <input type="text" name="fserialnum" id="fserialnum" class="form-control" placeholder="Serial Number">
                                </div>
                                <!--<span class="help-block" id="fpartname">Part Name</span>-->
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-12"><hr></div>
                        </div>

                        <div class="row">
                            <div class="col col-md-12">
                            <table id="data_grid2" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>X</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Part Name</th>
                                    <th>Stock</th>
                                    <th>Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div class="mt-4"></div>

                        <div class="row">
                            <div class="col col-md-10">&nbsp;</div>
                            <div class="col col-md-2">
                                Total Quantity: <span id="ttl_qty">10</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col col-md-4">
                        <button type="button" class="btn btn-block btn-danger">Cancel</button>
                    </div>
                    <div class="col col-md-4">
                        <button type="button" class="btn btn-block btn-warning">Hold</button>
                    </div>
                    <div class="col col-md-4">
                        <button type="submit" class="btn btn-block btn-success">Request</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">List Parts</h4>
            <table id="data_grid" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Part Number</th>
                    <th>Part Name</th>
                    <th>Stock</th>
                    <th>Part Type</th>
                    <th>FSL</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
    function logic_for_tickets(){
        var fticket = $('#fticket').val();
        var fpartnum = $('#fpartnum').val();

        // Responsive Datatable with Buttons examples
        var table = $('#data_grid').DataTable({
//            select: {
//                style: 'multi'
//            },
            dom: "<'row'<'col-sm-9'B><'col-sm-3'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-8'i><'col-sm-4'p>>",
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
                { "data": 'part_name' },
                { "data": 'part_stock' },
                { "data": 'part_type' },
                { "data": 'fsl_code' },
            ],
        });

//        table.buttons().container()
//                .appendTo('#data_grid_wrapper .col-md-6:eq(0)');

        var table2 = $('#data_grid2').DataTable({
//            select: {
//                style: 'multi'
//            },
            scrollY: '50vh',
            scrollCollapse: true,
            searching: false,
            ordering: false,
            info: false,
            paging: false,
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            lengthChange: false,
            ajax: {
                url: "<?= base_url('json/req_carts.json') ?>",
                type: "POST",
                dataType: "JSON",
                contentType: "application/json",
//                        data: JSON.stringify( { "fticket": fticket, "fpartnum": fpartnum } ),
//                        data: function(d){
//                            d.fticket = fticket;
//                            d.fpartnum = fpartnum;
//                            d.fqty = fqty;
//                            d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
//                        }
            },
            columns: [
                { "data": 'part_id' },
                { "data": 'part_number' },
                { "data": 'serial_number' },
                { "data": 'part_name' },
                { "data": 'part_stock' },
                { "data": 'qty' },
            ],
        });

        table2.buttons().container()
                .appendTo('#data_grid2_wrapper .col-md-6:eq(0)');
    }

</script>