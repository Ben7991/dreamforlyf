const fullName = document.querySelector("#full_name");
let isFullNameValidated = false;
let fullNameError = "Full name is required";

fullName.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        fullNameError = "Full name is required";
        isFullNameValidated = false;
    }
    else if (!/^[a-zA-Z ]*$/.test(value)) {
        fullNameError = "Only letters and whitespaces are allowed";
        isFullNameValidated = false;
    }
    else {
        fullNameError = "";
        isFullNameValidated = true;
    }

    checkInput(this, isFullNameValidated, fullNameError);
});


const bankName = document.querySelector("#bank_name");
let isBankNameValidated = false;
let bankNameError = "Bank name is required";

bankName.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        bankNameError = "Bank name is required";
        isBankNameValidated = false;
    }
    else {
        bankNameError = "";
        isBankNameValidated = true;
    }

    checkInput(this, isBankNameValidated, bankNameError);
});


const bankBranch = document.querySelector("#bank_branch");
let isBankBranchValidated = false;
let bankBranchError = "Bank branch is required";

bankBranch.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        bankBranchError = "Bank branch is required";
        isBankBranchValidated = false;
    }
    else {
        bankBranchError = "";
        isBankBranchValidated = true;
    }

    checkInput(this, isBankBranchValidated, bankBranchError);
});


const beneficiary = document.querySelector("#beneficiary_name");
let isBeneficiaryValidated = false;
let beneficiaryError = "Bank branch is required";

beneficiary.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        beneficiaryError = "Bank branch is required";
        isBeneficiaryValidated = false;
    }
    else if (!/^[a-zA-Z ]*$/.test(value)) {
        beneficiaryError = "Only letters and whitespaces are allowed";
        isBeneficiaryValidated = false;
    }
    else {
        beneficiaryError = "";
        isBeneficiaryValidated = true;
    }

    checkInput(this, isBeneficiaryValidated, beneficiaryError);
});


const accountNumber = document.querySelector("#account_number");
let isAccountNumberValidated = false;
let accountNumberError = "Account number is required";

accountNumber.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        accountNumberError = "Account number is required";
        isAccountNumberValidated = false;
    }
    else if (!/^[0-9]*$/.test(value)) {
        accountNumberError = "Only numbers are allowed";
        isAccountNumberValidated = false;
    }
    else {
        accountNumberError = "";
        isAccountNumberValidated = true;
    }

    checkInput(this, isAccountNumberValidated, accountNumberError);
});


const iban = document.querySelector("#iban");
let isIbanValidated = false;
let ibanError = "Iban number is required";

iban.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        ibanError = "Iban number is required";
        isIbanValidated = false;
    }
    else if (!/^[0-9]*$/.test(value)) {
        ibanError = "Only numbers are allowed";
        isIbanValidated = false;
    }
    else {
        ibanError = "";
        isIbanValidated = true;
    }

    checkInput(this, isIbanValidated, ibanError);
});


const swift = document.querySelector("#swift_number");
let isSwiftNumberValidated = false;
let swiftNumberError = "Swift number is required";

swift.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        swiftNumberError = "Swift number is required";
        isSwiftNumberValidated = false;
    }
    else if (!/^[0-9]*$/.test(value)) {
        swiftNumberError = "Only numbers are allowed";
        isSwiftNumberValidated = false;
    }
    else {
        swiftNumberError = "";
        isSwiftNumberValidated = true;
    }

    checkInput(this, isSwiftNumberValidated, swiftNumberError);
});


const phoneNumber = document.querySelector("#phone_number");
let isPhoneNumberValidated = false;
let phoneNumberError = "Phone number is required";

phoneNumber.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        phoneNumberError = "Phone number is required";
        isPhoneNumberValidated = false;
    }
    else if (!/^\+[0-9]*$/.test(value)) {
        phoneNumberError = "Only numbers are allowed";
        isPhoneNumberValidated = false;
    }
    else {
        phoneNumberError = "";
        isPhoneNumberValidated = true;
    }

    checkInput(this, isPhoneNumberValidated, phoneNumberError);
});


const bankDetailsForm = document.querySelector("#bank-details-form");
bankDetailsForm.addEventListener("submit", function(event) {
    if (!isFullNameValidated || !isBankNameValidated || !isBankBranchValidated
        || !isBeneficiaryValidated || !isAccountNumberValidated || !isIbanValidated
        || !isSwiftNumberValidated || !isPhoneNumberValidated
    ) {
        event.preventDefault();

        checkInput(fullName, isFullNameValidated, fullNameError);
        checkInput(bankName, isBankNameValidated, bankNameError);
        checkInput(bankBranch, isBankBranchValidated, bankBranchError);
        checkInput(beneficiary, isBeneficiaryValidated, beneficiaryError);
        checkInput(accountNumber, isAccountNumberValidated, accountNumberError);
        checkInput(iban, isIbanValidated, ibanError);
        checkInput(swift, isSwiftNumberValidated, swiftNumberError);
        checkInput(phoneNumber, isPhoneNumberValidated, phoneNumberError);
    }
});
