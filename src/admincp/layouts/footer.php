        <script type="text/javascript" src="<?= $Sezar_Theme_Link ?>\js\jquery.min.js"></script>
        <script type="text/javascript" src="<?= $Sezar_Theme_Link ?>\bootstrap-rtl-master\dist\js\bootstrap-rtl.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\metisMenu.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\jquery.nanoscroller.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\jquery-jvectormap-1.2.2.min.js"></script>
        <!-- Flot -->
        <script src="<?= $Sezar_Theme_Link ?>\js\flot\jquery.flot.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\flot\jquery.flot.tooltip.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\flot\jquery.flot.resize.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\flot\jquery.flot.pie.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\chartjs\Chart.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\pace.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\waves.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\morris_chart\raphael-2.1.0.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\morris_chart\morris.js"></script>

        <script src="<?= $Sezar_Theme_Link ?>\js\jquery-jvectormap-world-mill-en.js"></script>
        <!--        <script src="<?= $Sezar_Theme_Link ?>\js/jquery.nanoscroller.min.js"></script>-->
        <script type="text/javascript" src="<?= $Sezar_Theme_Link ?>\js\custom.js"></script>
        <script type="text/javascript" src="<?= $Sezar_Theme_Link ?>\js\summernote\summernote.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\sweet-alert\sweetalert.min.js"></script>

        <script src="<?= $Sezar_Theme_Link ?>\js\data-tables\jquery.dataTables.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\data-tables\dataTables.tableTools.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\data-tables\dataTables.bootstrap.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\data-tables\dataTables.responsive.js"></script>
        
        <!-- ChartJS -->
        <script src="<?= $Sezar_Theme_Link ?>\js\chartjs\Chart.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\slider\bootstrap-slider.min.js"></script>

        <!-- Persian DateTime Picker -->
        <script src="<?= $Sezar_Theme_Link ?>\js\moment-with-locales.min.js"></script>
        <script src="<?= $Sezar_Theme_Link ?>\js\bootstrap-persian-datetimepicker.js"></script>

        <!-- David Refoua Codes -->
        <script src="<?= $Sezar_Theme_Link ?>\js\sezar-js-v1.js"></script>
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>\js\blockui.min.js"></script>
        <script>
            $(document).ready(function () {

                $('.summernote').summernote();

            });
            var edit = function () {
                $('.click2edit').summernote({focus: true});
            };
            var save = function () {
                var aHTML = $('.click2edit').code(); //save HTML If you need(aHTML: array).
                $('.click2edit').destroy();
            };

        </script>

        <script>
            function showProducts ( type ) {
                var type = type.trim().toLowerCase();
                //$('[name=category]').val(type);
                $('section[data-type]').each(function() {
                    var attr = (' ' + $(this).attr('data-type').toLowerCase() + ' ').split(' ');
                    if ( attr.indexOf('all') >= 0 || attr.indexOf(type) >= 0 ) 
                        $(this).fadeIn();
                    else $(this).fadeOut();
                });
            }
            
            //$(function() {
                $('[name=category]').on('change', function() {
                    //alert( $(this).val());
                    showProducts( $(this).val() );
                }).trigger('change');
            //});
        </script>

        <script>
            //------------- tables-data.js -------------//
            $(document).ready(function() {

                
                //------------- Data tables -------------//
                //basic datatables
                $('[id^=basic-datatables]').dataTable({
                    "oLanguage": {
                        "sSearch": "",
                        "sLengthMenu": "<span>_MENU_</span>"
                    },
                    "sDom": "<'row'<'col-md-6 col-xs-12 'l><'col-md-6 col-xs-12'f>r>t<'row'<'col-md-4 col-xs-12'i><'col-md-8 col-xs-12'p>>"
                });

                //vertical scroll
                $('#vertical-scroll-datatables').dataTable( {
                    "scrollY":        "200px",
                    "scrollCollapse": true,
                    "paging":         false
                });

                //responsive datatables
                $('#responsive-datatables').dataTable({
                    "oLanguage": {
                        "sSearch": "",
                        "sLengthMenu": "<span>_MENU_</span>"
                    },
                    "sDom": "<'row'<'col-md-6 col-xs-12 'l><'col-md-6 col-xs-12'f>r>t<'row'<'col-md-4 col-xs-12'i><'col-md-8 col-xs-12'p>>"
                });

                //with tabletools
                $('#tabletools').DataTable( {
                    "oLanguage": {
                        "sSearch": "",
                        "sLengthMenu": "<span>_MENU_</span>"
                    },
                    "sDom": "T<'row'<'col-md-6 col-xs-12 'l><'col-md-6 col-xs-12'f>r>t<'row'<'col-md-4 col-xs-12'i><'col-md-8 col-xs-12'p>>",
                    tableTools: {
                        "sSwfPath": "https://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
                        "aButtons": [ 
                        "copy", 
                        "csv", 
                        "xls",
                        "print",
                        "select_all", 
                        "select_none" 
                    ]
                    }
                });
                
            });
        </script>
		
		
		<!--Modules JavaScript Codes-->
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>/js/SGBS/app.js"></script>
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>/js/SGBS/uniform.min.js"></script>
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>/js/SGBS/select.js"></script>
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>/js/SGBS/ckeditor.js"></script>
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>/js/SGBS/jquery.maskedinput.js"></script>
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>/js/SGBS/bootstrap_multiselect.js"></script>
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>/js/SGBS/form_multiselect.js"></script>
		<script src="<?= $Sezar_Theme_Link ?>/js/SGBS/base.js" type="text/javascript"></script>
		<script src="<?= $Sezar_Theme_Link ?>/js/SGBS/utility.js" type="text/javascript"></script>
		<script src="<?= $Sezar_Theme_Link ?>/js/SGBS/persiandate.js"></script>
		<script src="<?= $Sezar_Theme_Link ?>/js/SGBS/persian-datepicker.js"></script>
		<script type="text/javascript" src="<?= $Sezar_Theme_Link ?>/js/SGBS/date-c.js"></script>
		<script>
		   // File input
		   $(".file-styled").uniform({
		   fileButtonHtml: '<i class="icon-googleplus5"></i>',
		   wrapperClass: 'bg-primary'
		   });
		   
		   
		   
		   $(".styled, .multiselect-container input").uniform({
		   radioClass: 'choice'
		   });
		   
		   
		</script>
		<script type="text/javascript">
			$NXT_LIST_SETTINGS = {
				duplicate_buttons: false,
				duplicate_navigation: false,
				row_effects: false,
				show_as_buttons: false,
				record_counter: false
			}
		</script>
		<script language="javascript">
			jQuery( function ( $ ) {
				$( "#created_at_bet" ).mask( "9999/99/99" );
			} );
		</script>
    </body>
</html>
