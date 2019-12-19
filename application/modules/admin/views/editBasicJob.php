  <style type="text/css">
.customSlider .irs .irs-min, .customSlider .irs .irs-max {
    display: none;
}
.form-group label span {
    font-size: 13px;
    vertical-align: text-bottom;
}
</style>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Post Job
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Post Job</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <!-- <div class="box-header with-border">
              <h3 class="box-title">Add Bang Question</h3>
            </div> -->
            <!-- /.box-header -->
            <!-- form start -->

<!-- [jobTitleId] => 3 specilizationData
[jobTitleName] => Java Developer 
            [specializationId] => 51
            [specializationName] => Accounting employement_type
[count] => 0 -->
            <form role="form">
              <div class="box-body">
                <div class="form-group">

                  <label>Job Title</label>
                 <select class="form-control">
                    <option>Select Title</option>
                    <?php foreach($jobTitleData as $key =>$value){?>
                    <option value="<?php echo $value->jobTitleId;?>"><?php echo $value->jobTitleName.'('.$value->count.')';?></option>
                   <?php }?>
                  </select>

                </div>
                <div class="form-group is-empty">
                  <label>Location</label>
                  <input type="text" class="form-control" placeholder="Enter Job Location">
                </div>
                <div class="form-group">
                  <label>Industry</label>
                  <select class="form-control">
                    <option>Select Industry</option>
                     <?php foreach($specilizationData as $key =>$value){?>
                    <option value="<?php echo $value->specializationId;?> "><?php echo $value->specializationName;?></option>
                   <?php }?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Employment Type</label>
                  <select class="form-control">
                    <option>Select Employment Type</option>
                    <?php foreach($employement_type as $key =>$value){?>
                    <option value="<?php echo $key;?> "><?php echo $value;?></option>
                   <?php }?>
                  </select>
                </div>
                <div class="form-group customSlider">
                  <label>Salary $10,000 <span>(Excl. superannuation)</span></label>
                  <input id="range_1" type="text" name="range_1" value="">
                </div>
                <div class="form-group is-empty">
                  <label>Explain The Opportunity</label>
                  <textarea class="form-control" rows="3" placeholder="Make it impactful"></textarea>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer text-right">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>

        </div>
        <!--/.col (left) -->
      </div>

    </section>
    <!-- /.content -->
  </div>
