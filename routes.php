<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/router.php");

// ##################################################
// ##################################################
// ##################################################
get('/', 'views/view_home.php');
get('/user', 'views/view_user.php');
get('/delete', 'bridges/bridge_delete_account.php');
get('/login', 'views/view_login.php');
get('/logout', 'bridges/bridge_logout.php');
get('/profile', 'views/view_profile.php');
get('/signup', 'views/view_signup.php');
get('/users', 'views/view_users.php');
get('/update', 'views/view_update.php');
get('/therapist', 'views/view_admin.php');
get('/verify/$token', 'bridges/bridge_verify.php');

// ##################################################
// ##################################################
// ##################################################

post('/create-users', 'db/create_users.php');
post('/create-user-role', 'db/create_user_role.php');
post('/login', 'bridges/bridge_login.php');
post('/posts/$post_id/$like_or_dislike', 'apis/api_like_or_dislike.php');
// post('/posts/$post_id', 'apis/api_comment.php');
post('/seed-users', 'db/seed_users.php');
post('/search', 'apis/api_search.php');
post('/signup', 'apis/api_signup.php');
post('/comment', 'apis/api_comment.php');
post('/update-image', 'apis/api_update_image.php');
post('/update-profile', 'apis/api_update.php');
post('/users/block/$user_id', 'apis/api_block_user.php');

any('/404', 'views/view_404.php');
