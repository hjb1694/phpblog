<?php 
require('conf.php');

if(isset($_SESSION['uid'])){

    header('Location: index.php');
    die();

}

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('partials/head.php'); ?>
        <title>HB Blog | Login</title>
    </head>
    </body>
        <!-- header -->
        <?php include('partials/header.php'); ?>
        <main>
            <form class="login-form">
                <h1 class="heading-primary login-form__heading">Login</h1>
                <div class="fgrp">
                    <input type="text" id="username" name="username" class="text-input" placeholder="username"/>
                </div>
                <div class="fgrp">
                    <input type="password" name="password" id="password" class="text-input" placeholder="password"/>
                </div>
                <div class="fgrp">
                    <button type="submit" name="subbut" id="subbut" class="btn btn--primary">Login &rarr;</button>
                </div>
                <div class="errbox"></div>
                <p>Forgot Password? Click here</p>
                <p><a href="register.php">Don't have an account? Click here</a></p>
                <p><a href="index.php">Back to Home</a></p>
            </form>
        </main>
        <footer class="footer">
                <small style="font-size:1.2rem; display:inline-block; margin-top:1rem;">&copy; 2019 HB Blog</small>
        </footer>
        <script type="application/javascript">
            document.querySelector('.header__navtoggle').addEventListener('click',()=>{
                document.querySelector('.header__two').classList.toggle('show');
            });

            const errbox = document.querySelector('.errbox');

            document.querySelector('#subbut').addEventListener('click', e => {
                e.preventDefault();

                const fields = {
                    username : document.querySelector('#username').value.trim(),
                    password : document.querySelector('#password').value.trim()
                }

                const fd = new FormData();
                fd.append('username',fields.username);
                fd.append('password',fields.password);

                fetch('processLogin.php',{
                    method : 'POST',
                    body : fd
                })
                .then(resp => {
                    if(!resp.ok){
                        throw new Error();
                    }

                    return resp.text();

                })
                .then(resp => {
                    
                    console.log(resp);

                    if(resp == 1){
                        window.location.replace('index.php');
                    }else{
                        errbox.innerHTML = '<p><i class="fa fa-exclamation-triangle"></i> Authentification failed.</p>';
                    }
                })
                .catch(err => {
                    errbox.innerHTML = '<p><i class="fa fa-exclamation-triangle"></i> There was an error processing your request</p>';
                });
            });
        </script>
    </body>

</html>