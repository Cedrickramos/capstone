<?php
ob_start(); // Starts output buffering to prevent "headers already sent" error

require_once "navbar.php";

// $host = 'localhost';
// $dbname = 'accompanyme';
// $username = 'root';
// $password = ''; 

// kesug
$host = 'sql307.infinityfree.com';
$username = 'if0_36896748';
$password = 'rzQg0dnCh2BT';
$dbname = 'if0_36896748_accompanyme';

// infinity
// $host = 'sql202.infinityfree.com';
// $user = 'if0_37495817';
// $password = 'TQY8mKoPDq';
// $dbname = 'if0_37495817_accompanyme';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capture form data
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $message = htmlspecialchars(trim($_POST['message']));

        // Prepare SQL query to insert data into messages table
        $sql = "INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters to the query
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

        // Execute the query
        $stmt->execute();

        // Redirect to the same page with success
        header("Location: contact.php?success=1");
        exit;
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // Error handling if DB connection fails
    header("Location: contact.php?error=1");
    exit;
}

ob_end_flush(); // Ends output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mabuhay, Laguna</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px;
            padding: 20px;
            gap: 20px;
        }

        .left-column, .right-column {
            flex: 1;
            min-width: 300px; /* Ensure the layout stacks on smaller screens */
            max-width: 400px;
        }

        .left-column h1, .right-column h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .left-column h3 {
            color: grey;
            line-height: 1.5;
        }

        .right-column form {
            display: flex;
            flex-direction: column;
        }

        .right-column form label {
            margin-top: 10px;
            font-weight: bold;
        }

        .right-column form input, .right-column form textarea, .right-column form button {
            margin-top: 5px;
            padding: 10px;
            font-size: 14px;
        }

        .right-column form textarea {
            resize: vertical;
        }

        .right-column form button {
            margin-top: 15px;
            background-color: #e0bf01;
            color: #000;
            border: none;
            cursor: pointer;
        }

        .right-column form button:hover {
            background-color: #fff188;
        }

        .right-column p {
            margin-top: 10px;
            font-size: 14px;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .left-column, .right-column {
                max-width: 100%;
            }

            .right-column form {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Left Column -->
    <div class="left-column">
        <h1>Contact Section</h1>
        <h3>
            Have questions or need assistance? Reach out to us:<br>
            Email: <a href="mailto:accompanymelaguna@gmail.com" style="color: grey;">accompanymelaguna@gmail.com</a><br>
            Phone: +63 123 456 7890
        </h3>
    </div>

    <!-- Right Column -->
    <div class="right-column">
        <h1>Your Feedback Matters to Us</h1>
        <form action="contact.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Your Name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Your Email" required>
            <label for="message">Message:</label>
            <textarea id="message" name="message" placeholder="Your Feedback here" required></textarea>
            <button type="submit">Send Feedback</button>
            <?php if(isset($_GET['success'])): ?>
                <p style="color: green;">Your message has been sent successfully!</p>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <p style="color: red;">Oops! There was an issue sending your message. Please try again.</p>
            <?php endif; ?>
        </form>
    </div>
</div>

</body>
<?php
require_once "footer.php";
?>
</html>
