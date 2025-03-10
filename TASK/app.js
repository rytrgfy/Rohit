
$(document).ready(function () {
    $("#myform").validate({
        rules: {
            name: { required: true },
            mobile: { required: true, digits: true, minlength: 10, maxlength: 13 },
            email: { required: true, email: true },
            address: { required: true },
            age: { required: true, number: true, min: 1 },
            gender: { required: true },
            // photo: { required: true }
        },
        messages: {
            name: "Name is required",
            mobile: "Enter a valid 10-13 digit number",
            email: "Enter a valid email",
            address: "Address is required",
            age: "Enter a valid age",
            gender: "Please select a gender",
            // photo: "Upload a photo"
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "gender") {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            alert("Form submitted successfully!");
            form.submit();
        }
    });
});
