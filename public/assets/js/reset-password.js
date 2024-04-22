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

const newPassword = document.querySelector("#new_password");
let isNewPasswordValidated = false;
let newPasswordError = "New password is required";
newPassword.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        newPasswordError = "New password is required";
        isNewPasswordValidated = false;
    } else if (value.length < 8) {
        newPasswordError = "New password should be 8 characters or more";
        isNewPasswordValidated = false;
    } else if (!/^[A-Z]{1}[a-zA-Z0-9]+[0-9]{1}[a-zA-Z0-9]+$/.test(value)) {
        newPasswordError =
            "Should start with an uppercase and must contain at least a number";
        isNewPasswordValidated = false;
    } else {
        newPasswordError = "";
        isNewPasswordValidated = true;
    }

    checkInput(this, isNewPasswordValidated, newPasswordError);
});

const confirmPassword = document.querySelector("#confirm_password");
let isConfirmPasswordValidated = false;
let confirmPasswordError = "Confirm Password is required";
confirmPassword.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        confirmPasswordError = "Confirm Password is required";
        isConfirmPasswordValidated = false;
    } else if (value !== newPassword.value) {
        confirmPasswordError = "Passwords don't match each other";
        isConfirmPasswordValidated = false;
    } else {
        confirmPasswordError = "";
        isConfirmPasswordValidated = true;
    }

    checkInput(this, isConfirmPasswordValidated, confirmPasswordError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (!isNewPasswordValidated || !isConfirmPasswordValidated) {
        event.preventDefault();

        checkInput(newPassword, isNewPasswordValidated, newPasswordError);
        checkInput(
            confirmPassword,
            isConfirmPasswordValidated,
            confirmPasswordError
        );
    } else {
        document.querySelector(".btn-submit").disabled = true;
        document.querySelector(".main-btn").classList.add("d-none");
        document.querySelector(".loader").classList.remove("d-none");
    }
});
