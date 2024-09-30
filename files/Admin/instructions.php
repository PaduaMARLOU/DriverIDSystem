<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructions</title>
    <link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
</head>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        :root {
            --comfort-black: rgb(50, 45, 45);
            --comfort-blue: rgb(64, 138, 242);
            --comfort-red: rgba(247, 67, 67, 0.934);
            --comfort-orange: #FCBE59;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            color: white;
            text-shadow: 1px 1px 7px var(--comfort-black);
        }

        *::selection {
            background-color: var(--comfort-orange);
        }

        h1 {
            font-size: 50px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('../../img/green.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Add this line */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1000px;
            animation: fadeIn 1s ease forwards;
        }

        iframe {
            border: 2px solid white;
            border-radius: 4px;
            box-shadow: 1px 1px 6px var(--comfort-black);
        }

        .instruction {
            font-size: 25px;
        }

        em {
            font-size: 23px;
        }

        .or {
            margin: 2%;
        }

        .alt {
            font-size: 18px;
            transition: .25s;
        }

        .alt:hover {
            color: var(--comfort-orange);
        }

        .okay {
            text-decoration: none;
            font-size: 30px;
            border: 2px solid white;
            border-radius: 4px;
            padding-left: 1rem;
            padding-right: 1rem;
            box-shadow: 1px 1px 6px gray;
            transition: .25s;
        }

        .okay:hover {
            font-size: 34px;
            background-color: var(--comfort-blue);
        }

        .okay:active {
            display: inline-flex;
            transform: scale(.9);
        }

    </style>

    <center>
        <br><br><br><br><br><br><h1>Instructions:</h1><br>
        <dl class="instruction">Remember to set the layout to 100% to avoid further complications</dl>
        <br>
        <em>How to set the screen to 100%</em><br>
        <br>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/THpa07EvW34?si=HP997VJAypAMj4xd" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <dl class="or">or</dl>
        <a href="https://vi.ie/how-to-zoom-in-and-out-of-a-page-in-windows/#:~:text=Press%20and%20hold%20the%20Ctrl,a%20web%20page%20or%20document." class="alt" target="_blank">Alternative way of adjusting the screen</a><br><br><br>
        <a href="../Admin/index.php" class="okay">I understand</a><br><br>
    </center>
</body>
</html>