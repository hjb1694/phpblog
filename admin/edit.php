<?php 

require('../conf.php');

if($_SESSION['admin'] != 1){

    header('Location: ../index.php');
    die();

}

if(!isset($_GET['id'])){

    header('Location: allPosts.php');
    die();

}else{

    $postId = $_GET['id'];

    if(!is_numeric($postId)){

        header('Location: allPosts.php');
        die();

    }else{

        $qry = '
        SELECT id, post_title, post_content, post_image 
        FROM posts 
        WHERE id = :id
        ';

        $getPost = $conn->prepare($qry);
        $getPost->execute([
            'id' => $postId
        ]);
        $post = $getPost->fetch(PDO::FETCH_OBJ);


    }


}

if(isset($_POST['subbut'])){

    $postTitle = $_POST['postTitle'];
    $postContent = $_POST['postContent'];
    $postAuthor = 'Hayden Bradfield';

    if(isset($_FILES['postImage']) && $_FILES['postImage']['size'] > 0){

        $fileName = $_FILES['postImage']['name'];

        $fileTmp = $_FILES['postImage']['tmp_name'];

        move_uploaded_file($fileTmp,'../uploads/'.$fileName);

        $qry = '
        UPDATE posts
        SET post_title = :title, post_author = :author, post_content = :content, post_image = :image
        WHERE id = :id
        ';

        $params = [
            'title' => $postTitle, 
            'author' => $postAuthor, 
            'content' => $postContent, 
            'image' => $fileName, 
            'id' => $postId
        ];

    } else {

        $qry = '
        UPDATE posts
        SET post_title = :title, post_author = :author, post_content = :content
        WHERE id = :id
        ';

        $params = [
            'title' => $postTitle, 
            'author' => $postAuthor, 
            'content' => $postContent, 
            'id' => $postId
        ];


        
    }

    $insert = $conn->prepare($qry);
    $insert->execute($params);

    header('Location: allPosts.php');

}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <meta http-equiv="X-UA-COMPATIBLE" content="ie=edge"/>
        <title>Admin</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./css/style.css"/>
    </head>
    <body>
        <!-- Header -->
        <?php include('partials/header.php'); ?>
        <!-- Side Nav -->
        <?php include('partials/sidenav.php'); ?>
        <main class="main-container">
            <h1 class="heading-primary page-heading">Edit Post</h1>
            <form class="add-post-form" action="edit.php?id=<?= $post->id; ?>" method="POST" enctype="multipart/form-data">
                <div class="fgrp">
                    <input type="text" class="text-input" placeholder="title" name="postTitle" value="<?= $post->post_title; ?>"/>
                </div>
                <div class="fgrp">
                    <textarea class="textarea-input" rows="10" placeholder="content goes here..." name="postContent"><?= $post->post_content ?></textarea>
                </div>
                <div class="fgrp">
                    <img src="../uploads/<?= $post->post_image; ?>" width="100px"/>
                </div>
                <div class="fgrp">
                    <input type="file" name="postImage"/>
                </div>
                <div class="fgrp">
                    <button class="btn btn--primary" type="submit" name="subbut">Save Changes</button>
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