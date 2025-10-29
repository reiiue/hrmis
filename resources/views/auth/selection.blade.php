<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role | HRMIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="selection-box">
        <h2>Welcome to HRMIS</h2>
        <p>Please select your role</p>

        <div class="mt-4">
            <a href="{{ route('login', ['role' => 'employee']) }}" class="btn btn-primary btn-option">Employee</a>
            <a href="{{ route('login', ['role' => 'admin']) }}" class="btn btn-danger btn-option">Admin</a>
            <a href="{{ route('login', ['role' => 'hr']) }}" class="btn btn-success btn-option">HR</a>
        </div>
    </div>
</body>
</html>
