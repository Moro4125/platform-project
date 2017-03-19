// Script for cache web-fonts in local storage. Version: 1.0
// Usage: fontCache('Roboto', 'https://fonts.googleapis.com/css?family=Roboto:300&subset=latin,cyrillic-ext#v24');
//noinspection JSUnusedGlobalSymbols
function fontCache(ns, cssUrl) {
	var cssCache,
			fontCount = 1,
			functionConst = 'function',
			storage = localStorage,
			head = document.getElementsByTagName('head')[0];

	var isSupported = function() {
		try {
			storage.setItem(functionConst, functionConst);
			storage.removeItem(functionConst);
			return typeof XMLHttpRequest == functionConst && typeof Uint8Array == functionConst;
		} catch(e) {
			return false;
		}
	};

	var injectRawCssCache = function() {
		var style = document.createElement('style');
		style.innerHTML = cssCache;
		head.appendChild(style);
	};

	var arrayBuffer2base64 = function(buffer) {
		var bytes = new Uint8Array(buffer),
				b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
				o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='', length = bytes.byteLength;

		do {
			o1 = bytes[i++];
			o2 = bytes[i++];
			o3 = bytes[i++];

			bits = o1<<16 | o2<<8 | o3;

			h1 = bits>>18 & 0x3f;
			h2 = bits>>12 & 0x3f;
			h3 = bits>>6 & 0x3f;
			h4 = bits & 0x3f;

			enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
		} while (i < length);

		switch (length % 3) {
			case 1:
				enc = enc.slice(0, -2) + '==';
				break;
			case 2:
				enc = enc.slice(0, -1) + '=';
				break;
		}

		return enc;
	};

	var trySaveCssCacheInStorage = function() {
		if (1 > --fontCount) {
			storage.removeItem(storage.getItem(ns) || functionConst);
			storage.setItem(cssUrl, cssCache);
			storage.setItem(ns, cssUrl);
		}
	};

	var prepareCssForStorage = function() {
		var pattern = /url\((?:"|')?(https?:.*?)(?:"|')?\)/g,
				match;

		while (match = pattern.exec(cssCache)) {
			(function(fontUrl) {
				var xhr = new XMLHttpRequest();
				fontCount++;

				xhr.open('GET', fontUrl, true);
				xhr.responseType = 'arraybuffer';
				xhr.onreadystatechange = function() {
					if (xhr.readyState === 4) {
						if (xhr.status >= 200 && xhr.status < 400) {
							cssCache = cssCache.replace(
									new RegExp("(\"|')?" + fontUrl.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&") + "(\"|')?", ""),
									"'data:" + xhr.getResponseHeader("Content-Type") + ";base64," + arrayBuffer2base64(xhr.response) + "'"
							);
						}

						trySaveCssCacheInStorage();
					}
				};
				xhr.send();
			})(match[1]);
		}

		trySaveCssCacheInStorage();
	};

	if (isSupported()) {
		if (cssCache = storage.getItem(cssUrl)) {
			injectRawCssCache();
		}
		else {
			var xhr = new XMLHttpRequest();
			xhr.open('GET', cssUrl, true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState === 4) {
					cssCache = xhr.responseText;
					injectRawCssCache();
					setTimeout(prepareCssForStorage, 2016);
				}
			};
			xhr.send();
		}
	}
	else {
		cssCache = document.createElement('link');
		cssCache.setAttribute("rel", "stylesheet");
		cssCache.setAttribute("href", cssUrl);
		head.appendChild(cssCache);
	}
}
