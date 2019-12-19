<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Privacy policy
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Content</li>
        <li class="active">Privacy policy</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header">
              </h3>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
             <div class="container">
            <form id="uploadTc">
               <div class="form-group" >
                <label class="col-sm-12 control-label">Current File: <a target="_blank" href="<?php if(!empty($content->option_value)){echo base_url("uploads/pdf/$content->option_value"); } ?>"><?php  if(!empty($content->option_value)){ echo $content->option_value;}?></a></label></br></br>
                
                    <div class="col-sm-4">
                      <div class="form-control" style="margin-top: 30px;">
                      
                        <a style="cursor: pointer;"><label  for="upload-photo" id="inputImage">Select file to upload</label></a>
                      </div></br></br>
                      <input type="file" name="tc_file" id="upload-photo"  accept="application/pdf" style="cursor: pointer;"/></br>
                      <input type="hidden" name="content" value="pp_page">
                    </div>
                </div>
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-danger" style="background-color: #f3ac36;color: white;position:  relative;top: 15px;">Upload</button>
                </div>
            </form>
          </div> <!-- /container -->
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>



<script type="text/javascript">
  
  $('#upload-photo').change(function() {
      var i = $(this).prev('label').clone();
      var file = $('#upload-photo')[0].files[0].name;
      $('#inputImage').text(file);
    });
</script>
