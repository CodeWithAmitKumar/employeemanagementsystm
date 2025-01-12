// Automatically close all alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.classList.remove('show');
        alert.classList.add('fade'); // Add fade effect
        setTimeout(() => alert.remove(), 300); // Remove element from DOM after fade
    });
}, 2999);

document.addEventListener("DOMContentLoaded", () => {
    const successAlert = document.getElementById("success-alert");
    if (successAlert) {
        setTimeout(() => {
            window.location.href = "/employeedashboard/login/login.php";
        }, 3000); 
    }
});
