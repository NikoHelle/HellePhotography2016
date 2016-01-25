define("Animator",
    function(){
	
	
            var Animator={
                animations:[],
                len:0,
                eventController:false,
                COUNTER:0,
                _id:false
            };

            Animator.add  = function(animation){
                var aniObj = this.Animation(animation,this);
                if(!aniObj) return false;
                //window.aniObj = aniObj;
                //console.log("aniObj.id:"+aniObj);
                this.animations[aniObj.id] = aniObj;
                this.len++;
                //if(!this._id) this.start();
                return aniObj;
            }

            Animator.Animation = function(params,animator){

                var obj = {};
                obj.animator = animator;
                obj.func = false;
                obj.target = false;
                obj.param = false;
                obj.unit = false;
                obj.destroyable = params.destroyable !== false;
                //console.log("obj.destroyable:"+obj.destroyable);
                obj.active = params.active !== false;
                obj.chains = false; //???

                obj.setStartValues = function(params){
                    this.sVal = this.value = parseFloat(params.start);
                    this.dVal = parseFloat(params.end)-this.sVal;

                    this.sTime = Date.now();
                    this.pTime = 0;
                    this.pDuration = 0;
                    this.delay = params.delay ? parseInt(params.delay) : 0;
                    this.duration = params.duration;
                }

                if(params.target){
                    obj.target = params.target;
                    obj.param = params.param;
                    //if(obj.target instanceof jQuery) obj.target = obj.target.eq(0);
                    obj.func = params.param ? (typeof(params.target[params.param]) === "function") : false;
                    if(obj.func){

                        if(!obj.param) return false;
                        //window.xtarget = obj.target;
                        //window.xparam = obj.param;
                        if(!params.start && params.start !== 0) params.start = obj.target[obj.param].call(obj.target);
                        //if(!params.start && params.start !== 0) return false;
                    }
                    else {
                        if(!params.start && params.start !== 0 && params.param) params.start = obj.target[params.param];
                        else if(!params.start && params.start !== 0 && !params.param) params.start = obj.target;
                        if(!params.start && params.start !== 0) return false;

                    }
                }
                //console.log("params.start :"+params.start);
                if(params.start.toString().indexOf("px") !== -1) obj.unit = "px";
                if(params.start.toString().indexOf("em") !== -1) obj.unit = "em";
                if(params.start.toString().indexOf("%") !== -1) obj.unit = "%";



                if(params.target && !obj.func) obj.value = params.target;

                /*obj.sVal = obj.value = parseFloat(params.start);
                obj.dVal = parseFloat(params.end)-obj.sVal;

                obj.sTime = Date.now();
                obj.pTime = 0;
                obj.pDuration = 0;
                obj.delay = params.delay ? parseInt(params.delay) : 0;
                obj.duration = params.duration;
                */

                obj.setStartValues(params);

                //if(obj.duration) return false;

                obj.frameCallback = params.frameCallback;
                obj.endCallback = params.endCallback;
                obj.scope = params.scope;

                obj.id = animator.COUNTER++;

                obj.fcb = function(){
                    var animation = this;
                    if(!animation.frameCallback) return false;
                    if(typeof(animation.frameCallback) !== "function" && animation.animator.eventController){
                        animation.animator.eventController.trigger(animation.frameCallback,obj);
                    }
                    if(typeof(animation.frameCallback) === "function"){
                        if(animation.scope && typeof(animation.scope) === "object") animation.frameCallback.call(animation.scope,animation);
                        else animation.frameCallback.call({},animation);
                    }
                }

                obj.ecb = function(animation){
                    var animation = this;
                    if(!animation.endCallback) return false;
                    if(typeof(animation.endCallback) !== "function" && animation.animator.eventController){
                        animation.animator.eventController.trigger(animation.endCallback,obj);
                    }
                    if(typeof(animation.endCallback) === "function"){
                        if(animation.scope && typeof(animation.scope) === "object") animation.endCallback.call(animation.scope,animation);
                        else animation.endCallback.call({},animation);
                    }
                }

                obj.pause = function(turnOff,force){
                    if(turnOff !== false) turnOff = true;
                    if(turnOff === true && !this.active && !force) return;
                    if(turnOff === false && this.active && !force) return;
                    if(turnOff) {
                        this.active = false;
                        this.pTime = Date.now();
                        this.pDuration = 0;
                    }
                    else {
                        this.active = true;
                        this.pTime = 0;
                        this.pDuration = Date.now()-this.pTime;
                    }
                }

                obj.stop = function(triggerEned){
                    this.active = false;
                    //???triggerEned
                }

                obj.restart = function(params){
                    this.setStartValues(params);
                    this.active = true;
                }

                obj.chain = function(aniObj){

                }

                obj.destroy = function(){
                    console.log("I'm dead");
                    for(var n in this){
                        this[n] = null;
                        delete this[n];
                    }
                }

                obj.update = function(time){
                    //console.log("update:"+time);
                    if(this.delay && (time-this.sTime)<this.delay) return;
                    var p = (time-(this.sTime+this.delay))/this.duration;
                    if(p>1) p = 1;
                    if(p<0) p = 0;

                    var v = this.sVal+this.dVal*p;
                    if(obj.unit) v += obj.unit;

                    if(obj.func) obj.target[obj.param].call(obj.target,v);
                    else if(obj.target && obj.param) obj.target[obj.param] = v;
                    else if(obj.target && !obj.param) obj.target = v;

                    this.value = v;

                    this.fcb();
                    if(p===1) {
                        this.ecb();

                    }

                    return p;


                }

                return obj;
            }

            Animator.start = function(){
                if(this._id) return;

                if(!window.requestAnimationFrame) {

                        var lastTime = 0;
                        var vendors = ['ms', 'moz', 'webkit', 'o'];
                        for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
                            window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
                            window.cancelAnimationFrame =
                              window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
                        }

                        if (!window.requestAnimationFrame) {
                            window.requestAnimationFrame = function(callback, element) {
                                var currTime = new Date().getTime();
                                var timeToCall = Math.max(0, 16 - (currTime - lastTime));
                                var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                                  timeToCall);
                                lastTime = currTime + timeToCall;
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
                this._id = requestAnimationFrame(function(){self.frame()});
                //console.log("this._id :"+this._id );
            }

            Animator.stop = function(){
                //console.log("stop:"+this._id);
                cancelAnimationFrame(this._id);
                this._id = false;
            }

            Animator.clear = function(){
                this.stop();
                for(var ani in this.animations){
                    ani.destroy();
                }
                this.animations = [];

            }

            Animator.frame = function(){

                var self = this;

                if(!self.len){
                    self.stop();
                    return;
                }
                var p,time = Date.now();

                for(var ani in self.animations){
                    if(!self.animations[ani].active) continue;
                    p = self.animations[ani].update(time);
                    //console.log("obj.destroyable:"+obj.destroyable);
                    if(p === 1) {
                        if(self.animations[ani].destroyable){
                            self.animations[ani].destroy();
                            self.animations[ani] = null;
                            self.len--;
                            delete self.animations[ani];
                        }
                        else self.animations[ani].active = false;
                    }

                }
                requestAnimationFrame(function(){self.frame()});
            }



            return  Animator;



    }
);

