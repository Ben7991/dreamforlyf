$(document).ready(function() {
    $("#table").DataTable({paging: false});
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

    const myModalAlternative = new bootstrap.Modal('#exampleModal');
    const remainingDays = +document.querySelector("#remainingDays").value;

    if (remainingDays <= 15) {
        myModalAlternative.show();
    }
});
