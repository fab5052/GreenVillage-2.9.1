$("select").each(function () {
    var $this = $(this),
        $options = $(this).children("option").length;

    $this.addClass("select-hidden");
    $this.wrap("<div class='select'></div>");
    $this.after("<div class='select-styled'></div>");

    var $styledSelect = $this.next("div.select-styled");
    $styledSelect.text($this.children("option").eq(0).text());

    var $list = $("<ul />", {
        "class": "select-options"
    }).insertAfter($styledSelect);

    for (var i = 0; i < $options; i++) {
        $("<li />", {
            text: $this.children("option").eq(i).text(),
            rel: $this.children("option").eq(i).val()
        }).appendTo($list);
    }

    var $listItems = $list.children("li");

    $styledSelect.on("click", function (e) {
        e.stopPropagation();
        $("div.select-styled.active").each(function () {
            $(this).removeClass("active").next("ul.select-options").hide();
        });

        $(this).toggleClass("active").next("ul.select-options").toggle();
    });

    $listItems.on("click", function (e) {
        e.stopPropagation();
        $styledSelect.text($(this).text()).removeClass("active");
        $this.val($(this).attr("rel"));
        $list.hide();
    });

    $(document).on("click", function () {
        $styledSelect.removeClass("active");
        $list.hide();
    });

    $(".select-sibling").next(".select-styled").css({
        "border-top": "0px"
    });
});

(function () {
    var $addItem = $("#add-item");
    var $badge = $(".badge");
    var $count = 1;

    $addItem.on("click", function (e) {
        e.preventDefault();
        $badge.html($count++);
    });
}.call(this));