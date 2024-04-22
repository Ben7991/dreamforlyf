const btnUpload = document.querySelector("#upload-btn");
const imgUpload = document.querySelector("#img-upload");

btnUpload.addEventListener("click", function() {
    imgUpload.click();
});

// preparing alert element
const alertElement = document.createElement("div");
alertElement.className =
    "alert-response alert alert-danger alert-dismissible fade show";
const alertMessage = document.createElement("p");
alertMessage.className = "m-0";
const alertButton = document.createElement("button");
alertButton.className = "btn-close";
alertButton.setAttribute("data-bs-dismiss", "alert");
alertButton.setAttribute("aria-label", "Close");
alertButton.setAttribute("type", "button");
alertElement.appendChild(alertMessage);
alertElement.appendChild(alertButton);
let timer;

function showAlert(message) {
    if (timer) {
        clearTimeout(timer);
    }

    alertMessage.textContent = message;
    document.querySelector("body").appendChild(alertElement);
    timer = setTimeout(() => {
        document.querySelector("body").removeChild(alertElement);
    }, 4000);
}

imgUpload.addEventListener("change", function() {
    const file = this.files[0];
    const profileImages = document.querySelectorAll(".profile-image");
    const profilePlaceholders = document.querySelectorAll(".profile-placeholder");

    if (file === null) {
        profilePlaceholders.forEach(element => element.classList.remove('d-none'));
        profileImages.forEach(element => element.classList.add("d-none"));
        return;
    }

    const fileReader = new FileReader();

    fileReader.onload = function() {
        // profilePlaceholders.classList.add('d-none');
        // profileImages.classList.remove("d-none");
        profilePlaceholders.forEach(element => element.classList.add('d-none'));
        profileImages.forEach(element => element.classList.remove("d-none"));
        profileImages.forEach(element => element.src = fileReader.result);

        const token = document.querySelector("#img-token").value;
        const formData = new FormData();
        formData.append("image", file);

        const xmlHttp = new XMLHttpRequest();
        xmlHttp.addEventListener("loadend", function() {
            const response = JSON.parse(xmlHttp.response);
            if (response.code === "error") {
                showAlert("Unable to upload image, please try again");
            }
        });
        xmlHttp.open("POST", "/profile/image-change");
        xmlHttp.setRequestHeader("X-CSRF-TOKEN", token);
        xmlHttp.send(formData);
    }

    fileReader.readAsDataURL(file);
});


// ----- profile validation -------------------------

const nameInput = document.querySelector("#name");
let isNameInputValidated = true;
let nameError = "Name field is required";
nameInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        nameError = "Name field is required";
        isNameInputValidated = false;
    } else if (!/^[a-zA-Z ]+$/.test(value)) {
        nameError = "Only letters and whitespaces are allowed";
        isNameInputValidated = false;
    } else {
        nameError = "";
        isNameInputValidated = true;
    }

    checkInput(this, isNameInputValidated, nameError);
});

const form = document.querySelector("#personal-form");
form.addEventListener("submit", function (event) {
    if (!isNameInputValidated) {
        event.preventDefault();

        checkInput(nameInput, isNameInputValidated, nameError);
    }
});

// ----- end profile validation -----------------



// ----- password validation --------------------

const currentPassword = document.querySelector("#current_password");
let isCurrentPasswordValidated = false;
let currentPasswordError = "Current password field is required";
currentPassword.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        currentPasswordError = "Current password field is required";
        isCurrentPasswordValidated = false;
    } else {
        currentPasswordError = "";
        isCurrentPasswordValidated = true;
    }

    checkInput(this, isCurrentPasswordValidated, currentPasswordError);
});

const newPassword = document.querySelector("#new_password");
let isNewPasswordValidated = false;
let newPasswordError = "New password field is required";
newPassword.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        newPasswordError = "New password field is required";
        isNewPasswordValidated = false;
    } else if (value.length < 8) {
        newPasswordError = "Must be 8 characters or more";
        isNewPasswordValidated = false;
    } else if (!/^[A-Z]{1}[a-zA-Z0-9]+[0-9]{1}[a-zA-Z0-9]+$/.test(value)) {
        newPasswordError = "Should start with uppercase letter and contain a number. No symbols allowed";
        isNewPasswordValidated = false;
    } else {
        newPasswordError = "";
        isNewPasswordValidated = true;
    }

    checkInput(this, isNewPasswordValidated, newPasswordError);
});


const confirmPassword = document.querySelector("#confirm_password");
let isConfirmPasswordValidated = false;
let confirmPasswordError = "New password field is required";
confirmPassword.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        confirmPasswordError = "New password field is required";
        isConfirmPasswordValidated = false;
    } else if (value !== newPassword.value) {
        confirmPasswordError = "Passwords do not match each other";
        isConfirmPasswordValidated = false;
    } else {
        confirmPasswordError = "";
        isConfirmPasswordValidated = true;
    }

    checkInput(this, isConfirmPasswordValidated, confirmPasswordError);
});


const passwordForm = document.querySelector("#password-form");
passwordForm.addEventListener("submit", function (event) {
    if (!isCurrentPasswordValidated || !isNewPasswordValidated || !isConfirmPasswordValidated) {
        event.preventDefault();

        checkInput(nameInput, isCurrentPasswordValidated, currentPasswordError);
        checkInput(newPassword, isNewPasswordValidated, newPasswordError);
        checkInput(confirmPassword, isConfirmPasswordValidated, confirmPasswordError);
    }
});

// ----- end password validation --------------------
