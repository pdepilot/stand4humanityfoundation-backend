(function () {
    "use strict";

    var form = document.getElementById("contactCaptureForm");
    if (!form) return;

    function apiUrl() {
        return "/api/contacts";
    }

    function csrfHeaders() {
        var headers = {
            "Content-Type": "application/json",
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
        return "Unable to send this message.";
    }

    function showAlert(message, isError) {
        var alertEl = document.getElementById("contactAlert");
        if (!alertEl) {
            window.alert(message);
            return;
        }
        alertEl.textContent = message;
        alertEl.classList.remove("d-none", "alert-danger", "alert-success");
        alertEl.classList.add(isError ? "alert-danger" : "alert-success");
    }

    function clearAlert() {
        var alertEl = document.getElementById("contactAlert");
        if (alertEl) {
            alertEl.textContent = "";
            alertEl.classList.add("d-none");
            alertEl.classList.remove("alert-danger", "alert-success");
        }
    }

    function setLoading(isLoading) {
        var btn = document.getElementById("contactSubmitBtn");
        if (!btn) return;
        btn.disabled = isLoading;
        btn.textContent = isLoading ? "Sending…" : (btn.getAttribute("data-original-text") || "Request Assistance");
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

        var payload = {
            name: document.getElementById("name").value.trim(),
            email: document.getElementById("email").value.trim(),
            subject: document.getElementById("subject").value.trim(),
            message: document.getElementById("message").value.trim()
        };

        setLoading(true);

        fetch(apiUrl(), {
            method: "POST",
            headers: csrfHeaders(),
            credentials: "same-origin",
            body: JSON.stringify(payload),
            cache: "no-store"
        }).then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, status: response.status, data: data };
            }).catch(function () {
                return { ok: response.ok, status: response.status, data: null };
            });
        }).then(function (result) {
            if (result.status === 419) {
                showAlert("Your session expired. Refresh the page and try again.", true);
                setLoading(false);
                return;
            }

            if (!result.ok || !result.data || !result.data.ok || !result.data.contact) {
                showAlert(firstError(result.data), true);
                setLoading(false);
                return;
            }

            showAlert("Your request has been received. Reference " + result.data.contact.reference + ".", false);
            form.reset();
            setLoading(false);
        }).catch(function () {
            showAlert("Unable to reach the contact desk. Check your connection and try again.", true);
            setLoading(false);
        });
    });
})();
