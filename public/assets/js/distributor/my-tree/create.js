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

const uplineIdEmail = document.querySelector("#upline_id_email");
let isUplineIdEmailValidated = false,
    uplineIdEmailError = "Upline ID / Email is required";
uplineIdEmail.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isUplineIdEmailValidated = false;
        uplineIdEmailError = "Upline ID / Email is required";
        checkInput(this, isUplineIdEmailValidated, uplineIdEmailError);
    } else if (value !== "") {
        $.ajax({
            url: `/distributor/${value}/credential`,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": document.querySelector("#token").value,
            },
            success: function (data, status, xhr) {
                isUplineIdEmailValidated = true;
                uplineIdEmailError = "";

                uplineIdEmail.nextElementSibling.textContent = data.message;
                uplineIdEmail.nextElementSibling.classList.add("text-success");
                uplineIdEmail.nextElementSibling.classList.remove(
                    "text-danger"
                );
                uplineIdEmail.classList.remove("border-danger");
            },
            error: function (xhr, status, error) {
                isUplineIdEmailValidated = false;
                uplineIdEmailError = xhr.responseJSON.message;

                uplineIdEmail.nextElementSibling.textContent =
                    xhr.responseJSON.message;
                uplineIdEmail.nextElementSibling.classList.remove(
                    "text-success"
                );
                uplineIdEmail.nextElementSibling.classList.add("text-danger");
                uplineIdEmail.classList.add("border-danger");
            },
        });
    }
});

const country = document.querySelector("#country");
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

let city = document.querySelector("#city");
let isCityValidated = false;
let cityError = "City field is required";
city.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        cityError = "City field is required";
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
    } else if (!/^[+]*[0-9]+$/.test(value)) {
        phoneNumberError = "Invalid phone number";
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

const stockistInput = document.querySelector("#stockist_id");
let isStockistValidated = false;
let stockistError = "Stockist field is required";
stockistInput.addEventListener("change", function () {
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

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (
        !isNameInputValidated ||
        !isEmailValidated ||
        !isCountryValidated ||
        !isStockistValidated ||
        !isPhoneNumberValidated ||
        !isWaveValidated ||
        !isPackageValidated ||
        !isCityValidated ||
        !isUplineIdEmailValidated
    ) {
        event.preventDefault();

        checkInput(nameInput, isNameInputValidated, nameError);
        checkInput(email, isEmailValidated, emailError);
        checkInput(country, isCountryValidated, countryError);
        checkInput(phoneNumber, isPhoneNumberValidated, phoneNumberError);
        checkInput(wave, isWaveValidated, waveNumberError);
        checkInput(package, isPackageValidated, packageError);
        checkInput(stockistInput, isStockistValidated, stockistError);
        checkInput(city, isCityValidated, cityError);
        checkInput(uplineIdEmail, isUplineIdEmailValidated, uplineIdEmailError);

        if (!isPackageTypeValidated) {
            showMessage("Select package type", "danger");
        }
    } else {
        deactiveActionButton(); //user experience
    }
});
