<?php

/* Template name: Aggregator page */
/* Allows 3 columns of custom placeholder areas for displaying categorised listings and free-format content */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<?php $maincontent = get_the_content($id); ?>


<?php

remove_filter('pre_get_posts', 'filter_search');

function filter_news($query) {
    if ($query->is_tag && !is_admin()) {
		$query->set('post_type', array('news'));
    }
    return $query;
}; 

function filter_tasks($query) {
    if ($query->is_tag && !is_admin()) {
		$query->set('post_type', array('task'));
    }
    return $query;
}; 

?>
<div id="home-col-1" class="col-lg-6 col-md-6 col-sm-7">

<?php
// COLUMN 1
// check if the flexible content field has rows of data
if( have_rows('aggregator_column_1') ):
	?>

	<?php if ( $maincontent ): ?>
		<div class="category-block">
		<?php the_content(); ?>
		</div>
	<?php endif; ?>

	<?php
	// loop through the rows of data
	
    while ( have_rows('aggregator_column_1') ) : the_row();

		global $post;
		global $title;
		global $team;
		global $checkteam;
		global $tax;
		global $tag;
		global $n2n;
		global $freshness;
		global $num;
		global $compact;
		$title = '';
		$link = '';
		$gallery = '';
		$team = '';
		$freeformat = '';
		$num = '';
		$tag = '';
		$type = '';
		$freshness = '';
		$n2n = '';
		$tax = '';

		// NEWS LISTING
		
        if ( get_row_layout() == 'aggregator_news_listing' ) :
			$title = get_sub_field('aggregator_listing_title');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$tax = get_sub_field('aggregator_listing_tax');
			$tag = get_sub_field('aggregator_listing_tag');
			$n2n = get_sub_field('aggregator_listing_need_to_know');
			$freshness = intval(get_sub_field('aggregator_listing_freshness'));
			$num = intval(get_sub_field('aggregator_listing_number')); 
			if ( !$num ) $num = -1; 
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-news-listing');
       	endif;
		

		// BLOG LISTING
		
        if ( get_row_layout() == 'aggregator_blog_listing' ) :
			$title = get_sub_field('aggregator_listing_title');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$freshness = intval(get_sub_field('aggregator_listing_freshness'));
			$num = intval(get_sub_field('aggregator_listing_number')); 
			if ( !$num ) $num = -1; 
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-blog-listing');
       	endif;


		// FREE-FORMAT AREA

        if( get_row_layout() == 'aggregator_free_format_area' ): 
        	global $freeformat;
        	$freeformat = get_sub_field('aggregator_free_format_area_content');
        	get_template_part('page-aggregator/part-free-format');
		endif;


		// TEAM LISTING

        if( get_row_layout() == 'aggregator_team_listing' ): 
			global $alreadyshown;
			global $directorystyle;
			global $showmobile;
			global $teamid;
			global $teamleaderid;
			$alreadyshown = array();
			$directorystyle = get_option('options_staff_directory_style'); // 0 = squares, 1 = circles
			$showmobile = get_option('options_show_mobile_on_staff_cards'); // 1 = show
			$team = get_sub_field('aggregator_listing_team');
        	$title = get_sub_field('aggregator_listing_title');
			$teamid = $team[0]; 
			$teamleaderid = get_post_meta($teamid, 'team_lead', true);
        	get_template_part('page-aggregator/part-team-listing');
		endif;


		// LINKS

        if( get_row_layout() == 'aggregator_link_listing' ): 
        	global $link;
			$link = get_sub_field('aggregator_listing_link');
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-links-listing');
		endif;


		// DOCUMENT LINKS

        if( get_row_layout() == 'aggregator_document_listing' ): 
			global $cat_id;
			global $doctyp;
			$cat_id = get_sub_field('aggregator_listing_category'); 
			if ( !$cat_id ) $cat_id = "any";
			$doctyp = get_sub_field('aggregator_listing_doctype');
			if ( !$doctyp ) $doctyp = "any";
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-document-listing');
		endif;


		// GALLERY

        if( get_row_layout() == 'aggregator_gallery' ): 
        	global $gallery;
			$gallery = get_sub_field('aggregator_gallery_images');
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-gallery');
		endif;

	endwhile;?>
	<?php
