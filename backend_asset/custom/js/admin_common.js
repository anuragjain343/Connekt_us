var base_url = $('#tl_admin_main_body').attr('data-base-url');

function show_loader(){
    $('#tl_admin_loader').show();
}

function hide_loader(){
    $('#tl_admin_loader').hide();
}

/** start script in application **/
var logout = function () {
    bootbox.confirm('Are you sure want to logout?', function (isTrue) {
        if (isTrue) {
            $.ajax({
                url: base_url+'admin/logout',
                type: 'POST',
                dataType: "JSON",
                success: function (data) {
                    window.location.href = base_url+"admin/";
                }
            });
        }
    });
}

    $("#uploadTc").submit(function(e){
      e.preventDefault();
      $(".error").html(''); 
      $.ajax({
        type:"POST",
        url:base_url+"admin/option/update_tc_page",
        cache:false,
        contentType: false,
        processData: false,
        data: new FormData(this), 
        success:function(res){
            var obj = JSON.parse(res);
              if(obj.status == 1){
              toastr.success(obj.message);
            if(obj.url == 'pp_page'){
                var surl = base_url+"admin/privacyPolicy";
            }else{
                var surl = base_url+"admin/termCondition";
            }
            window.setTimeout(function() { window.location = surl; }, 2000);
              } 
              if(obj.status == 0){
              toastr.error(obj.message);
              }
        }
      });
    });

    


    /**** for updating admin profile ****/
    var base_url = $('#tl_admin_main_body').attr('data-base-url');
    $('body').on('click', ".update_admin_profile", function (event) { 
        var _that = $(this), 
            form = _that.closest('form'),      
            formData = new FormData(form[0]),
            f_action = form.attr('action');  
            
    //console.log(formData+'-----'+f_action);
        $.ajax({
            type: "POST",
            url: f_action,
            data: formData, //only input
            processData: false,
            contentType: false,
            dataType: "JSON",
            beforeSend: function () {
              show_loader()
            },
            success: function (data, textStatus, jqXHR) {
                    hide_loader()
                    if (data.status == 1){

                        toastr.success(data.message);
                            window.setTimeout(function () {
                                 window.location.href = data.url;
                            }, 2000);
                        $(".loaders").fadeOut("slow");

                    } else {

                        toastr.error(data.message);
                        //toastr.options.positionClass = 'toast-top-center'
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    } 
            },
            error:function (){
                
            }
        });

    });

    $('body').on('click', ".change_password", function (event) { 
        var _that = $(this), 
            form = _that.closest('form'),      
            formData = new FormData(form[0]),
            f_action = form.attr('action');  
            
    //console.log(formData+'-----'+f_action);
        $.ajax({
            type: "POST",
            url: f_action,
            data: formData, //only input
            processData: false,
            contentType: false,
            dataType: "JSON",
            beforeSend: function () {
              show_loader()
            },
            success: function (data, textStatus, jqXHR) {
                    hide_loader()
                    if (data.status == 1){

                        toastr.success(data.message);
                            window.setTimeout(function () {
                                 window.location.href = data.url;
                            }, 2000);
                        $(".loaders").fadeOut("slow");

                    } else {

                        toastr.error(data.message);
                        //toastr.options.positionClass = 'toast-top-center'
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    } 
            },
            error:function (){
                
            }
        });

    });



var value = $("#verify_email").val();
$(function (){
if(value == 1){
        $("#setMessage_verify").text('Enabled');
         $("#verify_email").val('0');
       
    }else{
        $("#setMessage_verify").text('Disabled');
        $("#verify_email").val('1');
    }
});

$("#verify_email").change(function(){
    var varh = $("#verify_email").val();
    if(varh == 1){
        $("#setMessage_verify").text('Enabled');
        $("#verify_email").val('0');
       
    }else{
        $("#setMessage_verify").text('Disabled');
         $("#verify_email").val('1');
    }
    $.ajax({
        type: "GET",
        url: base_url+"admin/verifyEmail_tab_action",
        success: function (response) {
            
        }
    });
    });

