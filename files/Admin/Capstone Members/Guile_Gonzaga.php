<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guile Gonzaga</title>
    <link rel="icon" href="../../../img/CHMSU%20Logo.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        :root {
            --comfort-black: rgb(50, 45, 45);
            --comfort-blue: rgb(64, 138, 242);
            --comfort-red: rgba(247, 67, 67, 0.934);
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            font-size: 20px;
            color: white;
            text-decoration: none;
        }

        .background {
            position: relative; /* Establish a positioning context for the pseudo-element */
            width: 100%;
            height: 100vh; /* Ensures the background takes full viewport height */
            overflow: hidden; /* Hide anything outside the background area */
        }

        .background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../../../img/CHMSU%20old.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            filter: blur(8px); /* Adjust blur intensity as needed */
            z-index: -1; /* Send the pseudo-element behind the content */
        }

        .back{
            position: absolute;
            bottom: .2rem;
            left: .2rem;
            border: 1.5px solid rgb(64, 138, 242);
            border-radius: 3px;
            font-size: 25px;
            color: white;
            padding-right: .5rem;
            padding-left: .4rem;;
            background-color: var(--comfort-blue);
            transition: .3s;
        }

        .back:hover {
            box-shadow: 1px 1px 5px rgb(74, 142, 238);
        }

        .back:active {
            display: inline-block;
            transform: scale(.9);
        }

        img {
            width: 20%;
            margin: 3%;
            object-fit: contain;
            border: 3px solid white;
            border-radius: 3px;
            box-shadow: 1px 1px 5px rgb(57, 56, 56);
        }

        a img, .adviser img {
            transition: .3s;
        }

        a img:hover, .adviser img:hover {
            box-shadow: 1px 1px 10px white;
            width: 265px;
        }

        h1 {
            position: absolute;
            font-size: 40px;
            top: .8rem;
            right: 21.5rem;
            text-shadow: 1px 1px 5px var(--comfort-black);
        }

        em {
            position: absolute;
            font-size: 30px;
            top: 4rem;
            right: 18.5rem;
            text-shadow: 1px 1px 5px var(--comfort-black);
        }

        /* Fade-out animation */
        @keyframes fade-out {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        /* Initial hidden state */
        body.fade-out {
            animation: fade-out 0.5s ease-out forwards; /* Adjust duration as needed */
        }
    </style>
</head>
<body>
    <div class="background">
        <section>
        <a href='../dev.php' class='back'>Back</a>
            <a href="../../../img/Guile.jpg"><img src="../../../img/Guile.jpg" alt="Guile Gonzaga" class="Guile"></a>
            <br>
            <h1>Guile Gonzaga</h1>
            <em>Documentation & Supporter</em>
        </section>
    </div>
</body>
</html>
