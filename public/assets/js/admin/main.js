/** drawer menu toggle */
const drawer = document.querySelector(".content-drawer");
const backDrop = document.querySelector(".backdrop");
let backDropTimer = null;

const hamburger = document.querySelector(".main-hamburger");
hamburger.addEventListener("click", function () {
    backDrop.classList.add("show");
    drawer.classList.add("show");
});

backDrop.addEventListener("click", function () {
    if (!backDropTimer) {
        clearTimeout(backDropTimer);
    }

    drawer.classList.remove("show");
    backDropTimer = setTimeout(function () {
        backDrop.classList.remove("show");
    }, 501);
});

/**
 * all popovers on the page
 */
const popoverTriggerList = document.querySelectorAll(
    '[data-bs-toggle="popover"]'
);
const popoverList = [...popoverTriggerList].map(
    (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
);

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

/**
 * set form action during deletion or updating of content
 */
function setFormAction(formAction) {
    document.querySelector("#form").action = formAction;
}

function hideImage() {
    const notice = document.querySelector(".upload-notice");

    if (notice.classList.contains("d-none")) {
        document.querySelector(".uploaded-image").classList.add("d-none");
        document.querySelector(".uploaded-image").src = value;
        notice.classList.remove("d-none");
    }
}

function displayUploadedImage(file) {
    if (!file) {
        return;
    }

    document.querySelector(".upload-notice").classList.add("d-none");

    const fileReader = new FileReader();
    fileReader.readAsDataURL(file);
    fileReader.addEventListener("load", function () {
        document.querySelector(".uploaded-image").src = this.result;
        document.querySelector(".uploaded-image").classList.remove("d-none");
    });
}

let alertMessageModal = null;
let alertMessageModalTimer = null;

function showMessage(message, type) {
    if (alertMessageModal) return;

    if (alertMessageModalTimer) {
        clearTimeout(alertMessageModalTimer);
    }

    alertMessageModal = document.createElement("div");
    alertMessageModal.className = `alert alert-${type} alert-dismissible fade show alert-response`;
    alertMessageModal.setAttribute("role", "alert");

    const alertMessage = document.createElement("div");
    alertMessage.textContent = message;

    const alertButton = document.createElement("button");
    alertButton.className = "btn-close";
    alertButton.setAttribute("data-bs-dismiss", "alert");
    alertButton.setAttribute("type", "button");
    alertButton.setAttribute("aria-label", "close");

    alertMessageModal.appendChild(alertMessage);
    alertMessageModal.appendChild(alertButton);
    document.querySelector("body").appendChild(alertMessageModal);

    alertMessageModalTimer = setTimeout(function () {
        alertMessageModal.remove();
        alertMessageModal = null;
    }, 2000);
}

function deactiveActionButton() {
    document.querySelector(".btn-submit").disabled = true;
    document.querySelector(".main-btn").classList.add("d-none");
    document.querySelector(".loader").classList.remove("d-none");
}