/** backend script **/

    var addFormBoot = function (ctrl, method)
    {
        $(document).on('submit', "#add-form-common", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + ctrl + "/" + method,
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    show_loader()
                },
                success: function (response, textStatus, jqXHR) {
                    hide_loader()
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
//                            bootbox.alert({
//                                message: data.message,
//                                callback: function (
//
//
//                                        ) { /* your callback code */
//                                }
//                            });
                            $("#commonModal").modal('show');
                            toastr.success(data.message);


                            window.setTimeout(function () {
                                window.location.href = "<?php echo base_url(); ?>" + ctrl;
                            }, 2000);
                            

                        } else {
                            toastr.error(data.message);
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        hide_loader()
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
    }

    var updateFormBoot = function (ctrl, method)
    {
        $("#edit-form-common").submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + ctrl + "/" + method,
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    show_loader()
                },
                success: function (response, textStatus, jqXHR) {
                    hide_loader()
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
//                            bootbox.alert({
//                                message: data.message,
//                                callback: function (
//
//
//                                        ) { /* your callback code */
//                                }
//                            });
                            $("#commonModal").modal('hide');
                            toastr.success(data.message);
                            window.setTimeout(function () {
                                window.location.href = base_url + ctrl;
                            }, 2000);
                            

                        } else {
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
    }

    var editFn = function (ctrl, method, id) {

        $.ajax({
            url: base_url + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                show_loader();
            },
            success: function (data, textStatus, jqXHR) {
                $('#form-modal-box').html(data);

                $("#commonModal").modal('show');
                addFormBoot();
                hide_loader();
            }
        });
    }


    var viewFn = function (ctrl, method, id) {
        $.ajax({
            url: base_url + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                show_loader()
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                addFormBoot();
                hide_loader();
            }
        });
    }

    var open_modal = function (controller) {
        $.ajax({
            url: base_url + controller + "/open_model",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');


            }
        });
    }

    var deleteFn = function (table, field, id, dataitem,itemurl) {
      
        bootbox.confirm({
           message: "Are you sure, you want to delete this "+dataitem+" ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                   show_loader();
                    var url = itemurl;
                    $.ajax({
                        method: "POST",
                        url: url,
                        dataType: "json",
                        data: {id: id, id_name: field, table: table},
                        success: function (response) {
                            hide_loader();
                            if (response == 200) {
                                toastr.success(dataitem+" "+"deleted successfully");
                                window.setTimeout(function () {
                                window.location.reload();
                                }, 2000);
                              
                            }
                        },
                        error: function (error, ror, r) {
                            bootbox.alert(error);
                        },
                    });
                }
            }
        });

    }
    
    var statusFn = function (table, field, id, status,dataitem) {
        
        var message = "";
        if (status == '1') {
            message = "inactive";
            tosMsg = dataitem+" "+'inactivated successfully ';
        } else if (status == '0') {
            message = "active";
            tosMsg = dataitem+" "+'activated successfully ';
        }

        bootbox.confirm({
            message: "Are you sure, you want to " + message + " this "+dataitem+" ?",
            buttons: {
                confirm: {
                    label: 'Ok',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    show_loader();
                    var url = base_url+"admin/status";
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {id: id, id_name: field, table: table, status: status},
                        success: function (response) {
                            hide_loader();
                            if (response == 200) {
                        
                               toastr.success(tosMsg);
                                window.setTimeout(function () {
                            window.location.reload();
                            }, 2000);
                              
                            }
                        },
                        error: function (error, ror, r) {
                            bootbox.alert(error);
                        },
                    });
                }
            }
        });


    }
   
/**   **/


