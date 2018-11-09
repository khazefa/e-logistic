<div class="row text-center">
    <div class="col-sm-6 col-lg-6 col-xl-6">
        <div class="card-box widget-flat border-custom bg-custom text-white">
            <i class="fi-archive"></i>
            <p class="text-uppercase m-b-5 font-18 font-600">Request Parts</p>
            <div class="button-list">
                <button type="button" onclick="location.href='<?php echo base_url('request-parts/add');?>'" class="btn btn-default waves-light waves-effect">
                    Add New
                </button>
                <button type="button" onclick="location.href='<?php echo base_url('request-parts/view');?>'" class="btn btn-warning waves-light waves-effect">
                    View Detail
                </button>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-6 col-xl-6">
        <div class="card-box bg-primary widget-flat border-primary text-white">
            <i class="fi-archive"></i>
            <p class="text-uppercase m-b-5 font-18 font-600">Transfer to FSL</p>
            <div class="button-list">
                <button type="button" onclick="location.href='<?php echo base_url('transfer-to-fsl/add');?>'" class="btn btn-default waves-light waves-effect">
                    Add New
                </button>
                <button type="button" onclick="location.href='<?php echo base_url('transfer-to-fsl/view');?>'" class="btn btn-warning waves-light waves-effect">
                    View Detail
                </button>
            </div>
        </div>
    </div>
</div>