endif;
?>
</div>
<div id="home-col-span-2" class="col-lg-6 col-md-6 col-sm-5">

<?php

// HERO COLUMN 
// check if the flexible content field has rows of data
if( have_rows('aggregator_column_hero') ):
	?>
	<div id="home-col-hero" class="col-lg-12 col-md-12 col-sm-12">

	<?php
	// loop through the rows of data
	
    while ( have_rows('aggregator_column_hero') ) : the_row();

		$title = '';
		$link = '';
		$gallery = '';
		$team = '';
		$freeformat = '';
		$num = '';
		$tag = '';
		$type = '';
		$freshness = '';
		$n2n = '';
		$tax = '';

		// NEWS LISTING

        if ( get_row_layout() == 'aggregator_news_listing' ) :
			$title = get_sub_field('aggregator_listing_title');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$tax = get_sub_field('aggregator_listing_tax');
			$tag = get_sub_field('aggregator_listing_tag');
			$n2n = get_sub_field('aggregator_listing_need_to_know');
			$freshness = intval(get_sub_field('aggregator_listing_freshness'));
			$num = intval(get_sub_field('aggregator_listing_number')); 
			if ( !$num ) $num = -1; 
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-news-listing');
       	endif;
		
		
		// TASK LISTING
		
        if( get_row_layout() == 'aggregator_task_listing' ): 
        	$title = get_sub_field('aggregator_listing_title');
			$type = get_sub_field('aggregator_listing_type');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$tax =  get_sub_field('aggregator_listing_tax');
			$tag =  get_sub_field('aggregator_listing_tag');
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-task-listing');
		endif;


		// BLOG LISTING
		
        if ( get_row_layout() == 'aggregator_blog_listing' ) :
			$title = get_sub_field('aggregator_listing_title');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$freshness = intval(get_sub_field('aggregator_listing_freshness'));
			$num = intval(get_sub_field('aggregator_listing_number')); 
			if ( !$num ) $num = -1; 
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-blog-listing');
       	endif;


		// FREE-FORMAT AREA

        if( get_row_layout() == 'aggregator_free_format_area' ): 
        	global $freeformat;
        	$freeformat = get_sub_field('aggregator_free_format_area_content');
        	get_template_part('page-aggregator/part-free-format');
		endif;


		// TEAM LISTING

        if( get_row_layout() == 'aggregator_team_listing' ): 
			global $alreadyshown;
			global $directorystyle;
			global $showmobile;
			global $teamid;
			global $teamleaderid;
			$alreadyshown = array();
			$directorystyle = get_option('options_staff_directory_style'); // 0 = squares, 1 = circles
			$showmobile = get_option('options_show_mobile_on_staff_cards'); // 1 = show
			$team = get_sub_field('aggregator_listing_team');
        	$title = get_sub_field('aggregator_listing_title');
			$teamid = $team[0]; 
			$teamleaderid = get_post_meta($teamid, 'team_lead', true);
        	get_template_part('page-aggregator/part-team-listing');
		endif;


		// LINKS

        if( get_row_layout() == 'aggregator_link_listing' ): 
        	global $link;
			$link = get_sub_field('aggregator_listing_link');
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-links-listing');
		endif;


		// DOCUMENT LINKS

        if( get_row_layout() == 'aggregator_document_listing' ): 
			global $cat_id;
			global $doctyp;
			$cat_id = get_sub_field('aggregator_listing_category'); 
			if ( !$cat_id ) $cat_id = "any";
			$doctyp = get_sub_field('aggregator_listing_doctype');
			if ( !$doctyp ) $doctyp = "any";
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-document-listing');
		endif;


		// GALLERY

        if( get_row_layout() == 'aggregator_gallery' ): 
        	global $gallery;
			$gallery = get_sub_field('aggregator_gallery_images');
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-gallery');
		endif;


	endwhile;?>
	</div>
	<?php
