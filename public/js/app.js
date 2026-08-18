$(document).ready(function () {
    // Toggle sidebar
    $(".btn-toggle-sidebar").click(function () {
        $("#sidebar").toggleClass("collapsed");
    });

    // Close sidebar on mobile
    $(".btn-close-sidebar").click(function () {
        $("#sidebar").removeClass("active");
    });

    // Mobile sidebar toggle
    $(".btn-toggle-sidebar").click(function () {
        $("#sidebar").toggleClass("active");
    });

    // Active submenu
    $(".sidebar-link.collapsed").click(function () {
        $(this).toggleClass("active");
    });

    // Prevent dropdown from closing when clicking inside
    $(".dropdown-menu").on("click", function (e) {
        e.stopPropagation();
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Auto-collapse sidebar if screen is small
    function checkScreenSize() {
        if ($(window).width() < 992) {
            $("#sidebar").addClass("collapsed");
        } else {
            $("#sidebar").removeClass("collapsed");
        }
    }

    // Run on load and resize
    checkScreenSize();
    $(window).resize(checkScreenSize);
});
