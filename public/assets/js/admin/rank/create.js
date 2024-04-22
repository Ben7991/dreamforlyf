const nameInput = document.querySelector("#name");
let isNameInputValidated = false;
let nameError = "Name field is required";
nameInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        typeError = "Name field is required";
        isNameInputValidated = false;
    } else if (!/^[a-zA-Z ]+$/.test(value)) {
        typeError = "Only letters and whitespaces are allowed";
        isNameInputValidated = false;
    } else {
        typeError = "";
        isNameInputValidated = true;
    }

    checkInput(this, isNameInputValidated, typeError);
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

const awardInput = document.querySelector("#award");
let isAwardInputValidated = false;
let awardError = "Award field is required";
awardInput.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        awardError = "Award field is required";
        isAwardInputValidated = false;
    } else if (!/^[a-zA-Z0-9$ ]+$/.test(value)) {
        awardError = "Only numbers, letters and $ are allowed";
        isAwardInputValidated = false;
    } else {
        awardError = "";
        isAwardInputValidated = true;
    }

    checkInput(this, isAwardInputValidated, awardError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isNameInputValidated ||
        !isBvPointValidated ||
        !isAwardInputValidated
    ) {
        event.preventDefault();

        checkInput(nameInput, isNameInputValidated, typeError);
        checkInput(bvPoint, isBvPointValidated, bvError);
        checkInput(awardInput, isAwardInputValidated, awardError);
    }
});