endif;


// COLUMN 2
// check if the flexible content field has rows of data
if( have_rows('aggregator_column_2') ):
	?>
	<div id="home-col-2" class="col-lg-6 col-md-6 col-sm-12">

	<?php
	// loop through the rows of data
	
    while ( have_rows('aggregator_column_2') ) : the_row();

		$title = '';
		$link = '';
		$gallery = '';
		$team = '';
		$freeformat = '';
		$num = '';
		$tag = '';
		$type = '';
		$freshness = '';
		$n2n = '';
		$tax = '';

		// NEWS LISTING

        if ( get_row_layout() == 'aggregator_news_listing' ) :
			$title = get_sub_field('aggregator_listing_title');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$tax = get_sub_field('aggregator_listing_tax');
			$tag = get_sub_field('aggregator_listing_tag');
			$n2n = get_sub_field('aggregator_listing_need_to_know');
			$freshness = intval(get_sub_field('aggregator_listing_freshness'));
			$num = intval(get_sub_field('aggregator_listing_number')); 
			if ( !$num ) $num = -1; 
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-news-listing');
       	endif;
		
		
		// TASK LISTING
		
        if( get_row_layout() == 'aggregator_task_listing' ): 
        	$title = get_sub_field('aggregator_listing_title');
			$type = get_sub_field('aggregator_listing_type');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$tax =  get_sub_field('aggregator_listing_tax');
			$tag =  get_sub_field('aggregator_listing_tag');
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-task-listing');
		endif;


		// BLOG LISTING
		
        if ( get_row_layout() == 'aggregator_blog_listing' ) :
			$title = get_sub_field('aggregator_listing_title');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$freshness = intval(get_sub_field('aggregator_listing_freshness'));
			$num = intval(get_sub_field('aggregator_listing_number')); 
			if ( !$num ) $num = -1; 
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-blog-listing');
       	endif;


		// FREE-FORMAT AREA

        if( get_row_layout() == 'aggregator_free_format_area' ): 
        	global $freeformat;
        	$freeformat = get_sub_field('aggregator_free_format_area_content');
        	get_template_part('page-aggregator/part-free-format');
		endif;


		// TEAM LISTING

        if( get_row_layout() == 'aggregator_team_listing' ): 
			global $alreadyshown;
			global $directorystyle;
			global $showmobile;
			global $teamid;
			global $teamleaderid;
			$alreadyshown = array();
			$directorystyle = get_option('options_staff_directory_style'); // 0 = squares, 1 = circles
			$showmobile = get_option('options_show_mobile_on_staff_cards'); // 1 = show
			$team = get_sub_field('aggregator_listing_team');
        	$title = get_sub_field('aggregator_listing_title');
			$teamid = $team[0]; 
			$teamleaderid = get_post_meta($teamid, 'team_lead', true);
        	get_template_part('page-aggregator/part-team-listing');
		endif;


		// DOCUMENT LINKS

        if( get_row_layout() == 'aggregator_document_listing' ): 
			global $cat_id;
			global $doctyp;
			$cat_id = get_sub_field('aggregator_listing_category'); 
			if ( !$cat_id ) $cat_id = "any";
			$doctyp = get_sub_field('aggregator_listing_doctype');
			if ( !$doctyp ) $doctyp = "any";
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-document-listing');
		endif;


		// LINKS

        if( get_row_layout() == 'aggregator_link_listing' ): 
        	global $link;
			$link = get_sub_field('aggregator_listing_link');
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-links-listing');
		endif;


	endwhile;?>
	</div>
	<?php
endif;

