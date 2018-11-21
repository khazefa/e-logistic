<div class="row text-center">
    <div class="col-sm-4 col-lg-4 col-xl-4">
        <div class="card-box widget-flat border-custom bg-custom text-white">
            <i class="fi-archive"></i>
            <p class="text-uppercase m-b-5 font-18 font-600">Request Parts</p>
            <div class="button-list">
                <?php
                    if(!$readonly){
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('request-parts/add');?>'" class="btn btn-default waves-light waves-effect">
                    Add New
                </button>
                <?php
                    }
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('request-parts/view');?>'" class="btn btn-warning waves-light waves-effect">
                    View Detail
                </button>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-lg-4 col-xl-4">
        <div class="card-box widget-flat border-custom bg-custom text-white">
            <i class="fi-archive"></i>
            <p class="text-uppercase m-b-5 font-18 font-600">Transfer Stock to FSL</p>
            <div class="button-list">
                <?php
                    if(!$readonly){
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('transfer-stock-to-fsl/add');?>'" class="btn btn-default waves-light waves-effect">
                    Add New
                </button>
                <?php
                    }
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('transfer-stock-to-fsl/view');?>'" class="btn btn-warning waves-light waves-effect">
                    View Detail
                </button>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-lg-4 col-xl-4">
        <div class="card-box widget-flat border-custom bg-custom text-white">
            <i class="fi-archive"></i>
            <p class="text-uppercase m-b-5 font-18 font-600">Transfer Bad Part/Bad Stock to Central Warehouse</p>
            <div class="button-list">
                <?php
                    if(!$readonly){
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('new-fsltocwh-trans');?>'" class="btn btn-default waves-light waves-effect">
                    Add New
                </button>
                <?php
                    }
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('fsltocwh-trans');?>'" class="btn btn-warning waves-light waves-effect">
                    View Detail
                </button>
            </div>
        </div>
    </div>
</div>