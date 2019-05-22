<?php 

require('../conf.php');

if($_SESSION['admin'] != 1){

    header('Location: ../index.php');
    die();

}

if(isset($_POST['subbut'])){

    $postTitle = $_POST['postTitle'];
    $postContent = $_POST['postContent'];
    $postAuthor = 'Hayden Bradfield';

    if(isset($_FILES['postImage']) && $_FILES['postImage']['size'] > 0){

        $fileName = $_FILES['postImage']['name'];

        $fileTmp = $_FILES['postImage']['tmp_name'];

        move_uploaded_file($fileTmp,'../uploads/'.$fileName);

    } else {

        $fileName = 'placeholder.png';
    }

    $qry = '
    INSERT INTO posts(post_title,post_author,post_content,post_image)
    VALUES(:title,:author,:content,:image)
    ';

    $insert = $conn->prepare($qry);
    $insert->execute([
        'title' => $postTitle, 
        'author' => $postAuthor, 
        'content' => $postContent, 
        'image' => $fileName
    ]);

    header('Location: allPosts.php');

}

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('partials/head.php'); ?>
        <title>Admin</title>
    </head>
    <body>
        <!-- Header -->
        <?php include('partials/header.php'); ?>
        <!-- Sidenav --> 
        <?php include('partials/sidenav.php'); ?>
        <main class="main-container">
            <h1 class="heading-primary page-heading">Add Post</h1>
            <form class="add-post-form" action="" method="POST" enctype="multipart/form-data">
                <div class="fgrp">
                    <input type="text" class="text-input" placeholder="title" name="postTitle"/>
                </div>
                <div class="fgrp">
                    <textarea class="textarea-input" rows="10" placeholder="content goes here..." name="postContent"></textarea>
                </div>
                <div class="fgrp">
                    <input type="file" name="postImage"/>
                </div>
                <div class="fgrp">
                    <button class="btn btn--primary" name="subbut">Submit</button>
                </div>
            </form>
        </main>
        <script type="application/javascript">
            const toggleShow = (item) => {

               document.querySelector(item).classList.toggle('show');

            }

            document.querySelector('#postsDropToggle').addEventListener('click',toggleShow.bind(null,'#postsDroplist'));
            document.querySelector('.header__navtoggle').addEventListener('click',toggleShow.bind(null,'.sidenav'));


        </script>
    </body>
</html>