$(document).ready(function () {
   var base_url = $('#tl_admin_main_body').attr('data-base-url'); 
  toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        "positionClass": "toast-top-right",
        timeOut: 2000,
        "fadeIn": 300,
    };
    
    $(document).on('submit', "#addFormAjax", function (event) {
        event.preventDefault();
        var _that = $(this),
        formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: _that.attr('action'),
            data: formData, //only input
            processData: false,
            contentType: false,
            beforeSend: function () {
                show_loader();
            },
            success: function (response, textStatus, jqXHR) {
                try {
                    var data = $.parseJSON(response);
                    if (data.status == 1)
                    {
                        $("#addModel").modal('hide');
                        toastr.success(data.message);
                        if(data.url != ""){
                        window.setTimeout(function () {
                            window.location.href = data.url;
                        }, 2000);
                       }
                        hide_loader();

                    } else {
                        toastr.error(data.message);
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        hide_loader();
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                } catch (e) {
                     $('#error-box').show();
                     $("#error-box").html(data.message);
                        
                    setTimeout(function () {
                            $('#error-box').hide(800);
                    }, 1000);
                }
            }
        });

    });


    $(document).on('submit', "#addFormAjaxReview", function (event) {
        event.preventDefault();
        var _that = $(this),
        formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: _that.attr('action'),
            data: formData, //only input
            processData: false,
            contentType: false,
            beforeSend: function () {
                show_loader();
            },
            success: function (response, textStatus, jqXHR) {
                try {
                    var data = $.parseJSON(response);
                    if (data.status == 1)
                    {
                        $("#addModel").modal('hide');
                        toastr.success(data.message);
                        if(data.url != ""){
                        location.reload();
                       }
                        hide_loader();

                    } else {
                        toastr.error(data.message);
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        hide_loader();
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                } catch (e) {
                     $('#error-box').show();
                     $("#error-box").html(data.message);
                        
                    setTimeout(function () {
                            $('#error-box').hide(800);
                    }, 1000);
                }
            }
        });

    });
    
    $(document).on('submit', "#editFormAjax", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData, //only input
                processData: false,
                contentType: false,
                 beforeSend: function () {
                    show_loader();
                 },
                success: function (response, textStatus, jqXHR) {
                    hide_loader();
                    try {
                        
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
                            $("#editModel").modal('hide');
                            toastr.success(data.message);
                            
                            window.setTimeout(function () {
                                window.location.href = data.url;
                            }, 2000);
                            
                        }else {
                            toastr.error(data.message);
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });

      $(document).on('submit', "#editFormAjaxReview", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData, //only input
                processData: false,
                contentType: false,
                 beforeSend: function () {
                    show_loader();
                 },
                success: function (response, textStatus, jqXHR) {
                    hide_loader();
                    try {
                        
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
                            $("#editModel").modal('hide');
                            toastr.success(data.message);
                            window.setTimeout(function () {
                                location.reload();
                            }, 2000);
                            
                            
                        }else {
                            toastr.error(data.message);
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });

});

jQuery('body').on('change', '.input_img2', function () {

        var file_name = jQuery(this).val(),
            fileObj = this.files[0],
            calculatedSize = fileObj.size / (1024 * 1024),
            split_extension = file_name.substr( (file_name.lastIndexOf('.') +1) ).toLowerCase(), //this assumes that string will end with ext
            ext = ["jpg", "png", "jpeg"];
            console.log(split_extension+'---'+file_name.split("."));
        if (jQuery.inArray(split_extension, ext) == -1){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('Invalid file format. Allowed formats: jpg, jpeg, png');
            return false;
        }
        
        if (calculatedSize > 5){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('File size should not be greater than 5MB');
            return false;
        }
        if (jQuery.inArray(split_extension, ext) != -1 && calculatedSize < 10){
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });

    jQuery('body').on('change', '.input_img3', function () {

        var file_name = jQuery(this).val(),
            fileObj = this.files[0],
            calculatedSize = fileObj.size / (1024 * 1024),
            split_extension = file_name.substr( (file_name.lastIndexOf('.') +1) ).toLowerCase(), //this assumes that string will end with ext
            ext = ["jpg", "png", "jpeg"];
        if (jQuery.inArray(split_extension, ext) == -1){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('Invalid file format. Allowed formats: jpg,jpeg,png');
            return false;
        }
        if (calculatedSize > 5){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('File size should not be greater than 5MB');
            return false;
        }
        if (jQuery.inArray(split_extension, ext) != -1 && calculatedSize < 10){
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });

    function readURL(input) {
        var cur = input;
        if (cur.files && cur.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(cur).hide();
                $(cur).next('span:first').hide();
                $(cur).next().next('img').attr('src', e.target.result);
                $(cur).next().next('img').css("display", "block");
                $(cur).next().next().next('span').attr('style', "");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    jQuery('body').on('click', '.remove_img', function () {
        var img = jQuery(this).prev()[0];
        var span = jQuery(this).prev().prev()[0];
        var input = jQuery(this).prev().prev().prev()[0];
        jQuery(img).attr('src', '').css("display", "none");
        jQuery(span).css("display", "block");
        jQuery(input).css("display", "inline-block");
        jQuery(this).css("display", "none");
        jQuery(".image_hide").css("display", "block");
        jQuery("#user_image").val("");
    });

var dataTable = $('#common_datatable_users');    
if(dataTable.length !== 0){
    $('#common_datatable_users').dataTable({
        /*columnDefs: [{orderable: false, targets: [4, 6, 7]}]*/
        "pageLength": 10
    });
}

$(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });

//admin profile update
$(document).on('submit', "#editProfile", function (event) {

    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: formData, //only input
        processData: false,
        contentType: false,
            beforeSend: function () {
            show_loader();
            },
        success: function (response, textStatus, jqXHR) {
            hide_loader();
            try {
                        
                var data = $.parseJSON(response);
                if (data.status == 1)
                {
                    toastr.success(data.message);
                            
                    window.setTimeout(function () {
                        window.location.href = data.url;
                    }, 2000);
                            
                }else {
                    toastr.error(data.message);
                           
                    setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
                }
            } catch (e) {
                        //$('#error-box').show();
                        //$("#error-box").html(data.message);
                toastr.error(data.message);
                setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
            }
        }
    });

});

//admin change password
$(document).on('submit', "#editPassword", function (event) {

    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: formData, //only input
        processData: false,
        contentType: false,
            beforeSend: function () {
            show_loader();
            },
        success: function (response, textStatus, jqXHR) {
            hide_loader();
            try {
                        
                var data = $.parseJSON(response);
                if (data.status == 1)
                {
                    toastr.success(data.message);
                            
                    window.setTimeout(function () {
                        window.location.href = data.url;
                    }, 2000);
                            
                }else {
                    toastr.error(data.message);
                           
                    setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
                }
            } catch (e) {
                        //$('#error-box').show();
                        //$("#error-box").html(data.message);
                toastr.error(data.message);
                setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
            }
        }
    });

});

  // list
