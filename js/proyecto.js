/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$.isBlank = function (string) {
    return(!string || $.trim(string) === "");
};

function empty(mixed_var) {
    //  discuss at: http://phpjs.org/functions/empty/
    // original by: Philippe Baumann
    //    input by: Onno Marsman
    //    input by: LH
    //    input by: Stoyan Kyosev (http://www.svest.org/)
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Onno Marsman
    // improved by: Francesco
    // improved by: Marc Jansen
    // improved by: Rafal Kukawski
    //   example 1: empty(null);
    //   returns 1: true
    //   example 2: empty(undefined);
    //   returns 2: true
    //   example 3: empty([]);
    //   returns 3: true
    //   example 4: empty({});
    //   returns 4: true
    //   example 5: empty({'aFunc' : function () { alert('humpty'); } });
    //   returns 5: false

    var undef, key, i, len;
    var emptyValues = [undef, null, false, 0, '', '0'];

    for (i = 0, len = emptyValues.length; i < len; i++) {
        if (mixed_var === emptyValues[i]) {
            return true;
        }
    }

    if (typeof mixed_var === 'object') {
        for (key in mixed_var) {
            // TODO: should we check for own properties only?
            //if (mixed_var.hasOwnProperty(key)) {
            return false;
            //}
        }
        return true;
    }

    return false;
}

unsetpreload = function () {
    //$('#waiting4').waiting('destroy');
};

jQuery.fn.exists = function () {
    return this.length > 0;
};

preload = function () {
    /*if (!$("#waiting4").exists()) {
     $('body').append('<div><div id="waiting4"><center>Cargando</center></div></div>');
     }
     $('#waiting4').waiting({
     className: 'waiting-circles',
     elements: 8,
     radius: 30,
     auto: true
     });*/
};

function send_ajaxly(url, data, async, redirect) {
    if (typeof async === 'undefined') {
        async = true;
    }

    //console.log(async);
    //console.log(redirect);

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        timeout: 3000,
        async: async,
        //dataType: 'json',
        beforeSend: function () {
            preload();
        },
        complete: function () {
            unsetpreload();
            if (typeof redirect !== 'undefined') {
                window.location = redirect;
            }
        },
        cache: false,
        success: function (data, textStatus, jqXHR) {
            if (ENVIROMENT === 'development') {
                $('#console').html(data);
            }
            return data;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //location.reload();
            $('#console').html(jqXHR.responseText);
            alert(textStatus + '\n4Un error ocurrió, porfavor, intentalo denuevo \n' + errorThrown);
            //alert(jqXHR.responseText);
            //next = false;
        }

    });
}

function load_ajaxly(url, data, async, selector, funct) {
    if (typeof async === 'undefined') {
        async = true;
    }

    //console.log(async);

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        timeout: 3000,
        dataType: 'json',
        async: async,
        beforeSend: function () {
            preload();
        },
        complete: function () {
            unsetpreload();
        },
        cache: false,
        success: function (data, textStatus, jqXHR) {
            if (typeof funct !== 'undefined') {
                funct(data);
            } else {
                $(selector).html(data);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            //location.reload();
            $('#console').html(jqXHR.responseText);
            alert(textStatus + '\n5Un error ocurrió, porfavor, intentalo denuevo \n' + errorThrown);
            //next = false;
        }

    });
}


$(document).ready(function () {
    $(".send-ajax").each(function () {
        var that = this;
        $(document).on("submit", $(that).selector, function (evt) {
            if (!$(that).prop('ready')) {
                evt.preventDefault();
                var $valid = $(that).valid();
                if (!$valid) {
                    return false;
                } else {
                    preload();
                    $.ajax({
                        type: 'post',
                        url: $(that).prop('verify'),
                        data: $(that).serializeArray(),
                        async: true,
                        dataType: 'json',
                        success: function (data, textStatus, jqXHR) {

                            if (data.status) {
                                console.log($(this));
                                var texto = 'Acción realizada correctamente.';
                                if (typeof data.mensaje !== 'undefined') {
                                    texto = data.mensaje;
                                }
                                var n = noty({text: texto,
                                    layout: 'topLeft',
                                    theme: 'defaultTheme',
                                    type: 'success',
                                    timeout: 5000});
                                $(that).prop('ready', true);
                                $(that).attr('ready', 'true');
                                $(that).submit();

                                //$().redirect($(that).attr('action'), $(that).serializeArray());
                            } else {
                                var texto = 'No es posible realizar esta acción.';
                                if (typeof data.mensaje !== 'undefined') {
                                    texto = data.mensaje;
                                }
                                var n = noty({text: texto,
                                    layout: 'topLeft',
                                    theme: 'defaultTheme',
                                    type: 'error',
                                    timeout: 5000});
                            }
                            unsetpreload();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            //location.reload();
                            console.log(this);
                            unsetpreload();
                            $('body').html(jqXHR.responseText);
                            alert('7Un error ocurrió, porfavor, intentalo denuevo \n' + errorThrown);
                            //next = false;
                        }
                    });//*/
                }
            }
        });
    });
});
