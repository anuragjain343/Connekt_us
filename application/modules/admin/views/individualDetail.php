  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Individual Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('admin/users/allIndividual');?>">Individuals</a></li>
        <li class="active">Individual Detail</li>
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
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $detail['info']->profileImage; ?>" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo display_placeholder_text(ucwords($detail['info']->fullName)); ?></h3>
               <p style="color:red;" class="text-center"><?php if($detail['info']->isActive == 0){echo "Profile deactivated by user";}?></p>  
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

              <strong><i class="fa fa-certificate margin-r-5"></i>Area of specialization</strong>
              <p><?php echo display_placeholder_text($detail['info']->specializationName); ?></p>
              <hr>

              <strong><i class="fa fa-rss margin-r-5"></i>Strengths</strong>
              <?php $color = ['danger','success','info','warning','primary','success','info','danger','primary','warning','danger','success','info','primary','warning']; ?>
              <p>
                <?php if(!empty($detail['info']->strength)) {
                  foreach ($detail['info']->strength as $key => $value) { ?>
                    <span class="label label-<?php echo $color[$key]; ?>"><?php echo $value; ?></span>
                      <?php }
                  }else{
                    echo 'NA';
                  }  ?>
              </p>
              <hr>

               <strong><i class="fa fa-sitemap valyes margin-r-5"></i>Values</strong>
              <?php $colors = ['info','warning','primary','success','info','danger','primary','warning','danger','success','info','primary','warning','success','info']; ?>
              <p>
              <?php if(!empty($detail['info']->value)) {
                      foreach ($detail['info']->value as $k => $v) { ?>
                        <span class="label label-<?php echo $colors[$k]; ?>"><?php echo $v; ?></span>
                      <?php } 
                    }else{
                      echo 'NA';
                    }  ?>
              </p>
              <hr>

              <strong><i class="fa fa-envelope margin-r-5"></i>Email</strong>
              <input type = "hidden" id="user_id" value="<?php echo $detail['info']->userId; ?>">
              <input type = "hidden" id="user_type" value="Individual">
              <p class="text-muted">
               <?php echo display_placeholder_text($detail['info']->email); ?>
                 <br>
               <?php if($detail['info']->isVerified==0){ ?> 
               
                 <span class="label label-danger">Not Verified</span>
               <?php } else{?>
             
                <span class="label label-success">Verified</span>
               <?php } ?>
              </p>
              <hr>

               <strong><i class="fa fa-phone margin-r-5"></i>Phone</strong>
              <p><?php echo display_placeholder_text($detail['info']->phone); ?></p>
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i>Address</strong>
              <p><?php echo display_placeholder_text($detail['info']->address); ?></p>
              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i>About me</strong><br>
              <p style="overflow-wrap: break-word;"><?php echo display_placeholder_text(emoji_decode($detail['info']->bio)); ?></p>
              <hr>

              <strong><i class="fa fa-user margin-r-5"></i>Status</strong>
              <p class="text-muted">
               <?php 
                 $req = status_color($detail['info']->status); 
                 $status = $detail['info']->status ? 'Active' : 'Inactive'; ?>
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
              <li><a href="#experience" data-toggle="tab">Experience</a></li>
              <li><a href="#resume" data-toggle="tab">Resume</a></li>
              <li><a href="#interviews" data-toggle="tab">Interview Requests</a></li>
              <li><a href="#appliedJob" data-toggle="tab">Applied Job</a></li>
              <li><a href="#saveProfile" data-toggle="tab">Saved Profiles</a></li>
              <li><a href="#savedJob" data-toggle="tab">Saved Jobs</a></li>
            </ul>
            <div class="tab-content">

              <div class="tab-pane" id="interviews">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                    
                        <table class="table table-striped" id="interview_List" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Request by</th>
                            <th>Interview Date/Time</th>
                            <th>Interview location</th>
                            <th>interviewer</th>
                            <th>status</th>
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

                                <center><div class="scroll_loader load-wdth" style="display:none">
                                    <img height="250" width="250" src="<?php echo base_url().ADMIN_THEME.'custom/images/show_loading.gif';?>" >
                                </div></center>
                                <div id="reviewList">
                                </div>
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
               <div class="tab-pane" id="appliedJob">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                         <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 cstm_select">
                              <div class="form-group">
                                <div style="display:flex;align-items:center;">
                                      <label for="exampleFormControlSelect1">Type: </label>
                                      <select class="form-control" id="gig-status1" style="font-size: 15px; margin-left:13px; ">
                                        <option value="0"> Select Job Type</option>
                                        <option value="1"> Basic</option>
                                        <option value="2"> Premium</option>
                                      </select>
                                  </div>
                                </div>
                              </div>
                          </div> 
                        <table class="table table-striped" id="appliedJobList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                          
                            <th>Title</th>
                            <th>Industry</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Job Status</th>
                            <th style="width: 12%">Action</th>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>

                      </div>
                    </div>
                
                  </div>
              </div>


               <div class="tab-pane" id="experience">
                    <div class="box-left">
                    <div class="tab-content ">
                        <?php if(!empty($detail['experience'])){ ?>
                        <!-- Current job role start-->
                        <div class="box box-default collapsed-box box-solid">
                          <div class="box-header with-border">
                              <h3 class="box-title ctil">Current Job Role</h3>
                              <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                              </div>
                          </div>
                          <?php if(empty($detail['experience']->current_job_title) && empty($detail['experience']->current_company) && empty($detail['experience']->current_start_date) && empty($detail['experience']->current_finish_date) && empty($detail['experience']->current_description)){ ?>
                            <div class="box-body">No current role found</div>
                            <?php }else{ ?>
                          <div class="box-body jobBox">
                            <div class="form-group">
                              <label class="col-md-3 control-label">Job Title</label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control" value="<?php echo display_placeholder_text($detail['experience']->current_job_title); ?>" readonly/>
                              </div>
                            </div>
                                 
                            <div class="form-group">
                              <label class="col-md-3 control-label">Company Name</label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control" value="<?php echo display_placeholder_text($detail['experience']->current_company); ?>"
                                  readonly/>
                              </div>
                            </div>
                               
                            
                                <div class="form-group">
                                  <label class="col-md-3 control-label">Experience</label>
                                  <div class="col-md-9">
                                      <input type="text" class="form-control" value="<?php echo display_placeholder_text(floatval($detail['experience']->currentExperience)); ?> <?php if(!empty(floatval($detail['experience']->currentExperience)) AND floatval($detail['experience']->currentExperience) > 1){?> Years<?php }?><?php if(!empty(floatval($detail['experience']->currentExperience)) AND floatval($detail['experience']->currentExperience) <= 1){?> Year<?php }?>" readonly/>
                                  </div>
                            </div>  

                            <div class="form-group">
                                  <label class="col-md-3 control-label">Total Experience</label>
                                  <div class="col-md-9">
                                      <input type="text" class="form-control" value="<?php echo display_placeholder_text(floatval($detail['experience']->totalExperience)); ?> <?php if(!empty(floatval($detail['experience']->totalExperience)) AND floatval($detail['experience']->totalExperience) > 1){?> Years<?php }?><?php if(!empty(floatval($detail['experience']->totalExperience)) AND floatval($detail['experience']->totalExperience) <= 1){?> Year<?php }?>" readonly/>
                                  </div>
                            </div> 

                            <div class="form-group">
                              <label class="col-md-3 control-label">Description</label>
                              <div class="col-md-9">
                                <textarea class="form-control" rows="3" readonly><?php echo display_placeholder_text(emoji_decode($detail['experience']->current_description)); ?></textarea>
                                  <!-- <input type="text" class="form-control" value="<?php// echo display_placeholder_text($detail['experience']->current_description); ?>" 
                                  readonly/> -->
                              </div>
                            </div> 
                              
                          </div>
                          <div class="box-footer clk"></div>
                          <?php } ?>
                        </div>
                         <!-- Current job role end-->

                        <!-- previous job role -->
                        <div class="box box-default collapsed-box box-solid">
                        <div class="box-header with-border">
                          <h3 class="box-title ctil">Previous Job Role</h3>
                          <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div>

                        <?php if(!empty($detail['privous'])) { 

                        foreach ($detail['privous'] as $prev) {  
                        ?>
                        <div class="box-body jobBox">
                          <div class="form-group">
                            <label class="col-md-3 control-label">Job Title</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo display_placeholder_text($prev->previous_job_title); ?>" readonly/>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label">Company Name</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo display_placeholder_text($prev->previousCompanyName); ?>" readonly/>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label">Experience</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo display_placeholder_text(floatval($prev->experience)); ?><?php if(!empty(floatval($prev->experience)) AND floatval($prev->experience) > 1){?> Years<?php }?><?php if(!empty(floatval($prev->experience)) AND floatval($prev->experience) <= 1){?> Year<?php }?>" readonly/>
                            </div>
                          </div>

                           <div class="form-group">
                            <label class="col-md-3 control-label">Description</label>
                            <div class="col-md-9">
                            <textarea class="form-control" rows="3" readonly><?php echo display_placeholder_text(emoji_decode($prev->previousDescription)); ?></textarea>
                            <!-- <input type="text" class="form-control" value="<?php //echo display_placeholder_text($prev['previous_description']); ?>" readonly/> -->
                            </div>
                          </div>  
                        </div>
                        <!-- <div class="box-footer clk"></div> -->

                        <?php }}else{ ?>
                         <div class="box-body">No previous job role found</div>
                         <?php  } ?>

                        </div>
                        <div class="box box-default collapsed-box box-solid">
                        <div class="box-header with-border">
                          <h3 class="box-title ctil">Next Job Role</h3>
                          <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div>
                        <?php if(empty($detail['experience']->next_speciality) && empty($detail['experience']->next_availability) && empty($detail['experience']->next_location)){ ?>
                       <div class="box-body">No next role found</div>
                         <?php }else{ ?>
                        <div class="box-body jobBox">
                          <div class="form-group">
                            <label class="col-md-3 control-label">speciality</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo display_placeholder_text($detail['experience']->next_speciality); ?>" readonly/>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label">availability</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo display_placeholder_text($detail['experience']->next_availability); ?>" readonly/>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="col-md-3 control-label">Location</label>
                                <div class="col-md-9">
                                <textarea class="form-control" rows="3" readonly><?php echo display_placeholder_text($detail['experience']->next_location); ?></textarea>
                            
                               <!--  <input type="text" class="form-control" value="<?php //echo display_placeholder_text($detail['experience']->next_location); ?>" readonly/> -->
                              </div>
                              </div>
                            </div>

                            <div class="col-md-6">
                              
                            </div> 
                          </div>  


                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="col-md-3 control-label">Expected Salary</label>
                                <div class="col-md-9">
                                  <?php 
                                  if($detail['experience']->expectedSalaryFrom != 'any' AND $detail['experience']->expectedSalaryFrom != '150000')
                                      {
                                      $salary = '$'.$detail['experience']->expectedSalaryFrom.'-'.'$'.$detail['experience']->expectedSalaryTo; 
                                      }elseif($detail['experience']->expectedSalaryFrom != 'any' AND $detail['experience']->expectedSalaryFrom == '150000'){ 
                                         $salary = '$150,000+';
                                        }else{
                                          $salary = 'Any';
                                        }
                                      ?>
                               <input type="text" class="form-control" value="<?php echo display_placeholder_text($salary); ?>" readonly>
                            
                               <!--  <input type="text" class="form-control" value="<?php //echo display_placeholder_text($detail['experience']->next_location); ?>" readonly/> -->
                              </div>
                              </div>
                            </div>

                            <div class="col-md-6">
                              
                            </div> 
                          </div>  


                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="col-md-3 control-label">Employement Type</label>
                                <div class="col-md-9">
                                <input type="text" class="form-control" value="<?php echo display_placeholder_text($detail['experience']->employementType); ?>" readonly>
                            
                               <!--  <input type="text" class="form-control" value="<?php //echo display_placeholder_text($detail['experience']->next_location); ?>" readonly/> -->
                              </div>
                              </div>
                            </div>

                            <div class="col-md-6">
                              
                            </div> 
                          </div>  
                        </div>
                        <div class="box-footer"></div>
                        <?php } ?>

                        </div>
                        <?php }else{ ?>

                            <div class="" style="display: block;">No experience found</div>
                         <?php  } ?>
                    </div>
                  <!-- /.tab-content -->
                  </div>
              </div>
              <!--  end experience-->

               <div class="tab-pane" id="resume">
                    <div class="box-left">
                    <div class="tab-content ">
                    <div class = "row">
                      <div class="col-md-6">
                        <div class="box box-solid">
                          <div class="box-header with-border">
                            <h3 class="box-title">Resume</h3>
                          </div>   
                          <div class="box-body">
                          <?php if(!empty($detail['info']->resume)){ ?>
                           <a href="<?php echo $detail['info']->resume;?>" target="blank"><img src="<?php echo base_url().ADMIN_THEME.'custom/images/resume.png';?>" style="height: 80px;width:80px;" download></a>
                           <?php }else{ ?>
                           No resume found
                           <?php } ?>
                          </div>       
                       </div>
                     </div>
                     <div class="col-md-6">
                       <div class="box box-solid">
                        <div class="box-header with-border">
                          <h3 class="box-title">CV</h3>
                        </div>   
                        <div class="box-body">
                        <?php if(!empty($detail['info']->cv)){ ?>

                         <a href="<?php echo $detail['info']->cv;?>" target="blank"><img src="<?php echo base_url().ADMIN_THEME.'custom/images/cv.png';?>" style="height: 80px;width:80px;" download></a>
                         <?php } else{ ?>
                          No cv found
                         <?php } ?>
                        </div>       
                     </div>
                     </div>



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
                          <thead><input type="hidden" name="user_id" id="profileId" value="<?php echo $detail['info']->userId;?>">
                      
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

          <div class="tab-pane" id="savedJob">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                          <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 cstm_select">
                              <div class="form-group">
                                <div style="display:flex;align-items:center;">
                                      <label for="exampleFormControlSelect1">Type: </label>
                                      <select class="form-control" id="gig-status12" style="font-size: 15px; margin-left:13px;">
                                        <option value="0"> Select Job Type</option>
                                        <option value="1"> Basic</option>
                                        <option value="2"> Premium</option>
                                      </select>
                                  </div>
                                </div>
                              </div>
                          </div> 
                        <table class="table table-striped" id="savedJob_List" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                          
                            <th>Title</th>
                            <th>Industry</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Job Status</th>
                            <th style="width: 12%">Action</th>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>

                      </div>
                    </div>
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

