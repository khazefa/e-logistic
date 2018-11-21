<div class="row text-center">
    <div class="col-sm-6 col-lg-6 col-xl-4">
        <div class="card-box widget-flat border-custom bg-custom text-white">
            <i class="fi-archive"></i>
            <p class="text-uppercase m-b-5 font-18 font-600">Return Parts</p>
            <div class="btn-list">
                <?php
                    if(!$readonly){
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('return-parts/add');?>'" class="btn btn-default waves-light waves-effect">
                    Add New
                </button>
                <?php
                    }
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('return-parts/view');?>'" class="btn btn-warning waves-light waves-effect">
                    View Detail
                </button>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-6 col-xl-4">
        <div class="card-box bg-primary widget-flat border-primary text-white">
            <i class="fi-archive"></i>
            <p class="text-uppercase m-b-5 font-18 font-600">Received Transfer from FSL</p>
            <div class="btn-list">
                <?php
                    if(!$readonly){
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('supply-fsl-to-fsl/add');?>'" class="btn btn-default waves-light waves-effect">
                    Add New
                </button>
                <?php
                    }
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('supply-fsl-to-fsl/view');?>'" class="btn btn-warning waves-light waves-effect">
                    View Detail
                </button>
            </div>
        </div>
    </div>
    <!--
    <div class="col-sm-6 col-lg-6 col-xl-4">
        <div class="card-box widget-flat border-success bg-success text-white">
            <i class="fi-archive"></i>
            <p class="text-uppercase m-b-5 font-18 font-600">Received Parts from Warehouse</p>
            <div class="btn-list">
                <?php
                    if(!$readonly){
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('supply-from-cwh/add');?>'" class="btn btn-default waves-light waves-effect">
                    Add New
                </button>
                <?php
                    }
                ?>
                <button type="button" onclick="location.href='<?php echo base_url('supply-from-cwh/view');?>'" class="btn btn-warning waves-light waves-effect">
                    View Detail
                </button>
            </div>
        </div>
    </div>
    -->

</div>