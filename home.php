<?php
    session_start();
    if(empty($_SESSION['username'])){
        header('location:login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <h1 class="font-mono text-5xl font-bold text-indigo-500 text-center mt-14">Welcome <span class="text-teal-500"><?php echo $_SESSION['username'] ?></span></h1>
</body>
</html>