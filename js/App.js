define("App",["jquery","ImageController","events","beacon","FormController"],
    function($,ImageController,events,beacon,FormController){
        var App = function(){
            console.log("hello app")

            //eventHandler.addEventListener(EditorPanel.EVENT_ADD_OBJECT,this.onAddObject,this);
            this.imageController = new ImageController($("section > div.imageContainer"));
            this.formController = new FormController();


        }

        App.EVENT_ERROR = "EVENT_ERROR";

        return App;

    }
);



