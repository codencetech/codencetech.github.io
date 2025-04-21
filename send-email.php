<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Process the form only when it's submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Collect form data
    $name = $_POST['name'] ?? 'No name provided';
    $email = $_POST['email'] ?? 'No email provided';
    $subject = $_POST['subject'] ?? 'New message from Codence Website';
    $message = $_POST['message'] ?? 'No message provided';
    
    // Load Composer's autoloader (if you have Composer installed)
    // require 'vendor/autoload.php';
    
    // Or manually include the PHPMailer files if not using Composer
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // SMTP Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gordeshailendra1@gmail.com'; // Your Gmail address
        $mail->Password = 'ptfj evys dekq dszu'; // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Recipients
        $mail->setFrom('gordeshailendra1@gmail.com', 'Codence Website');
        $mail->addAddress('codence.technology@gmail.com'); // Recipient's email
        $mail->addReplyTo($email, $name); // So you can reply to the sender
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Codence Contact: ' . $subject;
        
        // Create an HTML message body
        $mail->Body = "
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                    }
                    h2 {
                        color: #4e96c8;
                    }
                    .info {
                        margin-bottom: 20px;
                    }
                    .label {
                        font-weight: bold;
                    }
                    .message {
                        background-color: #f9f9f9;
                        padding: 15px;
                        border-radius: 5px;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>New Contact Form Submission</h2>
                    <div class='info'>
                        <p><span class='label'>Name:</span> $name</p>
                        <p><span class='label'>Email:</span> $email</p>
                        <p><span class='label'>Subject:</span> $subject</p>
                    </div>
                    <div class='message'>
                        <p class='label'>Message:</p>
                        <p>" . nl2br($message) . "</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        // Plain text version for email clients that don't support HTML
        $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
        
        // Send the email
        $mail->send();
        
        // Redirect to thank you page on success
        header('Location: thank-you.html');
        exit();
    } catch (Exception $e) {
        // Log the error
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        
        // Store error message in session to display on form page
        session_start();
        $_SESSION['form_error'] = "Sorry, your message could not be sent. Please try again later.";
        
        // Redirect back to the form
        header('Location: index.html#contact');
        exit();
    }
} else {
    // If someone tries to access this script directly, redirect to the home page
    header('Location: index.html');
    exit();
}
?>