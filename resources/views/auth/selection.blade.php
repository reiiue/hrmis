<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role | HRMIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .selection-box {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .btn-option {
            width: 200px;
            margin: 10px;
            padding: 15px;
            font-size: 18px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="selection-box">
        <h2>Welcome to HRMIS</h2>
        <p>Please select your role</p>

        <div class="mt-4">
            <a href="{{ route('login', ['role' => 'employee']) }}" class="btn btn-primary btn-option">Employee</a>
            <a href="{{ route('login', ['role' => 'admin']) }}" class="btn btn-danger btn-option">Admin</a>
        </div>
    </div>
</body>
</html>
