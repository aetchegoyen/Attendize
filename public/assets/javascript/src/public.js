function getAjaxFormConfig(form) {

    var $form = form;
    var $submitButton = $form.find('input[type=submit]');

    toggleSubmitDisabled($submitButton);

    var ajaxFormConf = {
        delegation: true,
        beforeSerialize: function (jqForm, options) {
            window.doSubmit = true;
            clearFormErrors(jqForm[0]);
            mirrorBuyer();
            toggleSubmitDisabled($submitButton);
        },
        beforeSubmit: function () {
            $submitButton = $form.find('input[type=submit]');
            toggleSubmitDisabled($submitButton);
            return window.doSubmit;
        },
        error: function (data, statusText, xhr, $form) {
            $submitButton = $form.find('input[type=submit]');

            // Form validation error.
            if (422 == data.status) {
                processFormErrors($form, $.parseJSON(data.responseText));
                return;
            }

            toggleSubmitDisabled($submitButton);
            showMessage(lang("whoops"));
        },
        success: function (data, statusText, xhr, $form) {
            var $submitButton = $form.find('input[type=submit]');

            if (data.message) {
                showMessage(data.message);
            }

            switch (data.status) {
                case 'success':
                    if (data.redirectUrl) {
                        if (data.redirectData) {
                            $.redirectPost(data.redirectUrl, data.redirectData);
                        } else {
                            if (data.isEmbedded) {
                                window.parent.location.href = data.redirectUrl;
                            } else {
                                document.location.href = data.redirectUrl;
                            }
                        }
                    }
                    break;
                case 'error':
                    if (data.messages) {
                        processFormErrors($form, data.messages);
                        return;
                    }
                    break;

                default:
                    break;
            }

            toggleSubmitDisabled($submitButton);

        },
        dataType: 'json'
    };

    return ajaxFormConf;
}

$(function() {

    $('form.ajax').on('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var ajaxFormConf = getAjaxFormConfig($(this));

        $(this).ajaxSubmit(ajaxFormConf);

    });

    initTimer();
    initCheckout();
    initCheckin();
    initLiveVideo();
    disableContext();
    initChat();

    //handles stripe payment form submit
    $('#stripe-payment-form').on('submit', function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();

        stripe.createToken(card).then(function (result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server.
                stripeTokenHandler(result.token);
            }
        });

    });

    function stripeTokenHandler(token) {

        var form = document.getElementById('stripe-payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        var $ajaxFormConf = getAjaxFormConfig($('#stripe-payment-form'));
        $('#stripe-payment-form').ajaxSubmit($ajaxFormConf);

    }

    $('#stripe-sca-payment-form').on('submit', function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();

        stripe.createPaymentMethod(
            'card',
            cardElement
        ).then(function (result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {

                stripePaymentMethodHandler(result.paymentMethod);
            }
        });
    });


    function stripePaymentMethodHandler(paymentMethod) {

        var form = document.getElementById('stripe-sca-payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'paymentMethod');
        hiddenInput.setAttribute('value', paymentMethod.id);
        form.appendChild(hiddenInput);

        var $ajaxFormConf = getAjaxFormConfig($('#stripe-sca-payment-form'));
        $('#stripe-sca-payment-form').ajaxSubmit($ajaxFormConf);

    }

    $('#pay_offline').change(function () {
        $('.online_payment').toggle(!this.checked);
        $('.offline_payment').toggle(this.checked);
    }).change();

    $('a').smoothScroll({
        offset: -60
    });

    /* Scroll to top */
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.totop').fadeIn();
        } else {
            $('.totop').fadeOut();
        }
    });

    $('#organiserHead').on('click', function(e) {
        e.stopImmediatePropagation();
        $('#organiser')[0].scrollIntoView();
    });

    $('#contact_organiser').on('click', function(e) {
        e.preventDefault();
        $('.contact_form').slideToggle();
    });

    $('#mirror_buyer_info').on('click', mirrorBuyer);

    $('.card-number').payment('formatCardNumber');
    $('.card-cvc').payment('formatCardCVC');

    // Apply access code here to unlock hidden tickets
    $('#apply_access_code').click(function(e) {
        var $clicked = $(this);
        // Hide any previous errors
        $clicked.closest('.form-group')
            .removeClass('has-error');

        var url = $clicked.closest('.has-access-codes').data('url');
        var data = {
            'access_code': $('#unlock_code').val(),
            '_token': $('input:hidden[name=_token]').val()
        };

        $.post(url, data, function(response) {
            if (response.status === 'error') {
                // Show any access code errors
                $clicked.closest('.form-group').addClass('has-error');
                showMessage(response.message);
                return;
            }

            $clicked.closest('.has-access-codes').before(response);
            $('#unlock_code').val('');
            $clicked.closest('.has-access-codes').remove();
        });
    });

    $('#is_business').click(function(e) {
        var $isBusiness = $(this);
        var isChecked = $isBusiness.hasClass('checked');

        if (isChecked == undefined || isChecked === false) {
            $isBusiness.addClass('checked');
            $('#business_details').removeClass('hidden').show();
        } else {
            $isBusiness.removeClass('checked');
            $('#business_details').addClass('hidden').hide();
        }
    });
});

