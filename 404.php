<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Not Found</title>
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            text-align: center;
        }
        .error-code {
            font-size: 10rem;
            font-weight: bold;
            color: #dc3545;
        }
        .error-text {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-text">Oops! The page you are looking for does not exist.</div>
 <div class="error-text">আপনি  <!--#echo var="REQUEST_URI" --> পৃষ্ঠাটি খুঁজছেন ,এটি বিদ্যমান নেই . পুনারায় চেক করুন ।</div>
        <a href="/" class="btn btn-primary">Go to Homepage</a>
    </div>
</body>
</html>