 <?php
  //Check if the user is logged in
  session_start();
  if (!isset($_SESSION['uuid'])) {
    header('Location: /login');
    exit();
  }
  //Check if the user is a customer
  if ($_SESSION['role'] != 1) {
    header('Location: /therapist');
    exit();
  }
  // This function will populate the comments and comments replies using a loop
function show_comments($comments, $post_id, $parent_id = -1) {
  $html = '';
  if ($parent_id != -1) {
      // If the comments are replies sort them by the "submit_date" column
      array_multisort(array_column($comments, 'time'), SORT_ASC, $comments);
  }
 
  // Iterate the comments using the foreach loop
  foreach ($comments as $comment) {
    if($comment->post_id === $post_id){
      if ($comment->parent_id == $parent_id) {
          // Add the comment to the $html variable
          $html .= '
          <div class="comment">
              <p class="content">'. $comment->comment_text . '</p>
              <a class="reply_comment_btn" href="#" data-comment-id="' . $comment->comment_id . '">Reply</a>
              '.  show_write_comment_form($comment->post_id, $comment->comment_id) .'
              <div class="replies">
              '. show_comments($comments, $post_id, $comment->comment_id). '
              </div>
          </div>
          ';
      }
    }
  }
  return $html;
}

// This function is the template for the write comment form
function show_write_comment_form($post_id, $parent_id = -1) {
  $html = '
  <div class="write_comment" data-comment-id="' . $parent_id . '">
      <form>
          <input name="parent-id" type="hidden" value="' . $parent_id . '">
          <input name="post-id" type="hidden" value="' . $post_id . '">
          <textarea name="comment-text" placeholder="Write your comment here..." required></textarea>
          <button type="submit">Submit Comment</button>
      </form>
  </div>
  ';
  return $html;
}

  require_once($_SERVER['DOCUMENT_ROOT'] . '/views/view_top.php');
  require_once(__DIR__ . '/../db/db.php');

  try {
    $q = $db->prepare('SELECT * FROM users WHERE uuid = :user_uuid'); 
    $q->bindValue(':user_uuid', $_SESSION['uuid']);
    $q->execute();
    $user = $q->fetch();
    if (!$user) {
      header('Location: /login');
      exit();
    }
  ?>
 <div class="admin_main">
     <h1 class="hi_user">Hi there <?php echo "$user->name" ?>! Welcome</h1>
     <button class="home_menu" onclick="toggleHomeMenu()">
         <span>Home menu</span>
         <i class="fa fa-bars fa-lg hamburger"></i>
     </button>
     <div class="admin_left hide_home_links">
         <h4 class="menu">Menu</h4>
         <a href="" class="selected"><i class="fa fa-home fa-fw" aria-hidden="true"></i>Home</a>
         <a href=""><i class="fa fa-file fa-fw" aria-hidden="true"></i>Topics</a>
         <a href=""><i class="fa fa-bookmark fa-fw" aria-hidden="true"></i>Saved</a>
         <a href=""><i class="fa fa-check fa-fw" aria-hidden="true"></i>Following</a>
     </div>

     <!-- <a href="/logout">Log out</a>
   <a href="/delete">Delete account</a>
   <a href="/update">Profile information</a> -->

     <?php
      $q = $db->prepare('SELECT posts.post_id, title, body, likes.like_id, count(number_likes.like_id) as likes FROM posts LEFT JOIN likes ON likes.post_id = posts.post_id AND likes.user_id = :uuid LEFT JOIN likes as number_likes ON number_likes.post_id = posts.post_id GROUP BY posts.post_id');
      $q->bindValue(':uuid', $_SESSION['uuid']);
      $q->execute();
      $posts = $q->fetchAll();

      $q = $db->prepare('SELECT * FROM comments ORDER BY time ASC');
      // $q->bindValue(':uuid', $_SESSION['uuid']);
      $q->execute();
      $comments = $q->fetchAll();


      //fetch all comments from a post

      ?>
     <div id="posts">
         <?php
        foreach ($posts as $post) {
        ?>
        <div class="post" id="<?= $post->post_id ?>">
             <p id="post_text"><?= $post->body?></p>
             <div class="position_likes">
                 <div class="author">
                     <img src="/assets/male-avatar.png" alt="male profile" class="author_image">
                     Posted by: <b>John Doe</b>
                 </div>
                 <div class="likes">
                     <div>
                         <?php if (!$post->like_id) { ?>
                         <button id="like-button" class="icon icon-like" onclick="likePost('<?= $post->post_id ?>')">
                         </button> <?php } else { ?>
                         <button id="dislike-button" class="icon icon-liked"
                             onclick="dislikePost('<?= $post->post_id ?>')"></button> <?php } ?>
                     </div>
                     <p id="likes"><?= $post->likes ?></p>
                 </div>
             </div>

             <!-- foreach comment in a post, display it -->

             <?=show_write_comment_form($post->post_id);?>
             <?=show_comments($comments, $post->post_id)?> 
             <div class="comments">
             
             </div>
             </div>
         <?php
        }
        ?>
     
 </div>
 <?php
  } catch (PDOException $ex) {
    echo $ex;
  }

  ?>
 <script src="../javascript/general.js"></script>
 <script src="../javascript/like_dislike_post.js"></script>
 <script src="../javascript/comment.js"></script>
 <?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/views/view_bottom.php');
  ?>