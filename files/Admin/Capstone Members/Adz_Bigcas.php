<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adriel M. Bigcas</title>
    <link rel="icon" href="../../../img/CHMSU%20Logo.png" type="image/png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            font-size: 20px;
            color: white;
            text-decoration: none;
        }

        *::selection {
            background-color: var(--comfort-orange);
        }

        .background {
            position: relative;
            /* Establish a positioning context for the pseudo-element */
            width: 100%;
            height: 100vh;
            /* Ensures the background takes full viewport height */
            overflow: hidden;
            /* Hide anything outside the background area */
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
            filter: blur(8px);
            /* Adjust blur intensity as needed */
            z-index: -1;
            /* Send the pseudo-element behind the content */
        }

        .back {
            position: absolute;
            bottom: .2rem;
            left: .2rem;
            border: 1.5px solid rgb(64, 138, 242);
            border-radius: 3px;
            font-size: 25px;
            color: white;
            padding-right: .5rem;
            padding-left: .4rem;
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

        a img,
        .adviser img {
            transition: .3s;
        }

        a img:hover,
        .adviser img:hover {
            box-shadow: 1px 1px 10px white;
            width: 265px;
        }

        .social-logos {
            position: relative;
            right: -1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .social-logos i {
            font-size: 2.3rem;
            transition: 0.25s;
        }

        .social-logos i:hover {
            font-size: 2.5rem;
        }

        .social-logos i:active {
            display: inline-flex;
            transform: scale(.9);
        }

        /* Facebook hover effect */
        .bxl-facebook-circle:hover {
            border: 1px none;
            border-radius: 50%;
            background-color: white;
            color: #3b5998;
        }

        /* Instagram hover effect */
        .bxl-instagram:hover {
            filter: drop-shadow(1px 1px 5px white);
            background: linear-gradient(to bottom, #feda75, #fa7e1e, #d62976, #962fbf, #4f5bd5);
            -webkit-background-clip: text; /* Clip background to text for Webkit browsers */
            -webkit-text-fill-color: transparent; /* Make text transparent for gradient effect */
            color: transparent; /* Ensure text is transparent on non-WebKit browsers */
            background-color: white;
        }

        .bxl-github:hover {
            border: 1px none;
            border-radius: 50%;
            background-color: white;
            color: #181717;
        }

        .bxl-tiktok:hover {
            border: 2px solid white;
            border-radius: 20%;
            background-color: #010101;
            color: white;
        }

        .quotes {
            position: absolute;
            bottom: 16rem;
            right: 4rem;
            text-shadow: 1px 1px 5px var(--comfort-black);
        }

        .creator {
            position: relative;
            left: -22rem;
        }

        q, dl {
            font-size: 28px;
        }

        h1 {
            position: absolute;
            font-size: 40px;
            top: .8rem;
            right: 23rem;
            text-shadow: 1px 1px 5px var(--comfort-black);
        }

        em {
            position: absolute;
            font-size: 30px;
            top: 4rem;
            right: 24.5rem;
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
            animation: fade-out 0.5s ease-out forwards;
            /* Adjust duration as needed */
        }
    </style>
</head>

<body>
    <div class="background">
        <section>
            <a href='../dev.php' class='back'>Back</a>
            <a href="../../../img/Adz.jpg"><img src="../../../img/Adz.jpg" alt="" class="Adz"></a>
            <br>
            <h1>Adriel M. Bigcas</h1>
            <em>Project Manager</em>
            <br><br>
            <div class="quotes">
                <q>Nothing is stronger than a man trying to build his dream life.</q><br>
                <q>The only way to fail is to stop trying</q>
                <em class="creator">- CreatorKarro</em>
            </div>
            <div class="social-logos">
                <a href="https://www.facebook.com/ady.bigcas" target="_blank"><i class='bx bxl-facebook-circle'></i></a>
                <a href="https://www.instagram.com/nelodec125/" target="_blank"><i class='bx bxl-instagram'></i></a>
                <a href="https://github.com/Adz-Nelo" target="_blank"><i class='bx bxl-github'></i></a>
                <a href="https://www.tiktok.com/@nelo.code?lang=en" target="_blank"><i class='bx bxl-tiktok'></i></a>
            </div>
        </section>
    </div>
</body>

</html>