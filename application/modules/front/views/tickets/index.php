<div class="row">
<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <strong class="card-title" v-if="headerText"><?php echo $contentTitle;?></strong>
        </div>

        <div class="card-body card-block">
            <form action="#" method="POST" class="form-horizontal">
                <div class="row form-group">
                    <div class="col col-md-4"><label for="ffsl" class=" form-control-label">FSL Code</label></div>
                    <div class="col col-md-6">
                        <!--
                        <select name="ffsl" data-placeholder="Choose warehouse" class="form-control standardSelect" tabindex="1">
                            <option value="0">FSL Code</option>
                            <?php
                                /**
                                foreach ($list_warehouses as $w) {
                                    echo '<option value="'.$w->fsl_code.'">'.$w->fsl_name.'</option>';
                                }
                                **/
                            ?>
                        </select>
                        -->
                        <input type="text" name="ffsl" id="ffsl" class="form-control" placeholder="Type Warehouse Name" required="required">
                        <span class="help-block" id="ffsl_info">Warehouse Name - Location</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col col-md-4"><label for="fticket" class=" form-control-label">Ticket Number</label></div>
                    <div class="col col-md-6">
                        <input type="text" name="fticket" id="fticket" class="form-control" value="<?php echo $ticket_num;?>" readonly="readonly">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col col-md-4"><label for="fpartnum" class=" form-control-label">Part Number</label></div>
                    <div class="col col-md-6">
                        <input type="text" name="fpartnum" id="fpartnum" class="form-control" placeholder="00050778000E" required="required">
                        <span class="help-block" id="fpartname">Part Name</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col col-md-4"><label for="fqty" class=" form-control-label">Qty</label></div>
                    <div class="col col-md-4">
                        <input type="number" name="fqty" id="fqty" class="form-control" value="1" required="required">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col col-md-4"><label class=" form-control-label">&nbsp;</label></div>
                    <div class="col col-md-4">
                        <button type="button" name="btn_add" id="btn_add" class="btn btn-sm btn-danger"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <strong class="card-title" v-if="headerText">Data List</strong>
        </div>
        <div class="card-body">
            <table id="data_grid" class="table table-striped table-bordered table-responsive-md">
              <thead>
                <tr>
                  <!--<th>Actions</th>-->
                  <th>Ticket</th>
                  <th>Part No</th>
                  <th>Qty</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <button type="button" class="btn btn-primary btn-sm" id="btn_save">
              <i class="fa fa-dot-circle-o"></i> Submit
            </button>
            <!--
            <button type="reset" class="btn btn-danger btn-sm">
              <i class="fa fa-ban"></i> Reset
            </button>
            -->
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    function logic_for_tickets(){
        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            "autoWidth": true,
//            "dom": '<"datatable-header"l><"datatable-scroll"tr><"datatable-footer"ip>',
            "dom": '<"datatable-header"><"datatable-scroll"tr><"datatable-footer">',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            }
        });
        
        var fticket = $("#fticket").val();
        var fpartnum = $("#fpartnum").val();
        var fqty = $("#fqty").val();
        var ffsl = $("#ffsl").val();
