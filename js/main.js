var articles = {};
var purchase = {};
var countries = {};

var cookie = {
    'id': 'cookie_page',
    'value': null
};

var ENVIROMENT = 'development';
var test_url = 'http://localhost/AdBullion_WorkingTest_BACK/';

function articles_show(data) {

    $.ajax({
        url: "views/template-item.html",
        async: false,
        success: function (resp) {

            for (i = 0; i < data.length; i++) {
                $("#articles").append(resp);
                var $that = $("#articles [data-article='']");
                $($that).attr("data-article", data[i].id).prop("data-article", data[i].id);
                $("img", $that).attr("src", data[i].img);
                $(".name_article a", $that).html(data[i].nombre);
                $(".desc_article", $that).html(data[i].descripcion);
                $(".price_article", $that).html(data[i].precio);

                articles[data[i].id] = data[i];
            }
            set_events_buy();
            set_events_country();

            unsetpreload();
        }
    });

}

function set_events_buy() {
    $("#articles").on("click", ".button", function () {
        var that = this;
        var id = $(that).parents("[data-article]").eq(0).prop("data-article");
        pre_buy(id);
    });
}

function set_events_country() {
    $("#purchase_article").on("change", "[name='country']", function () {
        pre_buy();
    });
}

function pre_buy(id) {
    if (!empty(id)) {
        purchase.article = articles[id];
        $("#purchase_article [name='n_article']").val(purchase.article.nombre);
    }

    var idcountry = $("#purchase_article [name='country'] option:selected").val();

    if (empty(idcountry)) {
        $("#purchase_article [name='value']").val('');
        purchase.value_country = 0;
    } else {
        purchase.value_country = countries[idcountry].rel;
    }

    if (!empty(purchase.article) && !empty(purchase.value_country)) {
        purchase.value = parseInt(purchase.value_country) * parseInt(purchase.article.peso) + parseInt(purchase.article.precio);

        $("#purchase_article [name='value']").val(purchase.value);
    }

    //console.log(purchase);
}

function load_2(data) {

    var that = $("#purchase_article [name='country']");

    for (i = 0; i < data.length; i++) {

        $(that).append("<option value='" + data[i].id + "'>" + data[i].nombre + "</option>");
        countries[data[i].id] = data[i];
    }

    load_ajaxly(test_url + "init.php/Articles/get_articles", [], true, "#articles", articles_show, 'json');
}

function set_page() {
    cookie.value = $.cookie(cookie.id);

    if (empty(cookie.value)) {
        cookie.value = Math.random();
        $.cookie(cookie.id, cookie.value);
        cookie.value = $.cookie(cookie.id);

        send_ajaxly(test_url + "init.php/Page/page_visit",
                {referal_link: cookie.value, browser: navigator.userAgent});

        //console.log(cookie.value);
    } else {
        console.warn(cookie.value);
    }

    return cookie.value;
}

function unset_page() {
    send_ajaxly(test_url + "init.php/Page/delete_visit",
            {referal_link: cookie.value}, false);
    $.removeCookie(cookie.id);
}

function set_events_formpurchase() {
    $(document).on("submit", "#purchase_article", function () {

        var bolvalid = $(this).valid();

        if (bolvalid) {

            if (!empty(purchase.article)) {
                prepurchase_send();
            } else {
                $("#purchase_error").html("Error, no product to purchase");
                $("#purchase_error").modal();
            }

        }

        return false;
    });
}

function prepurchase_send() {
    var data_send = $("#purchase_article").serializeObject();
    data_send.article = purchase.article;
    data_send.referal_link = cookie.value;

    var send = {prepurchase: data_send};

    load_ajaxly(test_url + "init.php/Purchase/generate_prepurchase", send, true, null, show_prepurchase, 'json');

    //console.log(send);
}

function show_prepurchase(data) {
    if (!empty(data.error)) {
        console.warn(data);

    } else {
        console.log(data);

        $("#purchase_desc .client").html("<strong>Buyer</strong><div>Name: " + data.client.nombre + "</div>");
        $("#purchase_desc .client").append("<div>Surname: " + data.client.apellido + "</div>");
        $("#purchase_desc .client").append("<div>Phone: " + data.client.telefono + "</div>");
        $("#purchase_desc .client").append("<div>E-Mail: " + data.client.email + "</div>");

        $("#purchase_desc .buying").html("<hr/><strong>Article</strong><div>Name: " + data.article.nombre + "</div>");
        $("#purchase_desc .buying").append("<div>Price: " + data.article.precio + "</div>");

        $("#purchase_desc .country").html("<div>Send to: " + data.country.nombre + "</div>");

        $("#purchase_desc .price").html("<hr/><strong>Price<strong/><div>$ " + data.value + " us</div>");

        $("#purchase_desc").modal();

    }
}

// Create a new anonymous function, to use as a wrapper
(function () {

    if (ENVIROMENT !== 'development' || document.URL.indexOf("localhost") <= -1) {
        test_url = '';
    }

    set_page();

    $(window).bind('beforeunload', function () {
        unset_page();
    });

    $("#purchase_article").validate();
    load_ajaxly(test_url + "init.php/Page/get_country", [], true, "#articles", load_2, 'json');

    set_events_formpurchase();

})();