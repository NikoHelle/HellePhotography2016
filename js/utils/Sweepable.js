(function(){
	
	
	var Sweepable = function(target,options){
		
			this.options = {};
			if(options && typeof(options) === "object") $.extend(this.options,options);
			this.target = $(target);
			
			if(!target.length) return false;
			
			this.pointerEventDetected = false;
			this.touchCancelDetected = false;
			this.hammerEventDetected = false;
			this.hammertime = false;
			
			this.eventType = this.options.eventType || "SWEEP_CHANGE";
			this.startEventType = this.options.startEventType || "SWEEP_START";
			this.endEventType = this.options.endEventType || "SWEEP_END";
			
			this.lastX=0;
			this.lastTouchEventX=0;
			this.lastTouchEventDirX=0;
			this.lastDragRelease = 0;
			
			if(!Hammer) {
				alert("this plugin requires jquery.hammer.min.js");
				return false;	
			}
			
			var self = this;
			
			
				
			this.hammertime = this.target.hammer();
			
			 if (window.navigator && window.navigator.msMaxTouchPoints) {
				 
				  this.target.get(0).addEventListener("MSPointerDown", function(e){ self.handleMsPointerEvent(e);}, false);
				  this.target.get(0).addEventListener("MSPointerMove", function(e){ self.handleMsPointerEvent(e);}, false);
				  this.target.get(0).addEventListener("MSPointerUp", function(e){ self.handleMsPointerEvent(e);}, false);
				  
				 
			}
			else {
			
				this.target.on("touchstart touchmove touchend touchcancel", function(e){self.handleTouchEvent(e)});
			 
				this.hammertime.on("tap swipe swipeleft swiperight", function(e){self.handleTouchEvent(e)});
			 
				this.hammertime.on("drag dragstart dragend",function(e){self.handleHammerEvent(e)});
			
			}
					
					
			
			
		
		
		this.handleTouchEvent = function(e){
			return; //??? use this && if last move > 1000 ms
			if(e.type !== "touchcancel" && !this.touchCancelDetected) return;
			if(e.type === "touchcancel") this.touchCancelDetected = true;
			
			if(e.type ==="dragstart") {
				this.lastTouchEventX = e.center.pageX;	
				
				return;
			}
			
			
		}
		
		this.handleMsPointerEvent = function(e){
			this.pointerEventDetected = true;

			var curX = e.pageX || e.targetTouches[0].pageX;
			
			if(!curX) return;
			this.lastMSPointerDown =  Date.now();
			if(e.type ==="MSPointerDown") {
				this.lastTouchEventX = curX;	
				return;
			}
			
			if(e.type !== "MSPointerUp") e.preventDefault();
			if(!this.lastTouchEventX) this.lastTouchEventX = curX;
			var dx = curX-this.lastTouchEventX;

			var dir = false;
			if(e.type === "MSPointerUp") {
				dir = this.lastTouchEventDirX;
				this.lastTouchEventX = false;
			}
			this.handleEventResult(e.type,dx,dir);	
			
			//this.lastTouchEventX = curX;
			
		}
		
		this.handleHammerEvent = function(e){
			this.hammerEventDetected = true;
			if(this.pointerEventDetected || this.touchCancelDetected) return;
			// use ??? pointerType {String}        kind of pointer that was used. matches Hammer.POINTER_MOUSE|TOUCH
		
			
			p = e.gesture;
			if(e.type !== "dragend") e.preventDefault();

	
			if(!p || !p.deltaX) return false;
			
			p.deltaX = parseInt(p.deltaX,10);
						
			this.handleEventResult(e.type,p.deltaX);	
			
		}
		
		this.handleEventResult = function(type,dx,dir){
			
			if(!type || (!dx && !dir)) return;
			
			
			
			if(!dir) dir = dx >0 ? -1 : 1;
			
			this.lastTouchEventDirX = dir;
			
			if(type === "dragend" || type === "touchcancel" || type === "MSPointerUp") {

				this.lastDragRelease = Date.now();
				//RayApp.debug("this.lastDragRelease reset:"+this.lastDragRelease);
				this.target.trigger(this.endEventType,{dx:dx,originalEvent:type,dir:dir});
			}
			else {
				this.target.trigger(this.eventType,{dx:dx,originalEvent:type,dir:dir});
			}
			
			
			
		}
		
	
			
			return this;
	}
	
	
	if(!window.namespace){
		if(!window.nhe){
			window.nhe = {}
		}
		
		
		window.namespace = window.nhe;
	}
	
	if(!window.namespace.utils){
			window.namespace.utils = {}
	}
	
	window.namespace.utils.Sweepable = Sweepable;
	
	
	
})(false);
;

		
		
	
	
	
	
	
	
	
	


