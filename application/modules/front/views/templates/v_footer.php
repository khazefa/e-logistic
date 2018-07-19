            </div> <!-- end container -->
        </div>
        <!-- end wrapper -->

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        2018 © Diebold Nixdorf Indonesia - Service Division
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->
    
        <!-- Modal Request Confirmation -->
        <div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
              </div>
              <div class="modal-body">
                <h4>Process transaction?</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal" data-toggle="modal" data-target="#add_request">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal Request Confirmation -->
        
        <!-- Modal Global Confirmation -->
        <div class="modal fade" id="global_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
              </div>
              <div class="modal-body">
                <h4></h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal" id="ans_yess">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="ans_no">No</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal Global Confirmation -->

        <!-- Modal Add Request -->
        <div class="modal fade" id="add_request" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Message</h4>
              </div>
              <div class="modal-body">
                <h4>Do you want to make other <strong>transaction</strong>?</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal" id="opt_yess">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="opt_no">No</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal Add Request -->
        
        <!-- Modal Error -->
        <div class="modal fade" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
              </div>
              <div class="modal-body">
                <h4></h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Ok</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal Error -->

        <!-- Required datatable js -->
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/jszip.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/pdfmake.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/vfs_fonts.js"></script>
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/buttons.html5.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/buttons.print.min.js"></script>

        <!-- Key Tables -->
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/dataTables.keyTable.min.js"></script>

        <!-- Responsive examples -->
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/responsive.bootstrap4.min.js"></script>

        <!-- Selection table -->
        <script src="<?php echo base_url();?>assets/public/plugins/datatables/dataTables.select.min.js"></script>
        
        <!-- Parsley js -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/public/plugins/parsleyjs/parsley.min.js"></script>
        
        <!-- Plugin js -->
        <script src="<?php echo base_url();?>assets/public/plugins/bootstrap-select/js/bootstrap-select.js" type="text/javascript"></script>
        
        <!-- App js -->
        <script src="<?php echo base_url();?>assets/public/js/jquery.core.js"></script>
        <script src="<?php echo base_url();?>assets/public/js/jquery.app.js"></script>

        <script type="text/javascript">
            function isEmpty(val){
                return (val === undefined || val == null || val.length <= 0) ? true : false;
            }
            $(document).ready(function() {
                $('form').parsley();
            });
        </script>
        
    </body>
</html>