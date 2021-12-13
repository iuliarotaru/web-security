<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/views/view_top.php');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set("log_errors", 1);
// ini_set("error_log", "/tmp/php-error.log");
?>

<div class="home_main">
    <div class="home_page_illustration">
    </div>
    <div class="home_left">
        <div>
            <h1>All about <br><span class="heading_highlight">pets</span></h1>
            <a href="/signup" class="signup_button">Sign up</a>
            <div class="therapist_link">
                <a href="/therapist">I am admin</a>
                <img src="/assets/next.svg" alt="next icon"></img>
            </div>
        </div>
    </div>
</div>

<script src="../javascript/general.js"></script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/views/view_bottom.php');
?>