<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate inputs
        if (empty($_POST['full_name']) || empty($_POST['phone_number']) || empty($_POST['email']) || empty($_POST['message'])) {
            throw new Exception("All fields are required");
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        $to = "Shamranmukalazi750@gmail.com";
        $subject = "New Contact Form Submission";
        
        // Sanitize inputs
        $name = htmlspecialchars($_POST['full_name']);
        $phone = htmlspecialchars($_POST['phone_number']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars($_POST['message']);
        
        // Create email content
        $email_content = "Name: $name\n";
        $email_content .= "Phone: $phone\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message";
        
        // Headers
        $headers = array(
            'From' => $email,
            'Reply-To' => $email,
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/plain; charset=UTF-8'
        );
        
        // Send email
        if(mail($to, $subject, $email_content, implode("\r\n", $headers))) {
            echo json_encode([
                "status" => "success",
                "message" => "Message sent successfully!"
            ]);
        } else {
            throw new Exception("Failed to send email");
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
    exit;
}

// Handle non-POST requests
http_response_code(405);
echo json_encode([
    "status" => "error",
    "message" => "Method not allowed"
]);
exit;
?>