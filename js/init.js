requirejs.config({
    baseUrl: "js",
    waitSeconds : 0,
	urlArgs: "t=" +  (new Date()).getTime(),
    paths: {
        jquery                   : "vendor/jquery-1.9.1.min",
        modernizr                : "vendor/modernizr",
        underscore               : "vendor/underscore-min",
        App                      : "App",
        Utils                    : "utils/Utils",
        EventHandler             : "events/EventHandler",
        domReady                 : "vendor/domReady",
        raf                      : "vendor/raf"
    }
});

require(["jquery", "modernizr","domReady","raf"], function($,modernizr,domReady,raf)
{
    domReady(function () {
        require(["App"],
            function(App)
            {
                window.app = new App();
            }
        );
    })
});