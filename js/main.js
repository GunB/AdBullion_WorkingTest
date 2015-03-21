var articles = {};
var purchase = {};
var countries = {};

var ENVIROMENT = 'development';
var test_url = 'http://localhost/BACK/';

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

    purchase.value_country = $("#purchase_article [name='country'] option:selected").val();

    if (empty(purchase.value_country)) {
        $("#purchase_article [name='value']").val('');
    }

    if (!empty(purchase.article) && !empty(purchase.value_country)) {
        purchase.value = purchase.value_country * purchase.article.peso;
        purchase.value += purchase.article.precio;

        $("#purchase_article [name='value']").val(purchase.value);
    }

    console.log(purchase);
}

function load_2(data) {

    var that = $("#purchase_article [name='country']");

    for (i = 0; i < data.length; i++) {

        $(that).append("<option value='" + data[i].id + "'>" + data[i].nombre + "</option>");
        countries[data[i].id] = data[i];
    }

    load_ajaxly(test_url+"init.php/Articles/get_articles", ["data", "bb"], true, "#articles", articles_show);
}

// Create a new anonymous function, to use as a wrapper
(function () {
    
    if(ENVIROMENT !== 'development' || document.URL.indexOf("localhost") <= -1){
        test_url = '';
    }
    
    $("#purchase_article").validate();
    load_ajaxly(test_url+"init.php/Page/get_country", [], true, "#articles", load_2);

})();