$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.btn-vote').attr('disabled', true);
    $('.poll-option').on('click', function() {
        $('.btn-vote').attr('disabled', !($('.poll-option').is(':checked')));
    });

    $('.btn-vote').on('click', function() {
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        divChangeAmount = $(this).parent();
        var url = divChangeAmount.data('url');
        var isRequiredEmail = divChangeAmount.data('isRequiredEmail');
        var nameVote = $('.nameVote').val();
        var emailVote = $('.emailVote').val();

        if (isRequiredEmail == 0) {
            if (emailVote != '') {
                if (testEmail.test(emailVote)) {
                    this.disabled = true;
                    $('.message-validation').html('');
                    $('#form-vote').submit();
                } else {
                    divChangeAmount = $(this).parent();
                    var message = divChangeAmount.data('messageValidateEmail');
                    $('.message-validation').html(message);
                }
            } else {
                this.disabled = true;
                $('.message-validation').html('');
                $('#form-vote').submit();
            }
        } else {
            if (emailVote != '') {
                if (testEmail.test(emailVote)) {
                    this.disabled = true;
                    $('.message-validation').html('');
                    $('#form-vote').submit();
                } else {
                    divChangeAmount = $(this).parent();
                    var message = divChangeAmount.data('messageValidateEmail');
                    $('.message-validation').html(message);
                }
            } else {
                divChangeAmount = $(this).parent();
                var message = divChangeAmount.data('messageRequiredEmail');
                $('.message-validation').html(message);
            }
        }
    });
});
