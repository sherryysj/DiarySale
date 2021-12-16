<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Homepage</title>
        <?php require_once("shared/_imports.php") ?>
    </head>
    <body>
        <?php require_once("shared/_navBar.php") ?>
        <?php
        require_once("shared/utils/MessageUtil.php");
        $msgUtil = new MessageUtil();
        $msgUtil->flashHtmlSuccessIfAny("order_success");
        ?>
        <div class="py-5 text-center">
            <img src="static/img/DiaryQueenLogo.png" alt="DiaryQueen Logo">
            <p class="lead">Your customization factory</p>
        </div>
    </body>
</html>
