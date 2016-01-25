(function(){
	
	
	var GoogleTracker={
	};
	

	
	GoogleTracker.track = function(category,action,label,value){
		
		
		category = category || "unknown";
		action = action || "unknown";
		label = label || "unknown";
		value = parseInt(value) || 0;
		if(isNaN(value)) value = 0;
		
		/*console.log("category:"+category);
		console.log("action:"+action);
		console.log("label:"+label);
		console.log("value:"+value);*/

		var trackObj = ['_trackEvent', category.toString(), action.toString(), label.toString(),value];
		//var trackObjWS = ['ws._trackEvent', category.toString(), action.toString(), label.toString(),value];
		
		if(!window._gaq) return false;  
		//RayObj.debug(trackObj);
		window._gaq.push(trackObj);	
		
		
	}
	
	/*GoogleTracker.eventTriggered = function(e,param1,param2){
		
		
		var type = e.type;
		var category,action,label,value;

		
		switch(type){
			case "9999"://RayObj.eventController.EVENT_UI_EXTERNAL_LINK_PRESS:
				category="ui";
				action = "link_click"
				label= param1; //href
				value = 0;
				break;
			case "9988282"://RayObj.eventController.EVENT_UI_ANSWER_INCORRECT:
				category="ui";
				action = "answer_incorrect"
				label= param1; //href
				value = 0;
				break;	
			case "xx":	
			case "xxxx":
			case "cxccc":				
				break;
			default:
				if(type.indexOf("EVENT_GAME") !== -1) category = "game";
				else if(type.indexOf("EVENT_UI") !== -1) category = "ui";
				else if(type.indexOf("EVENT_FB") !== -1) category = "facebook";
				else category="untracked"
				var action = type.split("_"); //EVENT_UI_ANSWER_INCORRECT -> EVENT,UI,ANSWER,INCORRECT
				if(action.length !== 0) action[0] = ""; //"",UI,ANSWER,INCORRECT
				if(action.length > 0) action[1] = "";//"","",ANSWER,INCORRECT
				action = action.join("_").replace("__","");
				action = action.toLowerCase(); // = answer_incorrect
				
				label= param1;
				value = 0;
				break;

		}
		
		this.track(category,action,label,value);
		
	}*/
	
	if(!window.namespace){
		if(!window.nhe){
			window.nhe = {}
		}
		
		
		window.namespace = window.nhe;
	}
	
	if(!window.namespace.utils){
			window.namespace.utils = {}
	}

	
	window.namespace.utils.googleTracker=GoogleTracker;
	
	
	
})();
;

