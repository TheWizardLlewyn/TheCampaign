$(document).ready(function() {
    $("#create_account_button").click(function() {
        if (!$("#agree_to_terms_of_service").is(":checked")) {
            alert("You must agree to Terms of service!");
            return false;
        }
        $.ajax({
            url: "api/createuser.php",
            type: "post",
            data: {email: $("#signup_email").val(), password: $("#signup_password").val(), name: $("#signup_name").val()}
        }).done(function(response) {
            resp = $.parseJSON(response);
            if (resp.errors != "") {
                alert (resp.errors);
            } else {
                alert("You are in!");
            }
        });
    });
});