<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <!-- Left side: Employee Icon -->
    <img src="/images/iconemployee.png" alt="Employee Icon" class="employee-icon">
    
    <!-- Centered Navbar Text -->
    <a class="navbar-brand" href="#">EMPLOYEE MANAGEMENT SYSTEM</a>

    <!-- Right side: Contact Us Button -->
    <div class="navbar-items">
      <a href="#contactus" class="btn btn-creative">Contact Us</a>
    </div>
  </div>
</nav>

<!-- scroll text section -->
<div class="run-text">
    <marquee behavior="scroll" direction="left to right">Building Stronger Teams, Empowering Every Employee to Achieve Excellence and Drive Success Together.😊</marquee>
</div>

<!-- Content Section -->
<header class="creative-header">
    <div class="header-content text-center">
        <h1 class="header-title">Hey🙋‍♂️...
            <br>
            Welcome to Employee World 🌍</h1>
        <p class="header-subtitle">An employee is a valuable contributor who helps an organization grow and succeed through their skills and dedication.</p>
        
        <button type="button" class="btn btn-primary btn-sm">Log-in</button>
        <button type="button" class="btn btn-secondary btn-sm">Register</button>
    </div>
</header>

<!-- Footer Section -->
<section id="contactus" class="footer-section">
    <h2>Contact Us</h2>
    <form>
        <div class="mb-3">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" placeholder="Enter your name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter your email">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Your Message</label>
            <textarea class="form-control" id="message" rows="4" placeholder="Enter your message"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</section>

<script src="script.js"></script>
</body>
</html>
