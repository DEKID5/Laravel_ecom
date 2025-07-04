document.addEventListener("DOMContentLoaded", function () {
    function handlePopup(popupId, duration = 3000) {
        let popup = document.getElementById(popupId);
        if (popup) {
            console.log(`${popupId} found!`);
            popup.style.display = "block";

            // Auto-hide after `duration` ms
            setTimeout(() => {
                popup.style.transition = "opacity 0.5s";
                popup.style.opacity = "0";
                setTimeout(() => popup.remove(), 500);
            }, duration);
        } else {
            console.log(`${popupId} not found!`);
        }
    }

    // Handle success and error popups
    handlePopup("successPopup");
    handlePopup("errorPopup");

    // Close popups on button click
    document.querySelectorAll(".close-popup").forEach(button => {
        button.addEventListener("click", function () {
            let popupId = this.getAttribute("data-popup");
            let popup = document.getElementById(popupId);
            if (popup) {
                popup.style.transition = "opacity 0.5s";
                popup.style.opacity = "0";
                setTimeout(() => popup.remove(), 500);
            }
        });
    });
});


document.querySelector('.mobile-menu-toggle').addEventListener('click', () => {
    document.querySelector('.sidebar col-md-3 col-lg-2 d-md-block sidebar').classList.toggle('active');
});