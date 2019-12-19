<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Send Notification<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url();?>admin/users/sendNotification">Send Notification</a></li>
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

            <form class="form-horizontal" id="sendNoti" method="POST" action="<?php echo base_url().'admin/users/sendNotificationToAll'?>"  autocomplete="off" >
                <div class="form-group">
                <label class="control-label col-sm-2" for="Title" >Type:</label>
                <div class="col-sm-10 selct_user">
                  <select id="inputState" class="form-control" name="type">
                    <option class="user_type_optn" value=" "> Select user type</option>
                    <option class="user_type_optn" value="individual"> Individual</option>
                    <option class="user_type_optn" value="business"> Business</option>
                    <option class="user_type_optn" value="both"> Both</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Title:</label>
                <div class="col-sm-10">
                  <input type="text"  name="title" class="form-control"  placeholder="Enter title">
               
                </div>

              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Description:</label>
                <div class="col-sm-10">
                  
                  <textarea class="form-control"  name="discription" rows="5" id="discription" placeholder="Description"></textarea>
                  <label id="titleerror" class="error" ></label>
                </div>
              </div>
             
              <div class="form-group mt-0">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-default send_btn" id="sendNoti">Send</button>
                </div>
              </div>
            </form>
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

  <div id="form-modal-box"></div>