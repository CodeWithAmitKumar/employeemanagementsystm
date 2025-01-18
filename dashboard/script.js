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
document.addEventListener('DOMContentLoaded', () => {
    const saveEmployeeButton = document.getElementById('saveEmployeeButton');
    const employeeTableBody = document.getElementById('employee-table-body');
    let rowBeingEdited = null; // Reference to the row being edited

    // Add or Update Employee Event
    saveEmployeeButton.addEventListener('click', () => {
        const employeeName = document.getElementById('employeeName').value.trim();
        const employeeID = document.getElementById('employeeID').value.trim();
        const department = document.getElementById('department').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const shiftTime = document.getElementById('shiftTime').value.trim();

        // Validate form fields
        if (!employeeName || !employeeID || !department || !email || !phone || !shiftTime) {
            alert("Please fill in all the fields.");
            return;
        }

        if (rowBeingEdited) {
            // Update the existing row
            const cells = rowBeingEdited.children;
            cells[1].textContent = employeeName;
            cells[2].textContent = employeeID;
            cells[3].textContent = department;
            cells[4].textContent = email;
            cells[5].textContent = phone;
            cells[6].textContent = shiftTime;

            alert("Employee details updated successfully!");
            rowBeingEdited = null; // Clear reference
        } else {
            // Add a new row to the table
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${employeeTableBody.rows.length + 1}</td>
                <td>${employeeName}</td>
                <td>${employeeID}</td>
                <td>${department}</td>
                <td>${email}</td>
                <td>${phone}</td>
                <td>${shiftTime}</td>
                <td>
                    <button class="btn btn-success btn-sm edit-btn">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                </td>
            `;
            employeeTableBody.appendChild(newRow);

            // Add Delete and Edit functionalities to the new row
            addRowEventListeners(newRow);
        }

        // Clear form fields and close modal
        document.getElementById('addEmployeeForm').reset();
        const modal = bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal'));
        modal.hide();
    });

    // Function to add event listeners to each row
    function addRowEventListeners(row) {
        const deleteButton = row.querySelector('.delete-btn');
        const editButton = row.querySelector('.edit-btn');

        // Delete functionality
        deleteButton.addEventListener('click', () => {
            row.remove();
            updateRowIDs(); // Update row IDs after deletion
            alert("Employee deleted successfully!");
        });

        // Edit functionality
        editButton.addEventListener('click', () => {
            const cells = row.querySelectorAll('td');
            document.getElementById('employeeName').value = cells[1].textContent;
            document.getElementById('employeeID').value = cells[2].textContent;
            document.getElementById('department').value = cells[3].textContent;
            document.getElementById('email').value = cells[4].textContent;
            document.getElementById('phone').value = cells[5].textContent;
            document.getElementById('shiftTime').value = cells[6].textContent;

            rowBeingEdited = row; // Store reference to the row being edited

            const modal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
            modal.show();
        });
    }

    // Function to update row IDs after deletion
    function updateRowIDs() {
        const rows = employeeTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            const idCell = row.children[0];
            idCell.textContent = index + 1; // Update ID to match row order
        });
    }

    // Fetch employees from PHP
    fetch('/employeedashboard/dashboard/employee/fetch_employees.php')
        .then(response => response.json())
        .then(data => {
            employeeTableBody.innerHTML = ''; // Clear existing rows
            data.forEach(employee => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${employee.id}</td>
                    <td>${employee.name}</td>
                    <td>${employee.employee_id}</td>
                    <td>${employee.department}</td>
                    <td>${employee.email}</td>
                    <td>${employee.phone}</td>
                    <td>${employee.shift_time}</td>
                    <td>
                        <button class="btn btn-success btn-sm edit-btn">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                    </td>
                `;
                employeeTableBody.appendChild(row);

                // Add Delete and Edit functionalities to each row
                addRowEventListeners(row);
            });
        })
        .catch(error => console.error('Error fetching employees:', error));
});
