<?php
/**
 * The template for displaying news taxonomy pages.
 */


get_header(); 
$catname = get_queried_object()->name;					
$catid = get_queried_object()->term_id;	
$catslug = get_queried_object()->slug;	
$catdesc = get_queried_object()->description;	
$catparentid = get_queried_object()->parent; 
$catparentlink = '';
$tasktagslug = '';
$tasktag = '';

if ($catparentid):
	$catparent = get_term($catparentid, 'category');
	$catparentlink = "<a href='".get_term_link($catparentid, 'news-type')."'>".$catparent->name."</a> &raquo; ";
endif;
if ( isset( $_GET['showtag'] ) ) $tasktagslug = $_GET['showtag'];
if ($tasktagslug):
	$tasktag = get_tags(array('slug'=>$tasktagslug));
	$tasktag = $tasktag[0]->name;
endif;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if ( have_posts() )
		the_post();
			 
?>

	<div class="col-lg-7 col-md-8 col-sm-12 white">
		<div class="row">
			<div class='breadcrumbs'>
				<a title="Go to Home." href="<?php echo site_url(); ?>/" class="site-home">Home</a> &raquo; 
				<a title="Go to News types?" href="<?php echo site_url(); ?>/newspage/">News</a> &raquo; <?php echo $catparentlink.$catname; ?>
			</div>
		</div>

		<h1 <?php echo "class='h1_" . $catid . "'>". single_tag_title( '', false ) ; if ($tasktag) echo " <small><span class='glyphicon glyphicon-tag'></span> ".$tasktag."</small>";?></h1>
	
			<?php echo $catdesc; ?>
				
		<h3 class='widget-title h1_<?php echo $catid; ?>'>Browse by tag</h3>
		<?php 
		//echo my_colorful_tag_cloud($catid, 'category' , 'task'); 
		echo gi_tag_cloud('news-type',$catslug,'news');
		/* Run the loop for the category page to output the posts.
		 */
		if ($tasktagslug):
			$taskitems = new WP_Query(
					array (
			'post_type'=>'news',
			'news-type'=>$catslug,
			'tag'=>$tasktagslug,
			'posts_per_page' => 25,
			'paged' => $paged,												
			'orderby'=>'date',
			'order'=>'DESC',
			'post_parent'=>0
			)
			);
		else:
			$taskitems = new WP_Query(
					array (
			'post_type'=>'news',
			'news-type'=>$catslug,
			'posts_per_page' => 25,
			'paged' => $paged,												
			'orderby'=>'date',
			'order'=>'DESC',
			'post_parent'=>0
			)
			);
		endif;
		if ($taskitems->post_count==0){
			echo "<p>Nothing to show.</p>";
		}
		while ($taskitems->have_posts()) {
			$taskitems->the_post();
			$image_url = get_the_post_thumbnail($id, 'thumbnail', array('class' => 'alignright'));
			echo "<hr>";			
			echo "<div class='newsitem'>".$image_url ;
			?>
		<h3>				
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( '%s %s', 'govintranetpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>

			<?php
			the_excerpt(); 
			echo "</div>";
			echo '<div class="clearfix"></div>';
		 }
		 ?>
<?php 	if (  $taskitems->max_num_pages > 1 ) : ?>
<?php 		if (function_exists(wp_pagenavi)) : ?>
			<?php wp_pagenavi(array('query' => $taskitems)); ?>
			<?php else : ?>
			<?php next_posts_link('&larr; Older items', $taskitems->max_num_pages); ?>
			<?php previous_posts_link('Newer items &rarr;', $taskitems->max_num_pages); ?>						
			<?php 
			endif; 
		endif; 
		wp_reset_query();								
		?>
	</div>

	<div class="col-lg-4 col-lg-offset-1 col-md-4 col-sm-12">

			<?php
				$terms = get_terms('news-type',array("hide_empty"=>true,"parent"=>$catid));
				if ($terms) {
				?>
		<div class="widget-box">
			<h3 class='widget-title'>Sub-categories</h3>
			<ul class="howdoi">
				<?php				
		  			foreach ((array)$terms as $taxonomy ) {
			  		    $themeid = $taxonomy->term_id;
			  			$themeURL= $taxonomy->slug;
			  			$desc='';
				  		if ($themeURL == 'uncategorized') {
			  		    	continue;
			  			}
						echo "
						<li class='howdoi'><span class='brd". $taxonomy->term_id ."'>&nbsp;</span>&nbsp;<a href='".site_url()."/news-type/{$themeURL}/'>".$taxonomy->name."</a>".$desc."</li>";
					}
					?>
			</ul>
		</div>
		<?php
				} 

			$taxonomies=array();
		$post_type = array();
		$taxonomies[] = 'news-type';
		$post_type[] = 'news';
		$post_cat = get_terms_by_post_type( $taxonomies, $post_type);
		if ($post_cat){
			echo "<div class='widget-box'><h3 class='widget-title'>Other categories</h3>";
			echo "<p class='taglisting {$post->post_type}'>";
			foreach($post_cat as $cat){
				if ( $cat->name!='Uncategorized' && $cat->name && $cat->term_id != $catid ){
					$newname = str_replace(" ", "&nbsp;", $cat->name );
					echo "<span class='wptag t".$cat->term_id."'><a href='".site_url()."/news-type/".$cat->slug."'>".$newname."</a></span> ";
				}
			}
			echo "</p></div>";
		}
		?>	
	</div>
<?php 
wp_reset_query();
get_footer(); 
?>
