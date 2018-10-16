<?php
/**
 * This is the main/default page template for the "manual" skin.
 *
 * This skin only uses one single template which includes most of its features.
 * It will also rely on default includes for specific dispays (like the comment form).
 *
 * For a quick explanation of b2evo 2.0 skins, please start here:
 * {@link http://b2evolution.net/man/skin-development-primer}
 *
 * The main page template is used to display the blog when no specific page template is available
 * to handle the request (based on $disp).
 *
 * @package evoskins
 * @subpackage bootstrap_manual
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );


global $cat, $tag, $MainList;


if( isset( $tag ) )
{ // Display posts list for selected tag

	// Display message if no post:
	display_if_empty();

	if( isset( $MainList ) && !empty( $MainList ) )
	{
		// ------------------------- "Item List" CONTAINER EMBEDDED HERE --------------------------
		// Display container contents:
		widget_container( 'item_list', array(
			// The following (optional) params will be used as defaults for widgets included in this container:
			'container_display_if_empty' => false, // If no widget, don't display container at all
			// This will enclose each widget in a block:
			'block_start'           => '<div class="center"><ul class="pagination">',
			'block_end'             => '</ul></div>',
			// This will enclose the title of each widget:
			'block_title_start'     => '<h3>',
			'block_title_end'       => '</h3>',
			// The following params will be used as default for widgets
			'page_current_template' => '<span class="current">$page_num$</span>',
			'page_item_before' => '<li>',
			'page_item_after' => '</li>','page_item_current_before' => '<li class="active">',
			'page_item_current_after'  => '</li>',
			'prev_text' => '<i class="fa fa-angle-left"></i>',
			'next_text' => '<i class="fa fa-angle-right"></i>',
		) );
		// ----------------------------- END OF "Item List" CONTAINER -----------------------------

		// --------------------------------- START OF POSTS -------------------------------------
		// Display lists of the posts
		echo '<ul class="posts_list">';
		while( $Item = & mainlist_get_item() )
		{
			skin_include( '_item_list.inc.php' );
		}
		echo '</ul>';
		// ---------------------------------- END OF POSTS ------------------------------------

		// -------------------- PREV/NEXT PAGE LINKS (POST LIST MODE) --------------------
		mainlist_page_links( array(
			'block_start' => '<div class="center"><ul class="pagination">',
			'block_end' => '</ul></div>',
			'page_current_template' => '<span class="current">$page_num$</span>',
			'page_item_before' => '<li>',
			'page_item_after' => '</li>','page_item_current_before' => '<li class="active">',
			'page_item_current_after'  => '</li>',
			'prev_text' => '<i class="fa fa-angle-left"></i>',
			'next_text' => '<i class="fa fa-angle-right"></i>',
		) );
		// ------------------------- END OF PREV/NEXT PAGE LINKS -------------------------
	}
}
elseif( !empty( $cat ) && ( $cat > 0 ) )
{ // Display Category's page
	global $Item;

	$ChapterCache = & get_ChapterCache();
	// Load blog's categories
	$ChapterCache->reveal_children( $Blog->ID );
	$curr_Chapter = & $ChapterCache->get_by_ID( $cat, false );

	// Go Grab the featured post:
	$intro_Item = get_featured_Item(); // $intro_Item is used below for comments form

	if( empty( $intro_Item ) || $intro_Item->get( 'title' ) == '' )
	{ // Display chapter title only if intro post has no title
		echo '<div class="cat_title">';

		echo '<h1>'.$curr_Chapter->get( 'name' ).'</h1>';
		echo '<div class="'.button_class( 'group' ).'">';
		echo $curr_Chapter->get_edit_link( array(
				'text'          => get_icon( 'edit' ).' '.T_('Edit Cat'),
				'class'         => button_class( 'text' ),
				'redirect_page' => 'front',
			) );

		// Button to create a new page
		$write_new_intro_url = $Blog->get_write_item_url( $cat, '', '', 1520 );
		if( !empty( $write_new_intro_url ) )
		{ // Display button to write a new intro
			echo '<a href="'.$write_new_intro_url.'" class="'.button_class( 'text' ).'">'
					.get_icon( 'add' ).' '
					.T_('Add Intro')
				.'</a>';
		}
		echo '</div>';

			echo '</div>';
	}

	if( ! empty( $intro_Item ) )
	{ // We have a featured/intro post to display:
		$Item = $intro_Item;
		echo '<div class="evo_content_block">'; // Beginning of posts display
		// ---------------------- ITEM BLOCK INCLUDED HERE ------------------------
		skin_include( '_item_block.inc.php', array_merge( array(
				'feature_block'     => true,
				'content_mode'      => 'auto',		// 'auto' will auto select depending on $disp-detail
				'intro_mode'        => 'normal',	// Intro posts will be displayed in normal mode
				'item_class'        => 'well evo_post evo_content_block',
				'disp_comments'     => false,
				'disp_comment_form' => false,
				'disp_notification' => false,
				'item_link_type'    => 'none',
			), $Skin->get_template( 'disp_params' ) ) );
		// ----------------------------END ITEM BLOCK  ----------------------------
		echo '</div>'; // End of posts display
	}

	$callbacks = array(
		'line'  => 'cat_inskin_display',
		'posts' => 'item_inskin_display'
	);

	// Display subcategories and posts
	echo '<ul class="chapters_list posts_list">';

	$ChapterCache->iterate_through_category_children( $curr_Chapter, $callbacks, false, array( 'sorted' => true ) );

	echo '</ul>';

	// Button to create a new sub-chapter
	$create_new_chapter_url = $Blog->get_create_chapter_url( $cat );
	// Button to create a new page
	$write_new_post_url = $Blog->get_write_item_url( $cat );
	if( ! empty( $create_new_chapter_url ) || ! empty( $write_new_post_url ) )
	{
		echo '<div class="'.button_class( 'group' ).'" style="margin:15px 0">';
		if( ! empty( $create_new_chapter_url ) )
		{ // Display button to write a new post
			echo '<a href="'.$create_new_chapter_url.'" class="'.button_class( 'text' ).'">'.get_icon( 'add' ).' '.T_('Add a sub-chapter here').'</a>';
		}
		if( ! empty( $write_new_post_url ) )
		{ // Display button to write a new post
			echo '<a href="'.$write_new_post_url.'" class="'.button_class( 'text' ).'">'.get_icon( 'add' ).' '.T_('Add a page here').'</a>';
		}
		echo '</div>';
	}

	if( ! empty( $intro_Item ) )
	{
		global $c, $ReqURI;
		$c = 1; // Display comments

		echo '<div class="evo_content_block">'; // Beginning of posts display
		// ------------------ FEEDBACK (COMMENTS/TRACKBACKS) INCLUDED HERE ------------------
		skin_include( '_item_feedback.inc.php', array_merge( array(
				'before_section_title' => '<h3 class="evo_comment__list_title">',
				'after_section_title'  => '</h3>',
				'Item'                 => $intro_Item,
				'form_title_text'      => T_('Comment form'),
				'comments_title_text'  => T_('Comments on this chapter'),
				'form_comment_redirect_to' => $ReqURI,
			), $Skin->get_template( 'disp_params' ) ) );
		// Note: You can customize the default item feedback by copying the generic
		// /skins/_item_feedback.inc.php file into the current skin folder.
		// ---------------------- END OF FEEDBACK (COMMENTS/TRACKBACKS) ---------------------
		echo '</div>'; // End of posts display
	}

} // End of Category's page
else
{ // Display the latest posts:
	// ------------------------- "Item List" CONTAINER EMBEDDED HERE --------------------------
	// Display container contents:
	widget_container( 'item_list', array(
		// The following (optional) params will be used as defaults for widgets included in this container:
		'container_display_if_empty' => false, // If no widget, don't display container at all
		// This will enclose each widget in a block:
		'block_start'           => '<div class="center"><ul class="pagination">',
		'block_end'             => '</ul></div>',
		// This will enclose the title of each widget:
		'block_title_start'     => '<h3>',
		'block_title_end'       => '</h3>',
		// The following params will be used as default for widgets
		'page_current_template' => '<span class="current">$page_num$</span>',
		'page_item_before' => '<li>',
		'page_item_after' => '</li>','page_item_current_before' => '<li class="active">',
		'page_item_current_after'  => '</li>',
		'prev_text' => '<i class="fa fa-angle-left"></i>',
		'next_text' => '<i class="fa fa-angle-right"></i>',
	) );
	// ----------------------------- END OF "Item List" CONTAINER -----------------------------
?>
<ul class="posts_list">
<?php
	while( $Item = & mainlist_get_item() )
	{	// For each blog post, do everything below up to the closing curly brace "}"
		// ---------------------- ITEM BLOCK INCLUDED HERE ------------------------
		skin_include( '_item_list.inc.php', array(
				'before_title'   => '<h3>',
				'after_title'    => '</h3>',
			) );
		// ----------------------------END ITEM BLOCK  ----------------------------
	}
?>
</ul>
<?php
	// -------------------- PREV/NEXT PAGE LINKS (POST LIST MODE) --------------------
	mainlist_page_links( array(
		'block_start' => '<div class="center"><ul class="pagination">',
		'block_end' => '</ul></div>',
		'page_current_template' => '<span class="current">$page_num$</span>',
		'page_item_before' => '<li>',
		'page_item_after' => '</li>','page_item_current_before' => '<li class="active">',
		'page_item_current_after'  => '</li>',
		'prev_text' => '<i class="fa fa-angle-left"></i>',
		'next_text' => '<i class="fa fa-angle-right"></i>',
	) );
	// ------------------------- END OF PREV/NEXT PAGE LINKS -------------------------
} // End of List of the latest posts

?>