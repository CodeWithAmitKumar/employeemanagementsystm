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

    // Function to fetch employees from the server and render them
    const fetchEmployees = () => {
        fetch("store.php") // Adjust URL if needed
            .then((response) => response.json())
            .then((data) => {
                if (Array.isArray(data)) {
                    renderEmployeeTable(data);
                } else {
                    console.error("Unexpected data format:", data);
                }
            })
            .catch((error) => {
                console.error("Error fetching employees:", error);
            });
    };

    // Function to render employees in the table
    const renderEmployeeTable = (employees) => {
        employeeTableBody.innerHTML = ""; // Clear table before rendering
        employees.forEach((employee, index) => {
            const row = createTableRow({
                ...employee,
                id: index + 1, // Reassign IDs based on the current index
            });
            employeeTableBody.appendChild(row);
        });
    };

    // Function to create a table row
    const createTableRow = (employee) => {
        const row = document.createElement("tr");
        row.dataset.id = employee.id; // Add a data attribute for easier manipulation
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

    // Add or update employee in the table and backend
    saveEmployeeButton.addEventListener("click", function () {
        const form = document.getElementById("addEmployeeForm");
        const formData = new FormData(form);

        fetch("store.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Employee saved successfully!");

                    // Collect form data
                    const employee = {
                        id: rowBeingEdited
                            ? rowBeingEdited.dataset.id
                            : employeeTableBody.children.length + 1,
                        name: formData.get("name"),
                        employeeID: formData.get("employeeID"),
                        department: formData.get("department"),
                        email: formData.get("email"),
                        phone: formData.get("phone"),
                        shiftTime: formData.get("shiftTime"),
                    };

                    if (rowBeingEdited) {
                        // Update existing row
                        const cells = rowBeingEdited.children;
                        cells[1].textContent = employee.name;
                        cells[2].textContent = employee.employeeID;
                        cells[3].textContent = employee.department;
                        cells[4].textContent = employee.email;
                        cells[5].textContent = employee.phone;
                        cells[6].textContent = employee.shiftTime;
                        rowBeingEdited = null;
                    } else {
                        // Add new row
                        const row = createTableRow(employee);
                        employeeTableBody.appendChild(row);
                    }

                    form.reset();
                    const modal = new bootstrap.Modal(
                        document.getElementById("addEmployeeModal")
                    );
                    modal.hide(); // Close modal
                } else {
                    alert("Failed to save employee. Try again.");
                }
            })
            .catch((error) => console.error("Error:", error));
    });

    // Delegate edit and delete actions
    employeeTableBody.addEventListener("click", (e) => {
        if (e.target.classList.contains("edit-btn")) {
            // Edit action
            const row = e.target.closest("tr");
            rowBeingEdited = row;

            const cells = row.children;
            document.getElementById("employeeName").value = cells[1].textContent;
            document.getElementById("employeeID").value = cells[2].textContent;
            document.getElementById("department").value = cells[3].textContent;
            document.getElementById("email").value = cells[4].textContent;
            document.getElementById("phone").value = cells[5].textContent;
            document.getElementById("shiftTime").value = cells[6].textContent;

            const modal = new bootstrap.Modal(
                document.getElementById("addEmployeeModal")
            );
            modal.show();
        } else if (e.target.classList.contains("delete-btn")) {
            // Delete action
            const row = e.target.closest("tr");
            const employeeID = row.children[2].textContent;

            fetch("store.php", {
                method: "POST",
                body: JSON.stringify({ action: "delete", employeeID }),
                headers: { "Content-Type": "application/json" },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        row.remove(); // Remove row
                        reassignIDs(); // Reassign IDs after deletion
                    } else {
                        alert("Failed to delete employee. Try again.");
                    }
                })
                .catch((error) => console.error("Error:", error));
        }
    });

    // Reassign IDs to table rows after deletion
    const reassignIDs = () => {
        const rows = employeeTableBody.children;
        Array.from(rows).forEach((row, index) => {
            row.children[0].textContent = index + 1; // Update ID cell
            row.dataset.id = index + 1; // Update dataset ID
        });
    };

    // Initial fetch of employees
    fetchEmployees();
});
