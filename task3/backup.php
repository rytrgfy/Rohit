<!-- here only backup code are presennt     -->

<script>
        $(document).ready(function () {
            console.log("ajax loaded");

            // Load employees on page load
            $.post("load-employee.php", function (data) {
                $("#employee_names").append(data);
            });

            // Add employee to list & remove from dropdown
            $("#add_employee").click(function () {
                let selected_option = $("#employee_names option:selected");
                let emp_id = selected_option.val();
                let emp_name = selected_option.text();
                console.log(emp_name, emp_id);

                if (emp_id) {
                    $("#selected_employees_div").append(
                        `<div class="employee" data-id="${emp_id}">
                ${emp_name} <button class="remove_employee">Remove</button>
                <input type="hidden" name="selected_employees_div_data[]" value="${emp_id}">
            </div>`
                    );
                    selected_option.remove();

                    // Remove error message if employee is added
                    $("#employee_error").text("");
                } else {
                    $("#employee_error").text("Please select an employee.");
                }
            });

            // Remove employee from list & add back to dropdown
            $(document).on("click", ".remove_employee", function () {
                let employee_div = $(this).closest(".employee");
                let emp_id = employee_div.data("id");
                let emp_name = employee_div.text().replace("Remove", "").trim();

                $("#employee_names").append(`<option value="${emp_id}">${emp_name}</option>`);
                employee_div.remove(); // Remove from selected list
            });

            // Form submission validation
            $("my_form").submit(function (e) {
                let selected_employees_div_data = $("#selected_employees_div_data").children().length;
                let task_details = $("#task_details").val().trim();
                let is_valid = true;

                // Validate employee selection
                if (selected_employees_div_data === 0) {
                    $("#employee_error").text("Please select at least one employee.");
                    is_valid = false;
                } else {
                    $("#employee_error").text("");
                }

                // Validate task details
                if (task_details === "") {
                    $("#task_error").text("");
                    is_valid = false;
                } else {
                    $("#task_error").text("");
                }

                if (!is_valid) {
                    e.preventDefault();
                }
            });
        });
    </script>