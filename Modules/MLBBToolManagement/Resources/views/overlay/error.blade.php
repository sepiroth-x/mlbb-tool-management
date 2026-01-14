<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overlay Error</title>
    <style>
        body {
            background: #0f0f23;
            color: #fff;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            padding: 40px;
        }
        h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        p {
            font-size: 20px;
            color: #ea4335;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>⚠️ Overlay Error</h1>
        <p>{{ $message }}</p>
    </div>
</body>
</html>
