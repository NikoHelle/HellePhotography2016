define("FormController",["jquery","underscore","events"],
    function($,_,events){
        var FormController = function(){
            this.form = $("form");
            this.textarea = $("textarea",this.form);
            this.email = $("#email",this.form);

            //this.email.on("focus change", _.bind(this.inputEvent,this));
            //this.textarea.on("focus change", _.bind(this.textAreaEvent,this));

            this.url = "lib/sendMail.php";
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
            postData.v1 = window.hpx;
            postData.v2 = "";
            postData.email = this.email.val();
            postData.message = this.textarea.val();
            //postData.ie1 = $("input[name='ie1']", this.form).val("");
            //postData.ta1 = $("input[name='ta1']", this.form).val("");

            this.form.addClass("sending");

            window._postData = postData;
            this.xhr = $.ajax({
                url: this.url,
                type: 'POST',
                dataType: 'text',
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
            console.log(success+":"+data);
            this.form.removeClass("sending");
            this.xhr = false;
        }
        return FormController;
    }
);