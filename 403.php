<?php
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .forbidden-card { max-width: 400px; margin: 80px auto; box-shadow: 0 2px 16px rgba(0,0,0,0.08); }
        .forbidden-icon { font-size: 64px; color: #dc3545; }
    </style>
</head>
<body>
    <div class="card forbidden-card text-center">
        <div class="card-body">
            <div class="forbidden-icon mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-slash-circle" viewBox="0 0 16 16">
                  <path d="M11.354 4.646a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708l6-6a.5.5 0 0 1 .708 0z"/>
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8z"/>
                </svg>
            </div>
            <h2 class="text-danger">403 Forbidden</h2>
            <p class="mt-3">You are not allowed to access this page directly.<br>Please visit in the right way.</p>
            <a href="/" class="btn btn-primary mt-2">Go to Homepage</a>
        </div>
    </div>
</body>
</html>