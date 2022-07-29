$.validator.addMethod("phone_validate",function(value,element){return this.optional(element)||/^[0-9 _.,!()+=`,"@$#%*-]*$/i.test(value);},"Please enter valid phone number");$.validator.setDefaults({highlight:function(element){$(element).closest('.form-group').addClass('has-error');},unhighlight:function(element){$(element).closest('.form-group').removeClass('has-error');},errorElement:'span',errorClass:'help-block',errorPlacement:function(error,element){if(element.parent('.input-group').length){error.insertAfter(element.parent());}else{if(element.attr('type')=='radio'||element.attr('type')=='checkbox'){error.insertAfter(element.parent().parent());}else{error.insertAfter(element);}}}});$('.profile_form').validate({rules:{lead_name:{require_from_group:[1,".name-group"]},lead_email:{require_from_group:[1,".name-group"]},user_license_number:{required:{depends:function(element){return $("#user_license_notify").is(":checked");}}},user_old_password:{minlength:6,maxlength:16,remote:baseUrl+"/user/checkpassword"},user_password:{minlength:6,maxlength:16},user_phone:{minlength:10},user_rpassword:{equalTo:'#user_password',minlength:6,maxlength:16},user_email:{required:true,email:true,},location:{required:{depends:function(element){return $("#miles").is(":filled");}}},miles:{required:{depends:function(element){return $("#location").is(":filled");}}},},messages:{user_rpassword:{equalTo:"Your password and confirmation password do not match. Please enter matching passwords."}}});$('#new_temp_new').validate({});jQuery.validator.addClassRules("checkemail",{remote:baseUrl+"/user/checkemail"});jQuery.validator.addClassRules("emailexists",{remote:baseUrl+"/user/checkemail/rev/1"});jQuery.validator.addClassRules("checkemail_exclude",{remote:baseUrl+"/user/checkemail?exclude=1"});jQuery.validator.addClassRules("checkprofileurl",{remote:baseUrl+"/landingpages/checkprofileurl/lp_id/"+$('#lp_id').val()});jQuery.validator.addClassRules("profilefiletype",{extension:"jpg|png|jpeg|bmp|png|gif|JPG|PNG|JPEG|BMP|GIF"});function AddIpData(lp_id){$.ajax({url:baseUrl+'/index/addip/lp_id/'+lp_id,type:"POST",async:false,success:function(data){}});}$(document).ready(function(){if($('.slider1').length>0){$('.slider1').bxSlider({minSlides:1,maxSlides:1,slideMargin:10});}if($('.slimtest1').length>0){$('.slimtest1').slimScroll({height:'150px'});}if(controller=='user')Login.init();});if($('.showcalender').length>0){$(function(){$(".showcalender").datetimepicker({inline:true,timepicker:false});});}function updateSize(){var vidEl=$('#video_parent');var mediaAspect=16/9;currMediaType='video';var windowW=$(window).width();var windowH=$(window).height();var windowAspect=windowW/windowH;if(windowAspect<mediaAspect){if(currMediaType==='video'){$(vidEl).css('top',0).css('left',-(windowH*mediaAspect-windowW)/2).css('height',windowH);}else{$('#fbg-video-image').width(windowH*mediaAspect).height(windowH).css('top',0).css('left',-(windowH*mediaAspect-windowW)/2);}}else{if(currMediaType==='video'){$(vidEl).css('top',-(windowW/mediaAspect-windowH)/2).css('left',0).css('height',windowW/mediaAspect);}else{$('#fbg-video-image').width(windowW).height(windowW/mediaAspect).css('top',-(windowW/mediaAspect-windowH)/2).css('left',0);}}}window.fbAsyncInit=function(){FB.init({appId:'1030961003628319',status:true,xfbml:true});};(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.src="//connect.facebook.net/en_US/all.js";fjs.parentNode.insertBefore(js,fjs);}(document,'script','facebook-jssdk'));function FBShareOp(share_title,share_url,share_desc,share_img){FB.ui({method:'feed',name:share_title,link:share_url,picture:share_img,caption:share_desc,description:share_desc,},function(response){if(response&&response.post_id){}else{}});}function change_bg_image(){
	
    $(".file-upload").fileupload({
        url: SITEURL + "/change-bg-image",
        add: function(a, b) {
            var c = b.originalFiles[0].name, d = c.substr(c.lastIndexOf(".") + 1), e = [ "jpg", "jpeg", "png", "JPG", "JPEG", "PNG" ];
            $.inArray(d, e) < 0 ? ($(".fileinput-filename").html(""), $(".fileinput").removeClass("fileinput-exists"), 
            $(".fileinput").addClass("fileinput-new"), alert("Please Provide valid file")) : b.submit();
        },
        done: function(a, b) {
			console.log(b);
            if ($(".fileinput-filename").html(""), $(".fileinput").removeClass("fileinput-exists"), 
            $(".fileinput").addClass("fileinput-new"), 0 == b.result) {$("#pic-progress").hide(), $('#landing_pages button').removeAttr('disabled'),
            alert("Please Provide valid file");} else if( $(".fileinput").addClass("fileinput-new"), 2 == b.result){
				$("#pic-progress").hide(), 
				$('#landing_pages button').removeAttr('disabled');
				alert('Please upload image with min 1600 X 1200 Dimension');
			}else {
                b = JSON.parse(b.result);
			   // b= b.result;
                $(".bg_image img").length > 0 && $(".bg_image img").remove(), $(".bg_image img").length > 0 && $(".bg_image img").remove(), 
                $(".user-bg img").length > 0 && $(".user-bg img").remove();
                var c = '<img src="' + AVTBIGURL + '/212/' + b + '" style="max-width:100%;height:100px;" alt="big-avatar" class="thumb-lg" />', d = AVTBIGURL + "/" + b;
                $(".bg_image").html(c);
				
				//$(".demothumb").css("background-image", "url(" + d + ")"),
                $("#pic-progress").hide();
				$('#landing_pages button').removeAttr('disabled');
				$('#lp_bg_image').val(b);
            }
        },
        progress: function(a, b) {
			$('#landing_pages button').attr('disabled','disabled');
            $("#pic-progress").show();
            var c = parseInt(b.loaded / b.total * 100, 10);
            $("#pic-bar").width(c + "%"), $("#pic-progress").css("width", c + "%");
        }
    });
}