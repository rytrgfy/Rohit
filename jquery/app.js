$(document).ready(function () {

    $.validator.addMethod("notToday", function (value) {
        let inputDate = new Date(value);
        let today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time for accurate comparison
        return inputDate < today;
    }, "DOB must not be today's date" );

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

// to check jquey is properly working or not
console.log(typeof jQuery); // Should print "function"
console.log(typeof $.fn.validate); // Should print "function"
