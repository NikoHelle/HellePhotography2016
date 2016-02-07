define("ImageController",["jquery"],
    function($){
        var ImageController = function(images,options){

            if(!images || !images.length) return;
            var defaults = {
                thresholdTop:100,
                thresholdBottom:400,
                interval:150,
                loadClass:"loading",
                base64Image: "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
            }

            this.lastScroll = false;
            this.window = $(window);
            this._id = false;

            this.options = $.extend({},defaults,options);

            if(!window.requestAnimationFrame) {

                var vendors = ['ms', 'moz', 'webkit', 'o'];
                for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
                    window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
                    window.cancelAnimationFrame =
                        window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
                }

                if (!window.requestAnimationFrame) {
                    var lastFrameTime = 0;
                    window.requestAnimationFrame = function(callback, element) {
                        var currTime = new Date().getTime();
                        var timeToCall = Math.max(0, 16 - (currTime - lastFrameTime));
                        var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                            timeToCall);
                        lastFrameTime = currTime + timeToCall;
                        return id;
                    };
                }

                if (!window.cancelAnimationFrame) {
                    window.cancelAnimationFrame = function(id) {
                        clearTimeout(id);
                    };
                }

            }
            var self = this;
            this.images = images;
            this._id = requestAnimationFrame(function(){self.onFrame()});
            this.window.on("scroll",function(){self.onScroll()})
            //???this.window.on("touchmove",function(){self.onScroll()})

        }


        ImageController.prototype.onFrame = function(){

            var self = this;
            var time = Date.now();
            if(!this.lastScroll || (time-this.lastScroll > this.options.interval)) {

                var topPos = this.window.scrollTop();
                var topBottom = topPos + this.window.height();
                topPos -= this.options.thresholdTop;
                topBottom += this.options.thresholdBottom;
                this.lastScroll = false;

                this.images.each(function(){
                    var img = $(this);
                    var top = img.offset().top;
                    var imgEl = $(".lazyload",img);
                    //check if top is inside viewport + threshold
                    if(top>topBottom) {
                        self.unloadImage(imgEl);
                        return;
                    }
                    if(top<=topBottom && top>=topPos){
                        self.loadImage(imgEl);
                        return;
                    }
                    //check if bottom is inside viewport + threshold
                    var bottom = top+img.height();
                    if(bottom<topPos) {
                        self.unloadImage(imgEl);
                        return;
                    }
                    if(bottom<=topBottom && bottom>=topPos){
                        self.loadImage(imgEl);
                        return;
                    }

                  })
            }
            requestAnimationFrame(function(){self.onFrame()});
        }

        ImageController.prototype.onScroll = function(){
            this.lastScroll = Date.now();

        }


        ImageController.prototype.loadImage = function(img){
            if(this.isImageLoaded(img)) return;
            if(img.data("loading")) return;
            if(img.data("loaded")) return;
            var self = this;
            var src = img.data("img-src");
            //console.log("src:"+src)
            if(!src) return;
            img.on("load",function(){
                self.onLoad(img);
            });
            img.attr("src",src);
            //img.addClass("loading");
            img.closest(".imageContainer").addClass("loading");
            img.data("loading",true)
            img.data("unloaded",false)
        }

        ImageController.prototype.unloadImage = function(img){
           // if(!this.isImageLoaded(img)) return;
            if(!img.data("loading") && !img.data("loaded")) return;
            img.off("load");
            console.log("unloaded:"+img.attr("src"))
            img.attr("src",this.options.base64Image);
            img.closest(".imageContainer").attr("style","");
            //img.removeClass("loading");
            //img.removeClass("loaded");
            img.closest(".imageContainer").removeClass("loading loaded");
            img.data("loaded",false)
            img.data("loading",false)
            img.data("unloaded",true)

        }

        ImageController.prototype.onLoad = function(img){
            //console.log("onLoad:"+img.attr("src"));
            container = img.closest(".imageContainer")
            $(".bg_img",container).attr("style","background-image:url("+img.attr("src")+");");
            img.off("load");
            //img.removeClass("loading");
            //img.addClass("loaded");
            img.closest(".imageContainer").removeClass("loading").addClass("loaded");
            img.data("loaded",true)
            img.data("loading",false)

        }

        ImageController.prototype.isImageLoaded = function(el){

            if(!el || !el.attr || !el.attr("src")) {return true;}
            if(el.attr("src").indexOf(";base64") != -1) return false;
            if(el.data("loaded")) return true
            if(el.data("unloaded") || el.data("loading")) return false
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

        return ImageController;
    }
);