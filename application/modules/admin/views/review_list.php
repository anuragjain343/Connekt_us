   
    <?php if(!empty($review_list)){ 
        foreach ($review_list as $val) { ?>

    <div class="row" id="reviews">

    <?php if($val->is_anonymous != 1){ ?>
        <a href="<?php echo base_url(); ?>admin/users/userDetail/<?php echo encoding($val->review_by); ?>">
    <?php }  ?>
        
            <div class="col-md-10 col-md-offset-1">
            <!-- Box Comment -->
            <div class="box box-widget">
                <!-- /.box-body -->
                <div class="box-footer box-comments">
                    <div class="box-comment">
                       <a class="text-muted pull-right rewsIcon" onclick="deleteFn('reviews','reviewId','<?php echo encoding($val->reviewId);?>','Review','<?php echo base_url()."admin/users/deleteReview";?>');" style="cursor: pointer;"><i class="fa fa-trash"></i></a>

                       <a class="text-muted pull-right rewsIcon"  href="javascript:void(0)" title="Edit" onclick="editFn('admin/users','editUserReview','<?php echo encoding($val->reviewId);?>')"><i class="fa fa-pencil"></i></a>
                        <!-- User image -->
                        <img class="img-circle img-sm" src="<?php echo $val->profileImage; ?>" alt="User Image">

                        <div class="comment-text">
                            <span class="username">
                            <?php echo $val->fullName; ?></span>
                            <span class="text-muted pull-right">   
                             <?php 
                                    $total_rating = $val->rating; 
                                    
                                ?>
                             <fieldset class="ratings-only-view" style="margin-top: -15px;">
                                <?php if(!empty($total_rating)){
                                   $rating__input_value =  rating_show();
                                    foreach($rating__input_value as $key => $valu){ 
                                    $checked = get_checked($valu['input']['value'],$total_rating); 
                                    ?>
                                    <input type="radio"  id="<?php echo $valu['input']['id'].$val->reviewId;?>" name="rating<?php echo $valu['input']['id'].$val->reviewId;?>" value=" <?php echo $valu['input']['value'];?>" <?php echo $checked;?> disabled/>
                                    <label class = "<?php echo $valu['label']['class'];?>" title=" <?php echo $valu['label']['title'];?>" for="update_<?php echo $valu['input']['id'].$val->reviewId;?>"></label>
                                <?php } }else{  
                                    $rating__input_value =  rating_show();
                                    foreach($rating__input_value as $key => $valu){ 
                                    $checked = get_checked($valu['input']['value'],0); ?>
                                    <input type="radio"  id="<?php echo $valu['input']['id'].$val->reviewId;?>" name="rating<?php echo $valu['input']['id'].$val->reviewId;?>" value=" <?php echo $valu['input']['value'];?>" <?php echo $checked;?> disabled/>
                                    <label class = "<?php echo $valu['label']['class'];?>" title=" <?php echo $valu['label']['title'];?>" for="<?php echo $valu['input']['id'].$val->reviewId;?>"></label>

                                <?php } } ?>
                            </fieldset>

                            </span>
                            <span class="timeMeta"><?php echo $val->created_on; ?></span>
                            <span class="cmtText"><?php echo ucfirst($val->comments); ?></span>
                        </div>
                        <!-- /.comment-text -->
                    </div>
                    <!-- /.box-comment -->
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
       </a>
    </div>
    <!-- /.row -->
    <?php  } }else{ ?>

        <div class="noRev">
            <img src="<?php echo base_url().ADMIN_THEME.'custom/images/no-revw.png';?>" >
            <h3>No review available !</h3>
        </div>
    <?php } ?>

    <?php if($offset==0){?>
    <div>
        <div id="moreData">
        <!--load data -->
        </div>

        <div class="PaginationBlock">
            <input type="hidden" name="totalCount" id="totalCount" value="<?php echo $total_count?>">
            <div id="loadMore" class="text-center" >
                <button class="btn themeBtn load" id="btnLoad" >Load More</button>
            </div>
        </div>
    </div>

    <?php }?>   
    <div id="form-modal-box"></div>

   


    

