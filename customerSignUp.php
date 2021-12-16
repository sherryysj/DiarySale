<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <?php require("shared/_imports.php") ?>
</head>
<body>
    <header>
    <?php 
        require('shared/_navBar.php');
    ?>
    </header>
    <div class="container">
        <?php 
            $formAction = "customerSignupValid.php"; 
            require("shared/_signupForm.php");
        ?>
    </div>
</body>
</html>