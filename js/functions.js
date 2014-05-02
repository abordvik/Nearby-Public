function invisible() {

    $('#visible').remove();
    $('#visibility').html('<img src="img/not-visible.png" id="invisible" onclick="visible()"/> <p id="lockdownarrow">&darr;</p> <p id="lockdowntext">You can\'t use the app in invisibility mode.</p>');
    $('#lockhold').html('<div id="lockdown"></div>');
    $('.friends').html('Invisibility');
    localStorage.setItem("visible", "no");

    
}
function visible() {
    
     $('#invisible').remove();
     $('#visibility').html('<img src="img/visible.png" id="visible" onclick="invisible()"/>');
     $('#lockdown').remove();
     $('.friends').html('<img src="img/spinnerLarge.gif" />');
     localStorage.setItem("visible", "yes");

}










$(document).ready(function(){

    /* Just because users phone says it's logged in, it might not be.
      Check if it is, if it's logged in, do nothing, if check fails, remove loggedin status from phone*/


      var loggedIn = localStorage.getItem("loggedin");

      var token = localStorage.getItem("token");
      var uID = localStorage.getItem("uID");

      var checkString = "uid=" + uID + "token=" + token;

    function message(type,message){
        if(type == "error"){
            $('.errorOverlay').show();
            $('.errorMessage').html('<p>' + message + '</p>');
            $('.errorMessage').show(); 
            $('.nearbyInit').css('animation', 'shake 0.6s').css('-webkit-animation', 'shake 0.6s');
            setTimeout(function () {
              $('.nearbyInit').css('animation', 'nan').css('-webkit-animation', 'nan');
            }, 2000);
        }
        if(type == "success"){
            $('.errorOverlay').show();
            $('.completeContainer').html('<p>' + message + '</p>');
            $('.completeContainer').show();
        }
        if(type == "remove"){
            $('.errorMessage').hide();
            $('.errorOverlay').hide();
            $('.completeContainer').hide();
        }
    }

   
   

   $('.errorMessage').click(function(){
        $('.errorMessage').hide();
        $('.errorOverlay').hide();

   });
   $('.completeContainer').click(function(){
        $('.completeContainer').hide();
        $('.errorOverlay').hide();

   });

    $('.errorOverlay').click(function(){
        $('.completeContainer').hide();
        $('.errorOverlay').hide();
        $('.errorMessage').hide();

   });

    //we run the accordion code, for the entry screen
    $(".options .option").click(function() {
      if($(this).next("div").is(":visible")){
        $(this).next("div").hide(0);
      } else {
        $(".option .option-content").hide(0);
        $(this).next("div").show(0);
      }
    });

    $('.login').click(function(){
        $('.register_content').hide();
        $('.login_content').show();

    });
    $('.register').click(function(){
        $('.register_content').show();
        $('.login_content').hide();

    });

   
    
    //If you set to invisible last time, stay that way:

     var visible = localStorage.getItem("visible");
    if (visible == "no") {
        $('#visible').remove();
        $('#visibility').html('<img src="img/not-visible.png" id="invisible" onclick="visible()"/> <p id="lockdownarrow">&darr;</p> <p id="lockdowntext">You can\'t use the app in invisibility mode.</p>');
        $('#lockhold').html('<div id="lockdown"></div>');
        $('.friends').html('Invisibility');

    }

     //If logged in, show logout, else show login/register
     setInterval(function() {
        var login = localStorage.getItem("loggedin");
        if (login == "yes"){
            $('#login-out').html('<a href="logout.html" data-rel="dialog" data-transition="slidedown">Logout</a>');
            $('#register-menu').html('');
            $('.pic-form').hide(500);
            $('.process').hide(500);
        }
        else {
            $('#login-out').html('<a href="login.html" data-rel="dialog" data-transition="slidedown">Login</a>');
            $('#register-menu').html('<a href="register.html" data-rel="dialog" data-transition="slidedown">Register</a>');
            $('.pic-form').show(500);

        }

       
    }, 1000);
   

    
    //Makes sure the first message seen, is "loading" if user is logged in when opening app:

    var login = localStorage.getItem("loggedin");
    if (login == "yes"){
        $('.nearbyInit').css('animation', 'fade 2s infinite').css('-webkit-animation', 'fade 2s infinite');
        $('.options').hide();
        $('#form').remove();
        $('#register-form').remove()




    }

    if (login != "yes"){
        $('#logout-form').remove();
        $('#linkuser-form').remove();
        $('.linkuser').remove();
        $('.options').show();
        $('.friends').html('');
        $('#link-form').hide();

    }

    function link(){
        var a=document.getElementsByTagName("a");
        for(var i=0;i<a.length;i++){
            a[i].onclick=function(){
                window.location=this.getAttribute("href");
                return false
            }       
        }
    }

    if ($('.login').length){
        // bind 'form' and provide a simple callback function 

               $('#form').submit(function() { 
                    // submit the form 
                    var queryString = $('#form').formSerialize(); 

                    // the data could now be submitted using $.get, $.post, $.ajax, etc 
                    $.get('http://dev1.intelli.dk/backend/login.php', queryString)
                    .done(function(data){

                        
                        var loginDetails = data.split(',');
                    

                        var uID = loginDetails[0];
                        var token = loginDetails[1];
                        var username = loginDetails[2];
                            
                            
                        if (typeof token != 'undefined')
                        {
                            localStorage.setItem("token", token);
                            localStorage.setItem("uID", uID);
                            localStorage.setItem("username", username);
                            localStorage.setItem("loggedin", "yes");
                            localStorage.setItem("visible", "yes");
                            location.reload();
                        }
                        else {
                            message("error", "Login is incorrect!");

                        }
                       
                        
                        
                    });
                    


                    // return false to prevent normal browser submit and page navigation 
                    return false; 


                });
                    
                    /*$('form').ajaxForm(function() {
                        $('#onit').html('<h1>You\'re signed in!<h1><p></p>');
                        alert('SENT');
                    }); */
                    
    }

    if ($('.register').length){

         $('#register-form').submit(function() { 
                    // submit the form 
                    var queryString = $('#register-form').formSerialize(); 

                    // the data could now be submitted using $.get, $.post, $.ajax, etc 
                    $.post('http://dev1.intelli.dk/backend/newUser.php', queryString)
                    .done(function(data){

                        if(data.indexOf("user already exsists") == -1){
                            var username;
                            username = queryString.split("&");
                            username = username['0'];
                            username = username.split("=");
                            username = username['1'];

                            $('#register-form').hide(0);
                            $('.register').hide();
                            message("success", "You\'ve sucessfully registered with username " + username + " proceed to login.");
                            

                        }
                        else {
                            message("error", "Username is taken, please choose another.");

                             

                        }
                        
                        
                    });
                    


                    // return false to prevent normal browser submit and page navigation 
                    return false; 


                });
    }



  if ($('.linkform').length){
        var token = localStorage.getItem("token");
        var uID = localStorage.getItem("uID");

        $('input[name=uid]').val(uID);
        $('input[name=token]').val(token);

        $('#link-form').submit(function() { 

            
            
            // submit the form 
            var queryString = $('#link-form').formSerialize(); 


            // the data could now be submitted using $.get, $.post, $.ajax, etc 
           $.post('http://dev1.intelli.dk/backend/linkUsers.php', queryString)
            .done(function(data){


                if (data.indexOf("not logged in") == -1){
                    if(data.indexOf("User does not exist") ==-1){
                        var username;
                        username = queryString.split('&');
                        username = username[0].split('=');
                        username = username[1];
                        message("success", "You have successfully linked " + username);
                        setTimeout(function () {
                          message("remove");
                        }, 3000);
                    }
                else{
                    message("error", "User does not exist.");
                      setTimeout(function () {
                          message("remove");
                        }, 3000);
                }


                }
                else {
                    message("error", "You are not logged in");
                      setTimeout(function () {
                          message("remove");
                        }, 3000);
                }

                
                
            });
                    


                    // return false to prevent normal browser submit and page navigation 
                    return false; 


                }); 
    } 

    if ($('.logout').length){

         $('#logout-form').submit(function() { 
                    // submit the form 
                    var queryString = $(this).formSerialize(); 

                    // the data could now be submitted using $.get, $.post, $.ajax, etc 
                    $.post('http://dev1.intelli.dk/backend/logout.php', queryString)
                    .done(function(data){
                        $('#logout-form').remove();
                        localStorage.setItem("loggedin", "no");
                        localStorage.setItem("uID", "nan");
                        localStorage.setItem("token", "nan");

                           location.reload();

                       
                    });
                    


                    // return false to prevent normal browser submit and page navigation 
                    return false; 


                });
    }


    //Run the first time, if logged in and visible is yes
     var login = localStorage.getItem("loggedin");
    if (login == "yes"){
        var visible = localStorage.getItem("visible");
        if (visible == "yes") {
            navigator.geolocation.getCurrentPosition(sucessHandler, errorHandler);
        }
    }

    //Run every 20 seconds
    setInterval(function() {
        
        if (localStorage.getItem("visible") == "yes") {
            if (localStorage.getItem("loggedin") == "yes"){
                navigator.geolocation.getCurrentPosition(sucessHandler);
            }
        }
    }, 20000); 

    
    var errorCount = 0;
    //If we get the location, we query our nearby.php for friends nearby.
    function sucessHandler(geolocation) {
		var token = localStorage.getItem("token");
        var uID = localStorage.getItem("uID");

        
        //var li = token1.length -1;
		//var token = token1.slice(0, li);

      
		
		//Here is our get function which will call nearby.php with useriD, location and range (has a default if not set.)
        $.post("http://dev1.intelli.dk/backend/nearby.php", { locY: geolocation.coords.latitude, locX: geolocation.coords.longitude, uid: uID, token: token}) 
        .done(function(data) {




            $('.nearbyInit').css('animation', 'nan').css('-webkit-animation', 'nan');
            message("remove");	  
             if(data.indexOf("not logged in") == -1){
                var friendArr = eval(data);
                // var friendArr = friendArr1.splice(0, 4);
                $('.friends').html('');
                for (var i = 0; i < friendArr.length; i++) {
                    friend = friendArr[i];
                    friend['distance'] = friend['distance']*1000;
                    friend['distance'] = friend['distance'].toFixed(0)
                    if(friend['user'] == null){
                        $('.friends').html("No friends nearby!!");
                    }
                    else if(friend['user'] == "not logged in"){
                        message("error","Error logging in, please log out and log in again!");
                    }
                    else{
                        var iCount = 0;
                        $('.friends').append('<div class="friend"><img src="' + friend['imageurl'] + '"/><div class="name">' + friend['user']+ '</div><div class="distance">' + friend['distance'] + ' m</div></div>');
                        if (friend['telephone'] != '') {
                            $('.friends').append('<div class="contact"><a href="tel:' + friend['telephone'] + '">Call</a> &nbsp;&nbsp; <a href="sms:' + friend['telephone'] + '">Text</a>' + '</div>');
                        }  
                        else{
                            $('.friends').append('<div class="contact">No contact details</div>');
                        } 
                    }    
                    };
                    //Run the accordion code
                    $(".friends .friend").click(function() {

                      if($(this).next("div").is(":visible")){
                        $(this).next("div").slideUp("fast");



                        
                      } else {
                        $(".friends .contact").slideUp("fast");
                        $(this).next("div").slideToggle("fast");
                      }
                    });


                }
                else {

                    message("error", "An error occured, please login again.");

                  
                  
                    localStorage.setItem("loggedin", "no");
                    setTimeout(function () {
                        location.reload();
                    }, 2000); 
                    


                }
			
        })
    
        .fail(function(data) {

            
            if (errorCount > 4 && errorCount < 15) {
                $('.loader').remove();
                message("remove");
                message("error", "Connection error, reconnecting ...");
               
            };

            if (errorCount > 14) {
                $('.loader').remove();

                message("remove");
                message("error", "Could not connect, please check your settings and relaunch the app.");
            };
           
            
            setTimeout(function () {
                navigator.geolocation.getCurrentPosition(sucessHandler);
            }, 5000); 
            
            errorCount++;
            
        });

    }


    //If we fail to get geo location
    function errorHandler() {
        message("error", "Could not find your location, please make sure you have granted access.");
    }
  
  //Handler for the file upload

        $(':file').change(function(){
            var file = this.files[0];
            var name = file.name;
            var size = file.size;
            var type = file.type;
            //Your validation

            if (type == "image/jpeg" || type == "image/png" || type == "image/gif"){
               

                     $(':button').click(function(){
                       $('.process').show();
                        var formData = new FormData($('.pic-form')[0]);
                        $.ajax({
                            url: 'http://dev1.intelli.dk/backend/upload.php',  //Server script to process data
                            type: 'POST',
                            xhr: function() {  // Custom XMLHttpRequest
                                var myXhr = $.ajaxSettings.xhr();
                                if(myXhr.upload){ // Check if upload property exists
                                    myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
                                }
                                return myXhr;
                            },
                            //Ajax events

                           
                            // Form data
                            data: formData,
                            //Options to tell jQuery not to process data or worry about content-type.
                            cache: false,
                            contentType: false,
                            processData: false
                        })
                            .done(function(data){
                                $('input[name=imgurl]').val(data);
                            });
                    });
                
            }
            else{
                message("error", "This is not a picture.");
                  setTimeout(function () {
                          message("remove");
                        }, 3000);
            }
        });
       
       
        function progressHandlingFunction(e){
            if(e.lengthComputable){
                $('progress').attr({value:e.loaded,max:e.total});
            }
            if(e.loaded == e.total){
                $('progress').hide();
                $('.uploadButton').hide();
                $('.fileCon').html('Upload completed');
                $('input[type="file"]').hide();

                /*
                $('.completeContainer').slideToggle(200); 
                $('.errorOverlay').show(); 
                $('.completeContainer').html('<p>Upload Completed</p>');*/ 


            }
        }
    

   
})
