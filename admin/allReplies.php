<?php 
require('../conf.php');

if($_SESSION['admin'] != 1){

    header('Location: ../index.php');
    die();

}


if(isset($_POST['deletePost'])){

    $postToDelete = $_POST['deletePost'];

    $qry = '
    DELETE FROM posts
    WHERE id = :id
    ';
    $delete = $conn->prepare($qry);
    $delete->execute([
        'id' => $postToDelete
    ]);

    header('Location: allposts.php');

}

$qry = '
SELECT comments.id AS id, posts.post_title AS postTitle, comments.author_username AS authorUsername, CONCAT(SUBSTRING(comments.content, 1, 50), "...") AS content, comments.created_date AS date 
FROM posts 
JOIN comments
ON comments.post_id = posts.id
ORDER BY comments.id DESC
';

$getComments = $conn->query($qry);
$comments = $getComments->fetchAll(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('partials/head.php'); ?>
        <title>Admin | All Replies</title>
    </head>
    <body>
        <!-- Header -->
        <?php include('partials/header.php'); ?>
        <!-- Side Nav -->
        <?php include('partials/sidenav.php'); ?>
        <main class="main-container">
            <h1 class="heading-primary page-heading">All Comments</h1>
            <section class="section-all-replies">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Author</th>
                            <th>Contents</th>
                            <th>Response To</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($comments as $comment): ?>
                            <tr>
                                <td><?= $comment->id ?></td>
                                <td><?= $comment->authorUsername ?></td>
                                <td><?= $comment->content ?></td>
                                <td><?= $comment->postTitle ?></td>
                                <td><?= date_format(date_create($comment->date), 'm/d/Y H:i'); ?></td>
                                <td>
                                    <form style="display:inline;" method="POST" action="" onclick="this.submit();">
                                        <span class="table__icon"><i class="fa fa-times"></i> delete</span>
                                        <input type="hidden" name="deletePost" value="<?= $comment->id; ?>"/>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
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