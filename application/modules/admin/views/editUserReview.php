<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form class="form-horizontal" role="form" id="editFormAjaxReview" method="post" action="<?php echo base_url('admin/users/updateReview') ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update Review</h4>
                </div>
                <div class="modal-body" style="margin-left: -78px;">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <fieldset class="rating">
                                    <?php if(!empty($results->rating)){
                                       $rating__input_value =  rating_show();
                                        foreach($rating__input_value as $key => $val){ 
                                    $checked = get_checked($val['input']['value'],$results->rating); 
                                        ?>
                                        <input type="radio"  id="<?php echo $val['input']['id'];?>" name="rating" value=" <?php echo $val['input']['value'];?>" <?php echo $checked;?>>
                                        <label class = "<?php echo $val['label']['class'];?>" title=" <?php echo $val['label']['title'];?>" for="<?php echo $val['input']['id'];?>"></label>
                                    <?php } }else{  
                                        ?>
                                        <?php  $rating__input_value =  rating_show();
                                        foreach($rating__input_value as $key => $val){ 
                                        $checked = get_checked($val['input']['value'],0);?>
                                        <input type="radio"  id="<?php echo $val['input']['id'];?>" name="rating" value=" <?php echo $val['input']['value'];?>" <?php echo $checked;?> >
                                        <label class = "<?php echo $val['label']['class'];?>" title=" <?php echo $val['label']['title'];?>" for="<?php echo $val['input']['id'];?>"></label>

                                    <?php } }?>
                                </fieldset>

                                    
                        </div>

                        <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Comment</label>
                                    <div class="col-md-9">
                                        <textarea type="textarea" class="form-control" name="reviewText" id="reviewText"  placeholder = "Write comment here..." maxlength="100" value="" ><?php echo display_placeholder_text($results->comments); ?></textarea>
                                        <input type="hidden" name="reviewId" id="reviewId" value="<?php echo $results->reviewId; ?>">
                                    </div>
                                </div>
                            </div>
                      
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="<?php echo THEME_BUTTON;?>" >Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
