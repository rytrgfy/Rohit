$(document).ready(function () {

    // No spaces validation
    $.validator.addMethod("noleadingspace", function (value, element) {
        return this.optional(element) || /^\S.*$/.test(value); // Ensures first character is not a space
    }, "Leading spaces are not allowed");

    // Custom regex validation method
    $.validator.addMethod("regex", function (value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Invalid format");

    // Validation
    $("#myform").validate({
        rules: {
            name: { required: true, regex: /^[a-zA-Z'.\s]{1,40}$/, noleadingspace: true },
            mobile: { required: true, digits: true, minlength: 10, maxlength: 13 },
            email: { required: true, email: true, noleadingspace: true },
            address: { required: true, noleadingspace: true },
            age: { required: true, number: true, min: 1 },
            gender: { required: true },
            photo: { 
                required: function () {
                    return $.trim($("#old_photo").val()) === ""; 
                }
            }
        },
        messages: {
            name: "Enter a valid name without spaces and numbers",
            mobile: "Enter a valid 10-13 digit number",
            email: "Enter a valid email",
            address: "Enter a valid address without spaces",
            age: "Enter a valid age",
            gender: "Please select a gender",
            photo: ""
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "gender" || element.attr("name") == "photo") {
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
