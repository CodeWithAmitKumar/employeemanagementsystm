<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <!-- External Stylesheets -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar Section -->
        <aside id="sidebar">
            <div class="sidebar-header d-flex justify-content-between p-4">
                <div class="sidebar-logo">
                    <a href="javascript:void(0);" onclick="window.location.reload();" class="text-white fw-bold">UserDashboard</a>
                </div>
                <button class="toggle-btn" type="button">
                    <i id="icon" class='bx bxs-chevrons-right'></i>
                </button>
            </div>

            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="#edit" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="edit"> 
                        <i class='bx bx-user'></i>
                        <span>Profile</span>
                    </a>
                    <ul id="edit" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item">
                            <a href="/employeedashboard/dashboard/updateprofile/update_user.php" class="sidebar-link">
                                <i class='bx bxs-edit'></i>
                                <span>Edit Profile</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a href="#logout" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="logout"> 
                        <i class='bx bx-cog'></i>
                        <span>Setting</span>
                    </a>
                    <ul id="logout" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item">
                            <a href="/employeedashboard/homepage/index.php" class="sidebar-link">
                                <i class='bx bx-log-out'></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="https://www.linkedin.com/in/amit-web-developer/" class="sidebar-link">
                    <i class='bx bxl-linkedin'></i>
                    <span>LinkedIn</span>
                </a>
                <a href="https://github.com/CodeWithAmitKumar" class="sidebar-link">
                    <i class='bx bxl-github'></i>
                    <span>GitHub</span>
                </a>
                <a href="https://www.instagram.com/thatodiapila/" class="sidebar-link">
                    <i class='bx bxl-instagram'></i>
                    <span>Instagram</span>
                </a>

                <div class="sidebar-copyright">
                    <p>
                        <abbr title="This project is developed by Amit Kumar Patra">Copyright 2025. All rights reserved.</abbr>
                    </p>
                </div>
            </div>
        </aside>

        <!-- Main Content Section -->
        <div class="main">
            <!-- Navbar -->
            <nav class="navbar navbar-expand px-4 py-3">
                <form action="#" class="d-none d-sm-inline-block">
                    <div class="input-group input-group-navbar">
                        <input type="text" class="form-control border-0 rounded-0 pe-0" placeholder="Search By Employee id..." aria-label="Search">
                        <button class="btn border-0 rounded-0" type="button"><i class='bx bx-search'></i></button>
                    </div>
                </form>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="images/av.png" class="avatar img-fluid rounded-circle" alt="User Avatar" style="width: 50px; height: 50px;">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end rounded-0 border-0 shadow mt-3">
                                <a href="/employeedashboard/dashboard/contact/contact.php" class="dropdown-item">
                                    <i class='bx bx-mail-send'></i>
                                    <span>Contact-me</span>
                                </a>
                                <a href="/employeedashboard/homepage/index.php" class="dropdown-item">
                                    <i class='bx bx-log-out'></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Welcome Note -->
            <div class="welcome text-center">
                <h4><big>Welcome</big> Back 🎉 Great to see you again!</h4>
            </div>

            <!-- Cards Section -->
            <div class="card-container">
                <!-- Total Employee Card -->
                <div class="card1">
                    <p>Total Employee</p>
                </div>

                <!-- Department Dropdown -->
                <div class="dropdown">
                    <label for="dept-select">Select Department:</label>
                    <select name="department" id="dept-select" class="form-control">
                        <option value="">--Please choose an option--</option>
                        <option value="Software">Software Development</option>
                        <option value="QA">Quality Assurance (QA) and Testing</option>
                        <option value="DevOps">DevOps and Infrastructure</option>
                        <option value="UI/UX">UI/UX Design</option>
                        <option value="R&D">Research and Development (R&D)</option>
                        <option value="HR">Human Resources (HR)</option>
                    </select>
                </div>

                <!-- Shift Timing Dropdown -->
                <div class="dropdown1">
                    <label for="shift-select">Select Shift:</label>
                    <select name="shift" id="shift-select" class="form-control">
                        <option value="">--Please choose an option--</option>
                        <option value="morning">8 am - 2 pm</option>
                        <option value="evening">2 pm - 8 pm</option>
                    </select>
                </div>

                <!-- Get Data Button -->
                <button class="btn">Get Data</button>
            </div>
        
           






<!-- Updated Add Employee Button -->
<div class="text-center my-4">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add Employee</button>
</div>


<!-- Manage Employee Table -->
<!-- Manage Employee Table -->
<div class="container my-4">
    <h2 class="text-center">Manage Employees</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Employee Name</th>
                <th>Employee ID</th>
                <th>Department</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>Shift Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="employee-table-body" class="text-center">
            <!-- Dynamic rows will be populated here -->
        </tbody>
    </table>
</div>






          



        </div>
    </div>


<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addEmployeeForm">
          <div class="mb-3">
            <label for="employeeName" class="form-label">Employee Name</label>
            <input type="text" class="form-control" id="employeeName" name="employeeName" required>
          </div>
          <div class="mb-3">
            <label for="employeeID" class="form-label">Employee ID</label>
            <input type="text" class="form-control" id="employeeID" name="employeeID" required>
          </div>
          <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select" id="department" name="department" required>
              <option value="" disabled selected>Select Department</option>
              <option value="Software">Software Development</option>
                        <option value="QA">Quality Assurance (QA) and Testing</option>
                        <option value="DevOps">DevOps and Infrastructure</option>
                        <option value="UI/UX">UI/UX Design</option>
                        <option value="R&D">Research and Development (R&D)</option>
                        <option value="HR">Human Resources (HR)</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone No</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
          </div>
          <div class="mb-3">
            <label for="shiftTime" class="form-label">Shift Time</label>
            <select class="form-select" id="shiftTime" name="shiftTime" required>
              <option value="" disabled selected>Select Shift Time</option>
              <option value="9:00 AM - 5:00 PM">8 am - 2 pm</option>
              <option value="5:00 PM - 1:00 AM">2 pm - 8 am</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveEmployeeButton">Save Employee</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>