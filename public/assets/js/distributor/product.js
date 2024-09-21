const quantity = document.querySelector("#quantity");
let isQuantityValidated = false;
let quantityError = "Quantity is required";
quantity.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isQuantityValidated = false;
        quantityError = "Quantity is required";
    } else if (!/^[0-9]+$/.test(value)) {
        isQuantityValidated = false;
        quantityError = "Only numbers are required";
    } else {
        isQuantityValidated = true;
        quantityError = "";
    }

    checkInput(this, isQuantityValidated, quantityError);
});

const stockist = document.querySelector("#stockist");
let isStockistValidated = false;
let stockistError = "Stockist is required";
stockist.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isStockistValidated = false;
        stockistError = "Stockist is required";
    } else {
        isStockistValidated = true;
        stockistError = "";
    }

    checkInput(this, isStockistValidated, stockistError);
});

const purchase = document.querySelector("#purchase");
let isPurchaseValidated = false;
let purchaseError = "Purchase type is required";
purchase.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isPurchaseValidated = false;
        purchaseError = "Purchase type is required";
    } else {
        isPurchaseValidated = true;
        purchaseError = "";

        let quantityHolder = document.querySelector("#quantity-holder");

        if (value === "direct") {
            quantityHolder.classList.remove("d-none");
        }
        else {
            quantityHolder.classList.add("d-none");
        }
    }

    checkInput(this, isPurchaseValidated, purchaseError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (purchase.value === "direct") {
        if (!isQuantityValidated || !isStockistValidated || !isPurchaseValidated) {
            event.preventDefault();

            checkInput(stockist, isStockistValidated, stockistError);
            checkInput(purchase, isPurchaseValidated, purchaseError);
            checkInput(quantity, isQuantityValidated, quantityError);
        } else {
            deactiveActionButton(); //user experience
        }
    }
    else if (purchase.value === "maintenance") {
        if (!isStockistValidated || !isPurchaseValidated) {
            event.preventDefault();

            checkInput(stockist, isStockistValidated, stockistError);
            checkInput(purchase, isPurchaseValidated, purchaseError);
        } else {
            deactiveActionButton(); //user experience
        }
    }
});
