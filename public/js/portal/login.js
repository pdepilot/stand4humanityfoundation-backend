(function () {
    "use strict";

    var form = document.querySelector(".login-form");
    var submitBtn = document.getElementById("loginSubmit");
    var toggle = document.getElementById("togglePassword");
    var password = document.getElementById("password");

    if (toggle && password) {
        toggle.addEventListener("click", function () {
            var hidden = password.getAttribute("type") === "password";
            password.setAttribute("type", hidden ? "text" : "password");
            toggle.setAttribute("aria-label", hidden ? "Hide password" : "Show password");
            toggle.innerHTML = hidden ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
        });
    }

    if (form && submitBtn) {
        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.textContent = "Entering…";
        });
    }
})();
