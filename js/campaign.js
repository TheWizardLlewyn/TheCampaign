var signup_step = 0;
var signup_values = {};
var signup_total_stat_value = 0;
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
                nextSignupStep();
            }
        });
    });
});

function nextSignupStep() {
    signup_step++;
    switch(signup_step) {
        case 1:
            //Party Selection
            $("#left_side_half").hide();
            $("#right_side_half").hide();
            $("#full_width").show();
            $.ajax({
                url: "docs/party_description.html",
                type: "post"
            }).done(function(response) {
                $("#full_width").html(response);
                $(".party").click(function() {
                    $(".party").removeClass("selected");
                    $(this).addClass("selected");
                });
                $("#party_selection_confirmation").click(function() {
                    if ($(".party.selected").length == 0) {
                        alert("You need to select a party!");
                    } else {
                        signup_values.party = $(".party.selected").data("value");
                        nextSignupStep();
                    }
                });
            });

            break;
        case 2:
            //Stat Selection
            $("#full_width").html("");
            $.ajax({
                url: "docs/stat_selection.html",
                type: "post"
            }).done(function(response) {
                $("#full_width").html(response);
                $(".signup_stat_value_increase").click(function() {
                    if (signup_total_stat_value < 16) {
                        signup_total_stat_value++;
                        var stat_value = $(this).parents(".signup_stat").data("stat_value");
                        stat_value++;
                        $(this).parents(".signup_stat").data("stat_value", stat_value);
                        console.log("trying to update stat to: " + stat_value);
                        $(this).parents(".signup_stat").find(".signup_stat_value_display").html(stat_value);
                    } else {
                        alert("You may not increase your stats beyond a total of 16 points.");
                    }
                });
                $(".signup_stat_value_decrease").click(function() {
                    if (signup_total_stat_value >0) {
                        var stat_value = $(this).parents(".signup_stat").data("stat_value");
                        if (stat_value >0) {
                            signup_total_stat_value--;
                            stat_value--;
                            $(this).parents(".signup_stat").data("stat_value", stat_value);
                            console.log("trying to update stat to: " + stat_value);
                            $(this).parents(".signup_stat").find(".signup_stat_value_display").html(stat_value);
                        }
                    }
                });
                $("#signup_stat_continue").click(function() {
                    //check to ensure only 16 points have been spent.
                    signup_values.stats = {};
                    var total_value_check = 0;
                    $(".signup_stat").each(function() {
                        if ($(this).data("stat_value")<0) {
                            alert("A stat is now less than 0... That cannot be...");
                            return;
                        }
                        total_value_check += $(this).data("stat_value");
                        signup_values.stats[$(this).data("value")] = $(this).data("stat_value");
                    });
                    if (total_value_check != 16) {
                        alert("You have 16 points to spend and must spend them.");
                        return;
                    } else {
                        nextSignupStep();
                    }
                });
             });
            break;
        case 3:
            //Job/Class Selection
            $("#full_width").html("");
            $.ajax({
                url: "docs/job_selection.html",
                type: "post"
            }).done(function(response) {
                $("#full_width").html(response);
                $(".signup_job").click(function() {
                    $(".signup_job").removeClass("selected");
                    $(this).addClass("selected");
                });
                $("#job_selection_continue").click(function() {
                    if ($(".signup_job.selected").length != 1) {
                        alert("You must select a single job");
                    } else {
                        signup_values.job_id = $(".signup_job.selected").data("job_id");
                        nextSignupStep();
                    }
                });
            });

            break;
        case 4:
            //save character information
            $.ajax({
                url: "api/finalize_user_information.php",
                type: "post",
                data: signup_values
            }).done(function(response) {
                if (response != "") {
                    alert(response);
                }
            });

            //Tutorial - Your First scenario
            $("#full_width").html("");
            $("#full_width").hide();
            $("#left_side_small").show();
            $("#right_side_large").show();
            $.ajax({
                url: "docs/scenario_help.html",
                type: "post"
            }).done(function(response) {
                $("#left_side_small").html(response);
            });

            $.ajax({
                url: "api/get_scenario.php",
                type: "post"
            }).done(function(response) {
                $("#right_side_large").html(response);
            });


            break;
    }


}