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

let isUploadInputValidated = true;
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
let isTypeInputValidated = true;
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
let isPackageInputValidated = true;
let packageError = "Registration Package is required";
packageInput.addEventListener("change", function () {
    const value = this.value;
    console.log(value);

    if (value === "") {
        packageError = "Registration Package is required";
        isPackageInputValidated = false;
    } else {
        packageError = "";
        isPackageInputValidated = true;
    }

    checkInput(this, isPackageInputValidated, packageError);
});

const form = document.querySelector("#type-form");
form.addEventListener("submit", function (event) {
    if (
        !isTypeInputValidated ||
        !isPackageInputValidated ||
        !isUploadInputValidated
    ) {
        event.preventDefault();

        checkInput(packageInput, isPackageInputValidated, packageError);
        checkInput(typeInput, isTypeInputValidated, typeError);

        if (!isUploadInputValidated) {
            showAlert("Please upload image");
        }
    }
});

// show details of product to update
let productId, quantity;
let quantityInput = document.querySelector("#quantity");
let productInput = document.querySelector("#product_id");

const actionBtns = document.querySelectorAll(".action-btn");
actionBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
        productId = +btn.previousElementSibling.value;
        quantity = btn.nextElementSibling.value;

        quantityInput.value = quantity;
        for (let option of productInput.children) {
            if (parseInt(option.value) === productId) {
                option.selected = true;
            } else {
                option.selected = false;
            }
        }
    });
});
