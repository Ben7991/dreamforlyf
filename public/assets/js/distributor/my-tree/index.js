let btnReferralLinks = document.querySelectorAll(".btn-referral-link");
let timer = null;

btnReferralLinks.forEach(btnLink => {
    btnLink.addEventListener("click", function() {
        if (timer) {
            clearTimeout(timer);
        }

        let inputElement = btnLink.previousElementSibling;
        inputElement.select();
        document.execCommand("copy");
        window.getSelection().removeAllRanges();

        let responseElement = btnLink.children[0];
        responseElement.classList.add("show");

        timer = setTimeout(() => {
            responseElement.classList.remove("show");
        }, 1000);
    });
});


let downlines = document.querySelectorAll(".downline");
let hiddenPersonSvg = document.querySelector("#person-svg");
let locale = document.querySelector("#locale").value;
downlines.forEach(downline => {
    downline.addEventListener("click", function() {
        $.ajax({
            url: `/${locale}/downline/${downline.dataset.id}/detail`,
            method: "GET",
            success: function(data, status, xhr) {
                toggleSpinner(true);
                showDetails(data);
                toggleErrorMessage(false);
            },
            error: function(xhr, status, error) {
                toggleSpinner(true);
                toggleErrorMessage(true);
            }
        });
    });
});

function toggleSpinner(hide) {
    const spinner = document.querySelector("#spinner-holder");

    if (hide) {
        spinner.classList.add("d-none");
    } else {
        spinner.classList.remove("d-none");
    }
}

function toggleErrorMessage(show) {
    const errorElement = document.querySelector("#modal-error-description");

    if (show) {
        errorElement.classList.remove("d-none");
    } else {
        errorElement.classList.add("d-none");
    }
}

function showDetails(data) {
    document.querySelector("#exampleModalLabel").textContent = "Downline Details";

    document.querySelector("#modal-content-description").classList.remove("d-none");

    document.querySelector("#modal-img").src = data.imagePath !== "none" ? data.imagePath : hiddenPersonSvg.value;
    document.querySelector("#modal-downline-name").textContent = `${data.name} - ${data.id}`;
    document.querySelector("#modal-downline-package").textContent = data.membershipPackage;
    document.querySelector("#rightBvPoint").textContent = data.rightBv;
    document.querySelector("#leftBvPoint").textContent = data.leftBv;
    document.querySelector("#leftBvDist").textContent = data.totalLeftDist,
    document.querySelector("#rightBvDist").textContent = data.totalRightDist,
    document.querySelector("#modal-link").href = data.link;
}

const myModalEl = document.getElementById('exampleModal');
myModalEl.addEventListener('hidden.bs.modal', event => {
    toggleSpinner(false);
    document.querySelector("#modal-content-description").classList.add("d-none");
    toggleErrorMessage(false);
});


const btnSearch = document.querySelector("#btn-search");
const inputSearch = document.querySelector("#input-search");

btnSearch.addEventListener("click", function() {
    const value = inputSearch.value;

    $.ajax({
        url: `/${locale}/users/${value}/detail`,
        method: "GET",
        success: function(data, status, xhr) {
            window.location.href = data.link;
        },
        error: function(xhr, status, error) {
            showMessage("Distributor doesn't exist", "danger");
        }
    });
});


