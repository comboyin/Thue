var Script = function () {

   
    $().ready(function() {
        // validate the comment form when it is submitted
        $("#commentForm").validate();

        // validate signup form on keyup and submit
        $("#setpassForm").validate({
            rules: {
                MatKhauCu: {
                    required: true,
                    minlength: 5
                },
                MatKhau: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#MatKhau"
                }
            },
            messages: {
                MatKhauCu: {
                    required: "Vui lòng nhập password",
                    minlength: "Password phải có ít nhất 5 ký tự"
                },
                MatKhau: {
                    required: "Vui lòng nhập password",
                    minlength: "Password phải có ít nhất 5 ký tự"
                },
                confirm_password: {
                    required: "Vui lòng nhập lại password",
                    minlength: "Password phải có ít nhất 5 ký tự",
                    equalTo: "Vui lòng xác nhận lại password chính xác!"
                }
            }
        });

    });


}();