function disableContext() {
    $("body").on("contextmenu",function(){
        return false;
    });
}

function initChat() {
    var first = true;
    var ref = "messages_" + $("#chtEid").val();
    
    if (typeof firebase == "undefined") {
        return;
    }

    // listen for incoming messages
    firebase.database().ref(ref).on("child_added", function (snapshot) {
        console.log(snapshot.val());
        var html = "";
        var className = snapshot.val().sender == $("#chtName").val() ? "own" : "";
		// give each message a unique ID
		html += "<li id='message-" + snapshot.key + "' class='"+className+"'>";
		// show delete button if message is sent by me
		// if (snapshot.val().sender == myName) {
		// 	html += "<button data-id='" + snapshot.key + "' onclick='deleteMessage(this);'>";
		// 		html += "Delete";
		// 	html += "</button>";
		// }
        html += "<p class='name'>" + snapshot.val().sender + "</p>";
        html += "<p class='message'> " + snapshot.val().message + "</p>";
        html += "<p class='time'> " + snapshot.val().time + "</p>";
		html += "</li>";

        document.getElementById("messages").innerHTML += html;
        
        scrollMsgs();
        first = false;
	});
}

function mirrorBuyer(e) {
    if (typeof e != "undefined") {
        e.preventDefault();
        e.stopPropagation();
    }
    console.log("hola");
    $('.ticket_holder_first_name').val($('#order_first_name').val());
    $('.ticket_holder_last_name').val($('#order_last_name').val());
    $('.ticket_holder_email').val($('#order_email').val());
}

function initTimer() {
    moment.locale("es"); //TODO: Fix

    $ecd = $("[data-start_date]");
    if($ecd){
        setInterval(function(){
            $("span", $ecd).html("Comienza "+moment($ecd.attr("data-start_date")).fromNow());
        },1000);
    }
}

function initCheckout() {
    //Habilitar y deshabilitar botón de checkout
    $("select[name^='ticket_']").change(function () { 
        var totalTickets = 0;
        $("select[name^='ticket_'] option:selected").each(function () { 
            var value = $(this).val();
            totalTickets += parseFloat(value) > 0 ? parseFloat(value) : 0;
        });
        $submit = $('input[type=submit]');
        if (totalTickets > 0) {
            $submit.removeAttr("disabled").removeClass("opacity-50 cursor-not-allowed ");
        } else {
            $submit.attr("disabled", "disabled").addClass("opacity-50 cursor-not-allowed ");
        }
    });
}

function initCheckin() {
    var $_messages = $('#checkInMessages');
    if ($_messages) {
        var _msgText = $_messages.text();
        var _msgClass = $_messages.attr("class");
    }

    $('#apply_check_in_code').click(function(e) {
        var $button = $(this);
        var $_accessCode = $('#access_code');
        var $_email = $('#access_email');

        var data = {
            'access_code': $_accessCode.val(),
            'email': $_email.val(),
            '_token': _token
        };

        $_messages.text(lang("loading"));
        $button.attr("disabled", "disabled").addClass("opacity-25");

        $.post(checkInRoute, data, function (response) {
            $button.removeAttr("disabled").removeClass("opacity-25");
            $_accessCode.val('');
            if (response.status === 'error') {
                $_messages.text(response.message.replace('"','')).attr("class", "text-red-700");
                setTimeout(function () { 
                    $_messages.text(_msgText).attr("class", _msgClass);
                },5000)
                return;
            }
            if (response.status === 'success') {
                location.href = response.dest;
                return;
            }
        });
    });
}

function initLiveVideo() {
    adjustAspectRatio($(".video-container"), 16, 10);
    $(window).resize(function () { adjustAspectRatio($(".video-container"), 16, 9) });

    setTimeout(function () {
        $("#vid1").css("opacity", "1");
        adjustAspectRatio($(".video-container"), 16, 10);
    }, 5000);

    $("#tfs").click(function () { toggleFullscreen($(".video-container")) });
}

function adjustAspectRatio($e, w, h) {
    $e.height($e.width() / w * h);
    scrollMsgs();
}

