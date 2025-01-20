// Sidebar Toggle
const hamburger = document.querySelector(".toggle-btn");
const toggler = document.querySelector("#icon");

hamburger.addEventListener("click", function () {
    const sidebar = document.querySelector("#sidebar");
    sidebar.classList.toggle("expand");
    toggler.classList.toggle("bxs-chevrons-right");
    toggler.classList.toggle("bxs-chevrons-left");
});

// Employee Table Management
document.addEventListener("DOMContentLoaded", () => {
    const saveEmployeeButton = document.getElementById("saveEmployeeButton");
    const employeeTableBody = document.getElementById("employee-table-body");
    let rowBeingEdited = null; // Track row being edited

    // Function to create a table row
    const createTableRow = (employee) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${employee.id}</td>
            <td>${employee.name}</td>
            <td>${employee.employeeID}</td>
            <td>${employee.department}</td>
            <td>${employee.email}</td>
            <td>${employee.phone}</td>
            <td>${employee.shiftTime}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn">Edit</button>
                <button class="btn btn-danger btn-sm delete-btn">Delete</button>
            </td>
        `;
        return row;
    };

    // Add or update employee to the table and backend
    saveEmployeeButton.addEventListener("click", function () {
        const form = document.getElementById("addEmployeeForm");
        const formData = new FormData(form);

        // Send form data to the backend using fetch
        fetch("store.php", {  // Update with the actual PHP script path
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Employee added successfully!");

                    // Collect form data after successful response
                    const name = document.getElementById("employeeName").value;
                    const employeeID = document.getElementById("employeeID").value;
                    const department = document.getElementById("department").value;
                    const email = document.getElementById("email").value;
                    const phone = document.getElementById("phone").value;
                    const shiftTime = document.getElementById("shiftTime").value;

                    const employee = {
                        id: Date.now(), 
                        name,
                        employeeID,
                        department,
                        email,
                        phone,
                        shiftTime,
                    };

                    if (rowBeingEdited) {
                        // Update the row if editing
                        rowBeingEdited.innerHTML = createTableRow(employee).innerHTML;
                        rowBeingEdited = null; // Reset edit tracking
                    } else {
                        // Add a new row
                        const row = createTableRow(employee);
                        employeeTableBody.appendChild(row);
                    }

                    // Reset the form and close the modal
                    form.reset();
                    const modal = new bootstrap.Modal(document.getElementById("addEmployeeModal"));
                    modal.hide(); // Close the modal after saving
                } else {
                    alert("Failed to add employee. Try again.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });

    // Delegate edit and delete actions
    employeeTableBody.addEventListener("click", (e) => {
        if (e.target.classList.contains("edit-btn")) {
            // Editing an employee
            const row = e.target.closest("tr");
            rowBeingEdited = row;

            const cells = row.children;
            document.getElementById("employeeName").value = cells[1].textContent;
            document.getElementById("employeeID").value = cells[2].textContent;
            document.getElementById("department").value = cells[3].textContent;
            document.getElementById("email").value = cells[4].textContent;
            document.getElementById("phone").value = cells[5].textContent;
            document.getElementById("shiftTime").value = cells[6].textContent;

            // Show the modal for editing
            const modal = new bootstrap.Modal(document.getElementById("addEmployeeModal"));
            modal.show();
        } else if (e.target.classList.contains("delete-btn")) {
            // Deleting an employee
            const row = e.target.closest("tr");
            const employeeID = row.children[2].textContent; // Use employee ID for deletion

            // Send delete request to the backend
            fetch("store.php", {  // Update with the actual PHP script path
                method: "POST",
                body: JSON.stringify({ action: "delete", employeeID: employeeID }),
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        row.remove(); // Remove the row from the table
                    } else {
                        alert("Failed to delete employee. Try again.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        }
    });
});
