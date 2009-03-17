<?php $settings = $this->getSettings(); ?>
<style type="text/css">
#designer-pages-table-encapsulator {
	width: 450px;
	float: left;
}
#designer-pages-widget-live-preview {
	float: right;
	width: 300px;
}
</style>
<div class="wrap">
	<?php if( function_exists( 'screen_icon' ) ) { screen_icon(); } ?>
	<h2><?php _e( 'Designer Pages Settings' ); ?></h2>
	<p><?php _e( 'On this page, you can change the settings for the Designer Pages widget.  After making changes, feel free to press the "Update" button see a preview.' ); ?></p>
	<form method="post" action="options-general.php?page=designer-pages">
	<div id="designer-pages-table-encapsulator">
		<h3><?php _e( 'Colors' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="header-font-color"><?php _e( 'Header Font Color' ); ?></label>
					</th>
					<td>
						#<input class="designer-pages-color-picker" type="text" size="7" id="header-font-color" name="header-font-color" value="<?php echo attribute_escape( $settings[ 'header-font-color' ] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="header-background-color"><?php _e( 'Header Background Color' ); ?></label>
					</th>
					<td>
						#<input class="designer-pages-color-picker" type="text" size="7" id="header-background-color" name="header-background-color" value="<?php echo attribute_escape( $settings[ 'header-background-color' ] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="font-color"><?php _e( 'Font Color' ); ?></label>
					</th>
					<td>
						#<input class="designer-pages-color-picker" type="text" size="7" id="font-color" name="font-color" value="<?php echo attribute_escape( $settings[ 'font-color' ] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label class="designer-pages-color-picker" for="background-color"><?php _e( 'Background Color' ); ?></label>
					</th>
					<td>
						#<input class="designer-pages-color-picker" type="text" size="7" id="background-color" name="background-color" value="<?php echo attribute_escape( $settings[ 'background-color' ] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="border-color"><?php _e( 'Border Color' ); ?></label>
					</th>
					<td>
						#<input class="designer-pages-color-picker" type="text" size="7" id="border-color" name="border-color" value="<?php echo attribute_escape( $settings[ 'border-color' ] ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
		<h3><?php _e( 'Other' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="collection-url"><?php _e( 'Collection URL' ); ?></label>
					</th>
					<td>
						<input type="text" size="60" id="collection-url" name="collection-url" value="<?php echo attribute_escape( $settings[ 'collection-url' ] ); ?>" /><br />
						<?php _e( 'This field expects a value like ' ); ?>:<br /><code>http://www.designerpages.com/collections/yanko-design/</code>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="font-family"><?php _e( 'Font Family' ); ?></label>
					</th>
					<td>
						<select id="font-family" name="font-family">
						<?php 
						foreach( $this->availableFonts as $fontName ) {
							?><option <?php selected( $fontName, $settings[ 'font-family' ] ); ?> value="<?php echo $fontName; ?>"><?php echo $fontName; ?></option><?php
						}
						?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="width"><?php _e( 'Width (px)' ); ?></label>
					</th>
					<td>
						<input type="text" size="4" id="width" name="width" value="<?php echo attribute_escape( $settings[ 'width' ] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="products-limit"><?php _e( '# of Products' ); ?></label>
					</th>
					<td>
						<select id="products-limit" name="products-limit">
							<?php 
							foreach( range( 2, 10 ) as $number ) {
								?><option <?php selected( $number, $settings[ 'products-limit' ] ); ?> value="<?php echo $number; ?>"><?php echo $number; ?></option><?php
							}
							?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="designer-pages-widget-live-preview">
		<div id="designer-pages-widget">
		<?php echo $this->getOutputFromRemoteServices();?>
		</div>
		<p><input class="button-secondary" type="submit" name="save-designer-pages-widget-settings" id="update-widget-live" value="<?php _e( 'Update Live Preview' ); ?>" /></p>
		<p id="update-message" style="display: none;"><?php _e( 'Click above to see a live preview of your changes.' ); ?><br /><br /><strong><?php _e( 'You must save your settings for them to take hold.  This widget is for preview only.' ); ?></strong></p>
	</div>
	<br class="clear" />
	<p class="submit">
		<?php wp_nonce_field( 'save-designer-pages-widget-settings' ); ?>
		<input class="button-primary" type="submit" name="save-designer-pages-widget-settings" id="save-designer-pages-widget-settings" value="<?php _e( 'Save Settings' ); ?>" />
	</p>
	</form>
</div>