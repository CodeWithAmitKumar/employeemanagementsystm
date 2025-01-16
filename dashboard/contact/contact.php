<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .contact-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        .contact-container h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group textarea {
            resize: none;
            height: 100px;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .success-message {
            text-align: center;
            color: green;
            margin-top: 15px;
        }

        .error-message {
            text-align: center;
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <h1>Contact Me</h1>
        <form id="contactForm" method="POST" action="/employeedashboard/dashboard/contact/contact.php">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Your Email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" placeholder="Your Message" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Send</button>
            </div>
        </form>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = htmlspecialchars($_POST['name']);
                $email = htmlspecialchars($_POST['email']);
                $message = htmlspecialchars($_POST['message']);

                $to = "noobodisha@gmail.com"; // Replace with your email address
                $subject = "New Contact Form Submission";
                $headers = "From: $email\r\nReply-To: $email";

                $emailBody = "Name: $name\nEmail: $email\n\nMessage:\n$message";

                if (mail($to, $subject, $emailBody, $headers)) {
                    echo '<p class="success-message">Message sent successfully!</p>';
                } else {
                    echo '<p class="error-message">Error sending message. Try again later.</p>';
                }
            }
        ?>
    </div>
</body>
</html>
