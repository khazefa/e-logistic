<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-box">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Input Data</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group form-group-sm col-sm-12">
                                        <div class="row">
                                            <div class="input-group col-sm-12">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-barcode"></i> </span>
                                                    </div>
                                                <input type="text" name="fpartnum_s" id="fpartnum_s" class="form-control" placeholder="Part Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-sm-12">
                                        <span id="fpartnum_s_notes" class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group form-group-sm col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input type="number" name="fqty_s" id="fqty_s" class="form-control" value="1" min="0" required="required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-sm-6">
                                        <div class="row">
                                            <div class="input-group col-sm-12">
                                                <button type="button" id="btn_add_s" class="btn btn-warning waves-effect waves-light pull-right">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group form-group-sm col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input type="text" name="fnotes_s" id="fnotes_s" class="form-control" required="required" placeholder="Delivery Notes">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btn_submit_s" class="btn btn-primary waves-effect waves-light">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-box table-responsive">
                            <h4 class="m-b-30 header-title">Detail Transaction</h4>
                            <table id="cart_grid_s" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Part Number</th>
                                    <th>Part Name</th>
                                    <th>Current Stock</th>
                                    <th>Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-3 offset-md-9">
                                    Total Quantity: <span id="ttl_qty_s">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- End Content Panel Supply -->