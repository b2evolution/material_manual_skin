<?php
/**
 * This is the template that displays the item block in list
 *
 * This file is not meant to be called directly.
 * It is meant to be called by an include in the main.page.php template (or other templates)
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/gnu-gpl-license}
 * @copyright (c)2003-2015 by Francois Planque - {@link http://fplanque.com/}
 *
 * @package evoskins
 * @subpackage bootstrap_manual
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

// Default params:
$params = array_merge( array(
		'post_navigation' => 'same_category', // Always navigate through category in this skin
		'before_title'    => '<h3>',
		'after_title'     => '</h3>',
		'Item'            => NULL
	), $params );

global $Item;
if( ! empty( $params['Item'] ) )
{ // Get Item from params:
	$Item = $params['Item'];
}

?>
<li>
	<?php
	$item_action_links = $Item->get_edit_link( array(
			'before' => '',
			'after'  => '',
			'class' => button_class( 'text' ),
		) );
	$item_action_links .= $Item->get_copy_link( array(
			'before' => '',
			'after'  => '',
			'class' => button_class(),
			'text'  => '#icon#',
		) );
	if( ! empty( $item_action_links ) )
	{	// Group all action icons:
		$item_action_links = '<div class="'.button_class( 'group' ).'">'.$item_action_links.'</div>';
	}

	// ------------------------- "Item in List" CONTAINER EMBEDDED HERE --------------------------
	// Display container contents:
	widget_container( 'item_in_list', array(
		'widget_context' => 'item', // Signal that we are displaying within an Item
		// The following (optional) params will be used as defaults for widgets included in this container:
		'container_display_if_empty' => false, // If no widget, don't display container at all
		// This will enclose each widget in a block:
		'block_start' => '<div class="evo_widget $wi_class$">',
		'block_end'   => '</div>',
		// This will enclose the title of each widget:
		'block_title_start' => '<h3>',
		'block_title_end'   => '</h3>',

		// Controlling the title:
		'widget_item_title_params'  => array(
			'before'          => $params['before_title'],
			'after'           => $params['after_title'].$item_action_links.'<div class="clear"></div>',
			'before_title'    => get_icon( 'file_message' ),
			'post_navigation' => $params['post_navigation'],
			'link_class'      => 'link',
		),
		// Item Visibility Badge widge template
		'widget_item_visibility_badge_display'  => ( ! $Item->is_intro() && $Item->status != 'published' ),
		'widget_item_visibility_badge_template' => '<div class="evo_status evo_status__$status$ badge pull-right" data-toggle="tooltip" data-placement="top" title="$tooltip_title$">$status_title$</div>',
	) );
	// ----------------------------- END OF "Item in List" CONTAINER -----------------------------

	// this will create a <section>
		// ---------------------- POST CONTENT INCLUDED HERE ----------------------
		skin_include( '_item_content.inc.php', $params );
		// Note: You can customize the default item content by copying the generic
		// /skins/_item_content.inc.php file into the current skin folder.
		// -------------------------- END OF POST CONTENT -------------------------
	// this will end a </section>
?></li>
