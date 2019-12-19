<style type="text/css">
    .detail{
        padding-left: 18px;
    }
    .profile-user-img {

    width: 45%;
    height: 105px;
    
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
    
        Admin Profile
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Admin profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          <div class="box box-primary"> 
            <div class="box-body box-profile">
              <?php 
              $imgUrl = base_url().ADMIN_DEFAULT_IMAGE;
              if(!empty($admin->image)){
                  $imgUrl = base_url().ADMIN_IMAGE.$admin->image;
              } ?>
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgUrl; ?>" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo ucfirst($admin->name); ?></h3>

              <!-- <p class="text-muted text-center">Software Engineer</p> -->

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

         <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Me</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>

              <p class="text-muted detail"><?php echo $admin->email; ?></p>

              <hr>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <!-- <li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>
              <li><a href="#timeline" data-toggle="tab">Timeline</a></li> -->
              <li class="active"><a href="#settings" data-toggle="tab">Profile</a></li>
              <li><a href="#changePassword" data-toggle="tab">Change Password</a></li>
              <li><a href="#verify" data-toggle="tab">Settings</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="settings">
                <form class="form-horizontal" method="post" action="<?php echo base_url('admin/updateProfile') ?>">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="name" id="inputName" placeholder="Name" value="<?php echo $admin->name; ?>" >
                    </div>
                  </div>

                 <div class="form-group">
                    <label for="inputImg" class="col-sm-2 control-label">Image</label>

                    <div class="col-sm-10">
                      <input class="form-control" type="text" placeholder="Browse..." readonly="">
                      <input type="file" accept="image/*" onchange="document.getElementById('pImg').src = window.URL.createObjectURL(this.files[0])" name="image" id="inputSkills" class="input_img2">
                    </div>

                    <div class="ceo_file_error file_error text-danger" style="margin-left: 163px;"></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="button" class="btn btn-danger update_admin_profile">Update</button>
                    </div>
                  </div>
                </form>
              </div>

                
              <!-- /.tab-pane -->

                <div class="tab-pane" id="changePassword">
                <form class="form-horizontal" method="POST" action="<?php echo base_url('admin/changePassword') ?>">
                  <div class="form-group">
                    <label for="inputCpass" class="col-sm-2 control-label">Current Password</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="password" id="inputCpass" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputNewPass" class="col-sm-2 control-label">New Password</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="npassword" id="inputNewPass" >
                    </div>
                  </div>
                   <div class="form-group">
                    <label for="inputRPass" class="col-sm-2 control-label">Retype New Password</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="rnpassword" id="inputRPass" >
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="button" class="btn btn-danger change_password">Update</button>
                    </div>
                  </div>
                </form>
              </div> 

              <div class="tab-pane" id="verify">

                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputCpass" class="col-sm-2 control-label setname">Email verification</label>

                   <div class="col-sm-10">
                    <div class="togglebutton">
                      <label>
                      <input type="checkbox" name="verifytab" value ="<?php echo $option->option_value;?>" id="verify_email" <?php if($option->option_value == 1){echo "checked";}?> >
                      <span id="setMessage_verify" style="color:black;"></span>
                      </label>
                    </div>
                    </div>
                  </div>
            
                </form>
        
              </div>
              <!-- /.tab-pane -->



            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>