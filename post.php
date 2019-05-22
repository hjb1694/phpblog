<?php 


if(!isset($_GET['id'])){

    header('Location: index.php');
    die();

}

if(!is_numeric($_GET['id'])){

    header('Location: index.php');
    die();

}

require('conf.php'); 

if(isset($_POST['commentsubbut'])){

    $commentPostId = (int) $_POST['postId'];
    $comment = strip_tags($_POST['comment']);

    if(isset($_SESSION['uid'])){

        $authorId = $_SESSION['uid'];
        $authorUsername = $_SESSION['uname'];

        $qry = '
        INSERT INTO comments(post_id,author_id,author_username,content)
        VALUES(:postid,:authorid,:username,:comment)
        ';


        $insertComment = $conn->prepare($qry);
        $insertComment->execute([
            'postid' => $commentPostId,
            'authorid' => $authorId, 
            'username' => $authorUsername, 
            'comment' => $comment
        ]);


    } 


   header('Location: post.php?id='.$commentPostId);
   die();

}


$postId = (int) $_GET['id'];



$qry = '
SELECT id, post_title, post_author, post_content, post_image, date_created 
FROM posts
WHERE id = :id
';

$getPost = $conn->prepare($qry);
$getPost->execute([
    'id' => $postId
]);
$post = $getPost->fetch(PDO::FETCH_OBJ);


$qry = '
SELECT * FROM comments 
WHERE post_id = :postId
ORDER BY id DESC
';

$getPostComments = $conn->prepare($qry);
$getPostComments->execute([
    'postId' => $postId
]);
$comments = $getPostComments->fetchAll(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('partials/head.php'); ?>
        <title>HB Blog | Home</title>
    </head>
    </body>
        <!-- Header -->
        <?php include('partials/header.php'); ?>
        <main>
            <div class="home-container">
                <div class="home-container__one">
                    <div class="post-more">
                        <h1 class="post-more__title"><?= $post->post_title; ?></h1>
                        <p class="post-more__author">By <?= $post->post_author; ?></p>
                        <p class="post-more__created"><i class="fa fa-clock"></i> Posted at <?= date_format(date_create($post->date_created),'m-d-Y H:i') ?></p>
                        <div class="post-more__img-container">
                            <div class="post-more__img" style="background-image:url(./uploads/<?= $post->post_image; ?>);"></div>
                        </div>
                        <div class="post-more__content">
                            <?= $post->post_content; ?>
                        </div>
                    </div>
                    <?php if(!isset($_SESSION['uid'])): ?>

                        <div class="notif">
                            <p class="notif__msg">Please <a href="login.php">Sign In</a> or <a href="register.php">Register</a> to post a comment.</p>
                        </div>

                    <?php else: ?>
                        <div class="card">
                            <form class="comment-form" method="POST" action="post.php?id=<?= $post->id ?>">
                                <h2 class="heading-secondary">Submit A Comment</h2>
                                <textarea name="comment" id="comment" class="textarea-input comment-form__input" rows="8"></textarea>
                                <input type="hidden" name="postId" value="<?= $post->id ?>"/>
                                <button name="commentsubbut" id="commentsubbut" class="btn btn--primary">Submit</button>
                            </form>
                        </div>

                    <?php endif; ?>

                    <h2 class="heading-secondary">Comments</h2>

                    <?php if(!count($comments)): ?>

                        <p>There are no comments yet for this post. Be the first to comment!</p>


                    <?php else: ?>

                        <?php foreach($comments as $comment): ?>

                            <div class="post-comment">
                            <p class="post-comment__author"><?= $comment->author_username ?></p>
                            <p class="post-comment__created"><i class="fa fa-clock"></i> <?= date_format(date_create($comment->created_date), 'm/d/Y H:i') ?></p>
                            <p class="post-comment__content"><?= $comment->content ?></p>
                            </div>

                        <?php endforeach; ?>


                    <?php endif; ?>
                    
                </div>
                <div class="home-container__two">
                    <div class="card search-box">
                        <form>
                            <h2 class="heading-secondary"><i class="fa fa-search"></i> Search</h2>
                            <div class="fgrp">
                                <input type="text" class="text-input" placeholder="Search terms..."/>
                            </div>
                            <div class="fgrp">
                                <button class="btn btn--primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="card most-recent-box">
                        <h2 class="heading-secondary">Most Recent</h2>
                        <p class="most-recent-box__no-recent">There are no recent posts to display.</p>
                    </div>
                </div>
            </div>
        </main>
        <footer class="footer">
                <small style="font-size:1.2rem; display:inline-block; margin-top:1rem;">&copy; 2019 HB Blog</small>
        </footer>
        <script type="application/javascript">
            document.querySelector('.header__navtoggle').addEventListener('click',()=>{
                document.querySelector('.header__two').classList.toggle('show');
            });
        </script>
    </body>

</html>