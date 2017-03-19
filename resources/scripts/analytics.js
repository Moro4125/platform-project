setTimeout(function() {
	if (typeof ga != "undefined") {
		ga("send", "event", "Non-Bounce", "13_seconds");
	}
}, 13000);

require(["jquery"], function(jQuery) {
	jQuery("a").each(function() {
		var self = jQuery(this),
			href = self.attr("href"),
			match = href.match(/^(https?:)?\/\/([^\/]+)/);

		if (match && !window.location.href.match(new RegExp("^(https?:)?//" + match[2]))) {
			self.on({
				click: function(event) {
					if (typeof ga != "undefined") {
						ga("send",  "event", "Outbound-Links", window.location.href, href);
					}
				}
			});
		}
	});
});