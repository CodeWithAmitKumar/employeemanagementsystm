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
        <aside id="sidebar">
            <div class="d-flex justify-content-between p-4">
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

        <div class="main">
            <!-- Main content goes here -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>
