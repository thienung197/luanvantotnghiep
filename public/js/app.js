//Su kien click khi co submenu
const liActivity = document.querySelectorAll("li");
liActivity.forEach(function (li) {
    let submenu = li.querySelector(".angle-down");
    if (submenu) {
        let link = li.querySelector("a");
        link.addEventListener("click", function (e) {
            e.preventDefault();
        });
    }
    li.addEventListener("click", function () {
        li.classList.toggle("show");
    });
});