// COLUMN 3
// check if the flexible content field has rows of data
if( have_rows('aggregator_column_3') ):
	?>
	<div id="home-col-3" class="col-lg-6 col-md-6 col-sm-12">

	<?php
	// loop through the rows of data
	
    while ( have_rows('aggregator_column_3') ) : the_row();

		$title = '';
		$link = '';
		$gallery = '';
		$team = '';
		$freeformat = '';
		$num = '';
		$tag = '';
		$type = '';
		$freshness = '';
		$n2n = '';
		$tax = '';

		// NEWS LISTING

        if ( get_row_layout() == 'aggregator_news_listing' ) :
			$title = get_sub_field('aggregator_listing_title');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$tax = get_sub_field('aggregator_listing_tax');
			$tag = get_sub_field('aggregator_listing_tag');
			$n2n = get_sub_field('aggregator_listing_need_to_know');
			$freshness = intval(get_sub_field('aggregator_listing_freshness'));
			$num = intval(get_sub_field('aggregator_listing_number')); 
			if ( !$num ) $num = -1; 
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-news-listing');
       	endif;
		
		
		// TASK LISTING
		
        if( get_row_layout() == 'aggregator_task_listing' ): 
        	$title = get_sub_field('aggregator_listing_title');
			$type = get_sub_field('aggregator_listing_type');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$tax =  get_sub_field('aggregator_listing_tax');
			$tag =  get_sub_field('aggregator_listing_tag');
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-task-listing');
		endif;


		// BLOG LISTING
		
        if ( get_row_layout() == 'aggregator_blog_listing' ) :
			$title = get_sub_field('aggregator_listing_title');
			$team = get_sub_field('aggregator_listing_team');
			$checkteam = $team[0]; 
			$freshness = intval(get_sub_field('aggregator_listing_freshness'));
			$num = intval(get_sub_field('aggregator_listing_number')); 
			if ( !$num ) $num = -1; 
			$compact = get_sub_field('aggregator_listing_compact_list');
			if ( $compact ) $larray = array();
        	get_template_part('page-aggregator/part-blog-listing');
       	endif;


		// FREE-FORMAT AREA

        if( get_row_layout() == 'aggregator_free_format_area' ): 
        	global $freeformat;
        	$freeformat = get_sub_field('aggregator_free_format_area_content');
        	get_template_part('page-aggregator/part-free-format');
		endif;


		// TEAM LISTING

        if( get_row_layout() == 'aggregator_team_listing' ): 
			global $alreadyshown;
			global $directorystyle;
			global $showmobile;
			global $teamid;
			global $teamleaderid;
			$alreadyshown = array();
			$directorystyle = get_option('options_staff_directory_style'); // 0 = squares, 1 = circles
			$showmobile = get_option('options_show_mobile_on_staff_cards'); // 1 = show
			$team = get_sub_field('aggregator_listing_team');
        	$title = get_sub_field('aggregator_listing_title');
			$teamid = $team[0]; 
			$teamleaderid = get_post_meta($teamid, 'team_lead', true);
        	get_template_part('page-aggregator/part-team-listing');
		endif;


		// DOCUMENT LINKS

        if( get_row_layout() == 'aggregator_document_listing' ): 
			global $cat_id;
			global $doctyp;
			$cat_id = get_sub_field('aggregator_listing_category'); 
			if ( !$cat_id ) $cat_id = "any";
			$doctyp = get_sub_field('aggregator_listing_doctype');
			if ( !$doctyp ) $doctyp = "any";
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-document-listing');
		endif;


		// LINKS

        if( get_row_layout() == 'aggregator_link_listing' ): 
        	global $link;
			$link = get_sub_field('aggregator_listing_link');
        	$title = get_sub_field('aggregator_listing_title');
        	get_template_part('page-aggregator/part-links-listing');
		endif;


	endwhile;?>
	</div>
	<?php
endif;
?>
</div>
<?php remove_filter('pre_get_posts', 'filter_search'); ?>
<?php endwhile; ?>
<?php get_footer(); ?>