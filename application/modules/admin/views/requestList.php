<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Request List (<?php echo $count;?>)
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li>Requests List</li>
      </ol>
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
              <table id="interviewList" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>
                  <th>S.No.</th>
                  <th>Request By</th>
                  <th>Request For</th>
                  <th>Interview Date</th>
                  <th>Interviewer</th>
                  
                  <th>Interview status</th>
                  <th style="width: 5%">Action</th>
                </tr>
                </thead>
                <tfoot>
                </tfoot>
              </table>
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