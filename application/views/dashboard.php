<div class="content-wrapper">   
    <section class="content"> 
         <div class="row">
            <a href="#"> 
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Users</span>
                            <span class="info-box-number"><?php echo $this->common_model->get_total_count(USERS); ?></span>
                        </div>  
                    </div>      
                </div>
            </a>
            <!-- /.col -->
           <!--  <a href="#"> 
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-cube"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">SPECIALIZATIONS</span>
                            <span class="info-box-number"><?php echo $this->common_model->get_total_count(SPECIALIZATIONS); ?></span>
                        </div>
                    </div>
                </div>
            </a>  -->
        </div>
    </section>
</div>
<div class="control-sidebar-bg"></div>
</div>