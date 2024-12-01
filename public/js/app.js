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
//Luu vi tri cuon
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("menu-container");
    const savedPosition = localStorage.getItem("sidebarScrollPosition");
    if (savedPosition) {
        sidebar.scrollTop = savedPosition;
    }
    sidebar.addEventListener("scroll", () => {
        localStorage.setItem("sidebarScrollPosition", sidebar.scrollTop);
    });
});

//su kien sub menu
document.addEventListener("DOMContentLoaded", () => {
    const menuItems = document.querySelectorAll("#menu-container li > a");
    menuItems.forEach((menuItem) => {
        menuItem.addEventListener("click", (e) => {
            // e.preventDefault();
            const subMenu = this.nextElementSibling;
            if (subMenu) {
                subMenu.style.display =
                    subMenu.style.display === "block" ? "none" : "block";
            }
        });
    });
    const active = document.querySelector(".bg-blue");
    if (active) {
        const submenu = active.closest(".sub-menu");
        if (submenu) {
            submenu.style.display = "block";
        }
    }
});
//Su kien load hinh anh
$(() => {
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(".show-image img").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("input[name='image']").change(function () {
        readURL(this);
    });
});

//Su kien xac nhan xoa
function confirmDelete() {
    return new Promise((resolve, reject) => {
        Swal.fire({
            title: "Bạn có chắc chắn?",
            text: "Bạn sẽ không thể hoàn tác hành động này!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Vâng, xóa nó!",
            cancelButtonText: "Hủy bỏ",
        }).then((result) => {
            if (result.isConfirmed) {
                resolve(true);
            } else {
                reject(false);
            }
        });
    });
}

$(document).on("click", ".btn-delete", function (e) {
    e.preventDefault();
    let id = $(this).data("id");
    confirmDelete()
        .then(function () {
            $(`#form-delete${id}`).submit();
        })
        .catch();
});

//Su kien toast
// Set toastr options
toastr.options = {
    closeButton: true, // Show close button
    debug: false,
    positionClass: "toast-top-right", // Position of the toast
    onclick: null,
    showDuration: "300", // Show duration in milliseconds
    hideDuration: "1000", // Hide duration in milliseconds
    timeOut: "5000", // Time before it disappears (in milliseconds)
    extendedTimeOut: "1000", // Time after hover before it disappears (in milliseconds)
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn", // Show animation method
    hideMethod: "fadeOut", // Hide animation method
};

//js cho don hang

// document.addEventListener("DOMContentLoaded", function () {
//     const rows = document.querySelectorAll(".goods-issue-row");

//     rows.forEach((row) => {
//         row.addEventListener("click", function () {
//             const goodsIssueId = this.getAttribute("data-id");

//             const detailsRow = document.getElementById(
//                 `details-${goodsIssueId}`
//             );

//             if (detailsRow.style.display === "none") {
//                 detailsRow.style.display = "table-row";
//                 row.style.backgroundColor = "rgb(230, 247, 236)";
//             } else {
//                 detailsRow.style.display = "none";
//                 row.style.backgroundColor = "#fff";
//             }
//         });
//     });
// });

// document.addEventListener("DOMContentLoaded", function () {
//     const rows = document.querySelectorAll(".restock-request-row");
//     rows.forEach((row) => {
//         row.addEventListener("click", function () {
//             const restockRequestId = this.getAttribute("data-id");
//             const detailsRow = document.getElementById(
//                 `details-${restockRequestId}`
//             );
//             console.log(detailsRow);

//             if (detailsRow.style.display === "none") {
//                 detailsRow.style.display = "table-row";
//                 row.style.backgroundColor = "rgb(230, 247, 236)";
//             } else {
//                 detailsRow.style.display = "none";
//                 row.style.backgroundColor = "#fff";
//             }
//         });
//     });
// });

// document.addEventListener("DOMContentLoaded", function () {
//     const rows = document.querySelectorAll(".comprehensive-stock-report-row");
//     rows.forEach((row) => {
//         row.addEventListener("click", function () {
//             const comprehensiveStockReportId = this.getAttribute("data-id");
//             const detailsRow = document.getElementById(
//                 `details-${comprehensiveStockReportId}`
//             );
//             console.log(detailsRow);

//             if (detailsRow.style.display === "none") {
//                 detailsRow.style.display = "table-row";
//                 row.style.backgroundColor = "rgb(230, 247, 236)";
//             } else {
//                 detailsRow.style.display = "none";
//                 row.style.backgroundColor = "#fff";
//             }
//         });
//     });
// });
