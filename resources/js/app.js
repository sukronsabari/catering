import "./bootstrap";
import "flowbite";
import "./charts";
import "./powergrid-livewire";


document.addEventListener('livewire:navigated', () => {

}, { once: true })


document.addEventListener("DOMContentLoaded", function () {
    initSidebar();
});

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
