define("FormController",["jquery","underscore","events","Utils","AppData"],
    function($,_,events,Utils,appData){
        var FormController = function(){
            this.form = $("form");
            this.textarea = $("textarea",this.form);
            this.email = $("#email",this.form);

            this.email.on("focus change", _.bind(this.inputEvent,this));
            this.textarea.on("focus change", _.bind(this.textAreaEvent,this));

            this.url = "lib/sendForm.php";
            this.xhr = false;

            $("a#sendForm").on("click",_.bind(this.send,this));
            $("a.overlay").on("click",function(e){e.preventDefault();});

        }

        FormController.prototype.inputEvent = function(e) {
            $("input[name='ie1']", this.form).val(e.type);
        }
        FormController.prototype.textAreaEvent = function(e){
            $("input[name='ta1']",this.form).val(e.type);
        }
        FormController.prototype.checksum = function(e) {
        }
        FormController.prototype.send = function(e) {
            if(e) e.preventDefault();
            if(this.xhr) return;
            var _this = this;
            var postData ={}
            postData.v2 = window.hpx;
            postData.v1 = "";
            postData.email = ""+this.email.val();
            postData.message = ""+this.textarea.val();
            postData.ie1 = ""+$("input[name='ie1']", this.form).val();
            postData.ta1 = ""+$("input[name='ta1']", this.form).val();



            if(!Utils.validateEmail(postData.email) || postData.message.length<20){
                this.form.addClass("invalid");
                appData.googleTracker.send("form","send-invalid",postData.email);
                return
            }

            appData.googleTracker.send("form","sending",postData.email);

            this.form.removeClass("sent error invalid sent").addClass("sending");

            //window._postData = postData;
            this.xhr = $.ajax({
                url: this.url,
                type: 'POST',
                cache: false,
                data:postData,
                success:function(data) {
                    _this.ajaxResponse(true,data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    _this.ajaxResponse(false,errorThrown);
                }
            });


        }
        FormController.prototype.ajaxResponse = function(success,data) {
            //console.log(success+":"+data);
            this.form.removeClass("sending");
            this.xhr = false;
            var successResponse = data.indexOf("success=true") != -1
            var vResponse = data.indexOf("v=") != -1
            var aResponse = data.indexOf("a=") != -1
            if(!success || !successResponse || vResponse || !aResponse){
                this.form.addClass("error");
                appData.googleTracker.send("form","send-error",data);
            }
            else{
                this.form.addClass("sent");
                this.email.val("");
                this.textarea.val("");
                appData.googleTracker.send("form","send-success",data);
            }

        }

        return FormController;
    }
);