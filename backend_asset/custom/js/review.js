
    
function show_loader(){
    $('#tl_admin_loader').show();
}

function hide_loader(){
    $('#tl_admin_loader').hide();
}



    var base_url = $('#tl_admin_main_body').attr('data-base-url');
    var user_type = $('#user_type').val();
    var user_id = $('#user_id').val();
    var offset=0;
    var limit = 4;

    review();


    function review(){

        $.ajax({
            url: base_url+"admin/users/getReviewsList",
            type: "POST",
            data:{userId: user_id,userType:user_type,offset:offset,limit:limit},              
            cache: false,   
            beforeSend: function() {
                
               // $(".scroll_loader").show();
               show_loader();
            },                          
            success: function(data){ 
               // $(".scroll_loader").hide();
               hide_loader();
                if(offset==0){
                   
                    $('#reviewList').html(data);

                }else{
                    
                    $("#moreData").append(data);
                }

                var totalCount = $('#totalCount').val(); 
                var resultCount = $('div[id=reviews]').length; 
                if(totalCount>resultCount){

                    $('div#loadMore').show();

                }else{

                    $('div#loadMore').hide();
                    
                }   
                offset += limit;

            }
        });
    }

    $(document).on('click',"#btnLoad", function(event){ 

        var totalCount = $('#totalCount').val(); 
        var resultCount = $('div[id=reviews]').length;
        if(totalCount>resultCount){

            $('#btnLoad').show();

        }else{

            $('#btnLoad').hide('fast');
            
        }  
        review();
    });


  