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
        showMessage("Only image is allowed", "danger");
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

const currentPackageInput = document.querySelector("#current_package");
let isCurrentPackageInputValidated = true;
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
let isNextPackageInput = true;
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


const form = document.querySelector("#package-form");
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


// show details of product to update
let productId, quantity;

const quantityInput = document.querySelector("#quantity");
let isQuantityValidated = true;
let quantityInputError = "";
quantityInput.addEventListener("change", function() {
    const value = this.value;

    if (value === "") {
        quantityInputError = "Quantity is required";
        isQuantityValidated = false;
    } else if (!/^[0-9]+$/.test(value)) {
        quantityInputError = "Only numbers are allowed";
        isQuantityValidated = false;
    } else {
        quantityInputError = "";
        isQuantityValidated = true;
    }

    checkInput(this, isQuantityValidated, quantityInputError);
});

const productInput = document.querySelector("#product_id");
let isProductInputValidated = true;
let productError = "";
productInput.addEventListener("change", function() {
    const value = this.value;

    if (value === "") {
        productError = "Product is required";
        isProductInputValidated = false;
    } else {
        productError = "";
        isProductInputValidated = true;
    }

    checkInput(this, isProductInputValidated, productError);
});

const actionBtns = document.querySelectorAll(".edit-btn");
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


const productForm = document.querySelector("#form");
productForm.addEventListener("submit", function (event) {
    if (!isProductInputValidated || !isQuantityValidated) {
        event.preventDefault();

        checkInput(productInput, isProductInputValidated, productError);
        checkInput(quantityInput, isQuantityValidated, quantityInputError);
    }
});
