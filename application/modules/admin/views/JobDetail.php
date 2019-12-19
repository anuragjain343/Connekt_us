
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Job Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('admin/jobs');?>">Job</a></li>
        <li class="active">Job Detail</li>
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
                <img class="profile-user-img img-responsive img-circle" src="<?php echo $detail->company_logo; ?>" alt="User profile picture">
             <!--    <div class="logoImg">
                  <img class="profile-user-img img-responsive img-circle com_logo" src="<?php echo $detail->company_logo; ?>" alt="User profile picture">
                </div> -->
              </div>
              </div>

              <h3 class="profile-username text-center"><?php echo display_placeholder_text(ucwords($detail->businessName)); ?></h3>
            
              <p class="text-muted text-center"><?php echo display_placeholder_text($detail->job_location); ?></p>
             
                <?php 
                    $total_rating = 0; 
      
                ?>
              <!--   <p style="color:red;" class="text-center"></p>  -->    
             <!--    <div class="reviewStar">
           <fieldset class="ratings-only-view text-center">
            
                    <input type="radio"  id="upate_business" name="" value="">

                    <label class = "" title=" " for="upate_business"></label>
          
           </fieldset>
             </div> -->

              <ul class="list-group list-group-unbordered">

                <li class="list-group-item">
                  <b>Rating</b> <a class="pull-right cntR"><?php echo '5'; ?></a>
                </li>
                  <li class="list-group-item">
                  <b>Reviews</b> <a class="pull-right cntR"><?php echo '4'; ?></a>
                </li>
                 <li class="list-group-item">
                  <b>Views</b> <a class="pull-right cntR"><?php echo '14'; ?></a>
                </li>
              </ul>

              <!-- <a href="#" class="btn btn-primary btn-raised btn-block"><b>Follow</b></a> -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <!-- <div class="box-header with-border">
              <h3 class="box-title">About Job</h3>
            </div> -->
            <!-- /.box-header -->
          <!--    <div class="box-body">
             
            <div class="box-body">

              <strong><i class="fa fa-briefcase margin-r-5"></i>Job Title</strong>
              <p><?php echo display_placeholder_text($detail->jobTitleName); ?></p>
              <hr>

               <strong><i class="fa fa-pie-chart margin-r-5"></i>Job Type</strong>
               <?php if($detail->job_type=='1') { $jobType = 'Basic'; } else{ $jobType ='Premium'; } ?>
              <p><?php echo display_placeholder_text($jobType); ?></p>
              <hr>

              <strong><i class="fa fa-certificate margin-r-5"></i>Area of specialization</strong>
              <p><?php echo display_placeholder_text($detail->industry); ?></p>
              <hr>

              <strong><i class="fa fa-money margin-r-5"></i>Salary From</strong>
              <p class="text-muted">
               <?php echo display_placeholder_text($detail->salary_from); ?>
              
              </p>
               <strong><i class="fa fa-money margin-r-5"></i>Salary To</strong>
              <p class="text-muted">
               <?php echo display_placeholder_text($detail->salary_to); ?>
              
              </p>
              <hr>

              <strong><i class="fa fa-phone margin-r-5"></i>Employment Type</strong>
              <?php if($detail->employment_type == '1'){  $type ='Full Time'; } else { $type = 'Part Time';
            } ?>
              <p><?php echo display_placeholder_text($type);?></p>
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i>Address</strong>
              <p><?php echo display_placeholder_text($detail->job_location); ?></p>
              <hr>

              <strong><i class="fa fa-user margin-r-5"></i>Status</strong>
              <p class="text-muted">
               <?php 
                 $req = status_color($detail->status); 
                 $status = $detail->status ? 'Active' : 'Inactive'; ?>
                 <span style="color:<?php echo $req; ?>"><?php echo $status; ?></span>
              </p>

            </div>
           
          </div> -->
          
        </div>
        </div>
        <!-- /.col -->
         <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Basic Detail</a></li>
               <li><a href="#applicants" data-toggle="tab">Applicants List</a></li>
               <li><a href="#shortlisted" data-toggle="tab">Shortlisted</a></li>
               <li><a href="#recommends" data-toggle="tab">Job Views List</a></li>
              <!--<li><a href="#interviews" data-toggle="tab">Interview request sended</a></li>
              <li><a href="#jobs" data-toggle="tab">Jobs</a></li>  -->
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                  <div class="box-left">
                    <div class="tab-content jobDetails">
                      <div class="row">
                          <div class="col-sm-6">
                          <div class="post">
                            <span class="username">
                            <a href="#"><b>Job Id</b></a>  
                            </span>
                            <p>
                            <?php echo $detail->jobId; ?>
                           </p>
                          </div> 
                        </div>
                          <div class="col-sm-6">
                          <div class="post">
                            <span class="username">
                            <a href="#"><b>Business Name</b></a>  
                            </span>
                            <p>
                            <?php echo $detail->businessName; ?>
                           </p>
                          </div> 
                        </div>
                        <div class="col-sm-6">
                          <div class="post">
                            <span class="username">
                            <a href="#"><b>Job Title</b></a>  
                            </span>
                            <p>
                            <?php echo $detail->jobTitleName; ?>
                           </p>
                          </div> 
                        </div>
                        <div class="col-sm-6">
                          <div class="post">
                            <span class="username">
                            <a href="#"><b>Job Type</b></a>  
                            </span>
                            <p>
                            <?php echo $jobType; ?>
                           </p>
                          </div> 
                        </div>
                        <div class="col-sm-6">
                          <div class="post">
                            <span class="username">
                            <a href="#"><b>Salary</b></a>  
                            </span>
                            <p>
                            <?php echo $detail->salary_to; ?>
                           </p>
                          </div>
                        </div>
                       <!--  <div class="col-sm-6">
                          <div class="post">
                            <span class="username">
                            <a href="#"><b>Salary To</b></a>  
                            </span>
                            <p>
                            <?php echo $detail->salary_to; ?>
                           </p>
                          </div>
                        </div> -->
                        <div class="col-sm-6">
                          <div class="post">
                            <span class="username">
                            <a href="#"><b>Employment Type</b></a>  
                            </span>
                            <p>
                              <?php if($detail->employment_type == '1'){  $type ='Full Time'; } else { $type = 'Part Time';
                                 } ?>
                            <?php echo $type; ?>

                           </p>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="post">
                            <span class="username">
                            <a href="#"><b>Status</b></a>  
                            </span>
                            <p>
                             <?php 
                            $req = status_color($detail->status); 
                            $status = $detail->status ? 'Active' : 'Inactive'; ?>
                            <?php echo $status; ?>

                           </p>
                          </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="post">
                            <span class="username">
                            <a href="#"><b>Area of Specialization</b></a>  
                            </span>
                            <p>
                            <?php echo $detail->industry; ?>
                           </p>
                          </div> 
                        </div>
                           <div class="col-sm-6">
                           <div class="post">
                            <span class="username">
                            <a href="#"><b>Total Job Views</b></a>  
                            </span>
                            <p>
                            <?php echo $detail->total_view; ?>
                           </p>
                          </div> 
                        </div>
                           <div class="col-sm-6">
                           <div class="post">
                            <span class="username">
                            <a href="#"><b>Total Applicants</b></a>  
                            </span>
                            <p>
                            <?php echo $detail->total_view; ?>
                           </p>
                          </div> 
                        </div>
                        <?php
 
                        ?>
                        <div class="col-sm-12">
                            <div class="post">
                              <span class="username">
                              <a href="#"><b>Explain Opportunity</b></a>  
                              </span>
                              <p>
                              <?php echo $detail->explain_opportunity; ?>
                             </p>
                            </div>
                        </div>
                      
                      
                      <?php if($detail->job_type==2){ ?>
                        <?php if(!empty($detail->why_they_should_join)){?>
                        <div class="col-sm-12">
                       <div class="post">
                        <span class="username">
                        <a href="#"><b>What's so exciting about your team</b></a>  
                        </span>
                        <p>
                        <?php echo $detail->why_they_should_join; ?>
                       </p>
                      </div>
                      </div>
                      <?php } ?>
                    <?php if ($detail->is_job_video_screening==1){ ?>
                      
                   <?php }?>
                      <?php if(!empty($detail->video)){?>
                      <div class="row margin-bottom">
                        <!-- <div class="col-sm-2">
                        </div> -->
                      <div class="col-sm-12 mb-15">
                          <div class="col-sm-8 videoBlock">
                            <div class="video_hed">
                              <span class="username">
                                <a href="#"><b>Video</b></a> 
                              </span>
                            </div>
                            <?php 
                          if (filter_var($detail->video, FILTER_VALIDATE_URL)){ 
                          $detail->video = $detail->video;
                           }else{
                            $detail->video = base_url().'/uploads/screening_video/'.$detail->video;
                            }
                            ?>
                            <video width="500" controls>
                            <source src="<?php echo $detail->video; ?>" type="video/mp4">
                            </video>
                          </div>
                      </div>

                            <div class="col-sm-12">
                                  <div class="post col-sm-12">
                                    <span class="username">
                                      <a href="#"><b>Questions</b></a> 
                                    </span>
                                    <div id="accordion" class="csAccordion">
                                      <div class="card">

                                      <?php if(!empty($detail->question_description)){
                                      $data  = json_decode($detail->question_description);
                                      
                                       foreach ($data as $key => $value) { 
                                        if(!empty($value[$key])){
                                        ?>
               
                                      <div class="card-header" id="headingOne<?php echo $key+1;?>">
                                        <h5 class="mb-0">
                                          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne<?php echo $key+1;?>" aria-expanded="true" aria-controls="collapseOne<?php echo $key+1;?>">
                                            Question #<?php echo $key+1;?>
                                          </button>
                                        </h5>
                                      </div>
                                    
                                      <div id="collapseOne<?php echo $key+1;?>" class="collapse" aria-labelledby="headingOne<?php echo $key+1;?>" data-parent="#accordion">
                                        <div class="card-body">
                                          <?php echo  $value; ?>
                                        </div>
                                      </div>
                                       <?php  } } }?>

                                      </div>
                                      </div>                
                                  </div>
                          </div>


                    <div class="col-sm-2">
                    </div>
                    </div>
                  <?php } ?>
                     <?php } ?>
                    </div>
                    </div>
                  </div>
              </div>
              <!-- /.tab-pane -->
             <!--  user review -->
                <div class="tab-pane" id="applicants">
                    <div class="box-left">
                        <div class="tab-content ">
                          <div class="">
                            <input type="hidden" name="jobId"  id="job_id" value="<?php echo $detail->jobId;?>">
                          <table class="table table-striped" id="applicants_List" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            
                            <th>Profile Image</th>
                            <th>Full Name</th>
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
                  <div class="tab-pane" id="shortlisted">
                    <div class="box-left">
                        <div class="tab-content ">
                          <div class="">
                            <input type="hidden" name="jobId"  id="job_id" value="<?php echo $detail->jobId;?>">
                          <table class="table table-striped" id="shortlisted_List" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            
                            <th>Profile Image</th>
                            <th>Full Name</th>
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


               <div class="tab-pane" id="recommends">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                        <input type="hidden" name="jobId"  id="job_id" value="<?php echo $detail->jobId;?>">
                        <table class="table table-striped" id="jobViewList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                          <!--   <th>S.no</th> -->
                            <th>Profile Image</th>
                            <th>Full Name</th>
                            <th style="width: 12%">Action</th>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>

                      </div>
                    </div>
                
                  </div>
              </div>
 
          <!--     <div class="tab-pane" id="interviews">
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
                 
                  </div>
              </div> -->

            
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

