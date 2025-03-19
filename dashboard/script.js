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
    const addEmployeeForm = document.getElementById("addEmployeeForm");
    let currentEmployeeId = null;

    // Fetch employees from server
    const fetchEmployees = () => {
        // Use absolute path to store.php to avoid path resolution issues
        const storePath = window.location.pathname.includes('/dashboard.php') ? 
            'store.php' : '/employeedashboard/dashboard/store.php';
            
        fetch(storePath)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || `Server error: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (Array.isArray(data)) {
                    renderEmployeeTable(data);
                } else if (data.message) {
                    showMessage(data.message, 'danger');
                } else {
                    console.error("Invalid data format received", data);
                    showMessage('Invalid data format received from server', 'danger');
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
                showMessage(`Error loading employees: ${error.message}`, 'danger');
            });
    };

    // Render employee table
    const renderEmployeeTable = employees => {
        employeeTableBody.innerHTML = employees.map(employee => {
            return `
            <tr data-id="${employee.id}">
                <td>${employee.id}</td>
                <td>${employee.name}</td>
                <td>${employee.employee_id}</td>
                <td>${employee.department}</td>
                <td>${employee.email}</td>
                <td>${employee.phone}</td>
                <td>${employee.shift_time}</td>
                <td>
                    ${employee.document ? `<a href="uploads/${employee.document}" target="_blank">View Document</a>` : 'No document'}
                </td>
                <td>
                    <button class="btn btn-warning btn-sm edit-btn">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                </td>
            </tr>
            `;
        }).join('');
    };

    // Event listeners
    addEmployeeForm.addEventListener("submit", function(e) {
        e.preventDefault();
        
        console.log("Form submission triggered");
        
        // Check form validity
        if (!addEmployeeForm.checkValidity()) {
            console.log("Form is invalid");
            addEmployeeForm.reportValidity();
            return false;
        }
        
        const formData = new FormData(addEmployeeForm);
        if (currentEmployeeId) formData.append('id', currentEmployeeId);
        
        // Debug output - show all form data being sent
        console.log("Form data entries:");
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        // Use the same path resolution as in fetchEmployees
        const storePath = window.location.pathname.includes('/dashboard.php') ? 
            'store.php' : '/employeedashboard/dashboard/store.php';
        
        console.log("Submitting to:", storePath);

        // Add a loading indicator
        const submitBtn = document.getElementById('saveEmployeeButton');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

        fetch(storePath, {
            method: "POST",
            body: formData
        })
        .then(response => {
            console.log("Response status:", response.status);
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || `Server error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log("Response data:", data);
            
            // Close the modal
            const modalEl = document.getElementById("addEmployeeModal");
            const bsModal = bootstrap.Modal.getInstance(modalEl);
            if (bsModal) {
                bsModal.hide();
            }
            
            // Show success/error message after modal closes
            setTimeout(() => {
                if (data.success) {
                    showMessage(`Employee ${currentEmployeeId ? 'updated' : 'added'} successfully!`, 'success');
                    fetchEmployees();
                    resetForm();
                } else {
                    showMessage(data.message || data.error || 'Operation failed', 'danger');
                }
            }, 300);
        })
        .catch(error => {
            console.error("Form submission error:", error);
            showMessage(`Submission failed: ${error.message}`, 'danger');
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
    
    employeeTableBody.addEventListener("click", e => {
        const row = e.target.closest('tr');
        if (!row) return;

        if (e.target.classList.contains("edit-btn")) handleEdit(row);
        if (e.target.classList.contains("delete-btn")) handleDelete(row);
    });

    // Edit employee
    const handleEdit = row => {
        const cells = row.cells;
        currentEmployeeId = row.dataset.id;
        
        document.getElementById("employeeName").value = cells[1].textContent;
        document.getElementById("employeeID").value = cells[2].textContent;
        document.getElementById("department").value = cells[3].textContent;
        document.getElementById("email").value = cells[4].textContent;
        document.getElementById("phone").value = cells[5].textContent;
        document.getElementById("shiftTime").value = cells[6].textContent;
        document.getElementById("document").required = false; // Remove required on edit

        new bootstrap.Modal(document.getElementById("addEmployeeModal")).show();
    };

    // Delete employee
    const handleDelete = row => {
        if (!confirm("Are you sure you want to delete this employee?")) return;

        // Use the same path resolution as in other fetch calls
        const storePath = window.location.pathname.includes('/dashboard.php') ? 
            'store.php' : '/employeedashboard/dashboard/store.php';

        fetch(`${storePath}?id=${encodeURIComponent(row.dataset.id)}`, {
            method: "DELETE"
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Employee deleted successfully!', 'success');
                row.remove();
            } else {
                showMessage('Failed to delete employee.', 'danger');
            }
        })
        .catch(error => showMessage('Delete operation failed.', 'danger'));
    };

    // Utility functions
    const resetForm = () => {
        addEmployeeForm.reset();
        currentEmployeeId = null;
        document.getElementById("document").required = true;
    };

    const showMessage = (text, type) => {
        console.log(`Showing message: ${text} (${type})`);
        
        // Create a unique ID for the modal
        const modalId = `messageModal-${Date.now()}`;
        
        // Create modal HTML
        const modalHTML = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-${type} text-white">
                        <h5 class="modal-title">${type === 'success' ? 'Success' : 'Error'}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center">
                            <i class="bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} fs-1 me-3 text-${type}"></i>
                            <span>${text}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-${type}" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        // Add modal to the document body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Get reference to the created modal element
        const modalElement = document.getElementById(modalId);
        
        try {
            console.log('Initializing modal with Bootstrap');
            
            // Make sure Bootstrap is loaded
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap is not loaded');
                alert(`${type === 'success' ? 'Success' : 'Error'}: ${text}`);
                return;
            }
            
            // Initialize and show the modal
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Set up auto-close and cleanup
            setTimeout(() => {
                try {
                    modal.hide();
                    
                    // Remove the modal from DOM after it's hidden
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        modalElement.remove();
                    });
                } catch (e) {
                    console.error('Error hiding modal:', e);
                    if (document.body.contains(modalElement)) {
                        modalElement.remove();
                    }
                }
            }, 5000);
            
        } catch (e) {
            console.error('Error showing modal:', e);
            alert(`${type === 'success' ? 'Success' : 'Error'}: ${text}`);
            if (document.body.contains(modalElement)) {
                modalElement.remove();
            }
        }
        
        // Also show a regular alert
        const alertContainer = document.querySelector('.container.my-4');
        if (alertContainer) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
            alertDiv.innerHTML = `
                ${text}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.prepend(alertDiv);
            
            // Auto-remove the alert after 5 seconds
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
        }
    };

    // Initial load
    fetchEmployees();
}); 