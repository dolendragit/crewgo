// Wait for the DOM to be ready
$(document).ready(function() {
	  // Initialize form validation on the registration form. It has the name attribute "customer_register"
  $("form[name='customer_register']").validate({
  	debug:true,
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute of an input field. Validation rules are defined on the right side
      name: "required",
      address: "required",
      phone: {
      	required: true,
      	minlength: 6,
      	number:true,
      },
      email: {
        required: true,
        email: true // Specify that email should be validated  by the built-in "email" rule
      },
      password: {
        required: true,
        minlength: 6
      },
     cpassword: {
      required: true,
      equalTo:"#password"
     },
    },
    // Specify validation error messages
    messages: {
      name: "Please enter your Name",
      address: "Please enter your Address",
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 6 characters long"
      },
      cpassword:{
      	required: "Please provide a password",
      	equalTo: "retyped password doesn't matched"
      },
      email: "Please enter a valid email address"
    }, 
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });

	  // Initialize form validation on the login form. It has the name attribute "customer_login"
   $("form[name='customer_login']").validate({
   	debug:true,
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute of an input field. Validation rules are defined on the right side
      email: {
        required: true,
        email: true // Specify that email should be validated  by the built-in "email" rule
      },
      password: "required"
    },
    // Specify validation error messages
    messages: {
      password: "Please provide a password",
      email: "Please enter a valid email address"
      }, 
    // Make sure the form is submitted to the destination defined in the "action" attribute of the form when valid
    submitHandler: function(form) {
    	form.submit();
    }
  });

   $("form[name='forgot_password']").validate({
   		debug:true,
   		rules: { email: { required: true, email: true, } },
   		messages: {email: "Please enter a valid email address"},
   		submitHandler : function(form){ form.submit(); }
   });
//end of form validation 


$("input[type='checkbox']").iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });
//end of icheck init for all checkbox


});//end of anonymous function


$(document).ready(function(){

    $('#username').editable({
      type: 'text',
      pk: 1,
      url: '/post',
      title: 'Enter username'
    });

});//end of document.ready function
