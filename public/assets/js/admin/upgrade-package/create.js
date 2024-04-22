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
        showMessage("Only image is allowed", "danger");
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

const currentPackageInput = document.querySelector("#current_package");
let isCurrentPackageInputValidated = false;
let currentPackageError = "Current Package is required";
currentPackageInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        currentPackageError = "Current Package is required";
        isCurrentPackageInputValidated = false;
    } else if (+nextPackageInput.value > 0 && +nextPackageInput.value <= +value) {
        currentPackageError = "Can't use a higher or same package";
        isCurrentPackageInputValidated = false;
    } else {
        currentPackageError = "";
        isCurrentPackageInputValidated = true;
    }

    checkInput(this, isCurrentPackageInputValidated, currentPackageError);
});


const nextPackageInput = document.querySelector("#next_package");
let isNextPackageInputValidated = false;
let nextPackageError = "Next Package is required";
nextPackageInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        nextPackageError = "Next Package is required";
        isNextPackageInputValidated = false;
    } else if (+value <= +currentPackageInput.value) {
        nextPackageError = "Can't use a lower or same package";
        isNextPackageInputValidated = false;
    } else {
        nextPackageError = "";
        isNextPackageInputValidated = true;
    }

    checkInput(this, isNextPackageInputValidated, nextPackageError);
});


const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isNextPackageInputValidated ||
        !isCurrentPackageInputValidated ||
        !isUploadInputValidated ||
        !isTypeInputValidated
    ) {
        event.preventDefault();

        checkInput(currentPackageInput, isCurrentPackageInputValidated, currentPackageError);
        checkInput(nextPackageInput, isNextPackageInputValidated, nextPackageError);
        checkInput(typeInput, isTypeInputValidated, typeError);

        if (!isUploadInputValidated) {
            showMessage("Please upload image", "danger");
        }
    }
});
