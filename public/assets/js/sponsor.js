/**
 * performs validation
 */
function checkInput(element, status, errorMessage = "") {
    if (status) {
        element.nextElementSibling.classList.contains("d-none")
            ? null
            : element.nextElementSibling.classList.add("d-none");
        element.classList.remove("border-danger");
    } else {
        element.nextElementSibling.classList.remove("d-none");
        element.classList.add("border-danger");
    }

    element.nextElementSibling.textContent = errorMessage;
}

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

const email = document.querySelector("#email");
let isEmailValidated = false;
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

let isCountryValidated = false;
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
let isCityValidated = false;
let cityError = "City is required";
city.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        cityError = "City is required";
        isCityValidated = false;
    } else {
        cityError = "";
        isCityValidated = true;
    }

    checkInput(this, isCityValidated, cityError);
});

const phoneNumber = document.querySelector("#phone_number");
let isPhoneNumberValidated = false;
let phoneNumberError = "Phone number field is required";
phoneNumber.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        phoneNumberError = "Phone number field is required";
        isPhoneNumberValidated = false;
    } else {
        phoneNumberError = "";
        isPhoneNumberValidated = true;
    }

    checkInput(this, isPhoneNumberValidated, phoneNumberError);
});

const wave = document.querySelector("#wave");
let isWaveValidated = false;
let waveNumberError = "Wave number field is required";
wave.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isWaveValidated = false;
        waveNumberError = "Wave number is required";
    } else if (!/^[+]*[0-9]+$/.test(value)) {
        isWaveValidated = false;
        waveNumberError = "Invalid wave number";
    } else {
        isWaveValidated = true;
        waveNumberError = "";
    }

    checkInput(this, isWaveValidated, waveNumberError);
});

const stockist = document.querySelector("#stockist_id");
let isStockistValidated = false;
let stockistError = "Stockist field is required";
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

const package = document.querySelector("#package_id");
const packageSpinner = document.querySelector("#package-spinner");
let isPackageValidated = false,
    packageError = "Registration package is required";
package.addEventListener("change", function () {
    const value = this.value;
    document.querySelector("#package-type-holder").innerHTML = "";

    if (value === "") {
        isPackageValidated = false;
        packageError = "Registration package is required";
    } else {
        isPackageValidated = true;
        packageError = "";
        packageSpinner.classList.remove("d-none");

        $.ajax({
            url: `/registration-packages/${value}/detail`,
            method: "GET",
            success: function (data, status, xhr) {
                packageSpinner.classList.add("d-none");
                createPackageTypes(data.data);
            },
            error: function (xhr, status, error) {
                isPackageValidated = false;
                packageError = xhr.responseJSON.message;
                checkInput(package, isPackageValidated, packageError);
            },
        });
    }

    checkInput(this, isPackageValidated, packageError);
});

let isTypeValidated = false;
function createPackageTypes(packages) {
    const packageHolder = document.querySelector("#package-type-holder");

    for (let package of packages) {
        const column = document.createElement("div");
        column.className = "col-12 col-md-4 col-xl-3 col-xxl-2";

        const formCheck = document.createElement("div");
        formCheck.className = "form-check";

        const radioButton = document.createElement("input");
        radioButton.setAttribute("type", "radio");
        radioButton.setAttribute("name", "type");
        radioButton.className = "form-check-input";
        radioButton.id = package.name;
        radioButton.value = package.id;
        radioButton.addEventListener("change", () => (isTypeValidated = true));

        const detailHolder = document.createElement("div");
        detailHolder.className = "d-flex flex-column";

        const label = document.createElement("label");
        label.setAttribute("for", package.name);
        label.className = "form-check-label";
        label.textContent = `Type ${package.name}`;

        const image = document.createElement("img");
        image.className = "img-fluid img-thumbnail";
        image.src = `http://localhost:8000/${package.path}`;
        image.addEventListener("click", function () {
            document.querySelector(".img-modal-image").src = this.src;
            document.querySelector(".img-modal").classList.add("show");
        });

        detailHolder.appendChild(label);
        detailHolder.appendChild(image);

        formCheck.appendChild(radioButton);
        formCheck.appendChild(detailHolder);

        column.appendChild(formCheck);

        packageHolder.appendChild(column);
    }
}

const btnToggleImageModal = document.querySelector(".img-modal-btn");
btnToggleImageModal.addEventListener("click", function () {
    document.querySelector(".img-modal").classList.remove("show");
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isNameInputValidated ||
        !isEmailValidated ||
        !isCountryValidated ||
        !isPhoneNumberValidated ||
        !isWaveValidated ||
        !isPackageValidated ||
        !isTypeValidated ||
        !isStockistValidated ||
        !isCityValidated
    ) {
        event.preventDefault();

        checkInput(nameInput, isNameInputValidated, nameError);
        checkInput(email, isEmailValidated, emailError);
        checkInput(country, isCountryValidated, countryError);
        checkInput(phoneNumber, isPhoneNumberValidated, phoneNumberError);
        checkInput(wave, isWaveValidated, waveNumberError);
        checkInput(package, isPackageValidated, packageError);
        checkInput(stockist, isStockistValidated, stockistError);
        checkInput(city, isCityValidated, cityError);

        if (!isTypeValidated) {
            showAlert("Please select package type");
        }
    } else {
        document.querySelector(".btn-submit").disabled = true;
        document.querySelector(".main-btn").classList.add("d-none");
        document.querySelector(".loader").classList.remove("d-none");
    }
});
