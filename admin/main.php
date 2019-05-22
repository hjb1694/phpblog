<?php 
require('../conf.php');

if($_SESSION['admin'] != 1){

    header('Location: ../index.php');
    die();

}


if(isset($_POST['deletePostId'])){

    $deleteId = $_POST['deletePostId'];

    $qry = '
    DELETE FROM posts 
    WHERE id = :id
    ';
    $deletePost = $conn->prepare($qry);
    $deletePost->execute([
        'id' => $deleteId
    ]);

    header('Location: main.php');

}


$qry = '
SELECT id, post_title, date_created
FROM posts 
ORDER BY id DESC 
LIMIT 3
';

$getPosts = $conn->query($qry);
$posts = $getPosts->fetchAll(PDO::FETCH_OBJ);


$qry = '
    SELECT (SELECT count(id) FROM posts) AS postCount, (SELECT count(id) FROM comments) AS commentCount
';

$getCounts = $conn->query($qry);
$counts = $getCounts->fetch(PDO::FETCH_OBJ);


$qry = '
SELECT comments.id AS id, posts.post_title AS postTitle, comments.author_username AS authorUsername, CONCAT(SUBSTRING(comments.content, 1, 50), "...") AS content, comments.created_date AS date 
FROM posts 
JOIN comments
ON comments.post_id = posts.id
ORDER BY comments.id DESC
LIMIT 3
';

$getRecentComments = $conn->query($qry);
$comments = $getRecentComments->fetchAll(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html>
    <head>
        <?php include('partials/head.php'); ?>
        <title>Admin</title>
    </head>
    <body>
        <!-- Header -->
        <?php include('partials/header.php'); ?>
        <!-- Side Nav -->
        <?php include('partials/sidenav.php'); ?>
        <main class="main-container">
            <h1 class="heading-primary page-heading">Dashboard</h1>
            <section class="summary-section">
                <div class="summary-section--one">
                    <div class="summary-card summary-card--one">
                        <i class="fa fa-edit"></i> Posts
                        <span class="summary-card__number"><?= $counts->postCount ?></span>
                    </div>
                </div>
                <div class="summary-section--two">
                    <div class="summary-card summary-card--two">
                        <i class="fa fa-reply"></i> Replies
                        <span class="summary-card__number"><?= $counts->commentCount ?></span>
                    </div>
                </div>
                <div class="summary-section--three">
                    <div class="summary-card summary-card--three">
                        <i class="fa fa-thumbs-up"></i> Likes
                        <span class="summary-card__number">0</span>
                    </div>
                </div>
                <div class="summary-section--four">
                    <div class="summary-card summary-card--four">
                        <i class="fa fa-id-card"></i> Inquiries
                        <span class="summary-card__number">0</span>
                    </div>
                </div>
            </section>
            <section class="recent-posts-section">
                <h2 class="heading-secondary">Most Recent Posts</h2>
                <?php if(count($posts) == 0): ?>

                    <p>There are no posts.</p>

                <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Title</th>
                            <th>Created Date</th>
                            <th>Reply Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($posts as $post): ?>
                        <tr>
                            <td><?= $post->id; ?></td>
                            <td><?= $post->post_title; ?></td>
                            <td><?= date_format(date_create($post->date_created), 'm/d/Y H:i'); ?></td>
                            <td>1</td>
                            <td>
                                <a href="edit.php?id=<?= $post->id; ?>" class="table__icon"><i class="fa fa-edit"></i> edit</a>
                                <form method="POST" action="" style="display:inline;" onclick="this.submit();">
                                    <span class="table__icon"><i class="fa fa-times"></i> delete</span>
                                    <input type="hidden" name="deletePostId" value="<?= $post->id; ?>"/>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
                <a href="allPosts.php" class="btn btn--primary mt-1">See All Posts</a>
            </section>
            <section class="recent-replies-section">
                <h2 class="heading-secondary">Most Recent Replies</h2>
                <?php if(!count($comments)): ?>

                    <p>There are no recent comments.</p>

                <?php else: ?>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Author</th>
                                <th>Content</th>
                                <th>Subsmission Date</th>
                                <th>Response to</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($comments as $comment): ?>
                            <tr>
                                <td><?= $comment->id ?></td>
                                <td><?= $comment->authorUsername ?></td>
                                <td><?= $comment->content ?></td>
                                <td><?= date_format(date_create($comment->date),'m/d/Y H:i') ?></td>
                                <td><?= $comment->postTitle ?></td>
                                <td><i class="fa fa-times"></i> delete</td>

                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>


                <?php endif; ?>
                <a href="" class="btn btn--primary mt-1">See All Replies</a>
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