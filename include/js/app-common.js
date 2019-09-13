function formLengthCheck(str, len) {
    return $(str).val().length < len;
}

function isEmpty(str) {
    return $(str).val().length == 0;
}

function alertEmptyFormValue(target, str) {
    $(target).focus();
    alert(str);
}

function dismiss_message() {
    $('.message .close').on('click', function() {
    $(this)
        .closest('.message')
        .transition('fade');
    });
}

function filterAlphabetNumber(str) {
    return str.replace(/[^a-zA-Z0-9_]/g, '');
}

function filterSpecialChar(str) {
    return str.replace(/[^a-zA-Zㄱ-힣ㅏ-ㅣ]/g, '');
}

function isEmailAddress(str) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(str);
}