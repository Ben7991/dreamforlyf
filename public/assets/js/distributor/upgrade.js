const stockist = document.querySelector("#stockist_id");
let isStockistValidated = false;
let stockistError = "Stockist is required";
stockist.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        stockistError = "Stockist is required";
        isStockistValidated = false;
    } else {
        stockistError = "";
        isStockistValidated = true;
    }

    checkInput(this, isStockistValidated, stockistError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (!isStockistValidated) {
        event.preventDefault();
        checkInput(stockist, isStockistValidated, stockistError);
    } else {
        deactiveActionButton(); //user experience
    }
});
