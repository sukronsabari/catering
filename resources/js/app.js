import "./bootstrap";
import "flowbite";
import "./charts";

import "../../vendor/rappasoft/laravel-livewire-tables/resources/imports/laravel-livewire-tables-all.js";

document.addEventListener("DOMContentLoaded", function () {
    initDarkMode();
    initSubNavSticky();
    initSidebar();
});

document.addEventListener("alpine:init", () => {
    Alpine.data("merchantSelect", () => ({
        init() {
            new TomSelect(this.$el.querySelector("#merchant-select"), {
                valueField: "id",
                labelField: "name",
                searchField: "name",
                create: false,
                load: function (query, callback) {
                    if (!query.length) return callback(); // Jika input kosong, jangan muat data

                    // Mengambil data dari endpoint API
                    fetch("/api/merchants?search=" + encodeURIComponent(query))
                        .then((response) => response.json())
                        .then((data) => {
                            callback(data); // Kembalikan data ke Tom Select
                        })
                        .catch((error) => {
                            console.error(
                                "Error fetching merchant data:",
                                error
                            );
                            callback(); // Jika ada error, kembalikan callback kosong
                        });
                },
            });
        },
    }));
});

function initDarkMode() {
    var themeToggleDarkIcon = document.getElementById("theme-toggle-dark-icon");
    var themeToggleLightIcon = document.getElementById(
        "theme-toggle-light-icon"
    );

    // Change the icons inside the button based on previous settings
    if (
        localStorage.getItem("color-theme") === "dark" ||
        (!("color-theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        themeToggleLightIcon.classList.remove("hidden");
    } else {
        themeToggleDarkIcon.classList.remove("hidden");
    }

    var themeToggleBtn = document.getElementById("theme-toggle");

    themeToggleBtn.addEventListener("click", function () {
        // toggle icons inside button
        themeToggleDarkIcon.classList.toggle("hidden");
        themeToggleLightIcon.classList.toggle("hidden");

        // if set via local storage previously
        if (localStorage.getItem("color-theme")) {
            if (localStorage.getItem("color-theme") === "light") {
                document.documentElement.classList.add("dark");
                localStorage.setItem("color-theme", "dark");
            } else {
                document.documentElement.classList.remove("dark");
                localStorage.setItem("color-theme", "light");
            }

            // if NOT set via local storage previously
        } else {
            if (document.documentElement.classList.contains("dark")) {
                document.documentElement.classList.remove("dark");
                localStorage.setItem("color-theme", "light");
            } else {
                document.documentElement.classList.add("dark");
                localStorage.setItem("color-theme", "dark");
            }
        }
    });
}

function initSubNavSticky() {
    // Sticky Subnavbar
    var navbar = document.querySelector("#navbar-submenu nav");
    var sticky = navbar?.offsetTop || 0;

    function myFunction() {
        if (navbar) {
            if (window.scrollY >= sticky) {
                navbar.classList.add("my-sticky");
            } else {
                navbar.classList.remove("my-sticky");
            }
        }
    }

    window.onscroll = () => myFunction();
}

function initSidebar() {
    const sidebar = document.getElementById("sidebar");

    if (sidebar) {
        const toggleSidebarMobile = (
            sidebar,
            sidebarBackdrop,
            toggleSidebarMobileHamburger,
            toggleSidebarMobileClose
        ) => {
            sidebar.classList.toggle("hidden");
            sidebarBackdrop.classList.toggle("hidden");
            toggleSidebarMobileHamburger.classList.toggle("hidden");
            toggleSidebarMobileClose.classList.toggle("hidden");
        };

        const toggleSidebarMobileEl = document.getElementById(
            "toggleSidebarMobile"
        );
        const sidebarBackdrop = document.getElementById("sidebarBackdrop");
        const toggleSidebarMobileHamburger = document.getElementById(
            "toggleSidebarMobileHamburger"
        );
        const toggleSidebarMobileClose = document.getElementById(
            "toggleSidebarMobileClose"
        );
        const toggleSidebarMobileSearch = document.getElementById(
            "toggleSidebarMobileSearch"
        );

        toggleSidebarMobileSearch.addEventListener("click", () => {
            toggleSidebarMobile(
                sidebar,
                sidebarBackdrop,
                toggleSidebarMobileHamburger,
                toggleSidebarMobileClose
            );
        });

        toggleSidebarMobileEl.addEventListener("click", () => {
            toggleSidebarMobile(
                sidebar,
                sidebarBackdrop,
                toggleSidebarMobileHamburger,
                toggleSidebarMobileClose
            );
        });

        sidebarBackdrop.addEventListener("click", () => {
            toggleSidebarMobile(
                sidebar,
                sidebarBackdrop,
                toggleSidebarMobileHamburger,
                toggleSidebarMobileClose
            );
        });
    }
}