$(function () {

    var interviewList = $('#interviewList').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No Interviews found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getInterviewList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]

    });
});

$(function () {

    var contactus = $('#contactus').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getContactUsList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]

    });
});

var viewModel = function (ctrl, method, id) {
        $.ajax({
            url: base_url + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                show_loader()
            },
            success: function (data, textStatus, jqXHR) {
                hide_loader()
                $('#form-modal-box').html(data);
                $("#commonModals").modal('show');
                addFormBoot();
                hide_loader()
            }
        });
    }

  
  
  $(function () {
  var userType = $('#userType').val();
   var user_list = $('#userList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getUsersList",
            "type": "POST",
            "data" : {'userType' : userType},
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]

    });

    var gigStatus = $('#jobs-status').val();
        $(document).on('change','#jobs-status',function(){
        jobs_list.draw();
    });
     



   var jobs_list = $('#jobsList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/jobs/getJobsList",
            "type": "POST",
            "dataType": "json",
            "data":function(res){
                 res.job_type = $('#jobs-status').val();
                 res.userType =0;
                
            },
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }


        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]

    });


    /*jobs-status*/
  

    var strength_list = $('#strengthList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No strength found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getStrengthList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });

    var value_list = $('#valueList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No value found',
        },
        
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getValueList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });

    var specialization_list = $('#specializationList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No specialization found',
        },
        
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getSpecializationList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });

    var jobTitle_list = $('#jobTitleList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No job title found',
        },
        
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getJobTitleList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });
    var user_type = $('#user_type').val();
    var user_id = $('#user_id').val();
    var job_id = $('#job_id').val();
 
   
     var interviewList = $('#interview_List').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getInterviewListByEmployer",
            "type": "POST",
            "data":{"userId":user_id,'user_type':user_type},
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]

    });



    var gigStatus = $('#gig-status').val();
    $(document).on('change','#gig-status',function(){
        jobs_List.draw();
    });
     

        
    var jobs_List = $('#jobs_List').DataTable({  
    
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [],        //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getEmployerJobList",
            "type": "POST",
            //"data":{"userId":user_id,'user_type':user_type,'job_type':$('#gig-status').val()},
            "dataType": "json",
            "data":function(res){
                 res.job_type = $('#gig-status').val();
                 res.userId =user_id;
                 res.user_type= user_type;
            },
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }

        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]


    });



    var gigStatus = $('#gig-status1').val();
    $(document).on('change','#gig-status1',function(){
        appliedJobList.draw();
    });
     

        
    var appliedJobList = $('#appliedJobList').DataTable({  
    
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [],        //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/appliedJobList",
            "type": "POST",
            //"data":{"userId":user_id,'user_type':user_type,'job_type':$('#gig-status').val()},
            "dataType": "json",
            "data":function(res){
                 res.job_type = $('#gig-status1').val();
                 res.userId =user_id;
                 res.user_type= user_type;
            },
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }

        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]


    });



