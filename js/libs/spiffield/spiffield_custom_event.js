/**
 * spiffieldCustomEvent
 * 
 * var obj = document.createElement("object");
 * spfdCustomEvent.addListener(obj, "loadComp", function(e){alert("loadComplete!")});
 * spfdCustomEvent.dispatch(obj, "loadComp");
 */
var spfdCustomEvent = spfdCustomEvent || {};
(function(){

  var _handlerFuncList = {};

	/**
	 * addListener
	 * addEventListener / attachEvent
	 * @param target : DOMElement
	 * @param type : String
	 * @param func : Function
	 */
	addListener = function(target, type, func){
		if(target.addEventListener){
			target.addEventListener(type, func, false);
		}
		else if(target.attachEvent){
			if(target.parentNode != document){
				target.style.display = "none";
				document.appendChild(target);
			}
			_handlerFuncList[type] = func;
			target.attachEvent("ondataavailable", handleFuncIE);
		}
	},

	/**
	 * removeListener
	 * removeEventListener / detachEvent
	 * @param target : DOMElement
	 * @param type : String
	 * @param func : Function
	 */
	removeListener = function(target, type, func){
		if(target.removeEventListener){
			target.removeEventListener(type, func, false);
		}
		else if(target.detachEvent){
			target.detachEvent("ondataavailable", handleFuncIE);
			delete _handlerFuncList[type];
		}
	},

	/**
	 * handleFuncIE
	 *
	 * @param e : Event
	 */
	 handleFuncIE = function(e){
	 	var func = _handlerFuncList[e.datatype];
	 	if(func){func(e);}
	 },

	/**
	 * dispatch
	 * dispatchEvent / fireEvent
	 * @param target : DOMElement
	 * @param type : String
	 * @param data : Object
	 */
	dispatch = function(target, type, data){
		var ev;
		if(document.createEvent){
			ev = document.createEvent("Event");
			ev.initEvent(type, true, true);
			ev.datatype = type;
			ev.data = data;
			target.dispatchEvent(ev);
		}
		else if(document.createEventObject){
			ev = document.createEventObject();
			ev.datatype = type;
			ev.data = data;
			target.fireEvent("ondataavailable", ev);
		}
	}

	//public
	spfdCustomEvent.addListener = addListener;
	spfdCustomEvent.removeListener = removeListener;
	spfdCustomEvent.dispatch = dispatch;

	
})();