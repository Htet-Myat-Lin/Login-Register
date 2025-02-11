<?php 
    // session_start();
    require_once 'Database/DbCreation.php';

    $username = $email = $password = $confirmpwd = '';
    $erruname = $erremail = $errpwd = '';

    if($_SERVER['REQUEST_METHOD']==="POST"){
        function checkUsername($pdo,$uname,$dbname){
            $getUsername = "SELECT username FROM users WHERE username = :uname";
            $pdo->exec("USE `$dbname`");
            $stmt = $pdo->prepare($getUsername);
            $stmt->bindParam(":uname",$uname);
            $stmt->execute(); 

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        function checkEmail($pdo,$email,$dbname){
            $getEmail = "SELECT username FROM users WHERE email = :email";
            $pdo->exec("USE `$dbname`");
            $stmt = $pdo->prepare($getEmail);
            $stmt->bindParam(":email",$email);
            $stmt->execute(); 
        
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        //Username Validation 
        if(empty($_POST['username'])){
            $erruname = 'Username is required!';
        }
        else if (preg_match('/\s/', $_POST['username'])) {
            $erruname = 'Username must not include white space';
        }
        else if(strlen($_POST['username'])<6){
            $erruname = 'Username must include at leat 6 characters';
        }
        else if(!empty(checkUsername($pdo,$_POST['username'],$dbname))){
            $erruname = "Username already exited!";
        }else{
            $username = $_POST['username'];
        }

        //Email Validation
        if(empty($_POST['email'])){
            $erremail = 'Email is required!';
        }
        else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $erremail = "Invalid email!";
        }
        else if(!empty(checkEmail($pdo,$_POST['email'],$dbname))){
            $erremail = "Email already existed!";
        }else{
            $email = $_POST["email"];
        }

        //Password Validation
        if(empty($_POST['password'])){
            $errpwd = 'Password is required!';
        }
        else if($_POST['password'] != $_POST['confirmpwd']){
            $errpwd = "Password does not match with Confirm Password!";
        }
        else if(preg_match('/\s/', $_POST['password'])){
            $errpwd = 'Password must not include white space!';
        }
        else if(strlen($_POST['password'])< 6){
            $errpwd = 'Password must include at least 6 characters!';
        }
        else if(!preg_match("/[A-Z]/",$_POST['password'])){
            $errpwd = 'Password must include at least one uppercase character!';
        }
        else if(!preg_match("/[a-z]/",$_POST['password'])){
            $errpwd = 'Password must include at least one lowercase character!';
        }
        else if(!preg_match("/[0-9]/",$_POST['password'])){
            $errpwd = 'Password must include at least one number!';
        }
        else if(!preg_match('/[!@#$%^&*(),.?":{}|<>]/',$_POST['password'])){
            $errpwd = 'Password must include at least one special character!';
        }
        else{
            $password = $_POST['password'];
        }

        // $_SESSION['erruname'] = $erruname;
        // $_SESSION['erremail'] = $erremail;
        // $_SESSION['errpwd'] = $errpwd;

        if(!empty($username) && !empty($email) && !empty($password)){
            try{
            $newUserQuery = "
                INSERT INTO users(username,email,password)
                VALUES(:username,:email,:password)
                ";
                $pdo->exec("USE `$dbname`");
                $stmt = $pdo->prepare($newUserQuery);
                $stmt->bindParam(":username",$username);
                $stmt->bindParam(":email",$email);
                $stmt->bindParam(":password",$password);
                $stmt->execute();
    
                $pdo = null;
                $stmt = null;
                header("Location: login.php");
                // die();
            }catch(PDOException $e){
                die("Failed" .$e->getMessage());
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-mono">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="flex flex-col justify-center text-lg px-7 rounded w-96 md:w-[500px] py-5 border border-indigo-400 text-indigo-500 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <h1 class="text-2xl font-semibold text-center mb-9">Register Here</h1>
        
        <div class="mb-5">
            <input type="text" name="username" placeholder="Username" class="placeholder:text-indigo-400 p-2 w-full focus:outline-none border border-indigo-400 rounded focus:border-indigo-700">
            <?php if(!empty($erruname)) echo '<p class="text-base mt-1 text-red-500">* ' . $erruname . '</p>';?>
        </div>

        <div class="mb-5">
            <input type="name" name="email" placeholder="Email" class="placeholder:text-indigo-400 p-2 w-full focus:outline-none border border-indigo-400 rounded focus:border-indigo-700">
            <?php if(!empty($erremail)) echo '<p class="text-base mt-1 text-red-500">* ' . $erremail . '</p>';?>
        </div>

        <div class="mb-5">
            <input type="password" name="password" placeholder="Password" class="placeholder:text-indigo-400 p-2 w-full focus:outline-none border border-indigo-400 rounded focus:border-indigo-700">
            <?php if(!empty($errpwd)) echo '<p class="text-base mt-1 text-red-500">* ' . $errpwd . '</p>';?>
        </div>

        <div class="mb-5">
            <input type="password" name="confirmpwd" placeholder="Confirm Password" class="placeholder:text-indigo-400 p-2 w-full focus:outline-none border border-indigo-400 rounded focus:border-indigo-700">
        </div>

        <button name="signup" class="p-2 mb-5 w-full rounded border bg-indigo-400 hover:bg-indigo-500 text-gray-200">Sign up</button>

        <p class="text-center mb-5">Already have an account?<a href="login.php" class="underline text-slate-400 hover:text-slate-500">Login</a></p>
    </form>
</body>
</html>