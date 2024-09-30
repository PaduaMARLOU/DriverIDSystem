<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin styles/unaccess.css">
    <title>Unauthorized</title>

    <link rel="icon" href="../../img/stop.png" type="image/png">
</head>
<body>
    <style>
        <?php include("admin styles/unaccess.css"); ?>
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../../img/mysterious\ place.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            filter: blur(3px);
            -webkit-filter: blur(2.5px);
            z-index: -1; /* Ensure it stays behind other content */
        }

        .understand:active {
            transform: scale(2);
            background-color: rgba(64, 138, 242, 0.609);
        }

    </style>

    <div class="background"></div>
        <div class="all">
            <h1><center class="super-admin">Super Admin</center></h1>
                <div class="container">
                    <center>
                        <div class="in-box">
                            <h2 class="sir">
                                Only Sir Sayson is allowed to verify
                            </h2>
                            <i class="clarify">You're not supposed to be here</i><br>
                            <br><a href="index.php" class="understand">I Understand</a>
                        </div>
                    </center>
                </div>
        </div>

        <div class="no"><img src="../../img/no.gif" alt="nope" width="225px"></div>
</body>
</html>