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