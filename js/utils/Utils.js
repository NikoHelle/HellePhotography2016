(function(){
	
	
	
	var Utils = {
		/*
		
		Modernizr.addTest('fileinput', function() {
					var elem = document.createElement('input');
					elem.type = 'file';
					return !elem.disabled;
				});
				
		*/		
		
		/*
		
		function supportAjaxUploadWithProgress() {
 2   return supportFileAPI() && supportAjaxUploadProgressEvents() && supportFormData();
 3 
 4   function supportFileAPI() {
 5     var fi = document.createElement('INPUT');
 6     fi.type = 'file';
 7     return 'files' in fi;
 8   };
 9 
10   function supportAjaxUploadProgressEvents() {
11     var xhr = new XMLHttpRequest();
12     return !! (xhr && ('upload' in xhr) && ('onprogress' in xhr.upload));
13   };
14 
15   function supportFormData() {
16     return !! window.FormData;
17   }
18 }

*/
			
	}
	
	Utils.getVendorPrefix = function(){
	
	
		var prop = false;	
		var root=document.documentElement //reference root element of document
		
		if ("transform" in root.style) return "";
		if ("MozTransform" in root.style) return "-moz-";
		if ("WebkitTransform" in root.style) return "-webkit-";
		if ("msTransform" in root.style) return "-ms-";
		if ("OTransform" in root.style) return "-o-";
		
		return "";
		
	}
	
	Utils.isInFrame = function(){
		return ( window.self !== window.top ) ;
	}
	
	Utils.hexToRGB = function(hex) {
		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function(m, r, g, b) {
			return r + r + g + g + b + b;
		});
	
		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}
	
	Utils.RGBToHex = function(r, g, b) {
		return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
	}
	
	
	Utils.validateEmail = function(email){
			
    	var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    	return re.test(email);

	}
	
	Utils.getScrollY = function(){
		var y = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;	
		return y;
	}
	
	Utils.getScrollX = function(){
		var x = (window.pageXOffset !== undefined) ? window.pageXOffset : (document.documentElement || document.body.parentNode || document.body).scrollLeft;
		return x;
	}
	
	Utils.createConsole = function(){
		if(window.console) return;
		window.console = {};
		window.console.log = function(text){
			
		}
		
		
		
	}
	
	Utils.isTouchDevice = function(){
		//for wp8+ie10
		//required modernizer etc.
		if(window.navigator && window.navigator.msMaxTouchPoints) return true;
		return $("html").hasClass("touch");
	
	}
	
	Utils.isIOS = function(){
		return /(iPad|iPhone|iPod)/ig.test( navigator.userAgent );	
	}
	
	Utils.isWP = function(){
		return /Windows Phone/ig.test( navigator.userAgent );	
	}
	
	Utils.isMac = function(){
		return /Macintosh/ig.test( navigator.userAgent );	
	}
	
	Utils.isAndroid = function(){
		return /Android/ig.test( navigator.userAgent );	
	}
	
	Utils.isWindows = function(){
		return /Windows/ig.test( navigator.userAgent );	
	}
	
	
	
	/*
	
	var is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
	*/
	
	Utils.getBrowser = function(){
		var ua= navigator.userAgent, tem, 
		M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*([\d\.]+)/i) || [];
		if(/trident/i.test(M[1])){
			tem=  /\brv[ :]+(\d+(\.\d+)?)/g.exec(ua) || [];
			return 'IE '+(tem[1] || '');
		}
		M= M[2]? [M[1], M[2]]:[navigator.appName, navigator.appVersion, '-?'];
		if((tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
		
		browser = String(M[0]).toLowerCase();
		var v = M[1].split(".");
		var version = parseInt(v[0],10)
		var minorVersion = parseInt(v[1],10);
		
		//if(testedBrowser === M[0] 
		
		var os = "";
		if(Utils.isIOS()) os = "ios";
		else if(Utils.isWP()) os = "wp";
		else if(Utils.isMac()) os = "mac";
		else if(Utils.isAndroid()) os = "android";
		else if(Utils.isWindows()) os = "windows";
		
		return {browser:browser,version:version,minorVersion:minorVersion,fullVersion:M[1],os:os};// M.join(' ');
	}
	
	Utils.isFullHeight = function(){
		//returns true, if not supported
		if(!document.documentElement || !document.documentElement.clientHeight) return true;
		if(!window.innerHeight) return true;
		
		
		return parseInt(window.innerHeight,10) <= parseInt(document.documentElement.clientHeight,10);

		

	}
	
	Utils.getBounds = function(target){
		
		var offset,size,bounds;
		bounds = {top:false,right:false,bottom:false,left:false,width:false,height:false};	
		$("> *",target).each(function(){
			if(!$(this).is(':visible')) {return;} 
			offset = $(this).position(); //!!! note doc: parent element MUST have position:relative/absolute.
			
			if(bounds.left===false || offset.left < bounds.left) {bounds.left = offset.left;}
			if(bounds.top===false || offset.top < bounds.top) {bounds.top = offset.top;}
			
			size = $(this).outerWidth(true);
			if(bounds.right===false || (offset.left + size) > bounds.right) {bounds.right = (offset.left + size);}
			//console.log("offset.left:"+offset.left+",offset.right:"+(offset.left + size))
			
			size = $(this).outerHeight(true);
			if(bounds.bottom===false || (offset.top + size) > bounds.bottom){ bounds.bottom = (offset.top + size);}
			//console.log("offset.top:"+offset.top+",offset.bottom:"+(offset.top + size))
			
		});	

		bounds.width = bounds.right-bounds.left;
		bounds.height = bounds.bottom-bounds.top;
		
		return bounds;
				
	}
	
	Utils.getContentHeight = function(target){
		
		//not tested			
		var h = 0;
		var pos,el,oh;
		var outlineHeight = this.target.outerHeight(true)-this.target.height();
		
		this.target.children().each(function(){
			el = $(this);
			pos = el.position();
			//console.log("pos:"+pos.top);
			oh = el.outerHeight(true);
			//console.log("oh:"+oh);
			if((oh+pos.top) > h) h = oh+pos.top;
			//console.log("h:"+h);
		});
		//console.log("outlineHeight:"+outlineHeight);
		return this.target.get(0).scrollTop+h+outlineHeight;
				
	}
	
	Utils.fillParent = function(target){
		if(!target || !target.parent || !target.length) return false;
		var parent = target.parent();
		var pw = parent.width();
		var ph = parent.height();
		var pr = pw/ph;
		if(target.data("ow")){
			var ow = parseInt(target.data("ow"),10);
			var oh = parseInt(target.data("oh"),10);
		}
		else if(target.attr("width")) {
			var ow = parseInt(target.attr("width"),10);
			var oh = parseInt(target.attr("height"),10);
		}
		else {
			var ow = parseInt(target.width(),10);
			var oh = parseInt(target.height(),10);
		}
		var or = ow/oh;
		if(isNaN(or)) return false;
		scale = Math.max(pw/ow,ph/oh);
	
		
		var nw,nh;
		if(pw/ow > ph/oh){
			nw = ow*scale;
			nh = nw/or;
			//console.log("pw/ow:"+pw/ow+", ph/oh:"+ph/oh);
		}
		else {
			nh = oh*scale;
			nw = nh*or;	
			//console.log("x");
		}
		
		if(isNaN(nw)) return false;
		if(isNaN(nh)) return false;
		
		nw = Math.round(nw);
		nh = Math.round(nh);
		//console.log(ow+"-->"+nw+", "+oh+" -->"+nh);
		//return;
		if(!target.hasClass("translate")){
			target.css({"left":(pw-nw)+"px","top":(ph-nh)+"px"})
		}
		if(target.hasClass("transform")){
			target.css('-webkit-transform','scaleX(' + nw/ow  + ') scaleY(' + nh/oh  + ')')
		}
		else target.css({width:nw,height:nh});
		
		return true;
	}
	
	Utils.extend = function(subclass, superclass) {
		var f = function() {};
		f.prototype = superclass.prototype;
		subclass.prototype = new f();
		subclass.prototype.constructor = subclass;
		subclass.superclass = superclass.prototype;
		if (superclass.prototype.constructor == Object.prototype.constructor) {
			superclass.prototype.constructor = superclass;
		}
	};
	
	Utils.isIE = function(mobileOnly){
		if(mobileOnly) return navigator.userAgent.match(/IEMobile/i);
		return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/msie/i);
		
	}
	
	Utils.getQueryParameters = function(sVar) {

        urlStr = window.location.search.substring(1);
        sv = urlStr.split("&");
        if(!sVar) ret = {};
        for (i=0;i< sv.length;i++) {
            ft = sv[i].split("=");
            if (sVar) {
                if(ft[0] == sVar) return ft[1];
            }
            else {
                ret[ft[0]]	 = ft[1];
            }
        }
        if(sVar) return null;
        return ret;

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
		return ret;*/
	}
	
	Utils.formatNumber = function(num,groupString) {
		return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, groupString);
	}
	
	Utils.tinyImageSrc = "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";
	
	if(!window.namespace){
		if(!window.nhe){
			window.nhe = {}
		}
		
		
		window.namespace = window.nhe;
	}
	
	if(!window.namespace.utils){
			window.namespace.utils = {}
	}
	
	window.namespace.utils.Utils = Utils;
	
	
	
})(false);
;

/*

var isMobile={Android:function(){return navigator.userAgent.match(/Android/i)},BlackBerry:function(){return navigator.userAgent.match(/BlackBerry/i)},iOS:function(){return navigator.userAgent.match(/iPhone|iPad|iPod/i)},Opera:function(){return navigator.userAgent.match(/Opera Mini/i)},Windows:function(){return navigator.userAgent.match(/IEMobile/i)},any:function(){return(isMobile.Android()||isMobile.BlackBerry()||isMobile.iOS()||isMobile.Opera()||isMobile.Windows())}};
*/