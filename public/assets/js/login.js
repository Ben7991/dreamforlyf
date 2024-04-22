function checkInput(element, status, errorMessage = "") {
    if (status) {
        element.nextElementSibling.classList.contains("d-none")
            ? null
            : element.nextElementSibling.classList.add("d-none");
        element.classList.remove("border-danger");
    } else {
        element.nextElementSibling.classList.remove("d-none");
        element.classList.add("border-danger");
    }

    element.nextElementSibling.textContent = errorMessage;
}

const emailInput = document.querySelector("#email");
let isEmailValidated = false;
let emailError = "Email address is required";
emailInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        emailError = "Email address is required";
        isEmailValidated = false;
    } else if (
        !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)
    ) {
        emailError = "Invalid email address";
        isEmailValidated = false;
    } else {
        emailError = "";
        isEmailValidated = true;
    }

    checkInput(this, isEmailValidated, emailError);
});

const passwordInput = document.querySelector("#password");
let isPasswordValidated = false;
let passwordError = "Password is required";
passwordInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        passwordError = "Password is required";
        isPasswordValidated = false;
    } else {
        passwordError = "";
        isPasswordValidated = true;
    }

    checkInput(this, isPasswordValidated, passwordError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (!isEmailValidated || !isPasswordValidated) {
        event.preventDefault();

        checkInput(emailInput, isEmailValidated, emailError);
        checkInput(passwordInput, isPasswordValidated, passwordError);
    } else {
        document.querySelector(".btn-submit").disabled = true;
        document.querySelector(".main-btn").classList.add("d-none");
        document.querySelector(".loader").classList.remove("d-none");
    }
});
