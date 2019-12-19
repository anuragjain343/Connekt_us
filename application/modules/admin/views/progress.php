  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Interview Request Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('admin/Users/requests');?>">Requests</a></li>
        <li class="active">Interview Request Detail</li>
      </ol>
      <button type="button" onclick="window.history.back();" class="btn bg-yellow btn-flat margin pull-right">Back</button>
    </section>


    <!-- Main content -->
    <section class="content">

      <div class="row">
        
        <!-- /.col -->
         <!-- /.col -->
        <div class="col-md-8">
          <div class=" box-primary">
            <div class="box-body box-profile m-t-40 main">
        <section class="content">
          <!-- The time line -->
          <ul class="timeline">
            <!-- timeline time label -->
           
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-comments bg-blue"></i>
            
              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"><?php echo !empty($requestData['data']->Created_Date) ? $requestData['data']->Created_Date : '';?></i></span>

                <h3 class="timeline-header"><b>Interview request sent by <a><?php echo !empty($requestData['data']->interviewData[0]->type) ? $requestData['data']->interviewData[0]->type : '';?></a></b> </h3>
                <br>
                <div class="timeline-body">
                  Location : <?php echo !empty($requestData['data']->interviewData[0]->location) ? $requestData['data']->interviewData[0]->location : '';?>
                </div>
                <div class="timeline-footer">
                
                </div>
              </div>

              
               <a class="btn-xs"></a>
                  <a class="btn-xs"></a>
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            <?php if($requestData['data']->count == 1) { 
              if($requestData['data']->interviewData[0]->interview_status == 1){?>
             <li>
              <i class="fa fa-user bg-yellow"></i>
              <div class="timeline-item">
                <h3 class="timeline-header"><a href="#"><?php echo $requestData['data']->requested_for;?></a> accepted interview request.</h3>
              </div>
            </li>
            <?php }elseif($requestData['data']->interviewData[0]->interview_status == 2 ){ ?>
            <li>
              <i class="fa fa-user bg-red"></i>
              <div class="timeline-item">
                <h3 class="timeline-header"><a href="#">Interview request declined.</a></h3>
              </div>
            </li>
            <? } elseif($requestData['data']->interviewData[0]->is_delete == 1 ){ ?>
            <li>
              <i class="fa fa-user bg-red"></i>
              <div class="timeline-item">
                <h3 class="timeline-header"><b>Interview request deleted by <a><?php echo $requestData['data']->requested_by.' '.'('.$requestData['data']->interviewData[0]->type.')';?></a></b></h3>
                
              </div>
            </li>


            <?}else{?>
             <li>
              <i class="fa fa-hourglass bg-red"></i>
              <div class="timeline-item">
                <h3 class="timeline-header"><a href="#">Interview request pending.</a></h3>
              </div>
            </li>
            <?php } }?>

            <?php if($requestData['data']->count == 2) { 
              if($requestData['data']->interviewData[0]->interview_status == 1){?>
             <li>
              <i class="fa fa-user bg-yellow"></i>
              <div class="timeline-item">
                <h3 class="timeline-header"><a href="#"><?php echo $requestData['data']->requested_for;?></a> accepted interview request</h3>
              </div>
            </li>
            <?php }elseif($requestData['data']->interviewData[0]->interview_status == 2 ){ ?>
            <li>
              <i class="fa fa-user bg-red"></i>
              <div class="timeline-item">
                <h3 class="timeline-header"><a href="#">Interview request declined.</a></h3>
              </div>
            </li>
            <? } elseif($requestData['data']->interviewData[0]->is_delete == 1 ){ ?>
            <li>
              <i class="fa fa-user bg-red"></i>
              <div class="timeline-item">
                <h3 class="timeline-header"><b>Interview request deleted by <a><?php echo $requestData['data']->requested_by.' '.'('.$requestData['data']->interviewData[0]->type.')';?></a></b></h3>
                 
              </div>
            </li>


            <?}else{?>
             <li>
              <i class="fa fa-hourglass bg-red"></i>
              <div class="timeline-item">
                <h3 class="timeline-header"><a href="#">Interview request pending.</a></h3>
              </div>
            </li>
            <?php } }?>


            
            <!-- END timeline item -->
            <!-- timeline item -->
            
            <?php if($requestData['data']->count == 1) { 
              if($requestData['data']->interviewData[0]->interview_status == 1){?>
              <li class="time-label">
                   
              </li>
                  <li>
                      <i class="fa fa-edit bg-yellow"></i>

                      <div class="timeline-item">
                        <h3 class="timeline-header">Interview with<a href="#"> <?php echo $requestData['data']->interviewData[0]->interviewer_name;?></a></h3>
                        <div class="timeline-body">
                         Date & Time : <?php echo !empty($requestData['data']->interviewData[0]->date) ? $requestData['data']->interviewData[0]->date : '';?>
                        </div>
                      </div>
                    </li>

            <?php } }?> 

            <?php if($requestData['data']->count == 2) { 
              if($requestData['data']->interviewData[0]->interview_status == 1){?>
              <li class="time-label">
                   
              </li>
                  <li>
                      <i class="fa fa-edit bg-yellow"></i>

                      <div class="timeline-item">
                        <h3 class="timeline-header">Interview with<a href="#"> Recruiter</a></h3>
                         <div class="timeline-body">
                           Date & time : <?php echo $requestData['data']->interviewData[0]->date.' '.$requestData['data']->interviewData[0]->time;?>
                         </div>
                      </div>
                    </li>

            <?php } }?>  


            <?php if($requestData['data']->count == 2) { 
              if($requestData['data']->interviewData[1]->interview_status == 1){?>
              <li class="time-label">
                    
              </li>
                  <li>
                      <i class="fa fa-edit bg-yellow"></i>

                      <div class="timeline-item">
                        <h3 class="timeline-header">Interview with<a href="#"> Employer</a></h3>
                        <div class="timeline-body">
                           Date & time : <?php echo $requestData['data']->interviewData[0]->date.' '.$requestData['data']->interviewData[0]->time;?>
                         </div>
                      </div>
                    </li>

            <?php } }?> 

            <?php  if($requestData['data']->request_offer_status == 1){?>
              <li class="time-label">
                   
              </li>
                  <li>
                      <i class="fa fa-briefcase bg-yellow"></i>

                      <div class="timeline-item">
                        <h3 class="timeline-header"><a  style="color:green;"> Job offered</a></h3>

                        <div class="timeline-body">
                          Offer date : <?php echo $requestData['data']->upd;?>
                        </div>
                      </div>
                    </li>

            <?php } ?> 


            <?php  if($requestData['data']->request_offer_status == 2 AND $requestData['data']->is_finished == 1){?>
              <li class="time-label">
                    
              </li>
                  <li>
                      <i class="fa fa-briefcase bg-red"></i>

                      <div class="timeline-item">
                        <h3 class="timeline-header" ><a style="color:red;"> Job not offered</a></h3>

                        <div class="timeline-body">
                           <?php echo $requestData['data']->upd;?>
                        </div>
                      </div>
                    </li>

            <?php } ?>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <?php if($requestData['data']->is_finished == 0 ){?>
            <li>
              <i class="fa fa-clock-o bg-red" title="Process running"></i>
              <div class="timeline-item">
                        <h3 class="timeline-header"><a href="#">Running</a></h3>

                        <div class="timeline-body">
                         
                        </div>
                      </div>
            </li>
            <?php }else{?>
            <li>
              <i class="fa fa-check bg-green" title="Process completed"></i>
              <div class="timeline-item">
                        <h3 class="timeline-header"><a href="#"> Completed</a></h3>

                  
                      </div>
            </li>
            <?php } ?>


                  
          </ul>

       </div>
     </div>
   </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


    </section>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>