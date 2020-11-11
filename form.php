<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add subscriber</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <form action="manage_subscriber.php" method="post">
            <h4 class="mb-3">Input Subscriber Details</h4>
            <div class="form-group">
                <label for="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="form-label">Custom ID</label>
                <input type="text" class="form-control" id="customID" name="customID" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Add Subscriber</button>
            </div>
        </form>
        <form action="login.php" method="get">
            <h4 class="mb-3">Login Aweber</h4>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Login Aweber</button>
            </div>
        </form>
        <form action="refresh_token.php" method="get">
            <h4 class="mb-3">Refresh Token</h4>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">RefreshToken</button>
            </div>
        </form>
    </div>
</body>
</html>
