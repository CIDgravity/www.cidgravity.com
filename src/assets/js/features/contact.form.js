function sendContact() {
	var valid;	
	valid = validateContact();

	if(valid) {
		jQuery.ajax({
			url: "assets/php/contact.form.php",
		    data:'name='+$("#name").val()+'&captcha='+$("#g-recaptcha-response").val()+'&email='+$("#email").val()+'&phoneNumber='+$("#phoneNumber").val()+'&message='+$(message).val(),
		    type: "POST",
		    success:function(data){
				var parsedData = JSON.parse(data)
				var returnMessageBlock = '';
		    
				if(!parsedData.success) {
					returnMessageBlock = '<div class="message msg-danger"><h4 class="message-header">Something wrong</h4><div class="message-body">' + parsedData.message + '</div></div>';
				} else {
					returnMessageBlock = '<div class="message msg-success"><h4 class="message-header">Success</h4><div class="message-body">' + parsedData.message + '</div></div>';
				}
				
		  		$("#mail-status").html(returnMessageBlock);
		    },
		    error:function (){

            }
		});
	}
}

function validateContact() {
	var valid = true;	
	$(".form-input-box").css('background-color','');
	$(".info").html('');
	
	if(!$("#name").val()) {
		$("#name-info").html("(required)");
		$("#name").css('background-color','#FFFFDF');
		valid = false;
	}

	if(!$("#email").val()) {
		$("#email-info").html("(required)");
		$("#email").css('background-color','#FFFFDF');
		valid = false;
	}

	if(!$("#email").val().match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
		$("#email-info").html("(invalid)");
		$("#email").css('background-color','#FFFFDF');
		valid = false;
	}

	if(!$("#phoneNumber").val()) {
		$("#phoneNumber-info").html("(required)");
		$("#phoneNumber").css('background-color','#FFFFDF');
		valid = false;
	}

	if(!$("#phoneNumber").val().match(/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/)) {
		$("#phoneNumber-info").html("(invalid)");
		$("#phoneNumber").css('background-color','#FFFFDF');
		valid = false;
	}

	if(!$("#message").val()) {
		$("#message-info").html("(required)");
		$("#message").css('background-color','#FFFFDF');
		valid = false;
	}
	
	return valid;
}