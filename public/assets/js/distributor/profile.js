const codeInput = document.querySelector("#code");
let isCodeValidated = false;
let codeError = "Pin field is required";
if (codeInput) {
    codeInput.addEventListener("change", function () {
        const value = this.value;

        if (value === "") {
            codeError = "Pin field is required";
            isCodeValidated = false;
        } else if (value === "1234") {
            codeError = "Invalid code",
            isCodeValidated = false;
        } else if (value.length < 4) {
            codeError = "Must be 4 digits or more"
            isCodeValidated = false;
        } else if (!/^[0-9]+$/.test(value)) {
            codeError = "Only numbers are allowed";
            isCodeValidated = false;
        }
        else {
            codeError = "";
            isCodeValidated = true;
        }

        checkInput(this, isCodeValidated, codeError);
    });
}


const withdrawalForm = document.querySelector("#withdrawal-form");
if (withdrawalForm) {
    withdrawalForm.addEventListener("submit", function (event) {
        if (!isCodeValidated) {
            event.preventDefault();
            checkInput(codeInput, isCodeValidated, codeError);
        }
    });
}



const currentPinInput = document.querySelector("#current_pin");
let isCurrentPinValidated = false;
let currentPinError = "Current PIN is required";
if (currentPinInput) {
    currentPinInput.addEventListener("change", function() {
        const value = this.value;

        if (value === "") {
            isCurrentPinValidated = false;
            currentPinError = "Old PIN is required";
        } else if (value === "1234") {
            currentPinError = "Invalid code",
            isCurrentPinValidated = false;
        } else if (value.length < 4) {
            currentPinError = "Must be 4 digits or more"
            isCurrentPinValidated = false;
        } else if (!/^[0-9]+$/.test(value)) {
            currentPinError = "Only numbers are allowed";
            isCurrentPinValidated = false;
        } else {
            currentPinError = "";
            isCurrentPinValidated = true;
        }

        checkInput(this, isCurrentPinValidated, currentPinError);
    });
}


const newPinInput = document.querySelector("#new_pin");
let isNewPinValidated = false;
let newPinError = "New PIN is required";
if (newPinInput) {
    newPinInput.addEventListener("change", function() {
        const value = this.value;

        if (value === "") {
            isNewPinValidated = false;
            newPinError = "New PIN is required";
        } else if (value === "1234") {
            newPinError = "Invalid code",
            isNewPinValidated = false;
        } else if (value.length < 4) {
            newPinError = "Must be 4 digits or more"
            isNewPinValidated = false;
        } else if (!/^[0-9]+$/.test(value)) {
            newPinError = "Only numbers are allowed";
            isNewPinValidated = false;
        } else {
            newPinError = "";
            isNewPinValidated = true;
        }

        checkInput(this, isNewPinValidated, newPinError);
    });
}


const confirmPinInput = document.querySelector("#confirm_pin");
let isConfirmPinInputValidated = false;
let confirmPinError = "Confirm PIN is required";
if (confirmPinInput) {
    confirmPinInput.addEventListener("change", function() {
        const value = this.value;

        if (value === "") {
            isConfirmPinInputValidated = false;
            confirmPinError = "Confirm PIN is required";
        } else if (value !== newPinInput.value) {
            confirmPinError = "PINs don't match each other",
            isConfirmPinInputValidated = false;
        } else {
            confirmPinError = "";
            isConfirmPinInputValidated = true;
        }

        checkInput(this, isConfirmPinInputValidated, confirmPinError);
    });
}


const changePinForm = document.querySelector("#change-withdrawal-form");
if (changePinForm) {
    changePinForm.addEventListener("submit", function(event) {
        if (!isCurrentPinValidated || !isNewPinValidated || !isConfirmPinInputValidated) {
            event.preventDefault();

            checkInput(currentPinInput, isCurrentPinValidated, currentPinError);
            checkInput(newPinInput, isNewPinValidated, newPinError);
            checkInput(confirmPinInput, isConfirmPinInputValidated, confirmPinError);
        }
    });
}
