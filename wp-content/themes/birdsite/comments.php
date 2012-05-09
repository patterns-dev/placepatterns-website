			<div id="comments">
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'birdsite' ); ?></p>
			</div>
<?php
		return;
	endif;
?>

<?php if ( have_comments() ) : ?>
			<h2 id="comments-title"><?php
			printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'birdsite' ),
			number_format_i18n( get_comments_number() ));
			?></h2>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation top">
				<div class="nav-next"><?php next_comments_link( __( '&laquo; Next Comments', 'birdsite' ) ); ?></div>
				<div class="nav-previous"><?php previous_comments_link( __( 'Previous Comments &raquo;', 'birdsite' ) ); ?></div>
			</div>
<?php endif;  ?>

				<ol class="commentlist">
				<?php
					wp_list_comments( array( 'callback' => 'birdsite_custom_comments' ) );
				?>
			</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation bottom">
				<div class="nav-next"><?php next_comments_link( __( '&laquo; Next Comments', 'birdsite' ) ); ?></div>
				<div class="nav-previous"><?php previous_comments_link( __( 'Previous Comments &raquo;', 'birdsite' ) ); ?></div>
			</div>
<?php endif; ?>

<?php else :
	if ( ! comments_open() ) :
?>
	<p class="nocomments"><?php _e( 'Comments are closed.', 'birdsite' ); ?></p>
<?php endif; ?>

<?php endif; ?>

<?php $myfields =  array(
	'author' => '<label for="author"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="22"' .($req ? ' aria-required="true"' : '') . ' /><em>' . __( 'Name', 'birdsite' ) . ($req ? ' ' .__( '(*required)', 'birdsite' ) : '') .'</em></label>',
	'email'  => '<label for="email"><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' .($req ? ' aria-required="true"' : '') . ' /><em>' . __('Email (will not be published)', 'birdsite') . ($req ? ' ' .__( '(*required)', 'birdsite' ) : '') .'</em></label>',
	'url' => '<label for="url"><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /><em>' . __( 'Website', 'birdsite' ) .'</em></label>',
); ?>

<?php $myform = array(
	'fields' => apply_filters( 'comment_form_default_fields', $myfields ),
	'comment_field' => '<label for="comment">' . '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></label>',
	'comment_notes_before' => '',
	); ?>

<?php comment_form($myform); ?>

</div>
