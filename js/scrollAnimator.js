define("ScrollAnimator",["jquery","underscore","events","beacon","Utils","AppData"],
    function($,_,events,beacon,Utils,appData){
        var ScrollAnimator = function(options){

            this.speed = 10; //10 px per frame (16ms)
            this.target = 0;
            this.startTime = 0;
            this.duration= 0;
            this.distance= 0;
            this.startY = 0;
            this.targetY = 0;
            this.y= 0;

            appData.scrollAnimationActive = false

            $("a[href*=#]").click(_.bind(this.onClick,this));

            events.addListener(beacon.FRAME_EVENT,this.onFrame,this)

        }

        ScrollAnimator.prototype.onClick = function(e){
            e.preventDefault();
            //window._e = e
            target = $(e.currentTarget)
            if(!target.length) return

            target = target.attr("href");
            if(!target) return

            target = $(target)
            if(!target.length) return

            this.start(target)

        }

        ScrollAnimator.prototype.onFrame = function(e,data){
            if(!this.target || !this.duration || !e ||!e.data) return;
            var dt = e.data.time - this.startTime
            //var perc = Math.min(1,dt/this.duration)
            var perc = this.easing(dt,0,1,this.duration);
            this.y = this.startY+this.distance*perc
            window.scrollTo(0,this.y);
            //console.log("this.y:"+this.y+", perc:"+perc+",dt:"+dt+",duration:"+this.duration)
            if(perc >= 1)this.stop();
        }
        ScrollAnimator.prototype.start = function(target){
            this.stop();
            if(!target) return false;
            if(!target.jquery) target = $(target);
            if(!target.length) return false

            y = target.offset().top;

            var topPos = beacon.scrollY;
            var topBottom = topPos + beacon.windowHeight;

            if(y >= topPos && y<=topBottom) return false

            this.startY = topPos
            this.targetY = y-30

            if(y> this.startY){
                this.targetY += 30
            }

            this.targetY = Math.max(0,this.targetY)

            this.distance = this.targetY - this.startY
            this.startTime = Date.now()
            this.duration = Math.abs(this.distance)/this.speed
            this.duration = Math.min(1000,this.duration)
            this.target =  target

            //window._sc = this


            /*
            console.log('this.distance:'+this.distance);
            console.log('this.startTime:'+this.startTime);
            console.log('this.duration:'+this.duration);
            console.log('this.startY:'+this.startY);
            console.log('this.targetY:'+this.targetY);
            console.log('y:'+y);
            */

            appData.scrollAnimationActive = true

            return true

        }

        ScrollAnimator.prototype.easing = function(t, b, c, d){
            //return c * t / d + b;
            return 1 - Math.pow(1 - (t / d), 5);
            if((t /= d / 2) < 1){
                return c / 2 * t * t * t * t * t + b;
            }
            return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
            //t is the current time (or position) of the tween.
            // This can be seconds or frames, steps, seconds, ms, whatever –
            // as long as the unit is the same as is used for the total time [3].
            //b is the beginning value of the property.
            //c is the change between the beginning and destination value of the property.
            //d is the total time of the tween.
        }
        ScrollAnimator.prototype.stop = function(){
            this.distance = 0
            this.startTime = 0
            this.target =  0

            appData.scrollAnimationActive = false
        }

        return ScrollAnimator;
    }
);