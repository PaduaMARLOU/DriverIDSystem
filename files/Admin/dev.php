<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <link rel="icon" href="../../img/CHMSU Logo.png" type="image/png">
    <style>
        <?php include("../../Dev/dev.css"); ?>
        .background {
            width: 100%;
            height: 100%;
            background-image: url('../../img/CHMSU old.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Animation styles */
        @keyframes slide-in-left {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slide-in-right {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Initial hidden state */
        .hidden {
            opacity: 0;
            transform: translateX(0);
            transition: all 0.25s ease-out;
        }

        /* Reveal classes */
        .slide-from-left.reveal {
            animation: slide-in-left 0.6s ease-out forwards;
        }

        .slide-from-right.reveal {
            animation: slide-in-right 0.6s ease-out forwards;
        }

        .back:hover {
            box-shadow: 1px 1px 5px rgb(74, 142, 238);
        }
    </style>
</head>

<body>
    <div class="background">
        <a href='index.php' class='back'>Back</a>
        <center>
            <br>
            <h1>Capstone Members:</h1><br>
            <div class="dev" id="dev">
                <nav class="members">
                    <a href="Capstone Members/Adz_Bigcas.php"><img src="../../img/Adz.jpg" alt="" class="Adz"></a>
                    <a href="Capstone Members/Marlou_Padua.php"><img src="../../img/Marlou.jpg" alt="" class="Marlou"></a>
                    <a href="Capstone Members/Dexter_Suansing.php"><img src="../../img/Dexter.jpg" alt="" class="Dexter"></a>
                    <a href="Capstone Members/Guile_Gonzaga.php"><img src="../../img/Guile.jpg" alt="" class="Guile"></a>
                </nav>

                <br>

                <h1>Capstone Adviser:</h1><br>
                <aside class="adviser">
                    <a href="Capstone Adviser/Doc_Oliver.php"><img src="../../img/Sir Holiber.jpg" alt=""></a>
                </aside>
            </div>
        </center>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elementsToReveal = document.querySelectorAll('.members img, .adviser img'); // Select all images to be revealed

            // Add initial hidden state
            elementsToReveal.forEach(element => {
                element.classList.add('hidden');
                // Randomly assign direction classes
                if (Math.random() > 0.5) {
                    element.classList.add('slide-from-left');
                } else {
                    element.classList.add('slide-from-right');
                }
            });

            const observerOptions = {
                threshold: 0.1 // Trigger when at least 10% of the element is in view
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal'); // Add reveal class to elements that come into view
                        observer.unobserve(entry.target); // Stop observing once revealed
                    }
                });
            }, observerOptions);

            elementsToReveal.forEach(element => {
                observer.observe(element); // Observe each element
            });
        });
    </script>
</body>

</html>