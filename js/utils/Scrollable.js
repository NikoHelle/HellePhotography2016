(function(){
	

	
	var Scrollable = function(target,options){
		//++ height = n elements!
			this.options = {};
			if(options && typeof(options) === "object") $.extend(this.options,options);
			this.target = $(target);
			//console.log("Scrollable:"+target);
			if(!target.length) return false;
			
			this.started = false;
			this.startPosY = false;
			this.posY = this.options.posY || 0;;
			this.maxY = this.options.maxY !==undefined ? this.options.maxY : false;
			this.minY = this.options.minY !==undefined ? this.options.minY : false;
			this.posX = this.options.posX || 0;;
			this.maxX = this.options.maxX !==undefined ? this.options.maxX : false;
			this.minX = this.options.minX !==undefined ? this.options.minX : false;
			this.scrollRatio = this.options.scrollRatio || 1;
			this.trackTotals = this.options.trackTotals || true;
			
			this.scrollThreshold = 4; //=px
			this.eventType = this.options.eventType || "SCROLL_CHANGE";
			this.startEventType = this.options.startEventType || "SCROLL_START";
			this.endEventType = this.options.endEventType || "SCROLL_END";
			
			var self = this;
			var sbObj = this;
			this.scrollpos = 0;
			/**/
			/*this.target.on("mousedown",function(e){
				e.preventDefault();
				sbObj.scrollStart(e);
				
			});*/
			
		
			
			this.target.on("mousewheel",function(e, delta, deltaX, deltaY){
				e.preventDefault();
				deltaX = deltaX || 0;
				deltaY = deltaY || 0;
				
				//if deltaX/Y undefined missing https://github.com/brandonaaron/jquery-mousewheel
				//console.log("deltaX:"+deltaX);
				if(!deltaX && !deltaY) return;
				sbObj.mouseWheelUpdate(deltaY*sbObj.scrollRatio,deltaX*sbObj.scrollRatio);
				
			});
			
			if(!Hammer) {
				alert("this plugin requires jquery.hammer.min.js");
				return false;	
			}
			
			var hammertime = Hammer(this.target).on("dragstart", function(e) {
				if(sbObj.started) return;
				sbObj.started = true;
				sbObj.startPosY = e.gesture ? e.gesture.center.pageY : 0;
				sbObj.target.trigger(sbObj.startEventType);
			});
			
			
			var hammertime = Hammer(this.target).on("drag", function(e) {
				//console.log("dragdown target");
				e.preventDefault();
				if(!e.gesture) return false;
				e.gesture.preventDefault();
				if(!sbObj.started) return;
				e.stopPropagation()
				e.gesture.stopPropagation();
				
				sbObj.mouseWheelUpdate(e.gesture.deltaY*sbObj.scrollRatio,e.gesture.deltaX*sbObj.scrollRatio);
			});
			
			
			var hammertime = Hammer(this.target).on("dragend mouseout", function(e) {
				if(!sbObj.started) return;
				//sbObj.startPosY = e.gesture ? e.gesture.center.pageY : false;
				sbObj.target.trigger(sbObj.endEventType);
				sbObj.started = false;
			});
			
			
			
			/*this.scrollStart = function(e){
				var sbObj = this;
				$(document).on("mousemove",function(e){
					if(!sbObj.startPosY) return;
					sbObj.scrollUpdate(e);
				})
				$(document).on("mouseup",function(e){
					if(!sbObj.startPosY) return;
					sbObj.scrollEnd();
					
				})
				this.startPosY = e.pageY;
			}*/
			
			this.scrollEnd = function(e){
				this.startPosY = false;
				
			}
			
			this.scrollUpdate = function(e){
				
				//console.log("this.posY:"+this.posY+",dy:"+e.dy);
				
				var y = this.posY+e.dy;

				if(this.minY !==false && y<this.minY) y = this.minY;
				else if(this.maxY !==false && y>this.maxY) y = this.maxY;
				
				var py = this.maxY ? y/this.maxY : false;
				
				var x = this.posX+e.dx;

				if(this.minX !==false && x<this.minX) x = this.minX;
				else if(this.maxX !==false && x>this.maxX) x = this.maxX;
				
				var px = this.maxX ? x/this.maxX : false;
				
				e.x = x;
				e.y = y;
				e.py = py;
				e.px = px;
				
				this.updateScrolling(e);
				if(this.trackTotals) {
					this.posY = y;
					this.posX = x;
				}
			}
			
			this.mouseWheelUpdate = function(deltaY,deltaX){
				
				//console.log("mouseWheelUpdate:"+deltaY);
				this.scrollUpdate({dy:-deltaY,dx:-deltaX});

			}
			
			this.updateScrolling = function(e){
				
				this.target.trigger(this.eventType,e);
				
				//console.log("updateScrolling:"+e.y);
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
	
	window.namespace.utils.Scrollable = Scrollable;
	
	
	
	
})(false);
;

