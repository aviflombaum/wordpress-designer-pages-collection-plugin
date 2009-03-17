jQuery(document).ready(function() {
	jQuery('#designer-pages-table-encapsulator input, #designer-pages-table-encapsulator select').bind('blur', function(event) { jQuery('#update-message').show(); } );
	jQuery('.designer-pages-color-picker').each(function(index, domElement) {
		var id = '#' + jQuery(this).attr('id');
		jQuery(this).ColorPicker(
			{
				onSubmit: function(hsb, hex, rgb) {
					jQuery(id).val(hex);
				},
				onBeforeShow: function () {
					jQuery(this).ColorPickerSetColor(this.value);
				},
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery(id).val(hex);
					jQuery(id).ColorPickerSetColor(hex);
				}
			}
		)
		.bind('keyup', function(){
			jQuery(this).ColorPickerSetColor(this.value);
		});

	});
	jQuery('#update-widget-live').click(updateLivePreview);
});

function updateLivePreview(event) {
	event.preventDefault();
	jQuery.post(
		'admin-ajax.php',
		{
			action: 'designer_pages_live_preview',
			'header-font-color': jQuery('#header-font-color').val(),
			'header-background-color': jQuery('#header-background-color').val(),
			'font-family': jQuery('#font-family').val(),
			'background-color': jQuery('#background-color').val(),
			'font-color': jQuery('#font-color').val(),
			'border-color': jQuery('#border-color').val(),
			'products-limit': jQuery('#products-limit').val(),
			'width': jQuery('#width').val(),
			'collection-url': jQuery('#collection-url').val()
		},
		function( data, textStatus ) {
			jQuery('#designer-pages-widget').html(data);
		}
	);
}