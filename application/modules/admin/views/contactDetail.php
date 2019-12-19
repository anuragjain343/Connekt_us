
<!-- Modal -->
<div class="modal fade" id="commonModals" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="">
                <div class="modal-header head-brdr">
                    <h4 class="modal-title app-r">Contact Details</h4>
                    <span data-dismiss="modal"><i class="fa fa-close" style="cursor:pointer;"></i></span> 
                </div>

                <div class="modal-body">
                    <div class="row invoice-info">
        

        


                        <div class="col-sm-12 invoice-col">
                        
                        <div class="userInfoBox" style="margin-top: 0px !important;">
                            <center><p style=""><!-- <?php echo display_placeholder_text(ucfirst($payment->fullName)); ?> --></p></center>
                            </div>
                        </div>
                         <div class="UserMainBox">
                        <div class="col-sm-12 invoice-col">
                       <b class="add-name"></b>
                            <div class="userInfoBox" style="margin-top: 0px !important;">

                            <p class="text-muted text-center">

                            <?php 
                            if(!empty($detail->rating)){
                                $total_rating = intval($detail->rating); 
                            ?>
                            <fieldset class="ratings-only-view text-center" >
                            <?php if(!empty($total_rating)){
                               $rating__input_value =  rating_show();
                                foreach($rating__input_value as $key => $valu){ 
                                $checked = get_checked($valu['input']['value'],$total_rating); 
                                ?>
                                <input type="radio"  id="<?php echo $valu['input']['id'];?>" name="rating" value="<?php echo $valu['input']['value'];?>" <?php echo $checked;?> disabled/>
                                <label class = "<?php echo $valu['label']['class'];?>" title=" <?php echo $valu['label']['title'];?>" for="<?php echo $valu['input']['id'];?>"></label>
                            <?php } }else{  
                                $rating__input_value =  rating_show();
                                foreach($rating__input_value as $key => $valu){ 
                                $checked = get_checked($valu['input']['value'],0); ?>
                                <input type="radio"  id="<?php echo $valu['input']['id'];?>" name="rating" value=" <?php echo $valu['input']['value'];?>" <?php echo $checked;?> disabled/>
                                <label class = "<?php echo $valu['label']['class'];?>" title=" <?php echo $valu['label']['title'];?>" for="<?php echo $valu['input']['id'];?>"></label>

                            <?php } } ?>
                       </fieldset>


                           
                        <?php }?>
                          </p>
                            </div>
                        </div>
                        <div class="col-sm-12 invoice-col">
                       <b class="add-name">Name</b>
                            <div class="userInfoBox" style="margin-top: 0px !important;">
                            <p style=""><?php echo display_placeholder_text($detail->name); ?></p>
                            </div>
                        </div>
                        <div class="col-sm-12 invoice-col">
                        <b class="add-name">Email</b>
                        <div class="userInfoBox" style="margin-top: 0px !important;">
                           <p style=""><?php echo display_placeholder_text($detail->email); ?></p>
                            </div>
                        </div>

                        
                        <div class="col-sm-12 invoice-col">
                        <b class="add-name">Subject</b>
                        <div class="userInfoBox" style="margin-top: 0px !important;">
                          <p style=""><?php echo display_placeholder_text($detail->subject); ?></p>
                            </div>
                        </div>

    
                       <div class="col-sm-12 invoice-col">
                        <b class="add-name">Message</b>
                        <div class="userInfoBox" style="margin-top: 0px !important;">
                           <p style=""><?php if($detail->message){ echo $detail->message;}?></p>
                            </div>
                        </div>

                       
                        <!-- /.col -->
                        </div>


                    </div>
                   <!-- /.row -->
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
 <script type="text/javascript">$(':radio:not(:checked)').attr('disabled', true);</script>
<!-- Modal -->
