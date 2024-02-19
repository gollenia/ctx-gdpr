<?php
	
	$title = empty($attributes['title']) ? __("Cookie Settings", "ctx-gdpr") : $attributes['title'];
	$id = 'modal-' . wp_rand(0, 1000);
	$block_attributes = get_block_wrapper_attributes();

?>
<a <?php echo $block_attributes ?> href="#"><?php echo $title ?></a>

<?php

add_action('wp_footer', function() use ($attributes, $content, $id) {
	?>
		<div id="<?php echo $id ?>" class="modal"> 
			
			
			<div class="modal__dialog">
				<div class="modal__header">
					<div class="modal__title"><h2><?php echo $attributes['modalTitle']; ?></h2><button @click="showModal = false" class="modal__close"></button></div>
					
				</div>
				
					
				<?php echo $content; ?>
						
				
			</div>
		</div> 
	<?php
});