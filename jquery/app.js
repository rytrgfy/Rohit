$(document).ready(function () {

    

    $("#myform").validate({
        rules: {
            name: { required: true },
            dob: { required: true, notToday: true }, // Applied the custom rule
            email: { required: true, email: true },
            address: { required: true },
            number: { required: true, digits: true, minlength: 10, maxlength: 13 },
            gender: { required: true },
            photo: { required: true },
            course: { required: true }
        },
        messages: {
            name: "Name is required",
            dob: "DOB should be less then today",
            email: "Enter a valid email",
            address: "Enter your address",
            number: "Value should be between 10 and 13 digits",
            gender: "Choose your gender",
            photo: "Choose an image",
            course: "Please choose your course"
        },
        submitHandler: function (form) {
            alert("Form submitted successfully!");
            form.submit();
        }
    });
});

