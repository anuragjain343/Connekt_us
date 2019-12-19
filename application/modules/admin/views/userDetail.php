  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('Users/allUser');?>">Users</a></li>
        <li class="active">User Detail</li>
      </ol>
      <button type="button" onclick="window.history.back();" class="btn bg-yellow btn-flat margin pull-right">Back</button>
    </section>


    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile m-t-40">

              <?php
              $url = base_url().DEFAULT_USER;
              if(!empty($detail->profileImage) && !empty($detail->socialId)) { 
                    $url = $detail->profileImage;
              }elseif(!empty($detail->profileImage) && empty($detail->socialId)){
                $url = base_url().USER_THUMB.$detail->profileImage;

              } ?>
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $url; ?>" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo display_placeholder_text(ucwords($detail->name)); ?></h3>

              <p class="text-muted text-center"><img src ="<?php echo star_color($detail->userPoints); ?>" alt="no-image" class="star">
              </p>

              <ul class="list-group list-group-unbordered">

                <li class="list-group-item">
                  <b>Points</b> <a class="pull-right"><?php echo $detail->userPoints; ?></a>
                </li>
                  <li class="list-group-item">
                  <b>Questions</b> <a class="pull-right"><?php echo $qCount; ?></a>
                </li>
                 <li class="list-group-item">
                  <b>Friends</b> <a class="pull-right"><?php echo $fCount; ?></a>
                </li>

              </ul>

              <!-- <a href="#" class="btn btn-primary btn-raised btn-block"><b>Follow</b></a> -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About User</h3>
            </div>
            <!-- /.box-header -->
             <div class="box-body">
             
            <div class="box-body">
              <strong><i class="fa fa-envelope margin-r-5"></i>Email</strong>
              <input type = "hidden" id="user_id" value="<?php echo $userId; ?>">
              <p class="text-muted">
               <?php echo display_placeholder_text($detail->email); ?>
              </p>
              <hr>

              <strong><i class="fa fa-sign-in margin-r-5"></i>loginType</strong>
              <p class="text-muted">
                <?php if(empty($detail->socialType)){ ?>
                    <span class="normalBtn">Normal</span>
               <?php } elseif($detail->socialType == 'facebook'){ ?>
                    <a class="btn btn-social-icon btn-facebook customBtn"><i class="fa fa-facebook"></i></a>
                <?php }else{ ?>
                    <span class="normalBtn">NA</span>;
                <?php } ?>

              </p>
              <hr>

              <strong><i class="fa fa-language margin-r-5"></i> language</strong>
              <p><?php echo $detail->language == 'ar' ? 'Arabic' : 'English'; ?></p>
              <hr>


              <strong><i class="fa fa-user margin-r-5"></i>Status</strong>
              <p class="text-muted">
               <?php 
                 $req = status_color($detail->status); 
                 $status = $detail->status ? 'Active' : 'Inactive'; ?>
                 <span style="color:<?php echo $req; ?>"><?php echo $status; ?></span>
              </p>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        </div>
        <!-- /.col -->
         <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">My Questions</a></li>
              <li><a href="#timeline" data-toggle="tab">My Friends</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
              
                  <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                        <table class="table table-striped" id="myQList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Question Title</th>
                            <th>Asking Type</th>
                            <th>Question Category</th>
                            <th style="width: 12%">Action</th>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>

                      </div>
                    </div>
                  <!-- /.tab-content -->
                  </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                    
                        <table class="table table-striped" id="friendList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Profile Image</th>
                            <th>Username</th>
                            <th style="width: 12%">Action</th>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>

                      </div>
                    </div>
                  <!-- /.tab-content -->
                  </div>

    
              </div>
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