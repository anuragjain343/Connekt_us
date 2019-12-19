//hide_loader();
function show_loader(){
    $('.preloader-area').show();
}

function hide_loader(){
    $('.preloader-area').delay(350).fadeOut('slow');
}

$(document).ready(function() {
$("#contactUs").validate({
        rules: {
            name: {
                required: true,
                maxlength: 20
            },
            email: {
                required: true,
                email:true,
 
            },
            subject: {
                required: true,
                maxlength: 20
            },
            message: {
                required: true,
                minlength: 10,
                maxlength: 100
            }
        }, 

        messages: {
        name: {
            required: "Name field is required.", 
            maxlength:"Max characters should be 20.",
        },
        subject: { 
            required: "Subject field is required.",
            maxlength:"Max characters should be 20."
        },
        
        email: {
                required: "Email field is required.",
                email: "Please enter a valid email address.",
        },
        message:{
                required:"Message field is required.",
                minlength:"Please enter atleast 10 characters.",
                maxlength:"Max characters should be 100.",
        }
    } 
    });


$("#newsLetter").validate({
        rules: {
           
            email_newsLetter: {
                required: true,
                email:true,
 
            }
        }, 

        messages: {
        email_newsLetter: {
                required: "Email field is required.",
                email: "Please enter a valid email address.",
        }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }, 
    });

});

$("#contactUs").submit(function(e){
    //alert("hello");
  e.preventDefault();
  if ($('#contactUs').valid()==false) {
        //toastr.error('');
        return false;
}
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:"home/contactUs",
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this), 
    beforeSend: function() {
            show_loader();
           
            //$(btn).buttonLoader('start');                               
    },
    success:function(res){
        hide_loader();
        var obj = JSON.parse(res);
            if(obj){
            var err = obj.messages;
            var er = '';
            $.each(err, function(k, v) { 
            er = '  ' + v; 
           $("#"+k+"_error").html(er);
           });
          }
          if(obj.messages.success){
            toastr.success(obj.messages.success);
            var surl = 'home'; 
            window.setTimeout(function() { window.location = surl; }, 2000);
          } 
          if(obj.messages.unsuccess){
            toastr.error(obj.messages.unsuccess);
          }
          
    }
  });
});


$("#newsletterSubscribe").click(function(){
    if ($('#newsLetter').valid()==false) {
        //toastr.error('');
        return false;
    }
     var _that = $(this); 
    form = _that.closest('form');
    formData = new FormData(form[0]);
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:"home/newsletterSubscribe",
    cache:false,
    contentType: false,
    processData: false,
    data: formData, 
    beforeSend: function() {
            show_loader();                             
    },
    success:function(res){
        hide_loader();
        var obj = JSON.parse(res);
        if(obj.messages.email_newsLetter){
            toastr.error(obj.messages.email_newsLetter);
        }
        if(obj.messages.success){
            toastr.success(obj.messages.success);
            var surl = 'home'; 
            window.setTimeout(function() { window.location = surl; }, 2000);
        } 
        if(obj.messages.unsuccess){
            toastr.error(obj.messages.unsuccess);
        }
          
    }
  });
});