

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

	if($('.socialshareprivacy').length) {
		$('.socialshareprivacy').socialSharePrivacy({
			'services' : {
				'facebook' : {
					'sharer' : {
						'status'    : 'on',
						'dummy_img' : $('.socialshareprivacy').data('basepath') + '/images/dummy_facebook_share_de.png',
						'img'       : $('.socialshareprivacy').data('basepath') + '/images/facebook_share_de.png'
					}
				},
				twitter : {
					'status' : 'on',
					'dummy_img' : $('.socialshareprivacy').data('basepath') + '/images/dummy_twitter.png'
				},
				gplus : {
					'status' : 'off'
				}
			},
			'lang_path' : $('.socialshareprivacy').data('basepath') + '/lang/',
			'css_path'	: $('.socialshareprivacy').data('basepath') +  '/socialshareprivacy.css',
			'language'  : 'de',
			'uri' 		: $(this).data('uri'),
			'info_link'	: '/impressum.html'
		});
	}
});
