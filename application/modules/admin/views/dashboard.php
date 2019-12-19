<div class="content-wrapper">   
    <section class="content"> 
         <div class="row">
            <a href="<?php echo base_url('admin/users/allBusiness'); ?>"> 
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-briefcase"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Business</span>
                            <span class="info-box-number"><?php echo $this->common_model->get_total_count(USERS,array('userType'=>'business')); ?></span>
                        </div>  
                    </div>      
                </div>
            </a>
            <!-- /.col -->
            <a href="<?php echo base_url('admin/users/allIndividual'); ?>"> 
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Individual</span>
                            <span class="info-box-number"><?php echo $this->common_model->get_total_count(USERS,array('userType'=>'individual')); ?></span>
                        </div>
                    </div>
                </div>
            </a>  
            <a href="<?php echo base_url('admin/users/requests'); ?>"> 
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-user-plus"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Requests</span>
                            <span class="info-box-number"><?php echo $this->common_model->get_total_count(REQUESTS); ?></span>
                        </div>
                    </div>
                </div>
            </a>

              <a href="<?php echo base_url('admin/jobs'); ?>"> 
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-tasks"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jobs</span>
                            <span class="info-box-number"><?php echo $this->common_model->get_total_count(JOBS); ?></span>
                        </div>
                    </div>
                </div>
            </a>   
        </div>
    </section>
</div>
<div class="control-sidebar-bg"></div>
</div>