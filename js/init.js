requirejs.config({
    baseUrl: "js",
    waitSeconds : 0,
	urlArgs: "t=" +  (new Date()).getTime(),
    paths: {
        jquery                   : "vendor/jquery-1.9.1.min",
        modernizr                : "vendor/modernizr",
        underscore                : "vendor/underscore-min",
        App                      : "App",
        Utils                    : "utils/Utils",
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

                if(!window.requestAnimationFrame) {

                    var vendors = ['ms', 'moz', 'webkit', 'o'];
                    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
                        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
                        window.cancelAnimationFrame =
                            window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
                    }

                    if (!window.requestAnimationFrame) {
                        var lastFrameTime = 0;
                        window.requestAnimationFrame = function(callback, element) {
                            var currTime = new Date().getTime();
                            var timeToCall = Math.max(0, 16 - (currTime - lastFrameTime));
                            var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                                timeToCall);
                            lastFrameTime = currTime + timeToCall;
                            return id;
                        };
                    }

                    if (!window.cancelAnimationFrame) {
                        window.cancelAnimationFrame = function(id) {
                            clearTimeout(id);
                        };
                    }

                }
                window.app = new App();


            }
        );
    })
});