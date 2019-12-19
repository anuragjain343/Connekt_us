  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Business Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('admin/users/allBusiness');?>">Business</a></li>
        <li class="active">Business Detail</li>
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
              <div class="prImgBlock">
              <div class="prImgBox">
                <img class="profile-user-img img-responsive img-circle" src="<?php echo $detail->profileImage; ?>" alt="User profile picture">
                <div class="logoImg">
                  <img class="profile-user-img img-responsive img-circle com_logo" src="<?php echo $detail->company_logo; ?>" alt="User profile picture">
                </div>
              </div>
              </div>

              <h3 class="profile-username text-center"><?php echo display_placeholder_text(ucwords($detail->fullName)); ?></h3>
            
              <p class="text-muted text-center"><?php echo display_placeholder_text(ucwords(wordwrap( $detail->businessName,10,"<br>\n"))); ?></p>
             
                <?php 
                    $total_rating = intval($detail->rating); 
                   
                ?>
                <p style="color:red;" class="text-center"><?php if($detail->isActive == 0){echo "Profile deactivated by user";}?></p>     
                <div class="reviewStar">
           <fieldset class="ratings-only-view text-center">
                <?php if(!empty($total_rating)){
                   $rating__input_value =  rating_show();
                    foreach($rating__input_value as $key => $valu){ 
                    $checked = get_checked($valu['input']['value'],$total_rating); 
                    ?>
                    <input type="radio"  id="upate_business<?php echo $valu['input']['id'].$detail->reviewId;?>" name="" value=" <?php echo $valu['input']['value'];?>" <?php echo $checked;?> disabled/>
                    <label class = "<?php echo $valu['label']['class'];?>" title=" <?php echo $valu['label']['title'];?>" for="upate_business<?php echo $valu['input']['id'].$detail->reviewId;?>"></label>
                <?php } }else{  
                    $rating__input_value =  rating_show();
                    foreach($rating__input_value as $key => $valu){ 
                    $checked = get_checked($valu['input']['value'],0); ?>
                    <input type="radio"  id="upate_business<?php echo $valu['input']['id'].$detail->reviewId;?>" name="" value=" <?php echo $valu['input']['value'];?>" <?php echo $checked;?> disabled/>
                    <label class = "<?php echo $valu['label']['class'];?>" title=" <?php echo $valu['label']['title'];?>" for="upate_business<?php echo $valu['input']['id'].$detail->reviewId;?>"></label>

                <?php } } ?>
           </fieldset>
             </div>

              <ul class="list-group list-group-unbordered">

                <li class="list-group-item">
                  <b>Favourites</b> <a class="pull-right cntR"><?php echo $detail->favourites; ?></a>
                </li>
                  <li class="list-group-item">
                  <b>Reviews</b> <a class="pull-right cntR"><?php echo $detail->reviews; ?></a>
                </li>
                 <li class="list-group-item">
                  <b>Recommends</b> <a class="pull-right cntR"><?php echo $detail->recommends; ?></a>
                </li>

                <li class="list-group-item">
                  <b>Premium Job Amount</b> <a class="pull-right cntR"><?php echo 0; ?></a>
                </li>
              </ul>
              <!-- 2: Add Premium job amount business user -->
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

              <strong><i class="fa fa-briefcase margin-r-5"></i>Job Title</strong>
              <p><?php echo display_placeholder_text($detail->jobTitleName); ?></p>
              <hr>

              <strong><i class="fa fa-certificate margin-r-5"></i>Area of specialization</strong>
              <p><?php echo display_placeholder_text($detail->specializationName); ?></p>
              <hr>

              <strong><i class="fa fa-envelope margin-r-5"></i>Email</strong>
              <input type = "hidden" id="user_id" value="<?php echo $detail->userId; ?>">
              <input type = "hidden" id="user_type" value="business">
              <p class="text-muted">
               <?php echo display_placeholder_text($detail->email); ?>
               <br>
               <?php if($detail->isVerified==0){ ?> 
               
                 <span class="label label-danger">Not Verified</span>
               <?php } else{?>
             
                <span class="label label-success">Verified</span>
               <?php } ?>
              </p>
              <hr>

              <strong><i class="fa fa-phone margin-r-5"></i>Phone</strong>
              <p><?php echo display_placeholder_text($detail->phone); ?></p>
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i>Address</strong>
              <p><?php echo display_placeholder_text($detail->address); ?></p>
              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i>About me</strong>
              <span style="overflow-wrap: break-word;"><br><?php 
              
              echo display_placeholder_text(emoji_decode($detail->bio)); ?></span>
              <hr> 


              <strong><i class="fa fa-copy  margin-r-5"></i>About <?php echo $detail->businessName;?></strong>
              <span style="overflow-wrap: break-word;"><br><?php 
              
              echo display_placeholder_text(emoji_decode($detail->description)); ?></span>
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
              <li class="active"><a href="#activity" data-toggle="tab">Favourites</a></li>
              <li><a href="#reviews" data-toggle="tab">Reviews</a></li>
              <li><a href="#recommends" data-toggle="tab">Recommends</a></li>
              <li><a href="#interviews" data-toggle="tab">Interview request sended</a></li>
              <li><a href="#jobs" data-toggle="tab">Jobs</a></li>
              <li><a href="#saveProfile" data-toggle="tab">Save Profiles</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
              
                  <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                        <table class="table table-striped" id="favList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Profile Image</th>
                            <th>Full Name</th>
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

             <!--  user review -->
              <div class="tab-pane" id="reviews">
                    <div class="box-left">
                        <div class="tab-content ">
                            <div class="">
                                <div id="reviewList">
                                </div>
                                 <center><div class="scroll_loader load-wdth" style="display:none">
                                    <img height="250" width="250" src="<?php echo base_url().ADMIN_THEME.'custom/images/show_loading.gif';?>" >
                                </div></center>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
              </div>

              <div class="tab-pane" id="recommends">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                    
                        <table class="table table-striped" id="recommendList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Profile Image</th>
                            <th>Full Name</th>
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

              <div class="tab-pane" id="interviews">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                    
                        <table class="table table-striped" id="interview_List" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Request to</th>
                            <th>Interview Date/Time</th>
                            <th>Interview location</th>
                            <th>interviewer</th>
                            <th>Status</th>
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

               <div class="tab-pane" id="jobs">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                         <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 cstm_select2">
                              <div class="form-group">
                                <div style="display:flex;align-items:center;">
                                      <label for="exampleFormControlSelect1">Type: </label>
                                      <select class="form-control" id="gig-status" style="font-size: 15px; margin-left:13px; ">
                                        <option value="0"> Select Job Type</option>
                                        <option value="1"> Basic</option>
                                        <option value="2"> Premium</option>
                                      </select>
                                  </div>
                                </div>
                              </div>
                          </div> 
                        <table class="table table-striped" id="jobs_List" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <!-- <th>S.no</th> -->
                            <th>Title</th>
                            <th>Area of specialty</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
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
              
                  <div class="tab-pane" id="saveProfile">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                    
                        <table class="table table-striped" id="saveProfileList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead><input type="hidden" name="user_id" id="profileId" value="<?php echo $detail->userId;?>">
                      
                            <th>Profile Image</th>
                            <th>Full Name</th>
                            <th>Status</th>
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