var applicants_List = $('#applicants_List').DataTable({  
    
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [],        //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getApplicatnsList",
            "type": "POST",
            "data":{"userId":user_id,'user_type':user_type,'job_id':job_id},
            "dataType": "json",
           
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }

        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]


    });
   

    var gigStatus = $('#gig-status12').val();
    $(document).on('change','#gig-status12',function(){
        savedJobList.draw();
    });


   var savedJobList = $('#savedJob_List').DataTable({  
   
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [],        //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getSavedJobList",
            "type": "POST",
            "data":function(res){
                 res.job_type = $('#gig-status12').val();
                 res.userId =user_id;
                 res.job_id= job_id;
            },
            "dataType": "json",
           
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }

        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]
    });

   var shortlisted_List = $('#shortlisted_List').DataTable({  
    
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [],        //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getShortlistedUser",
            "type": "POST",
            "data":{"userId":user_id,'user_type':user_type,'job_id':job_id,'job_status':1},
            "dataType": "json",
           
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }

        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]


    });

      var jobViewList = $('#jobViewList').DataTable({  
    
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [],        //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getjobViewsUser",
            "type": "POST",
            "data":{"userId":user_id,'user_type':user_type,'job_id':job_id,'job_status':1},
            "dataType": "json",
           
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }

        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]


    });

//$('#gig-status').change(function(){ 
    //var value = $(this).val();
    /* $.ajax({
        type:"POST",
        url:base_url+"admin/users/getEmployerJobList",
        data:{"userId":user_id,'user_type':user_type,'job_type':value}, 
        dataType: "json",
        dataSrc: function (jsonData) {
                return jsonData.data;
            }
      });*/
   /* columnDefs: [
            { orderable: false, targets: -1 },  
        ]*/

//});

/*    var review_list = $('#reviewList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
         "searching": false,
          "oLanguage": {
            "sEmptyTable" : 'No reviews found',
         },
        
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getReviewsList",
            "data" : {'userType' : user_type ,'userId':user_id},
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]
    });*/

var admin_noti = $("#sendNoti");

admin_noti.validate({
    rules: {
       
        title: { 
            required: true,
         
            minlength: 2,
             maxlength:30,
            },
            discription:{
                required:true,
                 maxlength:400,
                 minlength:6,
            },
    },       
});




  $("#sendNoti").submit(function(e){

      e.preventDefault();
      $(".error").html(''); 
      $.ajax({
        type:"POST",
        url:base_url+"admin/users/sendNotificationToAll",
        cache:false,
        contentType: false,
        processData: false,
        data: new FormData(this), 
        success:function(res){
        var obj = JSON.parse(res);
            if(obj.status == 1){
                toastr.success(obj.message);
                /*if(obj.url == 'pp_page'){
                    var surl = base_url+"admin/privacyPolicy";
                }else{
                    var surl = base_url+"admin/termCondition";
                }*/
                var surl = base_url+"admin/users/sendNotification";
                window.setTimeout(function() { window.location = surl; }, 2000);
            } 
            if(obj.status == 0){
                toastr.error(obj.message);
            }
        }
      });

    });

    var recommends_list = $('#recommendList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
         "searching": false,
          "oLanguage": {
            "sEmptyTable" : 'No recommends found',
         },
        
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getRecommendsList",
            "data" : {'userType' : user_type ,'userId':user_id},
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]
    });

    var fav_list = $('#favList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
         "searching": false,
          "oLanguage": {
            "sEmptyTable" : 'No favourites found',
         },
        
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getFavouritesList",
            "data" : {'userType' : user_type ,'userId':user_id},
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]
    });

});
    
     var saveProfile = $('#saveProfileList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
          "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
        
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/saveProfilesList",
            "type": "POST",
            "dataType": "json",
            "data":function(res){
                 res.user_id = $('#profileId').val();
                
                
            },
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }


        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]

    });



   


$('#addStrength').on('click',function(){

    $('#addStrengthModel').modal('show');
});

$('#addSpecialization').on('click',function(){

    $('#addSpecializationModel').modal('show');
});

$('#addValue').on('click',function(){

    $('#addValueModel').modal('show');
});

$('#addJobTitle').on('click',function(){

    $('#addJobTitleModel').modal('show');
});



   







