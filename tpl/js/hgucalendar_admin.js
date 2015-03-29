
/* 모듈 생성 후 */
function completeAdminInsertHgucalendar(ret_obj) {
    var error = ret_obj['error'];
    var message = ret_obj['message'];
    var page = ret_obj['page'];
    var module_srl = ret_obj['module_srl'];

    alert(message);

    var url = current_url.setQuery('act','dispHgucalendarAdminShow').setQuery('module_srl','');
    if(page) url.setQuery('page',page);
    location.href = url;
}

/* 모듈 삭제 후 */
function completeAdminDeleteBook(ret_obj) {
    var error = ret_obj['error'];
    var message = ret_obj['message'];
    var page = ret_obj['page'];
    alert(message);

    var url = current_url.setQuery('act','dispBookAdminList').setQuery('module_srl','');
    if(page) url = url.setQuery('page',page);
    location.href = url;
}
