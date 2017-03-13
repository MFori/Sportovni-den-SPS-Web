$(document).ready(function () {

    $('#user_plainPassword').attr('disabled', true);

    $('#user_generate').change(function () {
        var password = $('#user_plainPassword');
        if (this.checked) {
            password.val('');
            password.attr('disabled', true);
        } else {
            password.attr('disabled', false);
        }
    });

    $('.table-sort').tablesorter({headers: {3: {sorter: false},4: {sorter: false},5: {sorter: false}}});

});