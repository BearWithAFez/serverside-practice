jQuery(function($) {

	// Abuse Bootstraps Modal to act as a Lightbox, by Bramus!
	$('.book-cover').on('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var $img = $(this),
			$modal = $('#img-modal');
		$modal.find('img').attr('src', $img.attr('src')).css('max-height', $(window).height() - 250).end().find('.modal-title').html($img.attr('title')).end().modal('show');
	});

});