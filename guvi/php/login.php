<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
    	
    	<form class="shadow w-450 p-3" 
    	      action="redirect()" 
    	      method="post">

    		<h4 class="display-4  fs-1">LOGIN</h4><br>
    		<?php if(isset($_GET['error'])){ ?>
    		<div class="alert alert-danger" role="alert">
			  <?php echo $_GET['error']; ?>
			</div>
		    <?php } ?>

		  <div class="mb-3">
		    <label class="form-label">User name</label>
		    <input type="text" 
		           class="form-control"
		           name="uname"
		           value="<?php echo (isset($_GET['uname']))?$_GET['uname']:"" ?>">
		  </div>

		  <div class="mb-3">
		    <label class="form-label">Password</label>
		    <input type="password" 
		           class="form-control"
		           name="pass">
		  </div>
		  
		  <button type="submit" class="btn btn-primary">Login</button>
		  <a href="register.php" class="link-secondary">Sign Up</a>
		</form>
	    
	function redirect()
        {
	        session_start();

		if(isset($_POST['uname']) && 
		isset($_POST['pass']))
	    {

		$sName = "localhost";
		$uName = "root";
		$pass = "";
		$db_name = "auth_db";

		try {
		$conn = new PDO("mysql:host=$sName;dbname=$db_name", 
		$uName, $pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		echo "Connection failed : ". $e->getMessage();
		}

		$uname = $_POST['uname'];
		$pass = $_POST['pass'];

		$data = "uname=".$uname;

		
	    if(empty($uname))
	    {
		$em = "User name is required";
		header("Location: ../login.php?error=$em&$data");
		exit;
		
	    }
	    else if(empty($pass))
	    {
		$em = "Password is required";
		header("Location: ../login.php?error=$em&$data");
		exit;
	   }
	    else 
	    {

		$sql = "SELECT * FROM users WHERE username = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$uname]);

		if($stmt->rowCount() == 1){
		$user = $stmt->fetch();

		$username =  $user['username'];
		$password =  $user['password'];
		$fname =  $user['fname'];
		$id =  $user['id'];
		if($username === $uname){
		if(password_verify($pass, $password)){
		$_SESSION['id'] = $id;
		$_SESSION['fname'] = $fname;

		header("Location: ../profile.php");
		exit;
		
	    }
	    else
	    {
		$em = "Incorect User name or password";
		header("Location: ../login.php?error=$em&$data");
		exit;
		}

		
	    }
	    else
	    {
		$em = "Incorect User name or password";
		header("Location: ../login.php?error=$em&$data");
		exit;
	    }

	    }else {
		$em = "Incorect User name or password";
		header("Location: ../login.php?error=$em&$data");
		exit;
		}
		}


		}else {
		header("Location: ../login.php?error=error");
		exit;
		}

	    
	}
	    
	    
    </div>
</body>
</html>
