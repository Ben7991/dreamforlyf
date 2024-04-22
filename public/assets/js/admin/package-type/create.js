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

const btnFileUpload = document.querySelector("#upload-btn");
const fileInput = document.querySelector("#file-upload");
btnFileUpload.addEventListener("click", () => {
    fileInput.click();
});

let isUploadInputValidated = false;
fileInput.addEventListener("change", function () {
    const files = this.files;
    const acceptMime = ["image/jpg", "image/jpeg", "image/png"];

    if (!acceptMime.includes(files[0].type)) {
        showAlert("Only image is allowed");
        fileInput.value = "";
        isUploadInputValidated = false;
    } else {
        displayUploadedImage(files[0]);
        isUploadInputValidated = true;
    }
});

const typeInput = document.querySelector("#type");
let isTypeInputValidated = false;
let typeError = "Type field is required";
typeInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        typeError = "Type field is required";
        isTypeInputValidated = false;
    } else if (!/^[A-Z]{1}$/.test(value)) {
        typeError = "Only a single capital letter are allowed";
        isTypeInputValidated = false;
    } else {
        typeError = "";
        isTypeInputValidated = true;
    }

    checkInput(this, isTypeInputValidated, typeError);
});

const packageInput = document.querySelector("#package_id");
let isPackageInputValidated = false;
let packageError = "Registration Package is required";
currentPackageInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        currentPackageError = "Registration Package is required";
        isCurrentPackageInputValidated = false;
    } else {
        currentPackageError = "";
        isCurrentPackageInputValidated = true;
    }

    checkInput(this, isCurrentPackageInputValidated, currentPackageError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isTypeInputValidated ||
        !isCurrentPackageInputValidated ||
        !isUploadInputValidated
    ) {
        event.preventDefault();

        checkInput(currentPackageInput, isCurrentPackageInputValidated, currentPackageError);
        checkInput(typeInput, isTypeInputValidated, typeError);

        if (!isUploadInputValidated) {
            showAlert("Please upload image");
        }
    }
});
