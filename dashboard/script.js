const hamburger = document.querySelector(".toggle-btn");
const toggler = document.querySelector("#icon");

hamburger.addEventListener("click", function () {
    const sidebar = document.querySelector("#sidebar");
    sidebar.classList.toggle("expand");
    toggler.classList.toggle("bxs-chevrons-right");
    toggler.classList.toggle("bxs-chevrons-left");
});
