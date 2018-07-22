<form action="#" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Daily Report Consumed Part</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="input-group col-sm-6">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                         </div>
                                        <input type="date" name="fdate1" id="fdate1" class="form-control" placeholder="MM/DD/YYYY">
                                    </div>
                                    <div class="input-group col-sm-6">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                         </div>
                                        <input type="date" name="fdate2" id="fdate2" class="form-control" placeholder="MM/DD/YYYY">
                                    </div>
                                    <div class="input-group col-sm-12">
                                        <div id="fsearch_notes"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btn_submit_d" class="btn btn-success waves-effect waves-light">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>