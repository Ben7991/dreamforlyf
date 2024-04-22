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


let quill_en = new Quill("#editor_en", {
    theme: "snow",
});

let quill_fr = new Quill("#editor_fr", {
    theme: "snow",
});

let description_en = document.querySelector("#description_en");
let description_fr = document.querySelector("#description_fr");
description_fr.value ? quill_fr.setContents(JSON.parse(description_fr.value)) : null;
description_en.value ? quill_en.setContents(JSON.parse(description_en.value)) : null;

const nameInput = document.querySelector("#name");
let isNameInputValidated = true;
let nameError = "";
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

const quantityInput = document.querySelector("#quantity");
let isQuantityValidated = true;
let quantityError = "";
quantityInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        quantityError = "Quantity field is required";
        isQuantityValidated = false;
    } else if (!/^[0-9]+$/.test(value)) {
        quantityError = "Only digits are allowed";
        isQuantityValidated = false;
    } else {
        quantityError = "";
        isQuantityValidated = true;
    }

    checkInput(this, isQuantityValidated, quantityError);
});

const bvPointInput = document.querySelector("#bv_point");
let isBvPointValidated = true;
let bvPointError = "Bv Point field is required";
bvPointInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        bvPointError = "Bv Point field is required";
        isBvPointValidated = false;
    } else if (!/^[0-9]+$/.test(value)) {
        bvPointError = "Only digits are allowed";
        isBvPointValidated = false;
    } else {
        bvPointError = "";
        isBvPointValidated = true;
    }

    checkInput(this, isBvPointValidated, bvPointError);
});

const priceInput = document.querySelector("#price");
let isPriceValidated = true;
let priceError = "";
priceInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        priceError = "Price field is required";
        isPriceValidated = false;
    } else if (!/^[0-9]+(\.[0-9]{2})*$/.test(value)) {
        priceError = "Invalid amount format";
        isPriceValidated = false;
    } else {
        priceError = "";
        isPriceValidated = true;
    }

    checkInput(this, isPriceValidated, priceError);
});

const uploadNotice = document.querySelector(".upload-notice");
const uploadImage = document.querySelector(".uploaded-image");
const hiddenInput = uploadImage.nextElementSibling;

if (hiddenInput.value === "") {
    uploadImage.classList.add("d-none");
} else {
    uploadNotice.classList.add("d-none");
}

const btnFileUpload = document.querySelector("#upload-btn");
const imageInput = document.querySelector("#image");
btnFileUpload.addEventListener("click", () => {
    imageInput.click();
});

let isUploadInputValidated = hiddenInput.value === "" ? false : true;
imageInput.addEventListener("change", function () {
    const files = this.files;
    const acceptMime = ["image/jpg", "image/jpeg", "image/png"];

    if (files.length === 0) {
        showAlert("Image is required");
        fileInput.value = "";
        isUploadInputValidated = false;
    }
    if (!acceptMime.includes(files[0].type)) {
        showAlert("Only image is allowed");
        fileInput.value = "";
        isUploadInputValidated = false;
    } else {
        displayUploadedImage(files[0]);
        isUploadInputValidated = true;
    }
});

function hideImage() {
    const notice = document.querySelector(".upload-notice");

    if (notice.classList.contains("d-none")) {
        document.querySelector(".uploaded-image").classList.add("d-none");
        document.querySelector(".uploaded-image").src = value;
        notice.classList.remove("d-none");
    }
}

function displayUploadedImage(file) {
    if (!file) {
        return;
    }

    document.querySelector(".upload-notice").classList.add("d-none");

    const fileReader = new FileReader();
    fileReader.readAsDataURL(file);
    fileReader.addEventListener("load", function () {
        document.querySelector(".uploaded-image").src = this.result;
        document.querySelector(".uploaded-image").classList.remove("d-none");
    });
}

const form = document.querySelector("#form");

form.addEventListener("submit", function (event) {
    if (
        !isNameInputValidated ||
        !isQuantityValidated ||
        !isUploadInputValidated ||
        !isPriceValidated ||
        !isBvPointValidated
    ) {
        event.preventDefault();

        checkInput(nameInput, isNameInputValidated, nameError);
        checkInput(quantityInput, isQuantityValidated, quantityError);
        checkInput(priceInput, isPriceValidated, priceError);
        checkInput(bvPointInput, isBvPointValidated, bvPointError);

        if (!isUploadInputValidated) {
            showAlert("Please upload image");
        }
    }

    description_fr.value = JSON.stringify(quill_fr.getContents());
    description_en.value = JSON.stringify(quill_en.getContents());
});
