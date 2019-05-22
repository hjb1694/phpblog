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
            <form class="register-form" method="POST" action="" autocomplete="off">
                <h1 class="heading-primary register-form__heading">Sign Up</h1>
                <div class="fgrp">
                    <input type="text" class="text-input" name="username" id="username" placeholder="username" maxlength="20"/>
                </div>
                <div class="fgrp">
                    <input type="email" class="text-input" name="email" id="email" placeholder="email address" maxlength="100"/>
                </div>
                <div class="fgrp">
                    <input type="password" class="text-input" name="password" id="password" placeholder="password" maxlength="50"/>
                </div>
                <div class="fgrp">
                    <input type="password" class="text-input" name="confirmPassword" id="confirmPassword" placeholder="confirm password" maxlength="50"/>
                </div>
                <div class="fgrp">
                    <button type="submit" name="subbut" id="subbut" class="btn btn--primary">Register &rarr;</button>
                </div>
                <div class="errbox">
                </div>
                <p>Forgot Password? Click here</p>
                <p><a href="login.php">Already have an account? Click here</a></p>
                <p><a href="index.php">Back to Home</a></p>
            </form>
        </main>
        <footer class="footer">
                <small style="font-size:1.2rem; display:inline-block; margin-top:1rem;">&copy; 2019 HB Blog</small>
        </footer>
        <script src="node_modules/validator/validator.min.js"></script>
        <script type="application/javascript">
            document.querySelector('.header__navtoggle').addEventListener('click',()=>{
                document.querySelector('.header__two').classList.toggle('show');
            });

            let errCt = 0;
            const errs = [];
            const letters = /^[a-z]+$/ig;
            const errbox = document.querySelector('.errbox');

            document.querySelector('#subbut').addEventListener('click', e => {
                e.preventDefault();

                errbox.innerHTML = null;

                const fields = {
                    username : document.querySelector('#username').value.trim(),
                    email : document.querySelector('#email').value.trim(), 
                    password : document.querySelector('#password').value.trim(), 
                    confirmPassword : document.querySelector('#confirmPassword').value.trim()
                }

                if(!validator.isEmail(fields.email)){
                    errCt++
                    errs.push('Please enter a valid email address.');
                }

                if(!letters.test(fields.username) ||fields.username.length < 5){
                    errCt++;
                    errs.push('Username must be a minimum of 5 characters and contain no special characters, no numbers, and no spaces');
                }

                if(fields.password.length < 5){
                    errCt++;
                    errs.push('Passwords must be at least 6 characters');
                }

                if(fields.password !== fields.confirmPassword){
                    errCt++;
                    errs.push('Confirmed password does not match password');

                }

                if(errCt){

                    for(let i = 0; i < errs.length; i++){
                        errbox.insertAdjacentHTML('beforeend','<p><i class="fa fa-exclamation-triangle"></i> '+ errs[i] +'</p>');
                    }

                } else {

                    const fd = new FormData();
                    fd.append('username',fields.username);
                    fd.append('email',fields.email);
                    fd.append('password',fields.password);
                    
                    fetch('processRegistration.php',{
                        method : 'POST', 
                        body : fd
                    })
                    .then(resp => {
                        if(!resp.ok){
                            throw new Error('There was an error processing the registration.');
                        }

                        return resp.text();
                    })
                    .then(resp => {

                        if(resp == 1){
                            window.location.replace('login.php');
                        }else if(resp == 2){
                            errbox.textContent = 'This email or username has already been taken.';
                        }else{
                            throw new Error();
                        }

                    })
                    .catch(err => {
                        errbox.textContent = 'There was an issue processing the registration.';
                    });


                }

            });
        </script>
    </body>

</html>