(function(){
	
	if(!window.namespace){
		if(!window.nhe){
			window.nhe = {}
		}
		if(!window.nhe.utils){
			window.nhe.utils = {}
		}
		
		window.namespace = window.nhe.utils;
	}
	
	var SendFileIE9={
		initialized:false
	};
	

	
	SendFileIE9.init = function(){
		if(this.initialized) return;
		this.initialized = true;
		$(':file').change(function(){
            var file = this.files[0];
			
            name = file.name;
            size = file.size;
            type = file.type;
			
            if(file.name.length < 1) {

            }
            else if(file.size > 5000000) {
                return false;
            }
            else if(file.type != 'image/png' && file.type != 'image/jpg' && !file.type != 'image/gif' && file.type != 'image/jpeg' ) {
                 return false;
            }
            else { 
                $(':submit').click(function(e){
                    var formData = new FormData($('#manual-dropzone')[0]);
					e.preventDefault();
                    $.ajax({
                        url: 'php/upload.php',  //server script to process data
                        type: 'POST',
                        xhr: function() {  // custom xhr
                            myXhr = $.ajaxSettings.xhr();
                            if(myXhr.upload){ // if upload property exists
                                myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // progressbar
                            }
                            return myXhr;
                        },
                        //Ajax events
                        success: completeHandler = function(data) {
                            /*
                            * workaround for crome browser // delete the fakepath
                            */
                           //alert(data);

                        },
                        error: errorHandler = function(error) {
                           // alert(error);
                        },
                        // Form data
                        data: formData,
                        //Options to tell JQuery not to process data or worry about content-type
                        cache: false,
                        contentType: false,
                        processData: false
                    }, 'json');
                });
            }
    });	
	}
	
	if(!window.namespace){
		if(!window.nhe){
			window.nhe = {}
		}
		
		
		window.namespace = window.nhe;
	}
	
	if(!window.namespace.utils){
			window.namespace.utils = {}
	}
	
	window.namespace.utils.SendFileIE9=SendFileIE9;
	
	
	
})();
;