function scrollMsgs() {

    if (!$('#messages li:last-child').length) {
        return false;
    }

    $('#messages').parent().get(0).scrollTop = $('#messages li:last-child').position().top;
    
}

function toggleFullscreen($e) {
    if (!$e.attr("is-full")) {
        $e.attr("is-full", "0");
    }

    if ($e.attr("is-full") == "0") {
        $e.attr("is-full", "1");
        openFullscreen($e.get(0));
    } else {
        $e.attr("is-full", "0");
        closeFullscreen();
    }
}

function openFullscreen(elem) {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) { /* Firefox */
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
      elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE/Edge */
      elem.msRequestFullscreen();
    }
    
}

function closeFullscreen() {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.mozCancelFullScreen) { /* Firefox */
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { /* IE/Edge */
      document.msExitFullscreen();
    }
}

function sendMessage() {
    
    var date = new Date;
    var ref = "messages_"+$("#chtEid").val();

    firebase.database().ref(ref).push().set({
        "sender": $("#chtName").val(),
        "message": $("#chtMsg").val(),
        "key": $("#chtKey").val(),
        "time": date.getHours()+":"+date.getMinutes(),
    });

    $("#chtMsg").val("");

    scrollMsgs();

    // prevent form from submitting
    return false;
}

function processFormErrors($form, errors)
{
    var $errorsContainer = $(".formErrors");
    $errorsContainer.html("");

    $.each(errors, function (index, error)
    {
        var selector = (index.indexOf(".") >= 0) ? '.' + index.replace(/\./g, "\\.") : ':input[name=' + index + ']';
        var $input = $(selector, $form);

        if ($input.prop('type') === 'file') {
            $('#input-' + $input.prop('name')).append('<div class="help-block error">' + error + '</div>')
                .parent()
                .addClass('has-error');
        } else {
            if($input.parent().hasClass('input-group')) {
                $input = $input.parent();
            }
        }

        $errorsContainer.append($('<div class="help-block error">' + error + '</div>'));

        console.log(error, $errorsContainer);
    });

    var $submitButton = $form.find('input[type=submit]');
    toggleSubmitDisabled($submitButton);
}

/**
 * Toggle a submit button disabled/enabled
 *
 * @param element $submitButton
 * @returns void
 */
function toggleSubmitDisabled($submitButton) {

    if ($submitButton.hasClass('disabled')) {
        $submitButton.attr('disabled', false)
                .removeClass('disabled')
                .val($submitButton.data('original-text'));
        return;
    }

    $submitButton.data('original-text', $submitButton.val())
            .attr('disabled', true)
            .addClass('disabled')
            .val(lang("processing"));
}

/**
 * Clears given form of any error classes / messages
 *
 * @param {Element} $form
 * @returns {void}
 */
function clearFormErrors($form) {
    $($form).find(".formErrors").html("");
    $($form)
            .find('.error.help-block')
            .remove();
    $($form).find(':input')
            .parent()
            .removeClass('has-error');
    $($form).find(':input')
            .parent().parent()
            .removeClass('has-error');
            
}

function showFormError($formElement, message) {
    $formElement.after('<div class="help-block error">' + message + '</div>')
            .parent()
            .addClass('has-error');
}

/**
 * Shows users a message.
 * Currently uses humane.js
 *
 * @param string message
 * @returns void
 */
function showMessage(message) {
    humane.log(message, {
        timeoutAfterMove: 3000,
        waitForMove: true
    });
}

function hideMessage() {
    humane.remove();
}

/**
 * Counts down to the given number of seconds
 *
 * @param element $element
 * @param int seconds
 * @returns void
 */
function setCountdown($element, seconds) {

    var endTime, mins, msLeft, time, twoMinWarningShown = false;

    function twoDigits(n) {
        return (n <= 9 ? "0" + n : n);
    }

    function updateTimer() {
        msLeft = endTime - (+new Date);
        if (msLeft < 1000) {
            alert(lang("time_run_out"));
            location.reload();
        } else {

            if (msLeft < 120000 && !twoMinWarningShown) {
                showMessage(lang("just_2_minutes"));
                twoMinWarningShown = true;
            }

            time = new Date(msLeft);
            mins = time.getUTCMinutes();
            $element.html('<b>' + mins + ':' + twoDigits(time.getUTCSeconds()) + '</b>');
            setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
        }
    }

    endTime = (+new Date) + 1000 * seconds + 500;
    updateTimer();
}

$.extend(
    {
        redirectPost: function(location, args)
        {
            var form = '';
            $.each( args, function( key, value ) {
                value = value.split('"').join('\"')
                form += '<input type="hidden" name="'+key+'" value="'+value+'">';
            });
            $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
        }
    });

