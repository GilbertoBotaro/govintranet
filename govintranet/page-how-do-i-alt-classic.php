<?php
/* Template name: How do I? classic page */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); 
?>

<div class="col-sm-12 white">
	<div class="row">
		<div class='breadcrumbs'>
			<?php if(function_exists('bcn_display') && !is_front_page()) {
				bcn_display();
				}?>
		</div>
	</div>
	<div class="content-wrapper">
		<h1><?php echo the_title(); ?></h1>
		<?php echo the_content(); ?>
	</div>					

	<div class="category-search">
		<div class="well well-sm">
			<form class="form-horizontal" role="form" method="get" id="task-alt-search" action="<?php echo site_url('/'); ?>">
				<div class="input-group">
					<input type="text" value="" name="s" id="sbc-s" class="multi-cat form-control input-md" placeholder="How do I..." onblur="if (this.value == '') {this.value = '';}"  onfocus="if (this.value == '') {this.value = '';}" />
					 <span class="input-group-btn">
					 <input type="hidden" name="post_type[]" value="task" />
					 <button class="btn btn-primary" type="submit"><span class="dashicons dashicons-search"></span></button>
					 </span>
				</div><!-- /input-group -->
			</form>
		</div>
		<script type='text/javascript'>
		    jQuery(document).ready(function(){
				jQuery('#task-alt-search').submit(function(e) {
				    if (jQuery.trim(jQuery("#sbc-s").val()) === "") {
				        e.preventDefault();
				        jQuery('#sbc-s').focus();
				    }
				});	
			});	
		
		</script>	

	</div>
</div>


<?php
// Display category blocks

$catcount = 0;
$terms = get_terms('category');
	if ($terms) {
  		foreach ((array)$terms as $taxonomy ) {
  		    $themeid = $taxonomy->term_id;
  		    $themeURL= $taxonomy->slug;
   		    if ($themeURL == 'uncategorized') {
	  		    continue;
  		    }
  		    $catcount++;
  		    if ($catcount==4)
  		    {
	  		    $catcount=1;
  		    }
  		    if ($catcount==1){
				echo "<div class='row white'><br>";
				echo "<div class='content-wrapper'>";
  		    }
  			echo "
			<div class='col-sm-4 white";
			if ($catcount==3){
				echo ' last';
			} 
			echo "'>
				<div class='category-block'>
					<a class='btn btn-primary t" . $taxonomy->term_id ."' href='".get_term_link($taxonomy->slug, 'category')."'>".$taxonomy->name."</a>
					<p>".$taxonomy->description."</p>
				</div>
			</div>";
			if ($catcount==3){
				echo '</div></div>';
			}
		}
		if ($catcount==3){
			echo "<div class='row white'><br><div class='content-wrapper'>";
		}
		if ($catcount==2){
			echo "</div><div class='row white'><br><div class='col-sm-12'>";
		}
		if ($catcount==1){
			echo "</div></div><div class='row white'><br><div class='content-wrapper'>";
		}						
	}  

// Big tag cloud
?>

<div class="col-sm-12">

	<h3>Search by tag</h3>
	<?php 
	$taskcloud = get_option('options_module_tasks_showtags');
	if ( $taskcloud ):
		echo gi_howto_tag_cloud('task');
	else:
		echo my_colorful_tag_cloud('','category','task'); 
	endif;
	?>
	<br><br>
</div><br>
</div>
<?php 

if ($catcount == 3){
echo "</div>";
			}
if ($catcount == 2){
echo "</div></div>";
			}
if ($catcount == 1){
echo "</div>";
			}
?>			

<?php endwhile; ?>

<?php get_footer(); ?>