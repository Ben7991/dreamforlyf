window.onload = function () {
    let alertResponse = document.querySelector(".alert-response");
    if (alertResponse) {
        setTimeout(() => {
            alertResponse.remove();
        }, 3000);
    }
};
