define("App",["jquery","ImageController","events","beacon","FormController","ScrollAnimator","GoogleTracker","AppData"],
    function($,ImageController,events,beacon,FormController,ScrollAnimator,GoogleTracker,appData){
        var App = function(){
            //console.log("hello app")

            //eventHandler.addEventListener(EditorPanel.EVENT_ADD_OBJECT,this.onAddObject,this);
            this.imageController = new ImageController($("section > div.imageContainer"));
            this.formController = new FormController();
            this.scrollAnimator= new ScrollAnimator();
            appData.googleTracker= new GoogleTracker();


            $(".parse-data").each(function(index,element){
                var s = ""
                element = $(element)
                s += element.data("p1");
                s += element.data("p2");
                s += element.data("p3");
                s += element.data("p4");
                s += element.data("p5");
                s = s.replace(/#/g,"")
                element.html(s);
                var href = element.attr("href")
                if(href) element.attr("href",href+s)

            })

            this.topScroller = $("a.nav-up");

            var path = window.location.href.split("/");
            path = path.pop();
            if(path.indexOf("?")){
                path = path.split("?")[0]
            }
            if(path.indexOf("#")){
                path = path.split("#")[0]
            }

            //console.log("path:"+path);

            $("a[href="+path+"]").addClass("selected");

            events.addListener(beacon.THROTTLED_SCROLL_EVENT,this.onScroll,this)
            this.onScroll()

        }

        App.prototype.onScroll = function(e){
            if(beacon.scrollY>beacon.windowHeight){
                this.topScroller.addClass("active");
            }
            else{
                this.topScroller.removeClass("active");
            }
        }

        /*
         <script>
         var elm = document.getElementById("touchspot");

         elm.addEventListener("contextmenu", function (e) {
         e.target.innerHTML = "Show a custom menu instead of the default context menu";
         e.preventDefault();    // Disables system menu
         }, false);

         </script>
         */

        App.EVENT_ERROR = "EVENT_ERROR";

        return App;

    }
);



