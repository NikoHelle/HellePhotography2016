define("ImageController",["jquery","underscore","events","beacon","Utils","AppData"],
    function($,_,events,beacon,Utils,appData){
        var ImageController = function(images,options){

            if(!images || !images.length) return;
            var defaults = {
                thresholdTop:100,
                thresholdBottom:400,
                loadClass:"loading",
                base64Image: "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
            }

            this.options = $.extend({},defaults,options);
            this.images = images;
            this.isRetina = Utils.isRetina();
            this.imageSize = false;
            this.imageSizeFull = false;

           // window._ic = this;

            events.addListener(beacon.THROTTLED_SCROLL_EVENT,this.onScroll,this);
            events.addListener(beacon.RESIZE_EVENT,this.resolveImageSize,this);
            this.resolveImageSize();
            this.onScroll()

        }

        ImageController.prototype.onScroll = function(e,data){
            var topPos = beacon.scrollY;
            var topBottom = topPos + beacon.windowHeight;
            topPos -= this.options.thresholdTop;
            topBottom += this.options.thresholdBottom;

            var self = this;

            this.images.each(function() {
                var img = $(this);
                var top = img.offset().top;
                var imgEl = $(".lazyload", img);
                if (top > topBottom) {
                    self.unloadImage(imgEl);
                    return;
                }
                if (top <= topBottom && top >= topPos) {
                    self.loadImage(imgEl);
                    return;
                }
                //check if bottom is inside viewport + threshold
                var bottom = top + img.height();
                if (bottom < topPos) {
                    self.unloadImage(imgEl);
                    return;
                }
                if (bottom <= topBottom && bottom >= topPos) {
                    self.loadImage(imgEl);
                    return;
                }
            });
        }

        ImageController.prototype.loadImage = function(img){
            if(this.isImageLoaded(img)) return;
            if(img.data("loading")) return;
            if(img.data("loaded")) return;
            var self = this;
            var src = img.data("img-src");
            //console.log("src:"+src)
            if(!src) return;
            var url =  src.split("_b.jpg");
            var size = this.imageSize;
            var container = img.closest(".imageContainer");
            var _this = this;
            if(container.hasClass("full-width")){
                size = this.imageSizeFull;
            }
            if(url.length>1){
                var suffix = size ? "_"+size : "";
                src = url[0]+suffix+".jpg";
            }
            if(size == "h" || size=="k"){
                src = img.data("img-xl-src") || img.data("img-src");
            }
            img.on("load",function(){
                self.onLoad(img);
            });
            img.on("error",function(){
                appData.googleTracker.send("image","load-error",img.attr("src"));
                _this.unloadImage(img);
            });
            img.attr("src",src);
            //img.addClass("loading");
            img.closest(".imageContainer").addClass("loading");
            img.data("loading",true);
            img.data("unloaded",false)
        }

        ImageController.prototype.unloadImage = function(img){
           // if(!this.isImageLoaded(img)) return;
            if(!img.data("loading") && !img.data("loaded")) return;
            img.off("load");
            img.off("error");
            //console.log("unloaded:"+img.attr("src"))
            img.attr("src",this.options.base64Image);
            img.closest(".imageContainer").attr("style","");
            //img.removeClass("loading");
            //img.removeClass("loaded");
            img.closest(".imageContainer").removeClass("loading loaded");
            img.data("loaded",false);
            img.data("loading",false);
            img.data("unloaded",true)

        }

        ImageController.prototype.onLoad = function(img){
            //console.log("onLoad:"+img.attr("src"));
            var container = img.closest(".imageContainer");
            $(".bg_img",container).attr("style","background-image:url("+img.attr("src")+");");
            img.off("load");
            img.off("error");
            //img.removeClass("loading");
            //img.addClass("loaded");
            img.closest(".imageContainer").removeClass("loading").addClass("loaded");
            img.data("loaded",true);
            img.data("loading",false)

        }

        ImageController.prototype.isImageLoaded = function(el){

            if(!el || !el.attr || !el.attr("src")) {return true;}
            if(el.attr("src").indexOf(";base64") != -1) return false;
            if(el.data("loaded")) return true
            if(el.data("unloaded") || el.data("loading")) return false;
            //if(el.get(0).tagName.toLowerCase() != "img") return true;
            var img = el.get(0);
            // IE
            //log("img.complete:"+img.complete)
            if(img.complete===false) {return false;}
            // Others

            if(typeof img.naturalWidth !== "undefined" && img.naturalWidth === 0) {return false;}

            if(typeof img.readyState !== "undefined" && (img.readyState === "loaded" || img.readyState === "completed")) {return true;}

            return false;

        }

        ImageController.prototype.resolveImageSize = function(contentType, elementSize) {
            var ww = beacon.windowWidth;
            //console.log("ww:"+ww)
            var i,size,len = this.windowSizes.length;
            size = 3;
            for(i=0;i<len;i++){
                if(this.windowSizes[i] > ww && i){
                    //console.log("this.windowSizes[i]:"+this.windowSizes[i])
                    size = i-1;
                    break;
                }
            }

            if(this.isRetina) size = Math.min(size++,len-1);

            this.imageSize = this.imageSizes[size];
            this.imageSizeFull = this.imageSizes[size+2] || this.imageSize;

        }

        ImageController.prototype.imageSizes=  ["t","m","n",false,"z","c","b","h","k"];
        ImageController.prototype.windowSizes= [0,240,320,400,640,800,1200,1400,9999];
        ImageController.prototype.SIZE_SQUARE_SHORT = "s";
        ImageController.prototype.SIZE_LARGE_SQUARE_SHORT= "q";
        ImageController.prototype.SIZE_THUMBNAIL_SHORT = "t";
        ImageController.prototype.SIZE_SMALL_SHORT = "?";
        ImageController.prototype.SIZE_SMALL_240_SHORT = "m";
        ImageController.prototype.SIZE_SMALL_320_SHORT = "n";
        ImageController.prototype.SIZE_MEDIUM_SHORT = false;
        ImageController.prototype.SIZE_MEDIUM_640_SHORT = "z";
        ImageController.prototype.SIZE_MEDIUM_800_SHORT = "c";
        ImageController.prototype.SIZE_LARGE_SHORT = "b";
        ImageController.prototype.SIZE_LARGE_1600_SHORT = "h";
        ImageController.prototype.SIZE_LARGE = "k";
        ImageController.prototype.SIZE_ORIGINAL_SHORT = "o";

        return ImageController;
    }
);