/*!
 * Smooth Scroll - v1.4.13 - 2013-11-02
 * https://github.com/kswedberg/jquery-smooth-scroll
 * Copyright (c) 2013 Karl Swedberg
 * Licensed MIT (https://github.com/kswedberg/jquery-smooth-scroll/blob/master/LICENSE-MIT)
 */
(function(t) {
    function e(t) {
        return t.replace(/(:|\.)/g, "\\$1")
    }
    var l = "1.4.13", o = {}, s = {exclude: [], excludeWithin: [], offset: 0, direction: "top", scrollElement: null, scrollTarget: null, beforeScroll: function() {
        }, afterScroll: function() {
        }, easing: "swing", speed: 400, autoCoefficent: 2, preventDefault: !0}, n = function(e) {
        var l = [], o = !1, s = e.dir && "left" == e.dir ? "scrollLeft" : "scrollTop";
        return this.each(function() {
            if (this != document && this != window) {
                var e = t(this);
                e[s]() > 0 ? l.push(this) : (e[s](1), o = e[s]() > 0, o && l.push(this), e[s](0))
            }
        }), l.length || this.each(function() {
            "BODY" === this.nodeName && (l = [this])
        }), "first" === e.el && l.length > 1 && (l = [l[0]]), l
    };
    t.fn.extend({scrollable: function(t) {
            var e = n.call(this, {dir: t});
            return this.pushStack(e)
        }, firstScrollable: function(t) {
            var e = n.call(this, {el: "first", dir: t});
            return this.pushStack(e)
        }, smoothScroll: function(l, o) {
            if (l = l || {}, "options" === l)
                return o ? this.each(function() {
                    var e = t(this), l = t.extend(e.data("ssOpts") || {}, o);
                    t(this).data("ssOpts", l)
                }) : this.first().data("ssOpts");
            var s = t.extend({}, t.fn.smoothScroll.defaults, l), n = t.smoothScroll.filterPath(location.pathname);
            return this.unbind("click.smoothscroll").bind("click.smoothscroll", function(l) {
                var o = this, r = t(this), i = t.extend({}, s, r.data("ssOpts") || {}), c = s.exclude, a = i.excludeWithin, f = 0, h = 0, u = !0, d = {}, p = location.hostname === o.hostname || !o.hostname, m = i.scrollTarget || (t.smoothScroll.filterPath(o.pathname) || n) === n, S = e(o.hash);
                if (i.scrollTarget || p && m && S) {
                    for (; u && c.length > f; )
                        r.is(e(c[f++])) && (u = !1);
                    for (; u && a.length > h; )
                        r.closest(a[h++]).length && (u = !1)
                } else
                    u = !1;
                u && (i.preventDefault && l.preventDefault(), t.extend(d, i, {scrollTarget: i.scrollTarget || S, link: o}), t.smoothScroll(d))
            }), this
        }}), t.smoothScroll = function(e, l) {
        if ("options" === e && "object" == typeof l)
            return t.extend(o, l);
        var s, n, r, i, c = 0, a = "offset", f = "scrollTop", h = {}, u = {};
        "number" == typeof e ? (s = t.extend({link: null}, t.fn.smoothScroll.defaults, o), r = e) : (s = t.extend({link: null}, t.fn.smoothScroll.defaults, e || {}, o), s.scrollElement && (a = "position", "static" == s.scrollElement.css("position") && s.scrollElement.css("position", "relative"))), f = "left" == s.direction ? "scrollLeft" : f, s.scrollElement ? (n = s.scrollElement, /^(?:HTML|BODY)$/.test(n[0].nodeName) || (c = n[f]())) : n = t("html, body").firstScrollable(s.direction), s.beforeScroll.call(n, s), r = "number" == typeof e ? e : l || t(s.scrollTarget)[a]() && t(s.scrollTarget)[a]()[s.direction] || 0, h[f] = r + c + s.offset, i = s.speed, "auto" === i && (i = h[f] || n.scrollTop(), i /= s.autoCoefficent), u = {duration: i, easing: s.easing, complete: function() {
                s.afterScroll.call(s.link, s)
            }}, s.step && (u.step = s.step), n.length ? n.stop().animate(h, u) : s.afterScroll.call(s.link, s)
    }, t.smoothScroll.version = l, t.smoothScroll.filterPath = function(t) {
        return t.replace(/^\//, "").replace(/(?:index|default).[a-zA-Z]{3,4}$/, "").replace(/\/$/, "")
    }, t.fn.smoothScroll.defaults = s
})(jQuery);
