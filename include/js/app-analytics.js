function setValueForStep(stepNo, valueID, name, icon) {
    $("#step-"+stepNo).find(".description").addClass("hidden");
    $("#step-"+stepNo+"-form-value").val(valueID);
    $("#step-"+stepNo).find(".selected-value").html("<i class='icon "+icon+"'></i>" + name);
}

function completeStep(stepNo) {
    $('#step-'+stepNo).addClass('completed');
    $('#step-'+stepNo).removeClass('active');
    $('#step-'+stepNo+'-panel').transition('fade down');
    getUIContent(stepNo+1);
}

function getUIContent(stepNo) {
    if (stepNo != 1)
        $('#step-'+stepNo+'-panel').transition('zoom');
    $('#step-'+stepNo+'-panel').removeClass('hidden');
    $('#ui-title').html($('#step-'+stepNo).find('.title').text());
    $('#ui-message').html($('#step-'+stepNo).find('.message').text());
}