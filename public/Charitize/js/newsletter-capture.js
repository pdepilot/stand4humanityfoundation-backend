(function () {
    "use strict";

    function csrfHeaders(form) {
        var headers = {
            "Content-Type": "application/json",
            Accept: "application/json"
        };
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.getAttribute("content")) {
            headers["X-CSRF-TOKEN"] = meta.getAttribute("content");
        } else {
            var tokenInput = form.querySelector('input[name="_token"]');
            if (tokenInput && tokenInput.value) {
                headers["X-CSRF-TOKEN"] = tokenInput.value;
            }
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
        return "Unable to subscribe right now.";
    }

    function alertFor(form) {
        var section = form.closest(".newsletter-capture") || form.closest(".container-fluid") || form.parentElement;
        return section ? section.querySelector(".newsletter-alert") : null;
    }

    function showAlert(form, message, isError) {
        var alertEl = alertFor(form);
        if (!alertEl) {
            window.alert(message);
            return;
        }
        alertEl.textContent = message;
        alertEl.classList.remove("d-none", "alert-danger", "alert-success", "alert-light");
        alertEl.classList.add(isError ? "alert-danger" : "alert-success");
    }

    function clearAlert(form) {
        var alertEl = alertFor(form);
        if (alertEl) {
            alertEl.textContent = "";
            alertEl.classList.add("d-none");
            alertEl.classList.remove("alert-danger", "alert-success");
        }
    }

    function setLoading(form, isLoading) {
        var btn = form.querySelector('button[type="submit"]');
        if (!btn) {
            return;
        }
        btn.disabled = isLoading;
    }

    function csrfToken(form) {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.getAttribute("content")) {
            return meta.getAttribute("content");
        }
        var tokenInput = form.querySelector('input[name="_token"]');
        return tokenInput ? tokenInput.value : "";
    }

    document.addEventListener("submit", function (e) {
        var form = e.target;
        if (!form || !form.classList || !form.classList.contains("newsletter-capture-form")) {
            return;
        }

        e.preventDefault();
        clearAlert(form);

        var emailInput = form.querySelector('input[name="email"]');
        var email = emailInput ? emailInput.value.trim() : "";

        if (!email) {
            showAlert(form, "Enter your email address.", true);
            if (emailInput) {
                emailInput.focus();
            }
            return;
        }

        if (emailInput && typeof emailInput.checkValidity === "function" && !emailInput.checkValidity()) {
            showAlert(form, "Enter a valid email address.", true);
            emailInput.focus();
            return;
        }

        var payload = {
            email: email,
            _token: csrfToken(form)
        };

        setLoading(form, true);

        fetch(form.getAttribute("action") || "/api/newsletter", {
            method: "POST",
            headers: csrfHeaders(form),
            credentials: "same-origin",
            body: JSON.stringify(payload),
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
                    showAlert(form, "Your session expired. Refresh the page and try again.", true);
                    return;
                }

                if (result.status === 429) {
                    showAlert(form, "Too many tries from this connection. Wait about a minute, then subscribe again.", true);
                    return;
                }

                if (!result.ok || !result.data || !result.data.ok) {
                    showAlert(form, firstError(result.data), true);
                    return;
                }

                showAlert(
                    form,
                    result.data.created
                        ? "Thank you. You are on the programme notes list."
                        : "You are already on the programme notes list.",
                    false
                );
                form.reset();
            })
            .catch(function () {
                showAlert(form, "Unable to subscribe right now. Check your connection and try again.", true);
            })
            .finally(function () {
                setLoading(form, false);
            });
    });
})();
