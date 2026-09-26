(function () {
    "use strict";

    var form = document.getElementById("testimonyCaptureForm");
    var panel = document.getElementById("give-testimony");
    var opener = document.getElementById("giveTestimonyButton");

    if (panel && opener) {
        opener.addEventListener("click", function () {
            panel.hidden = false;
            panel.classList.remove("d-none");
            opener.setAttribute("aria-expanded", "true");
            var name = document.getElementById("testimonyName");
            if (name) {
                name.focus();
            }
            panel.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    }

    if (!form) {
        return;
    }

    function csrfHeaders() {
        var headers = {
            Accept: "application/json"
        };
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.getAttribute("content")) {
            headers["X-CSRF-TOKEN"] = meta.getAttribute("content");
        }
        return headers;
    }

    function firstError(payload) {
        if (payload && payload.errors) {
            var keys = Object.keys(payload.errors);
            if (keys.length && payload.errors[keys[0]] && payload.errors[keys[0]][0]) {
                return payload.errors[keys[0]][0];
            }
        }
        if (payload && payload.message) {
            return payload.message;
        }
        return "Unable to submit this testimony.";
    }

    function showAlert(message, isError) {
        var alertEl = document.getElementById("testimonyAlert");
        if (!alertEl) {
            window.alert(message);
            return;
        }
        alertEl.textContent = message;
        alertEl.classList.remove("d-none", "alert-danger", "alert-success");
        alertEl.classList.add(isError ? "alert-danger" : "alert-success");
    }

    function clearAlert() {
        var alertEl = document.getElementById("testimonyAlert");
        if (!alertEl) {
            return;
        }
        alertEl.textContent = "";
        alertEl.classList.add("d-none");
        alertEl.classList.remove("alert-danger", "alert-success");
    }

    function setLoading(isLoading) {
        var button = document.getElementById("testimonySubmitBtn");
        if (!button) {
            return;
        }
        button.disabled = isLoading;
        button.textContent = isLoading ? "Sending…" : (button.getAttribute("data-original-text") || "Submit testimony");
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        clearAlert();

        var honey = document.getElementById("testimonyWebsite");
        if (honey && honey.value) {
            return;
        }

        if (!form.reportValidity()) {
            return;
        }

        setLoading(true);

        fetch(form.getAttribute("action"), {
            method: "POST",
            headers: csrfHeaders(),
            credentials: "same-origin",
            body: new FormData(form),
            cache: "no-store"
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, status: response.status, data: data };
                }).catch(function () {
                    return { ok: response.ok, status: response.status, data: null };
                });
            })
            .then(function (result) {
                if (result.status === 419) {
                    showAlert("Your session expired. Refresh the page and try again.", true);
                    return;
                }

                if (!result.ok || !result.data || !result.data.ok) {
                    showAlert(firstError(result.data), true);
                    return;
                }

                form.reset();
                showAlert(result.data.message || "Thank you. Your testimony is waiting for review.", false);
            })
            .catch(function () {
                showAlert("Unable to submit this testimony.", true);
            })
            .finally(function () {
                setLoading(false);
            });
    });
}());
