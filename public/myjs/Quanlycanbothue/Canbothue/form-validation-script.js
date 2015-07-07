var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { alert("submitted!"); }
    });

    $().ready(function() {
        // validate the comment form when it is submitted
        $("#commentForm").validate();

        // validate signup form on keyup and submit
        $("#signupForm").validate({
            rules: {
            	TenUser: "required",
/*                lastname: "required",*/
                MaUser: {
                    required: true,
                    minlength: 9
                },
                MatKhau: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#MatKhau"
                },
                Email: {
                    required: true,
                    email: true
                },
                topic: {
                    required: "#newsletter:checked",
                    minlength: 2
                },
                agree: "required"
            },
            messages: {
            	TenUser: "Vui lòng nhập tên cán bộ thuế!",
                /*lastname: "Please enter your lastname",*/
                MaUser: {
                    required: "Vui lòng nhập mã cán bộ",
                    minlength: "Mã cán bộ phải có ít nhất 9 ký tự"
                },
                MatKhau: {
                    required: "Vui lòng nhập password",
                    minlength: "Password phải có ít nhất 5 ký tự"
                },
                confirm_password: {
                    required: "Vui lòng nhập lại password",
                    minlength: "Password phải có ít nhất 5 ký tự",
                    equalTo: "Vui lòng xác nhận lại password chính xác!"
                },
                Email: "Vui lòng nhập đúng định dạng email",
                agree: "Please accept our policy"
            }
        });

        $("#MaUser").blur(function(){
        	//ktMaUser
        	MaUser =  $("#MaUser").val();
        	$("span .MaUser").css("display","none");
        	$.get("ktMaUser", { MaUser : MaUser}, function(json){
        		
        		if(json.ktMaUser == false)
    			{
        			
        			$("span.MaUser").css("display","inline");
        			$("span.MaUser").css("color","red");
        			$("span.MaUser").html('Mã user này đã tồn tại! Bạn không thể sử dụng mã này!');
    			}
        		else if(json.ktMaUser == true){
        			if(MaUser.length >= 9){
	        			$("span.MaUser").css("display","inline");
	        			$("span.MaUser").css("color","green");
	        			$("span.MaUser").html('Bạn có thể sử dụng mã này!');
        			}else {
        				$("span.MaUser").html(' ');
        			}
        		}
        	}, "json");
        	
        });
        
        $("#Email").blur(function(){
        	//ktMaUser
        	Email =  $("#Email").val();
        	$("span .Email").css("display","none");
        	$.get("ktEmail", { Email : Email}, function(json){
        		
        		if(json.ktEmail == false)
    			{
        			$("span.Email").css("display","inline");
        			$("span.Email").css("color","red");
        			$("span.Email").html('Email này đã tồn tại!');
    			}
        		else
        		{
        			$("span.Email").html(' ');
        		}
        	}, "json");
        	
        });
        // propose username by combining first- and lastname
/*        $("#username").focus(function() {
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            if(firstname && lastname && !this.value) {
                this.value = firstname + "." + lastname;
            }
        });*/

        //code to hide topic selection, disable for demo
        var newsletter = $("#newsletter");
        // newsletter topics are optional, hide at first
        var inital = newsletter.is(":checked");
        var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
        var topicInputs = topics.find("input").attr("disabled", !inital);
        // show when newsletter is checked
        newsletter.click(function() {
            topics[this.checked ? "removeClass" : "addClass"]("gray");
            topicInputs.attr("disabled", !this.checked);
        });
    });


}();