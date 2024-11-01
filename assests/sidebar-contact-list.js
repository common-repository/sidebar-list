(function ($) {
	"use strict";

	$(document).ready(function () {

        $('.output-settings #output-color').wpColorPicker();

		$(document).on('click', '.sidebar-contact-list-tools .edit', function (e) {
			e.preventDefault();
			$(this).closest('li').children('.sidebar-contact-list-container').slideToggle();
		});

		$(document).on('click', ".sidebar-contact-list-img .add-img", function (e) {
			var frame = wp.media({
				title: 'Select or Upload Media for social image',
				button: {
					text: "Use This Media",
				},
				multiple: false,
			});
			e.preventDefault();
			frame.open();
			var img = $(this).siblings('.sidebar-contact-list-img img'),
				input = $(this).siblings('.sidebar-contact-list-img input');
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				input.prop("value", attachment.url);
				img.css('display', 'block');
				img.prop("src", attachment.url);
			});
			$(this).text("Change");
			$(this).next().css("display", "inline-block");
		});

		$(document).on('click', ".sidebar-contact-list-img .remove-img", function (e) {
			var list_body = $(this).closest('tr');
			e.preventDefault();
			$(this).prev().text("Add");
			list_body.find('input').attr("value", '');

			list_body.find('img').prop("src", '');
			list_body.find('img').css("display", 'none');
			$(this).hide();
		});

		$(document).on('click', '.add-list', function (e) {
			e.preventDefault();
			$(this).prev().append($(this).prev().find('#sample').html());
			$(this).prev().find('span.sidebar-contact-list-container').last().css({
				"display": "block"
			});
		});

		$(document).on('click', '.sidebar-contact-list-tools .delete', (function (e) {
			e.preventDefault();
			if (confirm("Are you sure want to delete?") == true) {
				$(this).closest('li').remove();
			}
		}));

	});
})(jQuery);
