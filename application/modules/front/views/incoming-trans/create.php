<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-supply-tab" data-toggle="tab" href="#nav-supply" role="tab" aria-controls="nav-supply" aria-selected="true">Supply</a>
                        <a class="nav-item nav-link" id="nav-return-tab" data-toggle="tab" href="#nav-return" role="tab" aria-controls="nav-return" aria-selected="false">Return</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- Begin Content Panel Supply -->
                    <div class="tab-pane fade show active" id="nav-supply" role="tabpanel" aria-labelledby="nav-supply-tab">
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
                                                    <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="Part Number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-sm-12">
                                            <span id="fpartnum_notes" class="help-block text-danger"><small></small></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group form-group-sm col-md-6 offset-md-6">
                                            <div class="row">
                                                <div class="input-group col-sm-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"> <i class="fa fa-calculator"></i> </span>
                                                     </div>
                                                    <input type="number" name="fqty" id="fqty" class="form-control" value="1" required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-b-10">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-box table-responsive">
                                <h4 class="m-b-30 header-title">List Supplied Sparepart</h4>
                                <table id="supply_cart_grid" class="table table-light dt-responsive nowrap" cellspacing="0" width="100%">
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
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- End Content Panel Supply -->
                    
                    <!-- Begin Content Panel Return -->
                    <div class="tab-pane fade" id="nav-return" role="tabpanel" aria-labelledby="nav-return-tab">
                        
                    </div>
                    <!-- End Content Panel Return -->
                </div>
            </div>
        </div>
    </div>
    
</div>
</form>
<script type="text/javascript">
    var e_purpose = $('#fpurpose');
    
    var dataSet = [];

</script>