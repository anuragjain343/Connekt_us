<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title  .'('. $count .')'; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $title; ?></li>
      </ol>
       <?php if($this->session->flashdata('success') != null) : ?>  <!-- for Delete -->
               
                <div class="">
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>  <i class="icon fa fa-check"></i> Success!</h4>
                        <?php  echo $this->session->flashdata('success');?>
                    </div>
                </div><!-- /.box-body -->
                 <script>
              setTimeout(function() {
              $('.alert-success').fadeOut('fast');
              }, 1000);
              </script>
      <?php endif; ?>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="pull-right col-md-3 noMargin">                   
            </div>
            <div class="pull-right div-select col-md-3 noMargin">  
              <input type = "hidden" id="userType" value="<?php echo $userType; ?>">
            </div>
            <div class="box-body">
              <div class="table-responsive">
              <table id="userList" class="table table-bordered table-striped" width="100%">
                <thead>
                  <th>S.No.</th>
                  <th>Image</th>
                  <?php if($userType == 'business'){ ?>
                      <th> Business Name </th>
                   <?php } ?>
                  <th>Full Name</th>
                  <th>Email</th>
                  <?php if($userType == 'individual'){ ?>

                    <th>Value</th>
                    <th>Strength</th>
                  <?php  } ?>
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
  
<div id="form-modal-box"></div>
 