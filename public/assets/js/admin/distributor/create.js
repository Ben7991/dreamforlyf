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

const uplineId = document.querySelector("#upline_id");
let isUplineIDValidated = false;
let uplineIdError = "Upline ID field is required";
uplineId.addEventListener("change", function () {
    const value = this.value;
    const accronym = value.substring(0, 3);

    if (value === "") {
        uplineIdError = "Upline ID field is required";
        isUplineIDValidated = false;
        checkInput(this, isUplineIDValidated, uplineIdError);
    } else if (accronym !== "DFL") {
        uplineIdError = "Should start with DFL";
        isUplineIDValidated = false;
        checkInput(this, isUplineIDValidated, uplineIdError);
    } else {
        $.ajax({
            url: `/users/${value}`,
            method: "GET",
            success: function (data, status, xhr) {
                uplineIdError = "";
                isUplineIDValidated = true;
                checkInput(uplineId, isUplineIDValidated, uplineIdError);
            },
            error: function (xhr, status, error) {
                isUplineIDValidated = false;
                uplineIdError = xhr.responseJSON.message;
                checkInput(uplineId, isUplineIDValidated, uplineIdError);
            },
        });
    }
});

const referralId = document.querySelector("#referral_id");
let isReferralIdValidated = false;
let referralIdError = "Referral ID field is required";
referralId.addEventListener("change", function () {
    const value = this.value;
    const accronym = value.substring(0, 3);

    if (value === "") {
        referralIdError = "Referral ID field is required";
        isReferralIdValidated = false;
        checkInput(this, isReferralIdValidated, referralIdError);
    } else if (accronym !== "DFL") {
        referralIdError = "Should start with DFL";
        isReferralIdValidated = false;
        checkInput(this, isReferralIdValidated, referralIdError);
    } else {
        $.ajax({
            url: `/users/${value}`,
            method: "GET",
            success: function (data, status, xhr) {
                referralIdError = "";
                isReferralIdValidated = true;
                checkInput(referralId, isReferralIdValidated, referralIdError);
            },
            error: function (xhr, status, error) {
                isReferralIdValidated = false;
                referralIdError = xhr.responseJSON.message;
                checkInput(referralId, isReferralIdValidated, referralIdError);
            },
        });
    }
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

let isPackageTypeValidated = false;

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
        radioButton.addEventListener("change", () => {
            isPackageTypeValidated = true;
        });

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

const stockist = document.querySelector("#stockist_id");
let isStockistValidated = false,
    stockistError = "Stockist field is required";
stockist.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isStockistValidated = false;
        stockistError = "Stockist field is required";
    } else {
        isStockistValidated = true;
        stockistError = "";
    }

    checkInput(stockist, isStockistValidated, stockistError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isNameInputValidated ||
        !isEmailValidated ||
        !isUplineIDValidated ||
        !isReferralIdValidated ||
        !isCountryValidated ||
        !isPhoneNumberValidated ||
        !isWaveValidated ||
        !isPackageValidated ||
        !isStockistValidated ||
        !isPackageTypeValidated ||
        !isCityValidated
    ) {
        event.preventDefault();

        checkInput(nameInput, isNameInputValidated, nameError);
        checkInput(email, isEmailValidated, emailError);
        checkInput(uplineId, isUplineIDValidated, uplineIdError);
        checkInput(referralId, isReferralIdValidated, referralIdError);
        checkInput(country, isCountryValidated, countryError);
        checkInput(phoneNumber, isPhoneNumberValidated, phoneNumberError);
        checkInput(wave, isWaveValidated, waveNumberError);
        checkInput(package, isPackageValidated, packageError);
        checkInput(stockist, isStockistValidated, stockistError);
        checkInput(city, isCityValidated, cityError);

        if (!isPackageTypeValidated) {
            showMessage("Select package type", "danger");
        }
    } else {
        deactiveActionButton();
    }
});
