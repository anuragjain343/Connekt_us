<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Specializations <?php echo '('. $count .')' ;?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Specializations</li>
      </ol>
      <button type="button" class="btn bg-yellow btn-flat margin pull-right" id = "addSpecialization">Add Specialization</button>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="pull-right col-md-3 noMargin">                 
            </div>
            <div class="pull-right div-select col-md-3 noMargin">  
            </div>
            <div class="box-body">
            <div class="table-responsive">
              <table id="specializationList" class="table table-bordered table-striped" width="100%">
                <thead>
                  <th>S.No.</th>
                  <th>Specialization Name</th>
                  <th>User Type</th>
                  <th>Status</th>
                  <th style="width: 15%">Action</th>
                </thead>
                <tbody>  
                </tbody>
                <tfoot>
                </tfoot>
              </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <div id="addSpecializationModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('admin/users/addSpecialization') ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Specialization</h4>
                </div>
                <div class="modal-body">
                    
                   <!--  <div class="alert alert-danger" id="error-box" style="display: none"></div> -->
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="specializationName" id="specializationName" placeholder="Specialization Name" maxlength="100"/>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-md-3 control-label">Specialization For</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="userType">
                                            <option value="" disabled selected>Select user type</option>
                                            <option value="business">Business</option>
                                            <option value="individual">Individual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="<?php echo THEME_BUTTON;?>" >Add</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div id="form-modal-box"></div>

<script type="text/javascript">
    $('#specializationName').keyup(function() {
        var $th = $(this);
        $th.val($th.val().replace(/(\s{2,})|[^a-zA-Z']/g, ' '));
        $th.val($th.val().replace(/^\s*/, ''));
    });
</script>
