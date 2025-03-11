$(document).ready(function () {
    // Custom regex validation method
    $.validator.addMethod("regex", function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Invalid format");

    $("#myform").validate({
        rules: {
            name: { required: true, regex: /^[a-zA-Z'.\s]{1,40}$/ },
            mobile: { required: true, digits: true, minlength: 10, maxlength: 13 },
            email: { required: true, email: true },
            address: { required: true },
            age: { required: true, number: true, min: 1 },
            gender: { required: true },
        },
        messages: {
            name: "Enter valid name without number",
            mobile: "Enter a valid 10-13 digit number",
            email: "Enter a valid email",
            address: "Address is required",
            age: "Enter a valid age",
            gender: "Please select a gender",
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
            form.submit(); // No need for preventDefault()
        }
    });
});
