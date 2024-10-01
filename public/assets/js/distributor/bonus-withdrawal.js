const amount = document.querySelector("#amount");
let isAmountValidated = false;
let amountError = "Amount field is required";

amount.addEventListener("change", function() {
    const value = this.value;
    const wallet = +document.querySelector("#wallet").value;

    if (value === "") {
        isAmountValidated = false;
        amountError = "Amount field is required";
    }
    else if (!/^[0-9]+(\.[0-9]{2})*$/.test(value)) {
        isAmountValidated = false;
        amountError = "Invalid amount";
    }
    else if (wallet === 0 || +value > +wallet ) {
        isAmountValidated = false;
        amountError = "Insufficient commission balance to make withdrawal";
    }
    else {
        isAmountValidated = true,
        amountError = "";
    }
    checkInput(this, isAmountValidated, amountError);
});

const code = document.querySelector("#code");
let isCodeValidated = false;
let codeError = "Code field is required";

code.addEventListener("change", function() {
    const value = this.value;

    if (value === "") {
        codeError = "Code field is required";
        isCodeValidated = false;
    } else {
        codeError = "";
        isCodeValidated = true;
    }

    checkInput(this, isCodeValidated, codeError);
});


const mode = document.querySelector("#mode");
let isModeValidated = false;
let modeError = "Payment mode is required";
mode.addEventListener("change", function() {
    const value = this.value;

    if (value === "") {
        modeError = "Payment mode is required";
        isModeValidated = false;
    } else {
        modeError = "";
        isModeValidated = true;
    }

    checkInput(this, isModeValidated, modeError);
});


const form = document.querySelector("#form");
form.addEventListener("submit", function(event) {
    if (!isAmountValidated || !isCodeValidated || !isModeValidated) {
        event.preventDefault();

        checkInput(amount, isAmountValidated, amountError);
        checkInput(code, isCodeValidated, codeError);
        checkInput(mode, isModeValidated, modeError);
    }
});
