const nameInput = document.querySelector("#name");
let isNameInputValidated = false;
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

const bvPoint = document.querySelector("#bv_point");
let isBvPointValidated = false;
let bvError = "BV Point field is required";
bvPoint.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        bvError = "BV Point field is required";
        isBvPointValidated = false;
    } else if (!/^[0-9]+$/.test(value)) {
        bvError = "Only digits are allowed";
        isBvPointValidated = false;
    } else {
        bvError = "";
        isBvPointValidated = true;
    }

    checkInput(this, isBvPointValidated, bvError);
});

const priceInput = document.querySelector("#price");
let isPriceInputValidated = false;
let priceError = "Price field is required";
priceInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        priceError = "Price field is required";
        isPriceInputValidated = false;
    } else if (!/^[0-9]+(\.[0-9]{2})*$/.test(value)) {
        priceError = "Only numbers are allowed";
        isPriceInputValidated = false;
    } else {
        priceError = "";
        isPriceInputValidated = true;
    }

    checkInput(this, isPriceInputValidated, priceError);
});

const cutOffInput = document.querySelector("#cutoff");
let isCutOffValidated = false;
let cutOffError = "Cutoff field is required";
cutOffInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        cutOffError = "Cutoff field is required";
        isCutOffValidated = false;
    } else if (!/^[0-9]+(\.[0-9]{2})*$/.test(value)) {
        cutOffError = "Only numbers are allowed";
        isCutOffValidated = false;
    } else {
        cutOffError = "";
        isCutOffValidated = true;
    }

    checkInput(this, isCutOffValidated, cutOffError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isNameInputValidated ||
        !isBvPointValidated ||
        !isPriceInputValidated ||
        !isCutOffValidated
    ) {
        event.preventDefault();

        checkInput(nameInput, isNameInputValidated, nameError);
        checkInput(bvPoint, isBvPointValidated, bvError);
        checkInput(priceInput, isPriceInputValidated, priceError);
        checkInput(cutOffInput, isCutOffValidated, cutOffError);
    }
});
