$(document).ready(function () {
    $("#product-table").DataTable();
});

const amount = document.querySelector("#amount");
let isAmountValidated = false;
let amountError = "Amount field is required";
amount.addEventListener("change", function () {
    const value = this.value;

    if (value === "") {
        isAmountValidated = false;
        amountError = "Amount field is required";
    } else if (!/^[0-9]+(\.[0-9]{2})*$/.test(value)) {
        isAmountValidated = false;
        amountError = "Only digits are allowed";
    } else {
        isAmountValidated = true;
        amountError = "";
    }

    checkInput(amount, isAmountValidated, amountError);
});

const form = document.querySelector("#form");
form.addEventListener("submit", function (event) {
    if (!isAmountValidated) {
        event.preventDefault();

        checkInput(amount, isAmountValidated, amountError);
    }
});

const nameInput = document.querySelector("#name");
const searchInput = document.querySelector("#search");
const locale = document.querySelector("#locale");
const loader = document.querySelector(".loader");
const searchError = document.querySelector("#search-error");

const btnSearch = document.querySelector("#search-btn");
btnSearch.addEventListener("click", function () {
    const searchTerm = searchInput.value;

    if (searchTerm === "") {
        return;
    }

    loader.classList.remove("d-none");
    searchInput.value = "";
    !searchError.classList.contains("d-none")
        ? searchError.classList.add("d-none")
        : null;

    $.ajax({
        url: `/users/${searchTerm}`,
        method: "GET",
        success: function (data, xhr, status) {
            nameInput.value = data.data.name;
            form.action = `/${locale.value}/stockist/transfer-wallet/${data.data.id}`;
            loader.classList.add("d-none");
        },
        error: function (xhr, status, error) {
            loader.classList.add("d-none");
            searchError.classList.remove("d-none");
        },
    });
});
