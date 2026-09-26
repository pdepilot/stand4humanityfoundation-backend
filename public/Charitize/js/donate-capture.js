(function () {
    "use strict";

    var form = document.getElementById("donationCaptureForm");
    if (!form) return;

    var STORAGE_KEY = "sfhf_donations_ledger";
    var JUST_KEY = "sfhf_just_donated";
    var PING_KEY = "sfhf_donations_ping";

    var causeLabels = {
        "child-protection": "Child Protection & Rescue",
        "rescue": "Emergency Rescue & Intervention",
        "victim-support": "Victim Support & Advocacy",
        "community-awareness": "Community Awareness & Advocacy",
        "general": "General Donation"
    };

    function apiUrl() {
        return "/api/donations";
    }

    function csrfHeaders() {
        var headers = {
            "Content-Type": "application/json",
            "Accept": "application/json"
        };
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.getAttribute("content")) {
            headers["X-CSRF-TOKEN"] = meta.getAttribute("content");
        }
        return headers;
    }

    function showError(message) {
        var alertEl = document.getElementById("donationAlert");
        if (!alertEl) {
            window.alert(message);
            return;
        }
        alertEl.textContent = message;
        alertEl.classList.remove("d-none");
    }

    function clearError() {
        var alertEl = document.getElementById("donationAlert");
        if (alertEl) {
            alertEl.textContent = "";
            alertEl.classList.add("d-none");
        }
    }

    function restoreButton(btn) {
        if (!btn) {
            return;
        }
        btn.disabled = false;
        btn.textContent = btn.getAttribute("data-original-text") || "Donate Now";
    }

    function ledgerUrl(id) {
        return "/donations?just=" + encodeURIComponent(id) + "&live=1";
    }

    function saveLocal(entry) {
        var list = [];
        try {
            list = JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");
        } catch (err) {
            list = [];
        }
        if (!Array.isArray(list)) list = [];
        list.push(entry);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
        try {
            localStorage.setItem(PING_KEY, String(Date.now()));
        } catch (err2) {}
    }

    var statusEl = document.getElementById("paymentMethodStatus");
    var methodLabels = { paystack: "Paystack selected", flutterwave: "Flutterwave selected" };
    form.querySelectorAll('input[name="payment_method"]').forEach(function (input) {
        input.addEventListener("change", function () {
            if (statusEl) statusEl.textContent = methodLabels[input.value] || "Payment method selected";
        });
    });

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        var nameField = document.getElementById("name");
        var amountField = document.getElementById("amount");
        var realName = nameField ? String(nameField.value || "").trim() : "";
        var amount = amountField ? Number(amountField.value) : 0;
        if (!realName || !amount || amount <= 0) {
            form.reportValidity();
            return;
        }

        var anonymousEl = document.getElementById("anonymous");
        var anonymous = !!(anonymousEl && anonymousEl.checked);
        var causeEl = document.getElementById("cause");
        var cause = causeEl ? causeEl.value : "general";
        var messageEl = document.getElementById("message");
        var message = messageEl ? String(messageEl.value || "").trim() : "";
        var paymentMethodEl = form.querySelector('input[name="payment_method"]:checked');
        var emailEl = document.getElementById("email");
        var phoneEl = document.getElementById("phone");
        var currencyEl = document.getElementById("currency");
        var frequencyEl = document.getElementById("frequency");
        var locationEl = document.getElementById("location");

        clearError();

        var entry = {
            id: "don-" + Date.now(),
            name: realName,
            email: emailEl ? String(emailEl.value || "").trim() : "",
            phone: phoneEl ? String(phoneEl.value || "").trim() : "",
            description: message || ("Donation toward " + (causeLabels[cause] || cause)),
            amount: amount,
            currency: currencyEl ? currencyEl.value : "NGN",
            cause: cause,
            frequency: frequencyEl ? frequencyEl.value : "one-time",
            location: locationEl ? String(locationEl.value || "").trim() : "",
            paymentMethod: paymentMethodEl ? paymentMethodEl.value : "",
            anonymous: anonymous,
            createdAt: new Date().toISOString()
        };

        var btn = form.querySelector('[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.setAttribute("data-original-text", btn.textContent);
            btn.textContent = "Recording donation…";
        }

        function finish() {
            try {
                sessionStorage.setItem(JUST_KEY, JSON.stringify({
                    id: entry.id,
                    name: realName,
                    amount: entry.amount,
                    currency: entry.currency,
                    anonymous: entry.anonymous
                }));
            } catch (err) {}
            saveLocal(entry);
            window.location.href = ledgerUrl(entry.id);
        }

        var payload = {
            name: entry.name,
            email: entry.email,
            phone: entry.phone,
            location: entry.location,
            description: entry.description,
            amount: entry.amount,
            currency: entry.currency,
            cause: entry.cause,
            frequency: entry.frequency,
            paymentMethod: entry.paymentMethod,
            anonymous: entry.anonymous
        };

        fetch(apiUrl(), {
            method: "POST",
            headers: csrfHeaders(),
            body: JSON.stringify(payload),
            cache: "no-store"
        }).then(function (res) {
            return res.json().then(function (data) {
                return { res: res, data: data };
            }).catch(function () {
                return { res: res, data: null };
            });
        }).then(function (result) {
            if (result.data && result.data.ok && result.data.checkoutUrl) {
                window.location.href = result.data.checkoutUrl;
                return;
            }

            if (result.data && result.data.ok && result.data.donation) {
                entry.id = result.data.donation.id;
                entry.createdAt = result.data.donation.createdAt || entry.createdAt;
                entry.anonymous = !!result.data.donation.anonymous;
                finish();
                return;
            }

            var message = "The donation could not be recorded. Please try again.";
            if (result.data && result.data.message) {
                message = result.data.message;
            } else if (result.data && result.data.errors) {
                var firstKey = Object.keys(result.data.errors)[0];
                if (firstKey && result.data.errors[firstKey][0]) {
                    message = result.data.errors[firstKey][0];
                }
            }
            restoreButton(btn);
            showError(message);
        }).catch(function () {
            restoreButton(btn);
            showError("The donation desk could not be reached. Please try again.");
        });
    });
})();
