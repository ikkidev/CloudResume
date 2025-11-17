jQuery(document).ready(function ($) {

    $('#subscribe-submit input').click(function (e) {
        e.preventDefault();

        $('#success').hide();
        $('#error').hide();

        var email = $('#subscribe-field').val();
        var nonce = $('#newfold-nonce-coming-soon-subscribe').val();

        $.ajax({
            type: 'POST',
            url: window.ajaxscript.ajax_url,
            data: {
                'action': 'newfold_coming_soon_subscribe',
                'email': email,
                'nonce': nonce
            },
            success: function (response) {
                var status = response.status;
                if (status == 'success') {
                    $('#success').show();
                } else if (status == 'active') {
                    $('#error-active').show();
                } else if (status == 'invalid_email') {
                    $('#error-invalid').show();
                } else {
                    $('#error-invalid').show();
                }
            },
        });
    });

});