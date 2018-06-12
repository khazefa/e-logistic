            </div><!-- .animated -->
        </div><!-- .content -->


    </div><!-- /#right-panel -->

    <!-- Right Panel -->


    <script src="<?php echo base_url();?>assets/public/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/popper.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/plugins.js"></script>
    
    <!--<script src="<?php echo base_url();?>assets/public/js/main.js"></script>-->
    <!--<script src="<?php echo base_url();?>assets/public/js/lib/chosen/chosen.jquery.min.js"></script>-->
    
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/datatables.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/buttons.bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/pdfmake.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/vfs_fonts.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/buttons.html5.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/buttons.print.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/buttons.colVis.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/lib/data-table/datatables-init.js"></script>
    
    <script src="<?php echo base_url();?>assets/public/js/lib/bootstrap3-typeahead/bootstrap3-typeahead.min.js"></script>

    <script type="text/javascript"> 
        function check_empty(e) {
            switch (e) {
                case "":
                case 0:
                case "0":
                case null:
                case false:
                case typeof this == "undefined":
                  return true;
                default:
                  return false;
            }
        }
        
        $(document).ready(function() {
            "use strict";

            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
                    new SelectFx(el);
            } );

            jQuery('.selectpicker').selectpicker;


            $('#menuToggle').on('click', function(event) {
                    $('body').toggleClass('open');
            });

            $('.search-trigger').on('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $('.search-trigger').parent('.header-left').addClass('open');
            });

            $('.search-close').on('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $('.search-trigger').parent('.header-left').removeClass('open');
            });
            
            /**
            $(".standardSelect").chosen({
                disable_search_threshold: 3,
                no_results_text: "Oops, nothing found!",
                width: "100%"
            });
            **/
            
            logic_for_tickets();
            
//            get_parts("00050778000E");
        });
    </script>


</body>
</html>