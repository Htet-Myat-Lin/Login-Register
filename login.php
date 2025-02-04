<?php 
    session_start();
    require_once 'Database/DbCreation.php';
    if($_SERVER['REQUEST_METHOD']==="POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $erruname = $errpwd = '';
        $checkQuery = "
            SELECT * FROM users WHERE username = :uname
        ";
        $pdo->exec("USE `$dbname`");
        $stmt = $pdo->prepare($checkQuery);
        $stmt->bindParam(":uname",$username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!empty($result)){
            if($result["password"]== $password){
                $_SESSION['username'] = $username;
                header("Location: home.php");
                $pdo = null;
                $stmt = null;
                die();
            }
            else{
                $errpwd = "Invalid Password";
            }
        }else{
            $erruname = "Invalid username";
        }
        $_SESSION['uerror'] = $erruname;
        $_SESSION['perror'] = $errpwd;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-mono">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="flex flex-col justify-center px-10 rounded w-96 h-96 border border-indigo-400 text-indigo-500 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <h1 class="text-2xl font-semibold text-center mb-9">Login Here</h1>

        <div class="mb-5">
            <input type="text" name="username" placeholder="Username" class="placeholder:text-indigo-400 p-2 w-full focus:outline-none border border-indigo-400 rounded focus:border-indigo-700">
            <?php 
            if(isset($_SESSION['uerror']) && !empty($_SESSION['uerror'])){
                $erruname = $_SESSION['uerror'];
                echo '<p class="mt-1 text-red-500">* ' . $erruname . '</p>';
            } ?>
        </div>

        <div class="mb-5">
            <input type="password" name="password" placeholder="Password" class="placeholder:text-indigo-400 p-2 w-full focus:outline-none border border-indigo-400 rounded focus:border-indigo-700">
            <?php 
            if(isset($_SESSION['perror']) && !empty($_SESSION['perror'])){
                $errpwd = $_SESSION['perror'];
                echo '<p class="mt-1 text-red-500">* ' . $errpwd . '</p>';
            } ?>
        </div>

        <button class="p-2 mb-5 w-full rounded border bg-indigo-400 hover:bg-indigo-500 text-gray-200">Login</button>

        <p>Don't you have an account?<a href="signup.php" class="underline text-slate-400 hover:text-slate-500">Sign up</a></p>
    </form>
</body>
</html>