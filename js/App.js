define("App",["jquery","ImageController","events","beacon"],
    function($,ImageController,events,beacon){
        var App = function(){
            console.log("hello app")

            //eventHandler.addEventListener(EditorPanel.EVENT_ADD_OBJECT,this.onAddObject,this);
            this.imageController = new ImageController($("section > div.imageContainer"));


        }

        App.EVENT_ERROR = "EVENT_ERROR";

        return App;

    }
);



