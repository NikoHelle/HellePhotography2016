define("App",["jquery","ImageController","events","beacon","FormController"],
    function($,ImageController,events,beacon,FormController){
        var App = function(){
            console.log("hello app")

            //eventHandler.addEventListener(EditorPanel.EVENT_ADD_OBJECT,this.onAddObject,this);
            this.imageController = new ImageController($("section > div.imageContainer"));
            this.formController = new FormController();


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



