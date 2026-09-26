(function () {
    "use strict";

    var MAX_FILES = 5;
    var MAX_FILE_SIZE = 10 * 1024 * 1024;
    var MAX_VIDEO_SIZE = 20 * 1024 * 1024;
    var MIN_DESC = 50;
    var ALLOWED_EXT = /\.(jpe?g|png|gif|webp|pdf|doc|docx|mp4|mov|webm|avi)$/i;
    var ALLOWED_MIME = /^(image\/(jpeg|png|gif|webp)|application\/(pdf|msword|vnd\.openxmlformats-officedocument\.wordprocessingml\.document)|video\/(mp4|quicktime|webm|x-msvideo))$/i;
    var STATUSES = [
        "Submitted",
        "Under Review",
        "Investigation Ongoing",
        "Referred",
        "Resolved",
        "Closed"
    ];

    var selectedFiles = [];
    var form = document.getElementById("incidentReportForm");
    var fileInput = document.getElementById("evidenceFiles");
    var preview = document.getElementById("evidencePreview");
    var descField = document.getElementById("incidentDescription");
    var charCounter = document.getElementById("descCharCount");
    var relationship = document.getElementById("relationship");
    var fullName = document.getElementById("fullName");
    var submitBtn = document.getElementById("submitReportBtn");
    var successBox = document.getElementById("reportSuccessBox");
    var refDisplay = document.getElementById("generatedReference");
    var mapFrame = document.getElementById("locationMapFrame");
    var locationStatus = document.getElementById("locationStatus");
    var latInput = document.getElementById("gpsLatitude");
    var lngInput = document.getElementById("gpsLongitude");
    var addressInput = document.getElementById("incidentAddress");

    function apiUrl(path) {
        return "/api/reports" + (path || "");
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
        return "Unable to submit this report.";
    }

    function updateCharCount() {
        if (!descField || !charCounter) return;
        var len = descField.value.trim().length;
        charCounter.textContent = len + " / " + MIN_DESC + " minimum characters";
        charCounter.classList.toggle("is-invalid-text", len > 0 && len < MIN_DESC);
    }

    function toggleAnonymousFields() {
        if (!relationship || !fullName) return;
        var isAnon = relationship.value === "Anonymous Reporter";
        fullName.required = !isAnon;
        fullName.closest(".col-md-6, .col-12") && fullName.setAttribute("aria-required", String(!isAnon));
        var hint = document.getElementById("nameOptionalHint");
        if (hint) {
            hint.textContent = isAnon ? "(optional for anonymous reports)" : "";
        }
    }

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + " B";
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + " KB";
        return (bytes / 1048576).toFixed(1) + " MB";
    }

    function renderPreviews() {
        if (!preview) return;
        preview.innerHTML = "";
        selectedFiles.forEach(function (file, index) {
            var item = document.createElement("div");
            item.className = "preview-item";

            var removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.className = "btn btn-secondary btn-remove";
            removeBtn.setAttribute("aria-label", "Remove " + file.name);
            removeBtn.innerHTML = "&times;";
            removeBtn.addEventListener("click", function () {
                selectedFiles.splice(index, 1);
                syncFileInput();
                renderPreviews();
            });

            if (file.type.indexOf("image/") === 0) {
                var img = document.createElement("img");
                img.alt = file.name;
                img.src = URL.createObjectURL(file);
                item.appendChild(img);
            } else {
                var icon = document.createElement("div");
                icon.className = "file-icon";
                icon.innerHTML = file.type.indexOf("video/") === 0
                    ? '<i class="fa fa-video"></i>'
                    : '<i class="fa fa-file"></i>';
                item.appendChild(icon);
            }

            var name = document.createElement("p");
            name.className = "file-name";
            name.textContent = file.name + " (" + formatBytes(file.size) + ")";
            item.appendChild(name);
            item.appendChild(removeBtn);
            preview.appendChild(item);
        });
    }

    function syncFileInput() {
        if (!fileInput) return;
        var dt = new DataTransfer();
        selectedFiles.forEach(function (f) {
            dt.items.add(f);
        });
        fileInput.files = dt.files;
    }

    function isVideoFile(file) {
        return (file.type && file.type.indexOf("video/") === 0) ||
            /\.(mp4|mov|webm|avi)$/i.test(file.name);
    }

    function validateFile(file) {
        var isVideo = isVideoFile(file);
        var limit = isVideo ? MAX_VIDEO_SIZE : MAX_FILE_SIZE;
        var limitLabel = isVideo ? "20 MB" : "10 MB";
        if (file.size > limit) {
            return file.name + " exceeds the " + limitLabel + " limit" +
                (isVideo ? " for videos." : " for files.");
        }
        if (!ALLOWED_EXT.test(file.name) && !(file.type && ALLOWED_MIME.test(file.type))) {
            return file.name + " is not an allowed file type.";
        }
        return null;
    }

    function handleFiles(fileList) {
        var errors = [];
        Array.prototype.forEach.call(fileList, function (file) {
            if (selectedFiles.length >= MAX_FILES) {
                errors.push("Maximum of " + MAX_FILES + " files allowed.");
                return;
            }
            var err = validateFile(file);
            if (err) {
                errors.push(err);
                return;
            }
            var duplicate = selectedFiles.some(function (f) {
                return f.name === file.name && f.size === file.size;
            });
            if (!duplicate) selectedFiles.push(file);
        });
        if (errors.length) {
            alert(errors.join("\n"));
        }
        syncFileInput();
        renderPreviews();
    }

    function updateMap(lat, lng) {
        if (!mapFrame) return;
        var q = encodeURIComponent(lat + "," + lng);
        mapFrame.src = "https://www.google.com/maps?q=" + q + "&z=15&output=embed";
        mapFrame.setAttribute("aria-label", "Map preview for coordinates " + lat + ", " + lng);
    }

    function setLocationStatus(message, isError) {
        if (!locationStatus) return;
        locationStatus.textContent = message;
        locationStatus.className = isError ? "small text-danger mt-2 mb-0" : "small text-secondary mt-2 mb-0";
    }

    function detectLocation() {
        if (!navigator.geolocation) {
            setLocationStatus("Geolocation is not supported by this browser.", true);
            return;
        }
        setLocationStatus("Detecting your location…", false);
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                var lat = pos.coords.latitude.toFixed(6);
                var lng = pos.coords.longitude.toFixed(6);
                if (latInput) latInput.value = lat;
                if (lngInput) lngInput.value = lng;
                if (addressInput && !addressInput.value.trim()) {
                    addressInput.value = "GPS " + lat + ", " + lng;
                }
                updateMap(lat, lng);
                setLocationStatus("Location captured successfully.", false);
            },
            function () {
                setLocationStatus("Unable to detect location. Please enter coordinates manually or allow location access.", true);
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    function clearFieldErrors() {
        if (!form) return;
        form.querySelectorAll(".is-invalid").forEach(function (el) {
            el.classList.remove("is-invalid");
        });
        var alertEl = document.getElementById("formValidationAlert");
        if (alertEl) alertEl.classList.add("d-none");
    }

    function showFieldError(el) {
        if (el) el.classList.add("is-invalid");
    }

    function showAlert(message) {
        var alertEl = document.getElementById("formValidationAlert");
        if (!alertEl) {
            window.alert(message);
            return;
        }
        alertEl.textContent = message;
        alertEl.classList.remove("d-none");
    }

    function validateForm() {
        clearFieldErrors();
        var valid = true;
        var messages = [];

        var honey = document.getElementById("website");
        if (honey && honey.value) {
            return { valid: false, spam: true };
        }

        var isAnon = relationship && relationship.value === "Anonymous Reporter";
        if (!isAnon && fullName && !fullName.value.trim()) {
            showFieldError(fullName);
            messages.push("Full name is required unless reporting anonymously.");
            valid = false;
        }

        var phone = document.getElementById("phoneNumber");
        if (phone && !isAnon && !phone.value.trim()) {
            showFieldError(phone);
            messages.push("Phone number is required unless reporting anonymously.");
            valid = false;
        }

        if (relationship && !relationship.value) {
            showFieldError(relationship);
            messages.push("Please select your relationship to the incident.");
            valid = false;
        }

        var incidentType = document.getElementById("incidentType");
        if (incidentType && !incidentType.value) {
            showFieldError(incidentType);
            messages.push("Please select an incident type.");
            valid = false;
        }

        var title = document.getElementById("incidentTitle");
        if (title && !title.value.trim()) {
            showFieldError(title);
            messages.push("Incident title is required.");
            valid = false;
        }

        var date = document.getElementById("incidentDate");
        if (date && !date.value) {
            showFieldError(date);
            messages.push("Date of incident is required.");
            valid = false;
        }

        var state = document.getElementById("incidentState");
        if (state && !state.value) {
            showFieldError(state);
            messages.push("State is required.");
            valid = false;
        }

        if (descField) {
            var len = descField.value.trim().length;
            if (len < MIN_DESC) {
                showFieldError(descField);
                messages.push("Please describe the incident in at least " + MIN_DESC + " characters.");
                valid = false;
            }
        }

        ["confirmAccurate", "confirmFalseReporting", "consentProcessing"].forEach(function (id) {
            var cb = document.getElementById(id);
            if (cb && !cb.checked) {
                showFieldError(cb);
                valid = false;
            }
        });
        if (!document.getElementById("confirmAccurate").checked ||
            !document.getElementById("confirmFalseReporting").checked ||
            !document.getElementById("consentProcessing").checked) {
            messages.push("Please accept all confidentiality confirmations before submitting.");
        }

        return { valid: valid, messages: messages, spam: false };
    }

    function setLoading(isLoading) {
        if (!submitBtn) return;
        submitBtn.disabled = isLoading;
        submitBtn.innerHTML = isLoading
            ? '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting…'
            : '<i class="fa fa-paper-plane me-2"></i>Submit Report';
    }

    function resetForm() {
        form.reset();
        selectedFiles = [];
        syncFileInput();
        renderPreviews();
        updateCharCount();
        toggleAnonymousFields();
    }

    function submitReport() {
        syncFileInput();

        var body = new FormData(form);

        fetch(apiUrl(), {
            method: "POST",
            headers: csrfHeaders(),
            credentials: "same-origin",
            body: body
        }).then(function (response) {
            return response.json().then(function (payload) {
                return { ok: response.ok, status: response.status, payload: payload };
            }).catch(function () {
                return { ok: response.ok, status: response.status, payload: null };
            });
        }).then(function (result) {
            if (result.status === 419) {
                showAlert("Your session expired. Refresh the page and try again.");
                setLoading(false);
                return;
            }

            if (!result.ok || !result.payload || !result.payload.report) {
                showAlert(firstError(result.payload));
                setLoading(false);
                return;
            }

            var report = result.payload.report;
            if (refDisplay) refDisplay.textContent = report.reference;
            if (successBox) {
                successBox.classList.add("show");
                successBox.scrollIntoView({ behavior: "smooth", block: "center" });
            }

            resetForm();
            setLoading(false);
        }).catch(function () {
            showAlert("Unable to reach the report desk. Check your connection and try again.");
            setLoading(false);
        });
    }

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            var result = validateForm();
            if (result.spam) return;

            if (!result.valid) {
                showAlert(result.messages.join(" "));
                var firstInvalid = form.querySelector(".is-invalid");
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            setLoading(true);
            if (successBox) successBox.classList.remove("show");
            submitReport();
        });
    }

    function renderTrackResult(ref, status, meta) {
        var resultBox = document.getElementById("trackResultBox");
        var resultRef = document.getElementById("trackResultRef");
        var resultStatus = document.getElementById("trackResultStatus");
        var resultMeta = document.getElementById("trackResultMeta");
        var statusItems = document.querySelectorAll("#trackStatusList .status-item");

        if (resultRef) resultRef.textContent = ref;
        if (resultStatus) resultStatus.textContent = status;
        if (resultMeta) resultMeta.textContent = meta;

        var activeIndex = STATUSES.indexOf(status);
        statusItems.forEach(function (item, i) {
            item.classList.remove("active", "done");
            if (activeIndex === -1) {
                return;
            }
            if (i < activeIndex) item.classList.add("done");
            if (i === activeIndex) item.classList.add("active");
        });

        if (resultBox) {
            resultBox.classList.add("show");
            resultBox.scrollIntoView({ behavior: "smooth", block: "nearest" });
        }
    }

    var trackForm = document.getElementById("trackReportForm");
    if (trackForm) {
        trackForm.addEventListener("submit", function (e) {
            e.preventDefault();
            var input = document.getElementById("trackReference");

            if (!input || !input.value.trim()) {
                if (input) input.classList.add("is-invalid");
                return;
            }
            input.classList.remove("is-invalid");

            var ref = input.value.trim().toUpperCase();

            fetch(apiUrl("/" + encodeURIComponent(ref)), {
                method: "GET",
                headers: { Accept: "application/json" },
                credentials: "same-origin"
            }).then(function (response) {
                return response.json().then(function (payload) {
                    return { ok: response.ok, payload: payload };
                }).catch(function () {
                    return { ok: response.ok, payload: null };
                });
            }).then(function (result) {
                if (!result.ok || !result.payload || !result.payload.report) {
                    renderTrackResult(ref, "Not Found", "No report matches this reference. Check the number and try again.");
                    return;
                }

                var report = result.payload.report;
                var submitted = report.submittedAt
                    ? "Submitted: " + new Date(report.submittedAt).toLocaleString()
                    : "Report received.";
                var type = report.incidentType ? " · Type: " + report.incidentType : "";
                renderTrackResult(report.reference || ref, report.status || "Submitted", submitted + type);
            }).catch(function () {
                renderTrackResult(ref, "Not Found", "Unable to reach the report desk. Check your connection and try again.");
            });
        });
    }

    if (fileInput) {
        fileInput.addEventListener("change", function () {
            handleFiles(fileInput.files);
            fileInput.value = "";
            syncFileInput();
        });
    }

    if (descField) {
        descField.addEventListener("input", updateCharCount);
        updateCharCount();
    }

    if (relationship) {
        relationship.addEventListener("change", toggleAnonymousFields);
        toggleAnonymousFields();
    }

    var detectBtn = document.getElementById("detectLocationBtn");
    var useCurrentBtn = document.getElementById("useCurrentLocationBtn");
    if (detectBtn) detectBtn.addEventListener("click", detectLocation);
    if (useCurrentBtn) useCurrentBtn.addEventListener("click", detectLocation);

    [latInput, lngInput].forEach(function (el) {
        if (!el) return;
        el.addEventListener("change", function () {
            if (latInput.value && lngInput.value) {
                updateMap(latInput.value, lngInput.value);
            }
        });
    });

    document.querySelectorAll("[data-scroll-target]").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            var target = document.querySelector(btn.getAttribute("data-scroll-target"));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        });
    });
})();
