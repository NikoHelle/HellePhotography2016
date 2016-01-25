define("EventHandler",
    function(){



            function EventHandler (){

                this.events = [];
                this.listeners = [];
                this._els = []; //more speed, remember array lengths
                this._ls = 0; //more speed, remember array lengths
                this.trace = false; // trace all events
                this.simpleRemove = false; // just destroy / disable removed events

            }

            EventHandler.idCounter = 0;

            EventHandler.listenerObject = function(eventHandler,eventType,listenerFunction,scope,properties){
                var ns,f  = {}
                ns = eventType.split(":");
                if(ns.length>1){
                    f.ns = ns[1];
                    eventType = ns[0];
                }
                else f.ns = false;
                f.oe = eventType.split("."); //original event
                f.type = f.oe[0]; //type
                f.d = f.oe.length; //depth
                f.s = scope || {};
                f.f = listenerFunction;
                f.priority = 0; //priority
                f.ctd = false; //current trigger data.
                f.da = false; // is deactivated
                if(properties){
                    for(var i in properties){
                        if(f[i]) continue;
                        f[i] = properties[i];
                    }
                }

                f.active = true;
                f.id = ++EventHandler.idCounter;
                f.eh = eventHandler; // EventHandler

                //f.call = function(ctd,data){this.ctd = ctd || {};this.f.call(this.s,this,data)};
                f.call = function(ctd,data){
                    this.ctd = ctd || {};
                    var argsArr = [];
                    if(data && data.constructor === Array) {
                        argsArr = data.slice(0);
                        argsArr.unshift(this);
                    }
                    else argsArr = [this,data];
                    this.f.apply(this.s,argsArr)
                };
                f.abort = function(){this.ctd.aborted=true;}
                f.done =  function(){delete this.ctd}
                f.stopPropagation = function(){this.ctd.bubbles=false;this.ctd.startDepth=this.d}
                f.destroy = function(noRemove){
                    var i,success;
                    if(this._d) return; //destroy started...
                    this._d = true;
                    if(!noRemove && this.eh) {
                        success = this.eh.removeEventListener(this);
                    }
                    else success = true;
                    for(var i in this){
                        delete this[i];
                    }
                    return success;
                }

                return f;
            };

            EventHandler.add$EventListener = function(eventType,element,listenerFunction,scope,properties){
                //$("FFF").on(EVENTS, SELECTOR, DATA,HANDLER);
            }

            EventHandler.prototype.addEventListener = function(eventType,listenerFunction,scope,properties){


                // +++ data.condition?


                if(!eventType || !eventType.length || (typeof eventType !== "string")) return false;
                if(!listenerFunction || (typeof listenerFunction !== "function")) return false;

                var f  = EventHandler.listenerObject(this,eventType,listenerFunction,scope,properties);
                if(!this.events[f.type]) {
                    this.events[f.type] = [];
                    this._els[f.type] = 0;
                }

                this.place(f);
                //this.events[eventType].push(f);
                this.listeners.push(f);
                this._ls++;
                return f;

            }
            //can remove all listeners by just scope / namespace????
            EventHandler.prototype.removeEventListener = function(eventType,func,scope){
                var path,ns,isID,isObj,i,jj,len2,eventObj,eventID,len;
                eventID = (typeof eventType === "number") ? eventType : false;
                eventObj = (typeof eventType  === "object" && eventType.id && eventType.oe) ? eventType : false;

                if(eventID){
                    eventObj = this.sweep(eventID);
                    if(!eventObj) return false;
                }
                if(!eventObj){
                    ns = eventType.split(":");
                    if(ns.length>1){
                        eventType = ns[0];
                        ns = ns[1];
                    }
                    else ns = false;

                    path = eventType.split(".");
                    eventType = path[0];

                }
                else eventType = eventObj.type;

                if(eventType !== "*" && (!this.events[eventType] || !this.events[eventType].length)) return false;

                var found = 0;
                var list = [];

                for(i=0;i<this._ls;i++){

                    if(eventObj) {
                        list.push(eventObj);
                        break;
                    }


                    if(ns && this.listeners[i].ns !== ns) continue;

                    if(eventType !== "*" && this.listeners[i].type !== eventType) continue;

                    //click.nav.level1 does not remove click.nav or click
                    if(path.length > this.listeners[i].d) continue;
                    //else if(!this.isInRange(path,this.listeners[i].oe)) continue;

                    if(scope && this.listeners[i].s !== scope) continue;

                    if(func && this.listeners[i].f !== func) continue;

                    list.push(this.listeners[i]);


                }

                len = list.length;
                for(i=0;i<len;i++){
                    if(this.sweep(list[i],true,true)) found++;
                }


                // console.log("done: found:"+found)
                return found;

                //console.log("####TOTAL FAILURE####");
            }

            EventHandler.prototype.place = function(eventObject){


                var i,len,eventType,list = [];
                eventType = eventObject.type;


                len = this._els[eventType];

                if(eventObject.priority || eventObject.d>1){
                    for(i=0;i<len;i++){
                        if(eventObject.priority){
                            if(this.events[eventType][i].priority<=eventObject.priority) {
                                this.events[eventType].splice(i,0,eventObject);
                                this._els[eventType]++;
                                return i;
                            }
                        }
                        else if(this.events[eventType][i].priority) continue;

                        if(!this.events[eventType][i].priority && eventObject.d > this.events[eventType][i].d) {
                            this.events[eventType].splice(i,0,eventObject);
                            this._els[eventType]++;
                            return i;
                        };

                    }
                }

                this.events[eventType].push(eventObject);
                this._els[eventType]++;
                return len; //len is before adding -> correct

                //click
                //click.nav
                //click.nav.level1


            }


            EventHandler.prototype.trigger = function(eventType,data,bubbles){
                if(!eventType || !eventType.length || (typeof eventType !== "string")) return false;
                bubbles = bubbles !== false;
                var path, rq,i,len,ctd,ns = eventType.split(":");
                if(ns.length>1){
                    eventType = ns[0];
                    ns = ns[1];
                }
                else ns = false;
                path = eventType.split(".");
                ctd = {aborted:false,bubbles:bubbles,startDepth:path.length,triggeredType:eventType};
                rq = [];
                eventType = path[0];
                if(!this.events[eventType] || !this.events[eventType].length) return false;
                len = this.events[eventType].length;

                for(i=0;i<len;i++){
                    //console.log("inRange::"+path+" vs "+this.events[eventType][i].oe+" res:"+this.isInRange(path,this.events[eventType][i].oe))
                    if(this.events[eventType][i].da) continue;
                    if(this.events[eventType][i].condition && !this.events[eventType][i].condition()) continue;
                    if(!this.isInRange(path,this.events[eventType][i].oe)) continue;
                    if((ns || this.events[eventType][i].ns) && this.events[eventType][i].ns != ns) continue;

                    try{
                        if(!ctd.bubbles && this.events[eventType][i].d != ctd.startDepth) break;
                        this.events[eventType][i].call(ctd,data);
                    }
                    catch(e){
                        if(window.console && window.console.log) console.log("**** EventHandler error:"+e+" for eventType:"+eventType);
                        this.events[eventType][i].destroy();
                        continue;
                    }
                    this.events[eventType][i].done();
                    if(this.events[eventType][i].once) rq.push(this.events[eventType][i]);

                    if(ctd.aborted)break;

                }
                len = rq.length;
                for(i=0;i<len;i++){
                    this.sweep(rq[i],true,true);
                }

            }

            EventHandler.prototype.sweep = function(obj,remove,destroy){
                var inListeners,inEvents, i,len;
                inListeners = inEvents = false;
                if(!obj) return false;

                if(typeof obj === "number") obj = {id:obj};
                else if(typeof obj === "object" && (!obj.id || !obj.oe)) return false;

                for(i=0;i<this._ls;i++){
                    if(this.listeners[i].id !== obj.id) continue;
                    if(remove){
                        this.listeners.splice(i,1);
                        this._ls--;
                    }
                    inListeners = true;
                    break;
                }

                if(!inListeners) return false;

                len = this._els[obj.type];

                for(i=0;i<len;i++){
                    if(this.events[obj.type][i].id !== obj.id) continue;
                    if(remove) {
                        this.events[obj.type].splice(i,1);
                        this._els[obj.type]--;
                    }
                    inEvents = true;
                    break;

                }

                if(!inEvents) return false;

                if(remove && destroy) obj.destroy(false);
                if(remove) return true;

                return obj;
            }

            //event "click.nav.level1" will trigger this.listeners for "click.nav" and "click"
            //event "click.nav" will NOT trigger "click.nav.level1", only this.listeners for "click.nav" and "click"

            EventHandler.prototype.isInRange = function(eventPath,listenerPath){
                var self = this;
                if(!eventPath || !listenerPath) return;
                if(!eventPath.pop) eventPath = String(eventPath).split(".");
                if(!listenerPath.pop) listenerPath = String(listenerPath).split(".");

                if(eventPath[0] !== listenerPath[0]) return false;
                if(eventPath.length < listenerPath.length) return false;
                if(eventPath.length === listenerPath.length) return true;
                return eventPath.slice(0,listenerPath.length).join(".") === listenerPath.join(".");
            }



        return new EventHandler();



    }
);


