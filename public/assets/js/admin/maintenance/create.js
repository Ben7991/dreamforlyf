const durationInMonth = document.querySelector("#duration_in_months");
let isDurationValidated = false;
let durationError = "Duration in months field is required";
durationInMonth.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        durationError = "Duration in months field is required";
        isDurationValidated = false;
    } else if (!/^[0-9]+$/.test(value)) {
        durationError = "Only digits are allowed";
        isDurationValidated = false;
    } else {
        durationError = "";
        isDurationValidated = true;
    }

    checkInput(this, isDurationValidated, durationError);
});

const totalProducts = document.querySelector("#total_products");
let totalProductsError = "Product number is required";
let isTotalProductValidated = false;
totalProducts.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        totalProductsError = "Product number is required";
        isTotalProductValidated = false;
    } else if (!/^[0-9]+$/.test(value)) {
        totalProductsError = "Only digits are allowed";
        isTotalProductValidated = false;
    } else {
        isTotalProductValidated = true;
        totalProductsError = "";
    }

    checkInput(this, isTotalProductValidated, totalProductsError);
});

const priceInput = document.querySelector("#total_price");
let priceError = "Amount is required";
let isPriceValidated = false;
priceInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        priceError = "Amount is required";
        isPriceValidated = false;
    } else if (!/^[0-9]+(\.[0-9]{2})*$/.test(value)) {
        priceError = "Only digits with two decimal places are allowed";
        isPriceValidated = false;
    } else {
        isPriceValidated = true;
        priceError = "";
    }

    checkInput(this, isPriceValidated, priceError);
});

const bvPoint = document.querySelector("#bv_point");
let bvPointError = "Bv point is required";
let isBvPointValidated = false;
bvPoint.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        bvPointError = "Bv point is required";
        isBvPointValidated = false;
    } else if (!/^[0-9]+$/.test(value)) {
        bvPointError = "Only digits are allowed";
        isBvPointValidated = false;
    } else {
        isBvPointValidated = true;
        bvPointError = "";
    }

    checkInput(this, isBvPointValidated, bvPointError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isDurationValidated ||
        !isTotalProductValidated ||
        !isPriceValidated ||
        !isBvPointValidatedawardInput
    ) {
        event.preventDefault();

        checkInput(durationInMonth, isDurationValidated, durationError);
        checkInput(totalProducts, isTotalProductValidated, totalProductsError);
        checkInput(priceInput, isPriceValidated, priceError);
        checkInput(bvPoint, isBvPointValidated, bvPointError);
    }
});
