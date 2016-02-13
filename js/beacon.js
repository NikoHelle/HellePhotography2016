define("beacon",["jquery","events","underscore","Utils"],
    function($,events,_,Utils){
        var Beacon = function(options){

            this.lastScrollEvent = false;
            this.lastSentScrollY= -1;
            this.lastFrameEvent= 0;
            this.scrollY = 0;
            this.windowWidth = 0;
            this.windowHeight= 0;

            this.window = $(window);


            var defaults = {};

            this.options = $.extend({},defaults,options);

            this.scrollThrottle = this.options.scrollThrottle || 150;
            this.scrollY = Utils.getScrollY();
            this.onResize();
            this.startOnResizeBeacon();

            this.rafCall = _.bind(this.onAnimationFrame,this);
            this.rafId = requestAnimationFrame(this.rafCall);

        }

        Beacon.prototype.startOnResizeBeacon = function(){

            this.window.on("resize", _.bind(this.onResize,this));

        }

        Beacon.prototype.onResize = function(){
            var ww = this.window.width();
            var wh = this.window.height();
            if(ww == this.windowWidth &&  wh == this.windowHeight) return;
            this.windowWidth = ww;
            this.windowHeight = wh;
            events.trigger(this.RESIZE_EVENT,{width:this.windowWidth,height:this.windowHeight})
        }

        Beacon.prototype.onAnimationFrame = function(){

            var sy = Utils.getScrollY();
            var dn = Date.now();

            if(sy != this.scrollY){
                this.lastScrollEvent = Date.now();
                //console.log("scroll y:"+sy);
                events.trigger(this.SCROLL_EVENT,{y:this.scrollY})
            }

            if(this.lastScrollEvent && this.scrollThrottle>0) {
                var std = dn - this.lastScrollEvent;
                if (std > this.scrollThrottle && this.lastSentScrollY != this.scrollY) {
                    events.trigger(this.THROTTLED_SCROLL_EVENT, {y: this.scrollY});
                    this.lastScrollEvent = false;
                    this.lastSentScrollY = this.scrollY
                }
            }
            this.scrollY = sy;

            this.ft = this.lastFrameEvent ? dn-this.lastFrameEvent : 0;

            events.trigger(this.FRAME_EVENT,{y:this.scrollY,frameTime:this.ft,time:dn});
            //console.log("ft:"+this.ft);
            this.lastFrameEvent = dn;

            this.rafId = requestAnimationFrame(this.rafCall);
        }

        Beacon.prototype.SCROLL_EVENT = "SCROLL_EVENT";
        Beacon.prototype.THROTTLED_SCROLL_EVENT = "THROTTLED_SCROLL_EVENT";
        Beacon.prototype.RESIZE_EVENT = "RESIZE_EVENT";
        Beacon.prototype.FRAME_EVENT = "FRAME_EVENT";



        return new Beacon();

    }
);



