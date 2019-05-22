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
SELECT id, post_title, post_author, post_image, date_created FROM posts
ORDER BY id DESC
';

$posts = $conn->query($qry);
$posts = $posts->fetchAll(PDO::FETCH_OBJ);

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
        <!-- Side Nav -->
        <?php include('partials/sidenav.php'); ?>
        <main class="main-container">
            <h1 class="heading-primary page-heading">All Posts</h1>
            <div class="section-all-posts">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($posts as $post): ?>
                            <tr>
                                <td><?= $post->id; ?></td>
                                <td><?= $post->post_title; ?></td>
                                <td><?= date_format(date_create($post->date_created), 'm/d/Y H:i'); ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $post->id; ?>" class="table__icon"><i class="fa fa-edit"></i> edit</a>
                                    <form style="display:inline;" method="POST" action="" onclick="this.submit();">
                                        <span class="table__icon"><i class="fa fa-times"></i> delete</span>
                                        <input type="hidden" name="deletePost" value="<?= $post->id; ?>"/>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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