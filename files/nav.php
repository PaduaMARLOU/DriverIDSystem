<center>
    <nav>
        <a href="admin_register.php" class="nav" id="register">Register</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="index.php" class="nav" id="log-in">Log-In</a>
    </nav>
</center>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        color: white;
    }
    
    .nav {
        position: relative;
        top: 1em;
        text-decoration: none;
        font-size: 25px;
        text-shadow: 1px 1px 6px black;
        transition: .4s;
    }

    .nav:hover {
        font-size: 28px;
        border: 2px solid white;
        padding-top: .1em;
        padding-left: .80em;
        padding-right: .80em;
        padding-bottom: .1em;
        box-shadow: 1px 1px 10px black;
        border-radius: 4px;
    }

    #register:hover {
        background-color: #57D1FC;
    }

    #log-in:hover {
        background-color: #FCA257;
    }

</style>