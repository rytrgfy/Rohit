$(document).ready(function () {

    $("#myform").validate({
        rules: {
            name: { required: true },
            dob: { required: true },
            email: { required: true, email: true },
            address: { required: true },
            number: { required: true, digits: true, minlength: 10, maxlength: 13  },
            gender: { required: true },
            photo: { required: true },
            course: { required: true }
        },
        message: {
            name: "Name is required",
            dob: "dob must not exists todays date",
            email: "enter valid email",
            address: "enter your address",
            number: "value should be between 10 and 12",
            gender: "choose your gender",
            photo: "choose your image",
            course: "please choose your course"



        }, submitHandler: function (form) {
            alert("Form submitted successfully!");
            form.submit();
        }
    });
});