//        var data = 'fticket='+fticket+'&fpartnum='+fpartnum+'&fqty='+fqty+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
    
        //mendeklarasikan dan mendefinisikan data table
        var table = $('#data_grid').DataTable({
            "responsive": true,
            "destroy": true,
            "stateSave": false,
            "deferRender": true,
            "processing": true, //Feature control the processing indicator.
            // "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ajax": {
                "url": "<?= base_url('front/tickets/get_carts/') ?>",
                "type": "POST",
                "data": function(d){
                    d.fticket = fticket;
                    d.fpartnum = fpartnum;
                    d.fqty = fqty;
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                }
            },
            "columns": [
//                { "data": 'button' },
                { "data": 'ticketnum' },
                { "data": 'partnum' },
                { "data": 'qty' },
            ],
//            "columnDefs" : [{
//                "targets"   : 0,
//                "orderable" : false, //set not orderable
//                "data"      : null,
//                "render"    : function ( data, type, full, meta ) {
//                    return '<button type="button" class="btn btn-danger" id="btn_delete"><i class="fa fa-trash"></i></button>';
//            }}]
        });
        //untuk menambahkan fungsi pada setiap button yang ada
        $('#data_grid tbody').on( 'click', 'button', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            var fid = data['button'];
            delete_cart(fid);
        });
        
        var input = $("input[name=ffsl]");

        $.get('<?php echo base_url('front/tickets/get_list_warehouse/'); ?>', function(data){
            input.typeahead({
                source: data,
                minLength: 1,
            });
        }, 'json');

        $("#ffsl").attr('readonly', false);
        input.change(function(){
            var current = input.typeahead("getActive");
            $('#ffsl').val(current.code);
            $('#ffsl_info').html(current.name+' - '+current.location);
            $("#ffsl").attr('readonly', true);
        });
            
        $('#fpartnum').on('keypress', function (e) {
            if(e.which === 13)
            {
                var fpartnum = $(this).val();
                get_parts(fpartnum);
            }
        });
        
        $('#btn_add').on('click', function (e) {       
            if(fqty === 0){
                alert("Please input quantity!");
                $("#fqty").focus();
            }else{
                add();
                $("#fpartname").html('Part Name');
            }
        });
        
        $('#btn_save').on('click', function (e) { 
            if($('#ffsl').val() === "" || $('#ffsl').val() === null){
                alert("Please input FSL Code!");
                $("#ffsl").focus();
            }else{
                save();
            }
        });
        
        function reload(){
            table.ajax.reload();
        }
        
        function get_parts(part_number){
            var url = '<?php echo base_url('front/tickets/get_part_name/'); ?>'+part_number;
            var type = 'GET';
    //        var data = 'fpartnum='+part_number+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';

            $.ajax({
                type: type,
                url: url,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
    //            data: data,
                success: function (jqXHR) {
                    $('#fpartname').html(jqXHR);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
                }
            });
        }
        
        function add(){
            var url = '<?php echo base_url('front/tickets/add_cart'); ?>';
            var type = 'POST';
            var fticket = $("#fticket").val();
            var fpartnum = $("#fpartnum").val();
            var fqty = $("#fqty").val();
            var data = 'fticket='+fticket+'&fpartnum='+fpartnum+'&fqty='+fqty+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';

            $.ajax({
                type: type,
                url: url,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: data,
                success: function (jqXHR) {
                    if(jqXHR.status == 1){
                        reload();
                        $("#fpartnum").val('');
                        $("#fqty").val('0');
                    }else if(jqXHR.status == 0){
                        alert("Message: "+jqXHR.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
                }
            });
        }
        
        function save(){
            var url = '<?php echo base_url('front/tickets/submit_ticket'); ?>';
            var type = 'POST';
            var ffsl = $("#ffsl").val();
            var fticket = $("#fticket").val();
            var data = 'ffsl='+ffsl+'&fticket='+fticket+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';

            $.ajax({
                type: type,
                url: url,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: data,
                success: function (jqXHR) {
                    if(jqXHR.status == 1){
                        window.location.href = jqXHR.redirect;
                    }else if(jqXHR.status == 0){
                        alert("Message: "+jqXHR.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
                }
            });
        }
        
        function delete_cart(fid){
            var url = '<?php echo base_url('front/tickets/delete_cart'); ?>';
            var type = 'POST';
            var data = 'fid='+fid+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';

            $.ajax({
                type: type,
                url: url,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: data,
                success: function (jqXHR) {
                    if(jqXHR.status == 1){
                        reload();
                    }else if(jqXHR.status == 0){
                        alert("Message: "+jqXHR.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('ERRORS: ' + textStatus + ' - ' + errorThrown );
                }
            });
        }

    }   

</script>