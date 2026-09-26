(function () {
    "use strict";

    var form = document.getElementById("volunteerCaptureForm");
    if (!form) return;

    function apiUrl() {
        return "/api/volunteers";
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
        return "Unable to submit this volunteer application.";
    }

    function showAlert(message, isError) {
        var alertEl = document.getElementById("volunteerAlert");
        if (!alertEl) {
            window.alert(message);
            return;
        }
        alertEl.textContent = message;
        alertEl.classList.remove("d-none", "alert-danger", "alert-success");
        alertEl.classList.add(isError ? "alert-danger" : "alert-success");
    }

    function clearAlert() {
        var alertEl = document.getElementById("volunteerAlert");
        if (alertEl) {
            alertEl.textContent = "";
            alertEl.classList.add("d-none");
            alertEl.classList.remove("alert-danger", "alert-success");
        }
    }

    function setLoading(isLoading) {
        var btn = document.getElementById("volunteerSubmitBtn");
        if (!btn) return;
        btn.disabled = isLoading;
        btn.textContent = isLoading ? "Sending…" : (btn.getAttribute("data-original-text") || "Submit application");
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        clearAlert();

        var honey = document.getElementById("website");
        if (honey && honey.value) {
            return;
        }

        if (!form.reportValidity()) {
            return;
        }

        var body = new FormData(form);

        setLoading(true);

        fetch(apiUrl(), {
            method: "POST",
            headers: csrfHeaders(),
            credentials: "same-origin",
            body: body,
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

                if (!result.ok || !result.data || !result.data.ok || !result.data.volunteer) {
                    showAlert(firstError(result.data), true);
                    return;
                }

                var reference = result.data.volunteer.reference || "";
                showAlert(
                    "Thank you. Your application is with the desk for vetting" +
                        (reference ? " (reference " + reference + ")" : "") +
                        ". We will contact you after review.",
                    false
                );
                form.reset();
            })
            .catch(function () {
                showAlert("Unable to submit this volunteer application right now.", true);
            })
            .finally(function () {
                setLoading(false);
            });
    });
})();
