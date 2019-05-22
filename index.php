<?php 

require('conf.php'); 

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
        <title>HB Blog | Home</title>
    </head>
    </body>
        <!-- Header -->
        <?php include('partials/header.php'); ?>
        <main>
            <div class="home-container">
                <div class="home-container__one">
                    <h1 class="heading-primary home-container__welcome">Welcome!</h1>

                    <?php foreach($posts as $post): ?>

                    <div class="card blogPostTile">
                        <h2 class="heading-secondary"><?= $post->post_title; ?></h2>
                        <p class="blogPostTile__author">By <?= $post->post_author; ?></p>
                        <p class="blogPostTile__createdAt"><i class="fa fa-clock"></i> <?= date_format(date_create($post->date_created), 'm/d/Y H:i'); ?></p>
                        <div class="blogPostTile__img" style="background-image:url(./uploads/<?= $post->post_image; ?>);"></div>
                        <a href="post.php?id=<?= $post->id; ?>" class="btn btn--primary blogPostTile__readmore">Read More</a>
                    </div>

                    <?php endforeach; ?>

                    <!-- <div class="card blogPostTile">
                        <h2 class="heading-secondary">Being Adventurous Can Improve Your Life!</h2>
                        <p class="blogPostTile__author">By Hayden Bradfield</p>
                        <div class="blogPostTile__img" style="background-image:url(./uploads/nat-2-large.jpg);"></div>
                        <a class="btn btn--primary blogPostTile__readmore">Read More</a>
                    </div>
                    <div class="card blogPostTile">
                        <h2 class="heading-secondary">A Sample Post Worth Talking About</h2>
                        <p class="blogPostTile__author">By Hayden Bradfield</p>
                        <div class="blogPostTile__img" style="background-image:url(./uploads/placeholder.png);"></div>
                        <a class="btn btn--primary blogPostTile__readmore">Read More</a>
                    </div> -->
                </div>
                <div class="home-container__two">
                    <div class="card search-box">
                        <form>
                            <h2 class="heading-secondary"><i class="fa fa-search"></i> Search</h2>
                            <div class="fgrp">
                                <input type="text" class="text-input" placeholder="Search terms..."/>
                            </div>
                            <div class="fgrp">
                                <button class="btn btn--primary">Go</button>
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