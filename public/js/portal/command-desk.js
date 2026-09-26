(function () {
    "use strict";

    var clock = document.querySelector("[data-clock]");
    var greeting = document.querySelector("[data-greeting]");
    var rule = document.querySelector("[data-overview-rule]");
    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (clock) {
        var tick = function () {
            clock.textContent = new Date().toLocaleTimeString("en-GB", {
                timeZone: "Africa/Lagos",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: false
            });
        };
        tick();
        window.setInterval(tick, 1000);
    }

    if (greeting) {
        var name = greeting.getAttribute("data-name");
        if (name) {
            var hour = Number(new Date().toLocaleString("en-GB", {
                timeZone: "Africa/Lagos",
                hour: "numeric",
                hour12: false
            }));
            var part = hour < 12 ? "morning" : hour < 16 ? "afternoon" : "evening";
            greeting.textContent = "Good " + part + ", " + name + ".";
        }
    }

    document.querySelectorAll("[data-count]").forEach(function (node) {
        var target = Number(node.getAttribute("data-count"));
        if (!isFinite(target)) {
            return;
        }
        if (reduceMotion) {
            node.textContent = String(target);
            return;
        }
        var start = performance.now();
        var step = function (now) {
            var t = Math.min(1, (now - start) / 1100);
            var eased = 1 - Math.pow(1 - t, 3);
            node.textContent = String(Math.round(target * eased));
            if (t < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    });

    if (rule && !reduceMotion) {
        window.addEventListener("mousemove", function (event) {
            rule.style.transform = "translateY(" + event.clientY + "px)";
        }, { passive: true });
    }
})();
