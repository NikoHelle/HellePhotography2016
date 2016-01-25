(function(){
	
	function Logger (target,template,maxEntries,consoleLog,eventController){
		this.active = true;
		this.target = $(target);
		this.template = template || "";
		this.consoleLog = consoleLog !==false
		this.maxEntries = maxEntries || false;
		this.eventController = eventController || false;
		this.filter = false;
		this.trigger = this.eventController ? true : false;
		this.group = false; //???not implemented
		this.minGap = false; //???not implemented
		this.lastCallTime = false; //???not implemented
		
	}

	Logger.prototype.TYPE_CRITICAL = "TYPE_CRITICAL";
	Logger.prototype.TYPE_NOTIFY ="TYPE_NOTIFY";
	Logger.prototype.TYPE_ERROR ="TYPE_ERROR";
	
	Logger.prototype.log = function(text,type,trigger,alternativeTarget,consoleLog){
		
		if(!this.active) return;
		
		if(this.filter &&  this.filter.indexOf(type) === -1) return;
		
		if(consoleLog !== false && this.consoleLog && window.console && window.console.log && window.console !== this) console.log(text); 
		
		if(trigger !== false && this.trigger && this.eventController){
			this.eventController.trigger(type,text)
		}
		
		var currentTarget = this.target;
	
		if(alternativeTarget) currentTarget = alternativeTarget;
		
		if(!currentTarget || !currentTarget.length) return;
		
		if(this.template) {
			text = String(this.template).replace("{{text}}",text);
			text = text.replace("{{type}}",type);
		}
		
		currentTarget.prepend(text);
		
		if(this.maxEntries) {
			if(currentTarget.children().length > this.maxEntries) currentTarget.children().eq(0).remove();

		}

	}

	Logger.prototype.show = function(){
		this.target.show();
	}
	
	Logger.prototype.hide = function(){
		this.target.hide();
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

	
	window.namespace.utils.Logger=Logger;
	
	
	
})();
;

