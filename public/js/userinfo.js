$("settingsContainer").click(function () {
if ($("settingsRow").is(":hidden")) {
$("settingsRow").slideDown("slow");
} else {
$("settingsRow").hide();
}
});

$(document).ready(function() { 
    $("input:text,input:checkbox,input:radio,textarea,select").one("change", function() { 
        window.onbeforeunload = function() { return 'You will lose data changes.'; } 
    }); 
    $('.noWarn').click(function() { window.onbeforeunload = null; }); 
}); 