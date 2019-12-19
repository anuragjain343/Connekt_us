<footer class="main-footer">

    <div class="pull-right hidden-xs">
      
    </div>
    <strong>ConnektUs </strong>&copy; <?php echo date('Y') ?> 
     All rights reserved.
  </footer>

<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url().ADMIN_THEME; ?>bootstrap/js/bootstrap.min.js"></script>
<!-- Material Design -->
<script src="<?php echo base_url().ADMIN_THEME; ?>dist/js/material.min.js"></script>
<script src="<?php echo base_url().ADMIN_THEME; ?>dist/js/ripples.min.js"></script>
<script>
    $.material.init();
</script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<!-- <script src="<?php echo base_url().ADMIN_THEME; ?>plugins/morris/morris.min.js"></script> -->
<!-- Sparkline -->
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<!-- DataTables -->
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/bootbox/bootbox.min.js"></script>
<script src="<?php echo base_url().ADMIN_THEME; ?>plugins/tostar/toastr.min.js"></script>


<script src="<?php echo base_url().ADMIN_THEME; ?>dist/js/app.min.js"></script>
<div id="tl_admin_loader" class="tl_loader" style="display: none;"></div> <!-- Loader -->
<script type="text/javascript">
var base_url = '<?php echo base_url(); ?>'
</script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="<?php echo base_url().ADMIN_THEME; ?>dist/js/pages/dashboard.js"></script> -->
<!-- AdminLTE for demo purposes -->

<script src="<?php echo base_url().ADMIN_THEME; ?>dist/js/demo.js"></script>
<?php if(!empty($admin_scripts)) load_admin_js($admin_scripts); //load required admin page scripts ?>
<script src="<?php echo base_url().ADMIN_THEME; ?>custom/js/admin_common.js?v=41344"></script>
  <script type="text/javascript">
  $("#range_1").ionRangeSlider({
      min: 0,
      max: 5000,
      from: 1000,
      type: 'single',
      step: 1,
      prefix: "$",
      prettify: false,
      hasGrid: true
  });
</script>
</body>
</html>
