define("GoogleTracker",["jquery","underscore","events","beacon","Utils","AppData"],
    function($,_,events,beacon,Utils,appData){
        var GoogleTracker = function(options){

            this.documentHeight = 0;
            this.maxScrollPerc = 0;
            this.container = $("html");
            //hh
            events.addListener(beacon.THROTTLED_SCROLL_EVENT,this.onScroll,this);
            events.addListener(beacon.RESIZE_EVENT,this.onResize,this);
            this.onResize();
            this.onScroll();
            $("a[href*=#]").click(_.bind(this.onClick,this));

        }

        GoogleTracker.prototype.onClick = function(e) {
            e.preventDefault();
            //window._e = e;
            target = $(e.currentTarget);
            if (!target.length) return;

            target = target.attr("href");
            if (!target) return;

            target = target.replace(/#/g,"");

            this.send("click","inner-click",target);
        }

        GoogleTracker.prototype.onScroll = function(e,data) {
            if(appData.scrollAnimationActive){
                return false;
            }
            var topPos = beacon.scrollY;
            var topBottom = topPos + beacon.windowHeight;
            var perc = topBottom/this.documentHeight;
            var perc10 = Math.round(perc*10)*10;
            if(perc10 <= this.maxScrollPerc) return;
            this.maxScrollPerc = perc10;
            this.send("scroll event","position-"+perc10,"scroll-down",Math.round(perc*1000));
        }

        GoogleTracker.prototype.onResize = function(e,data){
            this.documentHeight = this.container.height();

        }

        GoogleTracker.prototype.send = function(category,action,label,value){
            if(!ga || !category || !action) return;

            ga('send', 'event', category, action, label,value);

        }


        return GoogleTracker;
    }
);