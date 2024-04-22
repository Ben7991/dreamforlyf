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

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (!isEmailValidated) {
        event.preventDefault();

        checkInput(emailInput, isEmailValidated, emailError);
    }
});
