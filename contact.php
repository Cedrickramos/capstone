<?php require_once "navbar.php";?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form fields
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'allensomarsomarallen@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'RC2nDhQf'; // Replace with your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('accompanyme@gmail.com'); // Replace with your own email address

        // Content
        $mail->isHTML(false);
        $mail->Subject = "New message from $name via Contact Form";
        $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        $mail->send();
        header("Location: contact.php?success=1");
    } catch (Exception $e) {
        header("Location: contact.php?error=1");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mabuhay, Laguna</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    .content-sections {
    max-width: 800px; 
    margin: 0 auto; 
    padding: 20px;
    }

    .head {
        max-width: 800px; 
        /* margin: 0 auto;  */
        padding: 30px 50px;
    }

    </style>
</head>
<body>

<div class="head">
    <h1>Contact Section</h1>
    <h3 style="color: grey">Have questions or need assistance? Reach out to us:<br>
    Email: <a href="mailto:accompanyme@gmail.com" style="color: grey">accompanyme@gmail.com</a><br>

    Phone: +63 123 456 7890</h3>
</div>
<hr> 
<h1 style="padding-left: 30px; color: #4d4d4d; font-style: arial, sans-serif">Your Feedback matters with us</h1>

<div class="content-sections">

    <form action="contact.php" method="post" class="contact-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Your Name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required>
        <label for="message">Message:</label>
        <textarea id="message" name="message" placeholder="Your Feedback here" required></textarea>

        <button type="submit" class="btn">Send Feedback</button>
        <?php if(isset($_GET['success'])): ?>
    <p style="color: green;">Your message has been sent successfully!</p>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
    <p style="color: red;">Oops! There was an issue sending your message. Please try again.</p>
<?php endif; ?>
    </form>
</div>


<?php
require_once "footer.php";
?>

</body>
</html>