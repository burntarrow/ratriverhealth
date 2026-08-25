<?php
// Do not delete these lines
global $post;

if (function_exists('pll_current_language')) {
    $curlang = pll_current_language();
} else {
    $curlang = "en";
}

$strings = array(
    "en" => array(
        "nocommentsyet" => "No comments yet. Be the first!",
        "comment" => "Comment",
        "leavereply" => "Leave a Reply",
        "commentsclosed" => "Comments are closed.",
        "loggedinas" => "Logged in as",
        "logout" => "Log out",
        "typecomment" => "Type your comment here...",
        "tocomment" => "Comment",
        "postedby" => "Posted by",
        "fullname" => "Full name",
        "email" => "Email",
        "website" => "Website",
        "namereq" => "Name (required)",
        "emailreq" => "Email (required)",
        "websiteopt" => "Website (optional)",
        "required" => "(required)"
    ),
    "fr" => array(
        "nocommentsyet" => "Pas de commentaires. Soyez le premier!",
        "comment" => "Commentaire",
        "leavereply" => "Laisser un commentaire",
        "commentsclosed" => "Les commentaires sont fermés.",
        "loggedinas" => "Connect&eacute; en tant que",
        "logout" => "D&eacute;connecter",
        "typecomment" => "&Eacute;crivez votre commentaire ici...",
        "tocomment" => "Commenter",
        "postedby" => "Post&eacute; par",
        "fullname" => "Nom",
        "email" => "Courriel",
        "website" => "Site web",
        "namereq" => "Nom (requis)",
        "emailreq" => "Courriel (requis)",
        "websiteopt" => "Site web (optionnel)",
        "required" => "(requis)"
    )    
);


if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');

if ( post_password_required() ) { ?>
	<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php
return;
}
?>

<div id="comments">

<?php if ( have_comments() ) : ?>
	
<div class="basicInfo">
<?php
	$numberOfComments = get_comments_number();
	$commentsWording = 'Comments';
	
	if ($numberOfComments == 1) $commentsWording = 'Comment';
	
	echo $numberOfComments . " " . $commentsWording . ' to "' . $post->post_title . '"';
	
?>
	<a href="#respond" title="add comment" class="tinyButton roundButton right scroll">add comment</a>
</div>

<div class="commentlist">
<?php wp_list_comments('avatar_size=52&style=div&callback=tb_comment_template'); ?>
</div>

<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post->comment_status) : ?>

<div class="basicInfo"><?=$strings[$curlang]['nocommentsyet']?></div>



<?php else : // comments are closed ?>


    <div class="basicInfo nocomments"><?=$strings[$curlang]['commentsclosed']?></div>





<?php endif; ?>

<?php endif; ?>   



<?php if ('open' == $post->comment_status) : ?>

       <div id="respond">
      	
        	 <div class="cancel-comment-reply">
		    <small><?php cancel_comment_reply_link(); ?></small>
    		</div>


<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

<?php else : ?>

    <div class="respondHeader"><?=$strings[$curlang]['leavereply']?></div>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" class="horizform" id="commentform" onsubmit="if (url.value == 'Website (optional)') {url.value = '';}">
<fieldset>
<?php if ( is_user_logged_in() ) : ?>

<p><?=$strings[$curlang]['loggedinas']?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?=$strings[$curlang]['logout']?> &raquo;</a></p>

<div class="form-data respondTextarea">
    

    <label for="form_message"><?=$strings[$curlang]['comment']?></label> 
    
    
    <textarea id="form_message" name="comment" tabindex="4" onfocus="if (this.value == '<?=$strings[$curlang]['typecomment']?>') {this.value = '';}"  onblur="if (this.value == '') {this.value = '<?=$strings[$curlang]['typecomment']?>';}"><?=$strings[$curlang]['typecomment']?></textarea>
</div>

<div class="respondSubmit">
	<input type="submit" id="submit" class="tinyButton roundButton" name="submit" value="<?=$strings[$curlang]['tocomment']?>" />
</div>

<?php else : ?>
<div class="personal-data">

<div class="respondInput">
    <label for="author"><?=$strings[$curlang]['fullname']?></label>
    <input type="text" name="author" id="author" tabindex="1" class="txt" value="<?=$strings[$curlang]['fullname']?> <?php if ($req) echo $strings[$curlang]['required']; ?>" onfocus="if (this.value == '<?=$strings[$curlang]['namereq']?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?=$strings[$curlang]['namereq']?>';}" />
</div>

<div class="respondInput">
    <label for="email"><?=$strings[$curlang]['email']?></label>
    <input type="text" name="email" id="email" tabindex="2" value="<?=$strings[$curlang]['email']?> <?php if ($req) echo $strings[$curlang]['required']; ?>" onfocus="if (this.value == '<?=$strings[$curlang]['emailreq']?>') {this.value = '';}" class="txt" onblur="if (this.value == '') {this.value = '<?=$strings[$curlang]['emailreq']?>';}" />
</div>

<div class="respondInput">
    <label for="url"><?=$strings[$curlang]['website']?></label>
    <input type="text" name="url" id="url" tabindex="3" value="<?=$strings[$curlang]['websiteopt']?>" onfocus="if (this.value == '<?=$strings[$curlang]['websiteopt']?>') {this.value = '';}" class="txt" onblur="if (this.value == '') {this.value = '<?=$strings[$curlang]['websiteopt']?>';}" />
</div>

</div>

<div class="form-data respondTextarea">
 <label for="form_message"><?=$strings[$curlang]['comment']?></label> 
    <textarea id="form_message" name="comment" tabindex="4" onfocus="if (this.value == '<?=$strings[$curlang]['typecomment']?>') {this.value = '';}"  onblur="if (this.value == '') {this.value = '<?=$strings[$curlang]['typecomment']?>';}"><?=$strings[$curlang]['typecomment']?></textarea>
</div>

<div class="respondSubmit">
	<input type="submit" id="submit" class="tinyButton roundButton" name="submit" value="<?=$strings[$curlang]['tocomment']?>" />
</div>

<?php endif; // If logged in ?>
<?php comment_id_fields(); ?>
<?php do_action('comment_form', $post->ID); ?>
</fieldset>
</form>

<?php endif; ?>
</div>

<?php endif; ?>

</div>