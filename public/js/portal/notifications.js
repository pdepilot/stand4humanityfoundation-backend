(function () {
    "use strict";

    var root = document.querySelector("[data-desk-bell]");
    if (!root) {
        return;
    }

    var button = root.querySelector(".desk-bell-button");
    var badge = root.querySelector("[data-bell-count]");
    var list = root.querySelector("[data-bell-list]");
    var panel = root.querySelector("#deskBellPanel");
    var hint = root.querySelector("[data-bell-hint]");
    var clearBtn = root.querySelector("[data-bell-clear]");
    var feedUrl = root.getAttribute("data-feed");
    var readAllUrl = root.getAttribute("data-read-all");
    var readOneBase = root.getAttribute("data-read-one");
    var seenKey = "sfhf_desk_seen_alerts";
    var armedKey = "sfhf_desk_alert_armed";
    var armed = false;
    var audioCtx = null;
    var pollMs = 2500;
    var alarmPlaying = false;

    function csrfToken() {
        var field = document.querySelector('form.rail-exit-form input[name="_token"]');
        return field ? field.value : "";
    }

    function seenIds() {
        try {
            var raw = sessionStorage.getItem(seenKey);
            return raw ? JSON.parse(raw) : [];
        } catch (err) {
            return [];
        }
    }

    function storeSeen(ids) {
        try {
            sessionStorage.setItem(seenKey, JSON.stringify(ids.slice(-80)));
        } catch (err2) {}
    }

    function remember(ids) {
        var seen = seenIds();
        ids.forEach(function (id) {
            if (seen.indexOf(id) === -1) {
                seen.push(id);
            }
        });
        storeSeen(seen);
        return seen;
    }

    function wasArmed() {
        try {
            return sessionStorage.getItem(armedKey) === "1";
        } catch (err3) {
            return false;
        }
    }

    function markArmed() {
        armed = true;
        try {
            sessionStorage.setItem(armedKey, "1");
        } catch (err4) {}
        if (hint) {
            hint.hidden = true;
        }
    }

    function armAudio() {
        var Ctx = window.AudioContext || window.webkitAudioContext;
        if (!Ctx) {
            return;
        }
        audioCtx = audioCtx || new Ctx();
        if (audioCtx.state === "suspended") {
            audioCtx.resume();
        }
        markArmed();
    }

    function playSeriousAlarm() {
        if (!audioCtx || alarmPlaying) {
            return;
        }
        if (audioCtx.state === "suspended") {
            audioCtx.resume();
        }

        alarmPlaying = true;
        var now = audioCtx.currentTime;
        var duration = 6.2;
        var master = audioCtx.createGain();
        master.gain.setValueAtTime(0.0001, now);
        master.gain.exponentialRampToValueAtTime(0.72, now + 0.04);
        master.gain.setValueAtTime(0.72, now + duration - 0.35);
        master.gain.exponentialRampToValueAtTime(0.0001, now + duration);
        master.connect(audioCtx.destination);

        [0, 0.12, 0.24].forEach(function (offset, index) {
            var osc = audioCtx.createOscillator();
            var gain = audioCtx.createGain();
            osc.type = index === 0 ? "sawtooth" : index === 1 ? "square" : "triangle";
            for (var beat = 0; beat < 16; beat += 1) {
                var t = now + offset + beat * 0.36;
                var high = beat % 3 === 0 ? 980 : 760;
                var low = beat % 3 === 0 ? 310 : 420;
                osc.frequency.setValueAtTime(beat % 2 === 0 ? high : low, t);
            }
            gain.gain.setValueAtTime(index === 0 ? 0.42 : index === 1 ? 0.22 : 0.12, now);
            osc.connect(gain);
            gain.connect(master);
            osc.start(now + offset);
            osc.stop(now + duration);
        });

        if (button) {
            button.classList.add("is-live");
            window.setTimeout(function () {
                button.classList.remove("is-live");
                alarmPlaying = false;
            }, Math.ceil(duration * 1000) + 120);
        } else {
            window.setTimeout(function () {
                alarmPlaying = false;
            }, Math.ceil(duration * 1000) + 120);
        }
    }

    function render(payload) {
        var unread = payload.unread || 0;
        if (badge) {
            badge.textContent = unread > 99 ? "99+" : String(unread);
            badge.hidden = unread < 1;
        }

        if (!list) {
            return;
        }

        var items = payload.notifications || [];
        if (!items.length) {
            list.innerHTML = '<li class="desk-bell-empty">No alerts yet.</li>';
            return;
        }

        list.innerHTML = "";
        items.forEach(function (item) {
            var li = document.createElement("li");
            var link = document.createElement("a");
            link.href = item.url || "#";
            link.className = item.read ? "" : "is-unread";
            link.setAttribute("data-notification-id", item.id);

            var title = document.createElement("strong");
            title.textContent = item.title || "Desk alert";
            var body = document.createElement("span");
            body.textContent = item.body || "";
            link.appendChild(title);
            link.appendChild(body);
            li.appendChild(link);
            list.appendChild(li);
        });
    }

    function poll() {
        fetch(feedUrl, {
            headers: { Accept: "application/json" },
            credentials: "same-origin",
            cache: "no-store"
        }).then(function (response) {
            if (!response.ok) {
                return null;
            }
            return response.json();
        }).then(function (payload) {
            if (!payload || !payload.ok) {
                return;
            }

            var ids = (payload.notifications || []).map(function (item) {
                return item.id;
            });
            var seen = seenIds();
            var fresh = ids.filter(function (id) {
                return seen.indexOf(id) === -1;
            });

            render(payload);

            if (seen.length === 0) {
                remember(ids);
                return;
            }

            if (fresh.length) {
                remember(ids);
                if (armed) {
                    playSeriousAlarm();
                } else if (hint) {
                    hint.hidden = false;
                }
            }
        }).catch(function () {});
    }

    function post(url) {
        return fetch(url, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken()
            },
            credentials: "same-origin"
        }).then(function (response) {
            return response.json().catch(function () {
                return null;
            });
        });
    }

    if (button && panel) {
        button.addEventListener("click", function () {
            armAudio();
            var open = panel.hidden;
            panel.hidden = !open;
            button.setAttribute("aria-expanded", open ? "true" : "false");
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener("click", function () {
            post(readAllUrl).then(function () {
                poll();
            });
        });
    }

    if (list) {
        list.addEventListener("click", function (event) {
            var link = event.target.closest("a[data-notification-id]");
            if (!link) {
                return;
            }
            var id = link.getAttribute("data-notification-id");
            if (id) {
                post(readOneBase.replace(/\/$/, "") + "/" + encodeURIComponent(id) + "/read");
            }
        });
    }

    document.addEventListener("pointerdown", armAudio, { once: true });
    document.addEventListener("keydown", armAudio, { once: true });

    if (wasArmed()) {
        armAudio();
    } else if (hint) {
        hint.hidden = false;
    }

    poll();
    window.setInterval(poll, pollMs);
})();
