(function(){
	
	function FPSEqualizer (targetFPS,listenerFunction,scope){
		
  		this.targetFPS = targetFPS || 60;
 		this.frameTime = 1000/this.targetFPS;
  		this.lastDraw = 0;
		this.drawFrames = 0;
		this.calls = 0;
		this.startTime = 0;
		this.listeners = [];
		this.ids = 0;
		this.rafID = false;
		this.fps = 0;
		this.cps = 0;
		this.slowThreshold = 0.5;
		this.slowCount = 0;
		this.fpsResetGap = 5000; //in ms, how often is fps reset
		if(listenerFunction) this.start(listenerFunction,scope);
	}
	
	FPSEqualizer.prototype.update = function(){
		
		if(this.lastDraw){
			this.calls++;
			var dt = Date.now()-this.lastDraw;
			//console.log("dt:"+dt);
			if(dt<this.frameTime) {
			   this.requestFrame();
			  return;
			}
			
			var i,frames = Math.max(1,Math.round(dt/this.frameTime));
			
			for(i=0;i<frames;i++){
			  this.runListeners();
			  this.drawFrames++;
			}
			
			this.lastDraw +=this.frameTime*frames;
		}
		else {
			
			this.lastDraw = Date.now();
			this.startTime = Date.now();
			this.requestFrame();
			return;
		}
		
		if(this.fpsResetGap && Date.now()-this.startTime > this.fpsResetGap){
			this.drawFrames = 0;
			this.calls = 0;
			this.startTime += Date.now()-this.startTime;
		}
		
		//this.lastDraw = Date.now();
		
		if(this.drawFrames) {
			//this.fps = Math.round(1000/((Date.now()-this.startTime)/this.drawFrames)*100)/100;
			this.fps = Math.round((this.drawFrames/((Date.now()-this.startTime)/1000))*100)/100;
			if(this.fps<(this.targetFPS*this.slowThreshold)) this.slowCount++;
			this.cps = Math.round((this.calls/((Date.now()-this.startTime)/1000))*100)/100;
			
		}
		
		this.requestFrame();	
	}
	
	FPSEqualizer.prototype.runListeners = function(){
		
			var i,len = this.listeners.length;
			for(i=0;i<len;i++){
				if(!this.listeners[i]) continue; // possible if listener removes listener
				if(this.listeners[i].f) this.listeners[i].call();
			}
	}
	
	FPSEqualizer.prototype.start = function(listenerFunction,scope){
		if(listenerFunction) this.addListener(listenerFunction,scope);
		if(!this.rafID) this.requestFrame();
	}
	
	FPSEqualizer.prototype.stop = function(removeListeners){
		//if(this.rafID) window.cancelAnimationFrame(this.rafID);
		if(removeListeners !==false) {
			
			var i,len = this.listeners.length;

			for(i=0;i<len;i++){
				if(this.removeListener(this.listeners[i].id)){
					i--;
					len--;	
				}
			}
			this.listeners = [];
		}
	}
	


	FPSEqualizer.prototype.addListener = function(listenerFunction,scope,noStart){
		var f  = {}
		f.s = scope || {};
		f.f = listenerFunction;
		f.call = function(){this.f.call(this.s)};
		f.id = ++this.ids;
		
		this.listeners.push(f);
		if(!noStart && !this.rafID) this.start();
		return f.id;
		//console.log("addListener:"+(f.f===listenerFunction));
	}
	
	FPSEqualizer.prototype.removeListener = function(func,scope){
		var isID,i,len = this.listeners.length;
		isID = typeof(func) === "number";
		//console.log("isID "+isID+",func:"+func);
		for(i=0;i<len;i++){
			//console.log("isID "+isID+",func:"+func+" vs "+this.listeners[i].id);
			if(isID && this.listeners[i].id !== func) continue;
			
			if(scope && this.listeners[i].s !== scope) {
				//console.log("scope failed");
				continue;
			}
			if(!isID && func && this.listeners[i].f !== func) {
				//console.log("func failed+")// failed :"+func);
				continue;
			}
			
			delete this.listeners[i].f;
			delete this.listeners[i].s;
			delete this.listeners[i].call;
			//console.log("found");
			this.listeners.splice(i,1);
			//console.log("splice "+this.listeners.length);
			return true;
			
			//+++ splice array
		}
		return false;
		
		//console.log("####TOTAL FAILURE####");
	}
	
	FPSEqualizer.prototype.requestFrame = function(){
		var self = this;
		this.rafID = window.requestAnimationFrame(function(){
				 self.update();
			 
		});
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

	
	window.namespace.utils.FPSEqualizer=FPSEqualizer;
	
	
	
})();
;

