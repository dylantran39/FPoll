//autoload location
if (document.getElementById('location') != null) {
    google.maps.event.addDomListener(window, 'load', function () {
        var places = new google.maps.places.Autocomplete(document.getElementById('location'));
        google.maps.event.addListener(places, 'place_changed', function () {

        });
    });
}


var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
};

/*
 * get data from server
 */
var pollData = $('.hide').data("poll");
var dataAction = $('.hide').data("action");
var dataSettingEdit = $('.hide').data("settingEdit");

/**--------------------------------------------------------------
-                         USER CREATE POLL                      -
 ---------------------------------------------------------------*/
$(document).ready(function () {

    $(".finish").click(function () {
        if (validateParticipant()) {
            $('#form_create_poll').submit();
        }
    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}

$(window).on('load', function() {
    if (typeof pollData !== "undefined") {
        var oldInput = pollData.oldInput;
        var viewOption = pollData.view.option;
        var number = pollData.config.length.option;
        createOption(viewOption, number, oldInput);

        if (oldInput) {
            $('#email-participant').show();
        }
    }
});

// Create option
function createOption(viewOption, number, oldInput) {
    number = (typeof number === 'undefined') ? 1 : number;

    if (oldInput != null) {
        var oldOption = oldInput.optionText;
        jQuery.each( oldOption, function( id, val ) {
            var option = "";
            option = viewOption.replace(/idOption/g, id);
            $('.poll-option').append(option);
            $('#content-option-' + id).val(val);
        });
    } else {
        for (var i = 0; i < number; i++) {
            var id = rand();
            var option = "";
            option = viewOption.replace(/idOption/g, id);
            $('.poll-option').append(option);
        }
    }
}


//Show option image
function showOptionImage(idOption) {
    $('input[name = "optionImage[' + idOption + ']"]').click();
}

// Preview image
function readURL(input, idShow) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + idShow).show().attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

//remove option
function removeOpion(idOption, action) {
    if (typeof pollData !== "undefined" && typeof action !== "undefined") {
        console.log(pollData.message.confirm_delete_option);
        if (confirmDelete(pollData.message.confirm_delete_option)) {
            $("#" + idOption).remove();
        }
    } else {
        $("#" + idOption).remove();
    }

}

//add option
function addOption(data) {
    var number = $('#number').val();
    var view = data.view.option;
    number = (typeof number == 'undefined' || number == "") ? 1 : number;
    createOption(view, number);
}

//random name of option
var rand = function() {
    return Math.random().toString(36).substr(2); // remove `0.`
};

//show advance setting: custom link, set limit, set password
function settingAdvance(key) {
    if (key == pollData.config.setting.custom_link) {
        $("#setting-link").slideToggle();
    } else if (key == pollData.config.setting.set_limit) {
        $("#setting-limit").slideToggle();
    } else if (key == pollData.config.setting.set_password) {
        $("#setting-password").slideToggle();
    }
}


function validateOption() {
    var optionLists = $('input[name^="optionText"]');
    var imageLists = $('input[name^="optionImage"]');
    var isOption = false;
    $('#validateOption').html("");

    if (typeof dataAction !== "undefined") {
        if (dataAction == "edit") {
            if ($('.old-option').text().trim() !== "") {
                return true;
            }
        }
    }

    if (optionLists.length == 0 && imageLists.length == 0) {
        $('.error_option').closest('.form-group').addClass('has-error');
        $('.error_option').html('<span id="title-error" class="help-block">' + pollData.message.option_empty + '</span>');
        return false;
    }

    optionLists.each(function (key) {
        if ($(this).val() != "") {
            isOption = true;
        } else {
            imageLists.each(function () {
                if ($(this).val() != "") {
                    isOption = true;
                }
            });
        }
    });

    if (!isOption) {
        $('.error_option').closest('.form-group').addClass('has-error');
        $('.error_option').html('<span id="title-error" class="help-block">' + pollData.message.option_required + '</span>');
        return false;
    }

    return true;
}

function validateParticipant() {
    var members = $("#member").val();

    if (members == "") {
        $('.error_participant').closest('.form-group').removeClass('has-error');
        return true;
    }

    members = members.split(",");

    for (var index = 0; index < members.length; index++) {
        if (! validateEmail(members[index])) {
            $('.error_participant').closest('.form-group').addClass('has-error');
            $('.error_participant').html('<span id="title-error" class="help-block">' + pollData.message.email + '</span>');
            return false;
        }
    }

    $('.error_participant').closest('.form-group').removeClass('has-error');
    return true;

}


//check token of link exist
function checkLink(route, token) {
    if (typeof pollData !== "undefined") {
        return $.ajax({
            url: route,
            type: 'post',
            async: false,
            dataType: 'json',
            data: {
                'value': $('#link').val(),
                '_token': token,
            },
            success: function (data) {
                if (data.success) {
                    //link exists
                    $("#link").addClass("error");
                    $('.link-error').html("<div class='label label-danger'>" + dataPage.message.validate.link_exists + "</div>");
                } else {
                    $("#link").removeClass("error");
                    $('.link-error').html("<div class='alert alert-success'>" + dataPage.message.validate.link_valid + "</div>");
                }
            }
        });
    }
}

//Auto close message
$(".alert-dismissable").delay(3000).fadeOut(100);

//Datetime picker
$(function () {
    $('#time_close_poll').datetimepicker({
        format: 'DD-MM-YYYY HH:mm'
    });
});

function showOptionDetail() {
    $('#option-detail').slideToggle();
}

function showSettingDetail() {
    $('#setting-detail').slideToggle();
}

function confirmDelete(message) {
    return confirm(message);
}

//validate email
function validateEmailExists() {

    return $.ajax({
        url: $('.hide').data("routeEmail"),
        type: 'post',
        async: false,
        dataType: 'json',
        data: {
            'email': $('#email').val(),
            '_token': $('.hide').data("token")
        },
        success: function (data) {
            if (! data.success) {
                $('#email').closest('.form-group').addClass('has-error');
                $('.error_email').closest('.form-group').addClass('has-error');
                $('.error_email').html('<span id="title-error" class="help-block">' + pollData.message.email_exist + '</span>');
            }
        }
    });

}

/* jQuery Validate Emails with Regex */
function validateEmail(email) {
    var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return $.trim(email).match(pattern) ? true : false;
}

//validate link
function validateLink(token) {
    return $.ajax({
        url: $('.hide').data("routeLink"),
        type: 'post',
        async: false,
        dataType: 'json',
        data: {
            'token': token,
            '_token': $('.hide').data("token")
        },
        success: function (data) {

        }
    });
}

//add method validate time
if (typeof pollData !== "undefined") {
    jQuery.validator.addMethod("time", function(value, element) {
        if (value) {
            var currentDate = new Date();
            var current = moment(currentDate.toISOString()).format('DD-MM-YYYY HH:mm');
            var timeClosedPoll = $('#time_close_poll').val();

            return (current < timeClosedPoll)
        }

        return true;
    }, pollData.message.time_close_poll);
}


$(document).ready(function() {
    if (typeof pollData !== "undefined") {
        $.each(pollData.config.setting, function (index, value) {
            $("[name='setting\\[" + value + "\\]']").bootstrapSwitch({
                'onText' : pollData.message.on,
                'offText' : pollData.message.off
            });
        });

        $('[data-toggle="tooltip"]').tooltip();
        var $validator = $("#form_create_poll").validate({
            rules: {
                email: {
                    required: true,
                    maxlength: pollData.config.length.email,
                    email: true,
                },
                name: {
                    required: true,
                    maxlength: pollData.config.length.name
                },
                title: {
                    required: true,
                    maxlength: pollData.config.length.title
                },
                closingTime: {
                    time:true
                },
                member: {
                    participant:true
                }
            },
            messages: {
                email: {
                    required: pollData.message.required,
                    maxlength: pollData.message.max + pollData.config.length.email,
                    email: pollData.message.email,
                    // exist: pollData.message.email_exist
                },
                name: {
                    required: pollData.message.required,
                    maxlength: pollData.message.max + pollData.config.length.name
                },
                title: {
                    required: pollData.message.required,
                    maxlength: pollData.message.max +pollData.config.length.title
                },
                closingTime: {
                    time: pollData.message.time_close_poll
                }
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });


        $('#create_poll_wizard').bootstrapWizard({
            'tabClass': 'nav nav-pills',
            onNext: function(tab, navigation, index) {

                //get index of tab current
                var wizard = $('#create_poll_wizard').bootstrapWizard('currentIndex');

                //get validation of form
                var valid = $("#form_create_poll").valid();

                //check if form valid, it will be return TRUE
                if(! valid) {
                    $validator.focusInvalid();
                    return false;
                }

                //check option of poll
                if (wizard == 0) {
                    return validateEmailExists().responseJSON.success;
                } else if (wizard == 1) {
                    return validateOption();
                } else if (wizard == 2) {
                    var isValid = true;

                    $('input[name^="setting"]:checked').each(function () {
                        if ($(this).val() == pollData.config.setting.custom_link) {
                            isValid = checkLink();
                        } else if ($(this).val() == pollData.config.setting.set_limit) {
                            isValid = checkLimit();
                        } else if ($(this).val() == pollData.config.setting.set_password) {
                            isValid = checkPassword();
                        }
                    });

                    return isValid;
                }
            },
            onTabClick: function(tab, navigation, index) {
                return false;
            }
        });
    }

    $('#voting_wizard').bootstrapWizard({
        'tabClass': 'nav nav-pills'
    });
    $('#manager_poll_wizard').bootstrapWizard({
        'tabClass': 'nav nav-pills'
    });
    $('#edit_poll_wizard').bootstrapWizard({
        'tabClass': 'nav nav-pills'
    });
    $('#duplicate_poll_wizard').bootstrapWizard({
        'tabClass': 'nav nav-pills',
        onTabClick: function(tab, navigation, index) {
            return false;
        }
    });
});

/*
 show password
 */
function showAndHidePassword() {
    if($('#password').attr("type") == "password"){
        $('#password').attr("type", "text");
    } else {
        $('#password').attr("type", "password");
    }
}

/*
validate link of poll
 */
function checkLink() {
    var token = $('#link').val();

    if (token == "") {
        $('#link').closest('.form-group').addClass('has-error');
        $('.error_link').closest('.form-group').addClass('has-error');
        $('.error_link').html('<span id="title-error" class="help-block">' + pollData.message.required + '</span>');
        return false;
    }

    if (validateLink($('#link').val()).responseJSON.success) {
        $('#link').closest('.form-group').addClass('has-error');
        $('.error_link').closest('.form-group').addClass('has-error');
        $('.error_link').html('<span id="title-error" class="help-block">' + pollData.message.link_exists + '</span>');
        return false;
    }

    $('#link').closest('.form-group').removeClass('has-error');
    $('.error_link').closest('.form-group').removeClass('has-error');
    $('.error_link').html('<span id="title-success" class="help-block">' + pollData.message.link_valid + '</span>');
    return true;
}

/*
validate limit of poll
 */
function checkLimit() {
    var limit = $('#limit').val();

    if (limit == "") {
        $('#limit').closest('.form-group').addClass('has-error');
        $('.error_limit').closest('.form-group').addClass('has-error');
        $('.error_limit').html('<span id="title-error" class="help-block">' + pollData.message.required + '</span>');
        return false;
    }

    if (! Number.isInteger(limit) && ! (limit > 0)) {
        $('#limit').closest('.form-group').addClass('has-error');
        $('.error_limit').closest('.form-group').addClass('has-error');
        $('.error_limit').html('<span id="title-error" class="help-block">' + pollData.message.number + '</span>');
        return false;
    }

    $('#link').closest('.form-group').removeClass('has-error');
    $('.error_link').closest('.form-group').removeClass('has-error');
    return true;
}

/*
 validate password of poll
 */
function checkPassword() {
    var password = $('#password').val();

    if (password == "") {
        $('#password').closest('.form-group').addClass('has-error');
        $('.error_password').closest('.form-group').addClass('has-error');
        $('.error_password').html('<span id="title-error" class="help-block">' + pollData.message.required + '</span>');
        return false;
    }

    $('#password').closest('.form-group').removeClass('has-error');
    $('.error_password').closest('.form-group').removeClass('has-error');
    return true;
}

/*
show modal preview option image in view details
 */
function showModelImage(image) {
    $('#imageOfOptionPreview').attr("src", image);
    $('#modalImageOption').modal('show');
}

/*
show panel body of poll option horizontal in view details
 */
function showPanelImage(id) {
    $('#option_' + id).toggle('slow');
}

/*
check if mail exist, get name
 */
function checkMailExitsDatabase() {
    $.ajax({
        url: $('.hide').data("routeEmail"),
        type: 'post',
        data: {
            'email': $('#email').val(),
            'action': 'check_exists',
            '_token': $('.hide').data("token")
        },
        success: function (data) {
            if (data.success) {
                $('#email').closest('.form-group').removeClass('has-error');
                $('.error_email').closest('.form-group').removeClass('has-error');
                $('.error_email').html('<span id="title-warning" class="help-block">' + pollData.message.email_exist_database + '</span>');
                $('#name').val(data.name);
                $('#name').prop('readonly', true);
            }
        }
    });
}

function changeLinkAdmin() {
    $('#label_link_admin').html(pollData.config.link + $('#link_admin').val());
    if (validateLink($('#link_admin').val()).responseJSON.success) {
        $('#link_admin').closest('.form-group').addClass('has-error');
        $('.error_link_admin').closest('.form-group').addClass('has-error');
        $('.error_link_admin').html('<span id="title-error" class="help-block">' + pollData.message.link_exists + '</span>');
    } else {
        $('#link_admin').closest('.form-group').removeClass('has-error');
        $('.error_link_admin').closest('.form-group').removeClass('has-error');
        $('.error_link_admin').html('<span id="title-success" class="help-block">' + pollData.message.link_valid + '</span>');
    }
}
