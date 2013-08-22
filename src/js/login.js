/*
 * @author Abdelkader Benkhadra
*/

jQuery(function($) {

// change input behaviour on focus
var email = $("#email"),
    pw = $("#password"),
    email_val = email.val(),
    pw_val = pw.val();

  email.on("focus", function() {
    if($(this).val() == email_val) {
      $(this).val("").css("color", "black");
    }
  });

  email.on("focusout", function() {
    if($(this).val() == 0 ) {
      $(this).val(email_val).css("color", "#9c9595");
    }
  });  

  pw.on("focus", function() {
    if($(this).val() == pw_val) {
      $(this).val("").attr("type", "password").css("color", "black");
    }
  });

  pw.on("focusout", function() {
    if($(this).val() == 0 ) {
      $(this).val(pw_val).attr("type", "text").css("color", "#9c9595");
    }
  });

});
 