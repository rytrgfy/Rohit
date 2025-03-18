<!-- here only backup code are presennt     -->

<script>
    $(document).ready(function () {
        console.log("ajax loaded");

        // Load employees on page load
        $.post("load-employee.php", function (data) {
            $(".dropdown_menu").append(data);
        });

        // jQuery for adding dropdown when add button is clicked
        $("#add_employee").click(function () {
            let clone = $(".dropdown_wrapper_child:first").clone(); // Clone first dropdown
            clone.find("select").val("").find("option").show(); // Reset dropdown selection & show all options
            $("#dropdown_container_main").append(clone); // Append cloned dropdown
        });

        // When selecting an employee, hide it from other dropdowns
        $(document).on("change", ".dropdown_menu", function () {
            let selected_emp_id = $(this).val();
            console.log("Selected employee ID = ", selected_emp_id);

            // Show all options first (reset hiding)
            $(".dropdown_menu option").show();

            // Hide already selected employees in other dropdowns
            $(".dropdown_menu").each(function () {
                let emp_id = $(this).val();
                if (emp_id) {
                    $(".dropdown_menu").not(this).find(`option[value="${emp_id}"]`).hide();
                }
            });
        });

        // When clicking "Remove", add employee back to all dropdowns and remove the div
        $(document).on("click", ".remove_employee", function () {
            let dropdown_wrapper = $(this).closest(".dropdown_wrapper_child");
            let emp_id = dropdown_wrapper.find("select").val();

            // Show removed employee in all dropdowns
            if (emp_id) {
                $(".dropdown_menu option[value='" + emp_id + "']").show();
            }

            // Remove dropdown wrapper
            dropdown_wrapper.remove();

            // Ensure at least one dropdown remains
            if ($(".dropdown_wrapper_child").length === 0) {
                alert("At least one employee selection is required!");
                $("#add_employee").trigger("click"); // Auto-add one dropdown if all are removed
            }
        });

        // Form validation
        $("#my_form").submit(function (e) {
            // Store selected employees in an array
            let selected_employees = [];

            // Collect all selected employee IDs
            $(".dropdown_menu").each(function () {
                let selected_emp_id = $(this).val();
                if (selected_emp_id) {
                    selected_employees.push(selected_emp_id);
                }
            });

            console.log("Final Selected Employees Array:", selected_employees);

            // Store the array in a hidden input before submitting
            $("#selected_employees_input").val(selected_employees.join(","));

            let task_details = $("#task_details").val().trim();
            let is_valid = true;

            if (selected_employees.length === 0) {
                $("#employee_error").text("Please select at least one employee.");
                is_valid = false;
            } else {
                $("#employee_error").text("");
            }

            if (task_details === "") {
                $("#task_error").text("Please enter task details.");
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






/* Reset default browser styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }

        /* Form container */
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 380px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Label styling */
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
        }

        /* Input fields */
        input {
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 16px;
        }

        /* Error message styling */
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Submit button styling */
        button {
            background-color: #28a745;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #218838;
        }

        /* Navigation links */
        .nav-links {
            margin-top: 15px;
        }

        .nav-links a {
            display: block;
            color: blue;
            text-decoration: none;
            font-size: 16px;
            margin: 5px 0;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
        }



        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            margin-top: auto;
        }

        .error {
            color: red;
        }

        body {
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        form {
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 2px 2px 5px gray;
            max-width: 350px;
            margin: auto;
        }

        h2 {
            color: black;
            font-size: 18px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        select,
        textarea,
        button {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid gray;
            margin-top: 5px;
        }

        .addbtn {
            background-color: green;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .addbtn {
            background-color: green;
            color: white;
            font-size: 16px;

        }

        .addbtn:hover {
            background-color: orange;
        }

        button {
            background-color: grey;
            color: #f8f9fa;
        }

        button:hover {
            background-color: hsl(0, 88.70%, 65.30%);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid gray;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: black;
            color: white;
        }

        tr:nth-child(even) {
            background-color: lightgray;
        }

        .highlight {
            border: 2px solid red;
            background-color: #ffe6e6;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .employee {
            color: cadetblue;
            font-size: x-large;
        }



        body {
            background-color: antiquewhite;
            display: flex;
            flex-direction: column;
            align-items: center;
            
            min-height: 100vh;
            margin: 0;
        }

        
        .container {
            display: flex;
            justify-content: space-between;
            
            align-items: center;
            width: 80%;
            
            max-width: 800px;
            
            background-color: gainsboro;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
            
        }

       
        .nav-links {
            margin-left: auto;   
            display: flex;
            gap: 10px;
        }

        
        a {
            display: block;
            padding: 8px 15px;
            background: rgb(84, 233, 116);
            color: black;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        a:hover {
            background:rgb(129, 131, 133);
            color: white;
        }

       
        table {
            width: 80%;
            max-width: 100%;
            margin: 30px auto;
            
            border-collapse: collapse;
            background: white;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 2px solid black;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: black;
            color: white;
            text-transform: uppercase;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            margin-top: auto;
        }