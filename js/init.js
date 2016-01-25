requirejs.config({
    baseUrl: "js",
    waitSeconds : 0,
	urlArgs: "t=" +  (new Date()).getTime(),
    paths: {
        jquery                   : "vendor/jquery-1.9.1.min",
        modernizr                : "vendor/modernizr",
        App                      : "App",
        EventHandler             : "events/EventHandler",
        domReady                 : "vendor/domReady"
    }


});

require(["jquery", "modernizr","domReady"], function($,modernizr,domReady)
{
    domReady(function () {
        require(["App"],
            function(App)
            {
                //console.log("ready:"+app.init);
                window.App = new App();
                //app.init();


            }
        );
    })
});