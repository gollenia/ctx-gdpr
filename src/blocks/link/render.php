<?php
	$title = $attributes['title'] ?? "";
	$icon = $attributes['icon'] ?? "";
	$id = 'modal-' . wp_rand(0, 1000);
	$block_attributes = get_block_wrapper_attributes();
	
?>
<a <?php echo $block_attributes ?> href="#/" id="ctx-gdpr-link">
<?php echo $icon ? "<i class=\"material-icons\">" . $icon . "</i>" : "" ?>
<?php echo $title ? "<span>" . $title . "</span>" : "" ?>
</a>

<?php

add_action('wp_footer', function() use ($attributes, $content, $id) {
	
	$consent = \Contexis\Cookies\Cookies::get_consent();
	$consent_all = \Contexis\Cookies\Cookies::get_consent_all();
	?>
		<div id="ctx-gdpr-modal" class="ctx-gdpr-modal <?php echo $consent ? "" : "is-visible" ?>"> 
			
			<div class="ctx-gdpr-modal-dialog">
				<div class="ctx-gdpr-modal-header">
					<div class="modal__title">
						<h2><?php echo empty($attributes['modalTitle']) ? __('Cookie Settings', 'ctx-gdpr') : $attributes['modalTitle']; ?></h2>
						<button @click="showModal = false" class="modal__close"></button>
					</div>
				</div>
				<div class="ctx-gdpr-modal-body">	
				<?php echo $content; ?>
				<div class="ctx-gdpr-modal-checkboxes form">
					<div class="checkbox">
					<label for="ctx-gdpr-checkbox-1">
					<input type="checkbox" id="ctx-gdpr-checkbox-1" name="ctx-gdpr-checkbox-1" value="ctx-gdpr-checkbox-1" disabled checked>
					<?php 
						echo empty($attributes['neededCookiesLabelText']) ? __("Necessary Cookies", "ctx-gdpr") : $attributes['neededCookiesLabelText']; 
					?></label>
					</div>
					<div class="checkbox">
					<label for="ctx-gdpr-accept-third-party">	
					<input type="checkbox" id="ctx-gdpr-accept-third-party" name="ctx-gdpr-accept-third-party"  <?php echo $consent_all ? "checked" : "" ?>>
					<?php 
						echo empty($attributes['thirdPartyCookiesLabelText']) ? __("Accept third party cookies", "ctx-gdpr") : $attributes['thirdPartyCookiesLabelText'];
					?></label>
					</div>
				</div>
				<div class="ctx-gdpr-modal-footer">
					<button class="button button--primary ctx-gdpr-modal-save" id="ctx-gdpr-modal-save"><?php echo empty($attributes['saveSettingsButtonTitle']) ? __("Save Settings", "ctx-gdpr") : $attributes['saveSettingsButtonTitle']; ?></button>
					<button class="button button--primary ctx-gdpr-modal-accept-all" id="ctx-gdpr-modal-accept-all"><?php echo empty($attributes['acceptAllButtonTitle']) ? __("Accept All", "ctx-gdpr") : $attributes['acceptAllButtonTitle']; ?></button>
				</div>
			</div>
		</div> 
	<?php
});