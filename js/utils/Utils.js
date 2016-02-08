define(function(require) {
	var $, Utils;
	$ = require('jquery');
	Utils = {};
	Utils.getVendorPrefix = function() {
		var root;
		root = document.documentElement;
		if ("transform" in root.style) {
			return "";
		}
		if ("MozTransform" in root.style) {
			return "-moz-";
		}
		if ("WebkitTransform" in root.style) {
			return "-webkit-";
		}
		if ("msTransform" in root.style) {
			return "-ms-";
		}
		if ("OTransform" in root.style) {
			return "-o-";
		}
		return "";
	};
	Utils.getJSVendorPrefix = function(cssVendorPrefix) {
		cssVendorPrefix = cssVendorPrefix || this.getVendorPrefix();
		if (cssVendorPrefix === "-webkit-") {
			return "webkit";
		} else if (cssVendorPrefix === "-ms-") {
			return "ms";
		} else if (cssVendorPrefix === "-o-") {
			return "O";
		}
		return "";
	};
	Utils.convertJSVendorEvent = function(eventName, camelCaseEventName, cssVendorPrefix) {
		var jsPrefix;
		jsPrefix = this.getJSVendorPrefix(cssVendorPrefix);
		if (jsPrefix === "webkit" || jsPrefix === "O" || jsPrefix === "ms") {
			return jsPrefix + camelCaseEventName;
		}
		return eventName;
	};
	Utils.isInFrame = function() {
		return window.self !== window.top;
	};
	Utils.hexToRGB = function(hex) {
		var result, shorthandRegex;
		shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function(m, r, g, b) {
			return r + r + g + g + b + b;
		});
		result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		if (result) {
			return {
				r: parseInt(result[1], 16),
				g: parseInt(result[2], 16),
				b: parseInt(result[3], 16)
			};
		} else {
			return null;
		}
	};
	Utils.RGBToHex = function(r, g, b) {
		return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
	};
	Utils.validateEmail = function(email) {
		var re;
		re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		return re.test(email);
	};
	Utils.validateDomain = function(domain) {
		var re;
		if (!domain) {
			return false;
		}
		domain = String(domain).toLowerCase();
		if (domain.indexOf("http") === 0) {
			return true;
		}
		re = /\b((?=[a-z0-9-]{1,63}\.)(xn--)?[a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,63}\b/i;
		return re.test(domain);
	};
	Utils.getScrollY = function() {
		var y;
		y = window.pageYOffset !== void 0 ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
		return y;
	};
	Utils.getScrollX = function() {
		var x;
		x = window.pageXOffset !== void 0 ? window.pageXOffset : (document.documentElement || document.body.parentNode || document.body).scrollLeft;
		return x;
	};
	Utils.createConsole = function() {
		if (window.console) {
			return;
		}
		window.console = {};
		return window.console.log = function(text) {};
	};
	Utils.isTouchDevice = function() {
		if (window.navigator && window.navigator.msMaxTouchPoints) {
			return true;
		}
		return $("html").hasClass("touch");
	};
	Utils.hasTransitions = function() {
		return $("html").hasClass("csstransitions");
	};

	/*
	 var supportsTransitions  = (function() {
	 var s = document.createElement('p').style, // 's' for style. better to create an element if body yet to exist
	 v = ['ms','O','Moz','Webkit']; // 'v' for vendor

	 if( s['transition'] == '' ) return true; // check first for prefeixed-free support
	 while( v.length ) // now go over the list of vendor prefixes and check support until one is found
	 if( v.pop() + 'Transition' in s )
	 return true;
	 return false;
	 })();
	 */
	Utils.isIOS = function() {
		return /(iPad|iPhone|iPod)/ig.test(navigator.userAgent);
	};
	Utils.isWP = function() {
		return /Windows Phone/ig.test(navigator.userAgent);
	};
	Utils.isMac = function() {
		return /Macintosh/ig.test(navigator.userAgent);
	};
	Utils.isAndroid = function() {
		return /Android/ig.test(navigator.userAgent);
	};
	Utils.isWindows = function() {
		return /Windows/ig.test(navigator.userAgent);
	};
	Utils.isAndroidInternetBrowser = function() {
		var appleWebKitVersion, chromeVersion, isAndroidMobile, navU, regExAppleWebKit, regExChrome, resultAppleWebKitRegEx, resultChromeRegEx;
		navU = navigator.userAgent.toLowerCase();
		isAndroidMobile = navU.indexOf('android') > -1 && navU.indexOf('mozilla/5.0') > -1 && navU.indexOf('applewebkit') > -1;
		if (!isAndroidMobile) {
			return false;
		}
		regExAppleWebKit = new RegExp(/applewebkit\/([\d.]+)/);
		resultAppleWebKitRegEx = regExAppleWebKit.exec(navU);
		appleWebKitVersion = resultAppleWebKitRegEx === null ? null : parseFloat(regExAppleWebKit.exec(navU)[1]);
		if (appleWebKitVersion === null) {
			return false;
		}
		regExChrome = new RegExp(/chrome\/([\d.]+)/);
		resultChromeRegEx = regExChrome.exec(navU);
		chromeVersion = resultChromeRegEx === null ? null : parseFloat(regExChrome.exec(navU)[1]);
		if (chromeVersion === null) {
			chromeVersion = 0;
		}
		return appleWebKitVersion < 537 || chromeVersion < 37;
	};

	/*

	 is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
	 */
	Utils.isOwnLink = function(href, clean) {
		if (!href && href !== "") {
			return false;
		}
		if (!Utils.validateDomain(href)) {
			return href;
		}
		href = String(href).replace(/^http[s]?\:\/\//, "");
		if (href.indexOf(location.host) !== 0) {
			return false;
		}
		if (clean) {
			href = href.replace(location.host, "");
			return href;
		}
		return true;
	};
	Utils.getBrowser = function() {
		var M, av, browser, minorVersion, os, osVersion, tem, ua, v, version;
		ua = navigator.userAgent;
		av = navigator.appVersion;
		os = "";
		if (Utils.isIOS()) {
			os = "ios";
		} else if (Utils.isWP()) {
			os = "wp";
		} else if (Utils.isMac()) {
			os = "mac";
		} else if (Utils.isAndroid()) {
			os = "android";
		} else if (Utils.isWindows()) {
			os = "windows";
		}
		M = ua.match(/(opr|msie|iemobile(?=\/))\/?\s*([\d\.]+)/i) || [];
		if (!M || !M.length) {
			M = ua.match(/(opera|chrome|safari|firefox|trident(?=\/))\/?\s*([\d\.]+)/i) || [];
		}
		M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
		if ((tem = ua.match(/version\/([\.\d]+)/i)) !== null) {
			M[1] = tem[1];
		}
		browser = String(M[0]).toLowerCase();
		if (os === "android" && this.isAndroidInternetBrowser()) {
			browser = "android_internet";
			if ((tem = ua.match(/\/53([\.\d]+)/i)) !== null) {
				M[1] = tem[0].replace("/", "");
			}
		}
		if (browser === "opr") {
			browser = "opera";
		}
		v = M[1].split(".");
		version = parseInt(v[0], 10);
		minorVersion = parseInt(v[1], 10);
		if (browser === "trident") {
			browser = "msie";
			version += 4;
		}
		osVersion = "";
		if (os === "windows") {
			osVersion = /Windows (.*)/.exec(ua);
			if (osVersion && osVersion[1]) {
				osVersion = osVersion[1];
				osVersion = osVersion.split(")")[0];
				osVersion = osVersion.split(";")[0];
			}
		} else {
			switch (os) {
				case 'mac':
					osVersion = /Mac OS X (10[\.\_\d]+)/.exec(ua);
					if (osVersion && osVersion[1]) {
						osVersion = osVersion[1];
					}
					break;
				case 'android':
					osVersion = /Android ([\.\_\d]+)/.exec(ua);
					if (osVersion && osVersion[1]) {
						osVersion = osVersion[1];
					}
					break;
				case 'ios':
					osVersion = /OS (\d+)_(\d+)_?(\d+)?/.exec(av);
					if (osVersion) {
						osVersion = osVersion[1] + '.' + osVersion[2] + '.' + (osVersion[3] | 0);
					}
			}
		}
		if (osVersion) {
			osVersion = osVersion.replace(/_/g, ".");
		} else {
			osVersion = 9999;
		}
		return {
			browser: browser,
			version: version,
			minorVersion: minorVersion,
			fullVersion: M[1],
			os: os,
			osVersion: osVersion
		};
	};
	Utils.getIOSversion = function() {
		var v;
		if (!Utils.isIOS()) {
			return [0, 0, 0];
		}
		v = navigator.appVersion.match(/OS (\d+)_(\d+)_?(\d+)?/);
		if (!v || v.length < 2) {
			return [0, 0, 0];
		} else {
			return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
		}
	};
	Utils.isHigherVersion = function(min, current) {
		var j, k, l, len, len1, v;
		if (!min) {
			return true;
		}
		if (!current && current !== 0) {
			return false;
		}
		if (min.split) {
			min = min.split(".");
			for (k = j = 0, len = min.length; j < len; k = ++j) {
				v = min[k];
				min[k] = parseInt(v, 10);
			}
		} else {
			min = [parseInt(min, 0)];
		}
		if (current.split) {
			current = current.split(".");
			for (k = l = 0, len1 = current.length; l < len1; k = ++l) {
				v = current[k];
				current[k] = parseInt(v, 10);
			}
		} else {
			current = [parseInt(current, 0)];
		}
		min[1] = min[1] || 0;
		min[2] = min[2] || 0;
		current[1] = current[1] || 0;
		current[2] = current[2] || 0;
		if (current[0] > min[0]) {
			return true;
		}
		if (current[0] === min[0]) {
			if (!min[1]) {
				return true;
			}
			if (current[1] > min[1]) {
				return true;
			}
			if (current[1] < min[1]) {
				return false;
			}
			if (current[1] === min[1]) {
				if (!min[2]) {
					return true;
				}
				if (current[2] >= min[2]) {
					return true;
				}
			}
		}
		return false;
	};
	Utils.isFullHeight = function() {
		if (!document.documentElement || !document.documentElement.clientHeight) {
			return true;
		}
		if (!window.innerHeight) {
			return true;
		}
		return parseInt(window.innerHeight, 10) <= parseInt(document.documentElement.clientHeight, 10);
	};
	Utils.getBoundingBox = function(target, relativeToDocument, horizontal, scrollY, scrollX) {
		var e, rect, ret, scroll;
		if (!target) {
			return false;
		}
		if (target.jquery) {
			if (!target.length) {
				return false;
			}
			target = target.get(0);
		}
		try {
			rect = target.getBoundingClientRect();
		} catch (_error) {
			e = _error;
			return false;
		}
		ret = {
			top: rect.top,
			bottom: rect.bottom,
			left: rect.left,
			right: rect.right,
			height: rect.height,
			width: rect.width
		};
		if (relativeToDocument) {
			scroll = scrollY || this.getScrollY();
			ret.top += scroll;
			ret.bottom += scroll;
			if (horizontal) {
				scroll = scrollX || this.getScrollX();
				ret.left += scroll;
				ret.right += scroll;
			}
		}
		return ret;
	};
	Utils.boxHit = function(rect1, rect2, horizontal) {
		var hHit, vHit;
		if (!rect1 || rect1.top === void 0 || rect1.bottom === void 0 || !rect2 || rect2.top === void 0 || rect2.bottom === void 0) {
			return false;
		}
		vHit = rect1.bottom >= rect2.top && rect1.top <= rect2.bottom;
		if (!horizontal || !vHit) {
			return vHit;
		}
		if (rect1.left === void 0 || rect2.left === void 0 || rect1.right === void 0 || rect2.right === void 0) {
			return false;
		}
		hHit = rect1.right >= rect2.left && rect1.left <= rect2.right;
		return vHit && hHit;
	};
	Utils.pointInBox = function(point, rect, horizontal) {
		if (!rect || rect.top === void 0 || rect.bottom === void 0 || !point || point.x === void 0 || point.y === void 0) {
			return false;
		}
		if (point.x > rect.right || point.x < rect.left) {
			return false;
		}
		if (horizontal) {
			return true;
		}
		return point.y >= rect.top && point.y <= rect.bottom;
	};
	Utils.getBounds = function(target) {
		var bounds;
		bounds = {
			top: false,
			right: false,
			bottom: false,
			left: false,
			width: false,
			height: false
		};
		$("> *", target).each(function() {
			var offset, size;
			if (!$(this).is(':visible')) {
				return;
			}
			offset = $(this).position();
			if (bounds.left === false || offset.left < bounds.left) {
				bounds.left = offset.left;
			}
			if (bounds.top === false || offset.top < bounds.top) {
				bounds.top = offset.top;
			}
			size = $(this).outerWidth(true);
			if (bounds.right === false || (offset.left + size) > bounds.right) {
				bounds.right = offset.left + size;
			}
			size = $(this).outerHeight(true);
			if (bounds.bottom === false || (offset.top + size) > bounds.bottom) {
				bounds.bottom = offset.top + size;
			}
			bounds.width = bounds.right - bounds.left;
			return bounds.height = bounds.bottom - bounds.top;
		});
		return bounds;
	};
	Utils.getContentHeight = function(target) {
		var h, outlineHeight;
		h = 0;
		outlineHeight = target.outerHeight(true) - target.height();
		target.children().each(function() {
			var el, oh, pos;
			el = $(this);
			pos = el.position();
			oh = el.outerHeight(true);
			if ((oh + pos.top) > h) {
				return h = oh + pos.top;
			}
		});
		return target.get(0).scrollTop + h + outlineHeight;
	};
	Utils.fillParent = function(target) {
		var nh, nw, oh, oratio, ow, parent, ph, pr, pw, scale;
		if (!target || !target.parent || !target.length) {
			return false;
		}
		parent = target.parent();
		pw = parent.width();
		ph = parent.height();
		pr = pw / ph;
		if (target.data("ow")) {
			ow = parseInt(target.data("ow"), 10);
			oh = parseInt(target.data("oh"), 10);
		} else if (target.attr("width")) {
			ow = parseInt(target.attr("width"), 10);
			oh = parseInt(target.attr("height"), 10);
		} else {
			ow = parseInt(target.width(), 10);
			oh = parseInt(target.height(), 10);
		}
		oratio = ow / oh;
		if (isNaN(oratio)) {
			return false;
		}
		scale = Math.max(pw / ow, ph / oh);
		if (pw / ow > ph / oh) {
			nw = ow * scale;
			nh = nw / oratio;
		} else {
			nh = oh * scale;
			nw = nh * oratio;
		}
		if (isNaN(nw)) {
			return false;
		}
		if (isNaN(nh)) {
			return false;
		}
		nw = Math.round(nw);
		nh = Math.round(nh);
		if (!target.hasClass("translate")) {
			target.css({
				"left": (pw - nw) + "px",
				"top": (ph - nh) + "px"
			});
		}
		if (target.hasClass("transform")) {
			target.css('-webkit-transform', 'scaleX(' + nw / ow + ') scaleY(' + nh / oh + ')');
		} else {
			target.css({
				width: nw,
				height: nh
			});
		}
		return true;
	};
	Utils.extend = function(subclass, superclass) {
		var f;
		f = function() {};
		f.prototype = superclass.prototype;
		subclass.prototype = new f();
		subclass.prototype.constructor = subclass;
		subclass.superclass = superclass.prototype;
		if (superclass.prototype.constructor === Object.prototype.constructor) {
			return superclass.prototype.constructor = superclass;
		}
	};
	Utils.isIE = function(mobileOnly) {
		if (mobileOnly) {
			return navigator.userAgent.match(/IEMobile/i);
		}
		return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/msie/i);
	};
	Utils.getQueryParameters = function(sVar, urlStr) {
		var ft, i, j, ref, ret, sv;
		urlStr = urlStr || window.location.search.substring(1);
		if (!urlStr || !urlStr.length) {
			if (sVar) {
				return null;
			} else {
				return {};
			}
		}
		sv = urlStr.split("&");
		if (!sVar) {
			ret = {};
		}
		for (i = j = 0, ref = sv.length; j < ref; i = j += 1) {
			ft = sv[i].split("=");
			if (sVar) {
				if (ft[0] === sVar) {
					return ft[1];
				}
			} else {
				ret[ft[0]] = ft[1];
			}
		}
		if (sVar) {
			return null;
		}
		return ret;
	};

	/*
	 urlStr = window.location.search.substring(1);
	 sv = urlStr.split("&");
	 if(!sVar) ret = {};
	 for (i=0;i< sv.length;i++) {
	 ft = sv[i].split("=");
	 if (sVar && ft[0] == sVar) return ft[1];
	 else {
	 ret[ft[0]]	 = ft[1];
	 }
	 }
	 return ret;
	 */
	Utils.formatNumber = function(num, groupString) {
		return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, groupString);
	};
	Utils.tinyImageSrc = "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";
	Utils.isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows() || false;
		}
	};
	Utils.isRetina = function() {
		var mediaQuery;
		mediaQuery = "(-webkit-min-device-pixel-ratio: 1.5),(min--moz-device-pixel-ratio: 1.5),(-o-min-device-pixel-ratio: 3/2),(min-resolution: 1.5dppx)";
		if (window.devicePixelRatio > 1) {
			return true;
		}
		if (window.matchMedia && window.matchMedia(mediaQuery).matches) {
			return true;
		}
		return false;
	};
	Utils.resolveSelector = function(selector, element) {
		var container, first;
		if (!selector || selector === "") {
			return false;
		}
		if (selector.jquery) {
			return selector;
		}
		container = selector.split("closest:");
		if (container.length > 1) {
			container = container[1].split(" ");
			first = container.shift();
			if (container.length === 0) {
				selector = false;
			} else {
				selector = container.join(" ");
			}
			container = element.closest(first);
		} else {
			container = element;
		}
		selector = selector ? $(selector, container) : container;
		if (selector.length) {
			return selector;
		} else {
			return false;
		}
	};
	Utils.createEventHandler = function() {

		/*
		 creates an object for handling simple events

		 UNIT TESTS:

		 Utils = require('cs!helper/utils');
		 eh = Utils.createEventHandler();
		 var log = {};
		 log.eCount = 0;
		 log.dCount = 0;
		 log.xCount = 0;
		 log.yCount = 0;
		 log.allCount = 0;
		 log.allNamespacedCount = 0;

		 var l = {};
		 l.fe = function(args){console.log("event fe type:"+args.type);log.eCount++;};
		 l.fe2 = function(args){console.log("event fe2 type:"+args.type);log.eCount++;};
		 l.fd = function(args){console.log("event fd type:"+args.type);log.dCount++;};
		 l.fd2 = function(args){console.log("event fd2 type:"+args.type);log.dCount++;};

		 function all(args){
		 console.log("event all type:"+args.type);
		 log.allCount++;
		 }
		 function allNamespaced(args){
		 console.log("event allNamespaced type:"+args.type);
		 log.allNamespacedCount++;
		 }

		 eh.addListener("all",all); //0
		 eh.addListener("all.fd",allNamespaced); //0
		 eh.addListener("all.fe",allNamespaced); //0
		 eh.addListener("e",l.fe,l); //1
		 eh.addListener("d",l.fd,l); //2
		 eh.addListener("d",l.fd2,l); //3
		 eh.addListener("e.fd",l.fd,l); //4
		 eh.addListener("d.fe",l.fe,l); //5

		 console.log("t e");
		 eh.trigger("e");
		 //triggers e: 1
		 if(log.eCount != 1) console.error("ERROR eCount 1 !="+log.eCount)
		 if(log.allCount != 1) console.error("ERROR allCount 1 !="+log.allCount)
		 eh.addListener("e",l.fe2,l); //6

		 list = eh.getListeners("e");
		 // 0,1,6
		 if(list.length != 3) console.error("ERROR eh.getListeners(e).length! 3 !="+list.length);

		 list = eh.getListeners("d");
		 // 0,2,3
		 if(list.length != 3) console.error("ERROR eh.getListeners(d).length! 3 !="+list.length);

		 list = eh.getListeners("*.fe");
		 // 0,5
		 if(list.length != 2) console.error("ERROR eh.getListeners(*.fe).length! 2 !="+list.length);

		 console.log("t e2");
		 eh.trigger("e")
		 //triggers e: 1,6
		 if(log.eCount != 3) console.error("ERROR eCount 3 !="+log.eCount)

		 console.log("t e.fd");
		 eh.trigger("e.fd")
		 //triggers e:1,6,d:4
		 if(log.dCount != 1) console.error("ERROR dCount 1 !="+log.dCount)
		 if(log.eCount != 5) console.error("ERROR eCount 5 !="+log.eCount)
		 if(log.allNamespacedCount != 1) console.error("ERROR allNamespacedCount 1 !="+log.allNamespacedCount)

		 console.log("t d.fe");
		 eh.trigger("d.fe")
		 //triggers e:4,d:2,3
		 if(log.eCount != 6) console.error("ERROR eCount 6 !="+log.eCount)
		 if(log.dCount != 3) console.error("ERROR dCount 3 !="+log.dCount)
		 if(log.allNamespacedCount != 2) console.error("ERROR allNamespacedCount 2 !="+log.allNamespacedCount)


		 console.log("t d");
		 eh.trigger("d")
		 //triggers d:2,3
		 if(log.dCount != 5) console.error("ERROR dCount 5 !="+log.dCount)
		 if(log.eCount != 6) console.error("ERROR eCount 6 !="+log.eCount)

		 if(log.allCount != 5) console.error("ERROR allCount 5 !="+log.allCount)

		 var q = {};
		 q.fx = function(args){console.log("event fx type:"+args.type);log.xCount++;};
		 q.fx2 = function(args){console.log("event fx2 type:"+args.type);log.xCount++;};
		 q.fy = function(args){console.log("event fy type:"+args.type);log.yCount++;};
		 q.fy2 = function(args){console.log("event fy2 type:"+args.type);log.yCount++;};

		 eh.addListener("x",q.fx,q); //7
		 eh.addListener("x",q.fx2,q); //8
		 eh.addListener("x.y",q.fy,q); //9
		 eh.addListener("y",q.fy,q); //10
		 eh.addListener("y",q.fy2,q); //11
		 eh.addListener("y.x",q.fx,q); //12

		 eh.addListener("all.y",allNamespaced); //13
		 eh.addListener("all.x",allNamespaced); //14

		 list = eh.getListeners("x");
		 // 0,7,8
		 if(list.length != 3) console.error("ERROR eh.getListeners(x).length! 3 !="+list.length);

		 list = eh.getListeners("*.x");
		 // 12,14
		 if(list.length != 2) console.error("ERROR eh.getListeners(*.x).length! 2 !="+list.length);

		 console.log("t x");
		 eh.trigger("x")
		 //triggers x:7,8
		 if(log.xCount != 2) console.error("ERROR xCount 2 !="+log.xCount)

		 console.log("t y");
		 eh.trigger("y")
		 //triggers y:10,11
		 if(log.yCount != 2) console.error("ERROR yCount 2 !="+log.yCount)

		 console.log("t x.y");
		 eh.trigger("x.y")
		 //triggers x:7,8, y:9
		 if(log.xCount != 4) console.error("ERROR xCount 4 !="+log.xCount)
		 if(log.yCount != 3) console.error("ERROR yCount 3 !="+log.yCount)

		 console.log("t y.x");
		 eh.trigger("y.x")
		 //triggers all, all.x x:14, y.x:12, y:10,11
		 if(log.xCount != 5) console.error("ERROR xCount 5 !="+log.xCount)
		 if(log.yCount != 5) console.error("ERROR yCount 5 !="+log.yCount)

		 if(log.allNamespacedCount != 4) console.error("ERROR allNamespacedCount 4 !="+log.allNamespacedCount)

		 if(log.allCount != 9) console.error("ERROR allCount 9 !="+log.allCount);

		 count = eh.removeListener(false,"e",false,"all");
		 // 1,6
		 if(count != 2) console.error("ERROR eh.removeListener(false,e,false,all), count 2 !="+count);

		 count = eh.removeListener(false,"y.x",[0]);
		 // 12,10,11,14
		 if(count != 4) console.error("ERROR eh.removeListener(false,y.x,0), count 4 !="+count);

		 console.log("t e");
		 eh.trigger("e")
		 //triggers x:all
		 if(log.allCount != 10) console.error("ERROR allCount 10 !="+log.allCount)
		 // not triggered
		 if(log.eCount != 6) console.error("ERROR eCount 6 !="+log.eCount)

		 count = eh.removeListener(false,"y");
		 // all
		 if(count != 1) console.error("ERROR eh.removeListener(false,y.x,0), count 1 !="+count);
		 */
		var handler;
		handler = {
			listeners: [],
			listenedEvents: {},
			namespacedEvents: {},
			idCounter: 1,

			/*

			 Adds a listener. If "events" is "all", listener listens to all events (in namespace). Namespacing style: event.namespace.
			 One listener can only listen to one event in one namespace

			 @param [string] a space delimited list of events to listen to.
			 @param [function] function to call when event is triggered
			 @param [obj] scope in which the function is called

			 @returns [object] listener object if parameters are valid
			 */
			addListener: function(events, func, scope, data) {
				var listenerObj;
				if (!events || !func || (typeof func !== 'function')) {
					return false;
				}
				listenerObj = {
					id: this.idCounter++,
					func: func,
					scope: scope || {},
					data: data || false,
					events: [],
					eventStr: false,
					namespaces: [],
					handler: this,
					remove: function() {
						return this.handler.removeListener(this);
					}
				};
				if (!events || (typeof events !== 'string')) {
					events = "all";
				}
				listenerObj.eventStr = events;

				/*
				 " " are added so indexOf(" "+<eventname>+" ") matches exact event
				 for example " active " does not match " inactive "
				 */
				return this.register(listenerObj);
			},

			/*

			 Same as addListener, but params in an object

			 @param [objet] object with params events, func, scope, data

			 @returns [object] listener object if parameters are valid Object
			 */
			addListenerObject: function(listener) {
				if (!listener || !listener.events || !listener.func) {
					return false;
				}
				return this.addListener(listener.events, listener.func, listener.scope, listener.data);
			},

			/*
			 internal
			 adds object to
			 listenedEvents: 2D array holding eventlisteners by type
			 namespacedEvents: 2D array holding eventlisteners by namespace
			 listeners: an array holding all eventlisteners
			 */
			register: function(listenerObj) {
				var event, eventList, j, len;
				eventList = listenerObj.eventStr.split(" ");
				for (j = 0, len = eventList.length; j < len; j++) {
					event = eventList[j];
					event = this.parseEvent(event);
					if (!event) {
						return false;
					}
					listenerObj.events[event.type] = event;
					if (event.namespace) {
						if (!listenerObj.namespaces[event.namespace]) {
							listenerObj.namespaces[event.namespace] = [];
						}
						listenerObj.namespaces[event.namespace] = event.type;
						if (!this.namespacedEvents[event.namespace]) {
							this.namespacedEvents[event.namespace] = [];
						}
						this.namespacedEvents[event.namespace].push(listenerObj);
					}
					if (!this.listenedEvents[event.type]) {
						this.listenedEvents[event.type] = [];
					}
					this.listenedEvents[event.type].push(listenerObj);
				}
				this.listeners.push(listenerObj);
				return listenerObj;
			},

			/*
			 parses an event to an object. Valid format: eventType.eventNamespace

			 @returns [object] type:type of event, namespace: namespace of the event if any
			 */
			parseEvent: function(event, data) {
				var e, namespace;
				namespace = false;
				if (event && (typeof event === 'object') && event.type) {
					return event;
				}
				if (event && event.indexOf('.') > -1) {
					e = event.split('.');
					event = e[0];
					namespace = e[1];
				}
				if (!event || event === "") {
					return false;
				}
				if (namespace === "") {
					namespace = false;
				}
				return {
					type: event,
					namespace: namespace,
					data: data
				};
			},

			/*
			 finds eventlisteners for an event
			 if namespace is set, wildcard character (*) may be used as an event type to get all events in a namespace. Example: *.dropdown returns all listeners in "dropdown" namespace
			 */
			getListeners: function(event) {
				var ids, j, l, len, len1, len2, listener, n, ref, ref1, ref2, ret;
				ret = [];
				ids = [];
				event = this.parseEvent(event);
				if (this.listenedEvents["all"]) {
					ref = this.listenedEvents["all"];
					for (j = 0, len = ref.length; j < len; j++) {
						listener = ref[j];
						if (this.isListening(listener, event, true) && !ids[listener.id]) {
							ret.push(listener);
							ids[listener.id] = true;
						}
					}
				}
				if (event.namespace && event.type === '*') {
					if (!this.namespacedEvents[event.namespace]) {
						return ret;
					}
					return this.namespacedEvents[event.namespace];
				} else if (event.namespace) {
					if (!this.namespacedEvents[event.namespace]) {
						return ret;
					}
					ref1 = this.namespacedEvents[event.namespace];
					for (l = 0, len1 = ref1.length; l < len1; l++) {
						listener = ref1[l];
						if (this.isListening(listener, event, true) && !ids[listener.id]) {
							ret.push(listener);
							ids[listener.id] = true;
						}
					}
				}
				if (event.type !== '*') {
					if (!this.listenedEvents[event.type]) {
						return ret;
					}
					ref2 = this.listenedEvents[event.type];
					for (n = 0, len2 = ref2.length; n < len2; n++) {
						listener = ref2[n];
						if (this.isListening(listener, event) && !ids[listener.id]) {
							ret.push(listener);
							ids[listener.id] = true;
						}
					}
				}
				return ret;
			},

			/*
			 @param [object] optional listenerObject to remove
			 @param [string] optional events to remove
			 @param [array] optional array of listenerObject ids NOT to remove
			 @param [array] optional array of events whose listeners NOT to remove

			 @returns
			 */
			removeListener: function(obj, event, ignoredIds, ignoredEvents) {
				var count, ignore, j, l, len, len1, listener, listeners;
				if (!obj && !event) {
					return 0;
				}
				if (event) {
					listeners = this.getListeners(event);
				} else {
					listeners = [obj];
				}
				if (ignoredIds && ignoredIds.length) {
					ignoredIds = "," + ignoredIds.join(",") + ",";
				} else {
					ignoredIds = false;
				}
				count = 0;
				for (j = 0, len = listeners.length; j < len; j++) {
					listener = listeners[j];
					if (ignoredIds && ignoredIds.indexOf("," + listener.id + ",") > -1) {
						continue;
					}
					ignore = false;
					if (ignoredEvents && ignoredEvents.length) {
						for (l = 0, len1 = ignoredEvents.length; l < len1; l++) {
							event = ignoredEvents[l];
							if (this.isListening(listener, ignoredEvents)) {
								ignore = true;
								break;
							}
						}
					}
					if (ignore) {
						continue;
					}
					this.destroyObj(listener, true);
					count++;
				}
				return count;
			},

			/*
			 check if a listener is listening to an event
			 if the event has a namespace but the listener does not, event is triggered
			 if the listener has a namespace but the event does not, event is not triggered
			 */
			isListening: function(listener, event) {
				var allEvents, listenerEvent;
				event = this.parseEvent(event);
				if (!listener || !event || !event.type || !listener) {
					return false;
				}
				allEvents = listener.events["all"];
				listenerEvent = listener.events[event.type];
				if (allEvents && (!allEvents.namespace || allEvents.namespace === event.namespace)) {
					return true;
				}
				listenerEvent = listener.events[event.type];
				if (!listenerEvent) {
					return false;
				}
				if (!listenerEvent.namespace) {
					return true;
				}
				if (event.namespace === listenerEvent.namespace) {
					return true;
				}
				return false;
			},
			trigger: function(event, data, listener) {
				var j, len, listeners;
				data = data || false;
				event = this.parseEvent(event, data);
				if (listener) {
					listeners = [listener];
				} else {
					listeners = this.getListeners(event);
				}
				for (j = 0, len = listeners.length; j < len; j++) {
					listener = listeners[j];
					this.triggerObj(listener, [event, listener.data]);
				}
			},
			triggerObj: function(listenerObj, args) {
				if (!listenerObj || !listenerObj.func) {
					return false;
				}
				args = args || [];
				listenerObj.func.apply(listenerObj.scope, args);
				return true;
			},
			destroyObj: function(listenerObj, remove) {
				var event, eventType, i, j, l, len, len1, len2, listener, n, ref, ref1, ref2;
				if (remove) {
					for (eventType in listenerObj.events) {
						event = listenerObj.events[eventType];
						if (event.namespace) {
							i = 0;
							ref = this.namespacedEvents[event.namespace];
							for (j = 0, len = ref.length; j < len; j++) {
								listener = ref[j];
								if (listener.id === listenerObj.id) {
									this.namespacedEvents[event.namespace].splice(i, 1);
									break;
								}
								i++;
							}
						}
						i = 0;
						ref1 = this.listenedEvents[event.type];
						for (l = 0, len1 = ref1.length; l < len1; l++) {
							listener = ref1[l];
							if (listener.id === listenerObj.id) {
								this.listenedEvents[event.type].splice(i, 1);
								break;
							}
							i++;
						}
						i = 0;
						ref2 = this.listeners;
						for (n = 0, len2 = ref2.length; n < len2; n++) {
							listener = ref2[n];
							if (listener.id === listenerObj.id) {
								this.listeners.splice(i, 1);
								break;
							}
							i++;
						}
					}
				}
				listenerObj.id = null;
				listenerObj.func = null;
				listenerObj.scope = null;
				listenerObj.data = null;
				listenerObj.events = null;
				listenerObj.namespaces = null;
				listenerObj.eventStr = null;
				return listenerObj.remove = null;
			},
			destroy: function() {
				var j, len, listener, ref;
				ref = this.listeners;
				for (j = 0, len = ref.length; j < len; j++) {
					listener = ref[j];
					this.destroyObj(listener);
				}
				this.listeners = null;
				this.listenedEvents = null;
				this.namespacedEvents = null;
				this.namespacedEvents = null;
				return this.idCounter = null;
			}
		};
		return handler;
	};
	return Utils;

	/*

	 isMobile={Android:()->return navigator.userAgent.match(/Android/i)},BlackBerry:()->return navigator.userAgent.match(/BlackBerry/i)},iOS:()->return navigator.userAgent.match(/iPhone|iPad|iPod/i)},Opera:()->return navigator.userAgent.match(/Opera Mini/i)},Windows:()->return navigator.userAgent.match(/IEMobile/i)},any:()->return(isMobile.Android()||isMobile.BlackBerry()||isMobile.iOS()||isMobile.Opera()||isMobile.Windows())}};
	 */
});