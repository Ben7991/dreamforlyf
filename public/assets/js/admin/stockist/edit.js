const nameInput = document.querySelector("#name");
let isNameInputValidated = true;
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

const email = document.querySelector("#email");
let isEmailValidated = true;
let emailError = "Email field is required";
email.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        emailError = "Email field is required";
        isEmailValidated = false;
        checkInput(this, isEmailValidated, emailError);
    } else if (
        !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)
    ) {
        emailError = "Invalid email address";
        isEmailValidated = false;
        checkInput(this, isEmailValidated, emailError);
    } else {
        $.ajax({
            url: `/users/${value}/check`,
            method: "GET",
            success: function (data, status, xhr) {
                isEmailValidated = true;
                emailError = "";
                checkInput(email, isEmailValidated, emailError);
            },
            error: function (xhr, status, error) {
                isEmailValidated = false;
                emailError = xhr.responseJSON.message;
                checkInput(email, isEmailValidated, emailError);
            },
        });
    }
});

const country = document.querySelector("#country");

window.onload = function () {
    const options = {
        method: "GET",
    };
    $.ajax({
        url: "https://restcountries.com/v3.1/independent?status=true",
        method: "GET",
        success: function (data, status, xhr) {
            let countries = data.sort((a, b) => {
                if (a.name.common > b.name.common) {
                    return 1;
                } else if (a.name.common < b.name.common) {
                    return -1;
                } else {
                    return 0;
                }
            });
            createDropDownList(countries);
        },
    });
};

function createDropDownList(countries) {
    for (let each of countries) {
        const option = document.createElement("option");
        option.textContent = each.name.common;
        option.value = each.name.common;

        country.appendChild(option);
    }
}

let isCountryValidated = true;
let countryError = "Country field is required";
country.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        countryError = "Country field is required";
        isCountryValidated = false;
    } else {
        countryError = "";
        isCountryValidated = true;
    }

    checkInput(this, isCountryValidated, countryError);
});

const city = document.querySelector("#city");
let isCityValidated = true;
let cityError = "City field is required";
city.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isCityValidated = false;
        cityError = "City field is required";
    } else {
        isCityValidated = true;
        cityError = "";
    }

    checkInput(this, isCityValidated, cityError);
});

const code = document.querySelector("#code");
let isCodeValidated = true,
    codeError = "Code field is required";
code.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isCodeValidated = false;
        codeError = "Code field is required";
    } else {
        isCodeValidated = true;
        codeError = "";
    }

    checkInput(code, isCodeValidated, codeError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isNameInputValidated ||
        !isEmailValidated ||
        !isCountryValidated ||
        !isCityValidated ||
        !isCodeValidated
    ) {
        event.preventDefault();

        checkInput(nameInput, isNameInputValidated, nameError);
        checkInput(email, isEmailValidated, emailError);
        checkInput(country, isCountryValidated, countryError);
        checkInput(city, isCityValidated, cityError);
        checkInput(code, isCodeValidated, codeError);
    }
});

let amount = document.querySelector("#amount");
let isAmountValidated = false,
    amountError = "Amount field is required";
amount.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isAmountValidated = false;
        amountError = "Amount field is required";
    } else if (!/^[0-9]+(\.[0-9]{2})?$/.test(value)) {
        isAmountValidated = false;
        amountError = "Invalid amount";
    } else {
        isAmountValidated = true;
        amountError = "";
    }

    checkInput(this, isAmountValidated, amountError);
});

const transferForm = document.querySelector("#transfer-form");
transferForm.addEventListener("submit", function (event) {
    if (!isAmountValidated) {
        event.preventDefault();

        checkInput(amount, isAmountValidated, amountError);
    }
});
