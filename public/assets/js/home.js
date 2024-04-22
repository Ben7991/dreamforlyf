const hamburger = document.querySelector(".navigation-hamburger");
const collapsibleNavigation = document.querySelector('.navigation-collapse');
const backDrop = document.querySelector(".backdrop");

hamburger.addEventListener("click", function() {
    backDrop.classList.add("show");
    collapsibleNavigation.classList.add("show");
});

backDrop.addEventListener("click", function() {
    backDrop.classList.remove("show");
    collapsibleNavigation.classList.remove("show");
});
