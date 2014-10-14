

$(function() {

	/** Carousel **/
	$('.carousel').carousel({
		interval: 3000
	})

	/** Lightbox **/
	$('.lightbox').magnificPopup({
		type: 'image'
	});

	/** GMaps Integration **/
	$('.map-canvas').each(function (index) {

		var map = new GMaps({
			div: $(this).attr('id'),
			lat: $(this).data('latitude'),
			lng: $(this).data('longitude')
		});

		map.addMarker({
			lat: $(this).data('latitude'),
			lng: $(this).data('longitude'),
			title: $(this).data('markertitle')
		});
	});
});
