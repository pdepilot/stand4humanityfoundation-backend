(function () {
    var form = document.querySelector("[data-news-form]");
    if (!form) {
        return;
    }

    var title = form.querySelector("[data-news-title]");
    var excerpt = form.querySelector("[data-news-excerpt]");
    var body = form.querySelector("[data-news-body]");
    var titleCount = form.querySelector("[data-news-title-count]");
    var excerptCount = form.querySelector("[data-news-excerpt-count]");
    var bodyCount = form.querySelector("[data-news-body-count]");

    function countWords(value) {
        var trimmed = (value || "").trim();
        if (!trimmed) {
            return 0;
        }

        return trimmed.split(/\s+/).length;
    }

    function refresh() {
        if (title && titleCount) {
            titleCount.textContent = (title.value || "").length + " chars";
        }

        if (excerpt && excerptCount) {
            excerptCount.textContent = (excerpt.value || "").length + " chars";
        }

        if (body && bodyCount) {
            var words = countWords(body.value);
            bodyCount.textContent = words + (words === 1 ? " word" : " words");
        }
    }

    ["input", "change"].forEach(function (eventName) {
        form.addEventListener(eventName, refresh);
    });

    refresh();
})();
