window.addEventListener("load", () => {
    const favbuttons = document.querySelectorAll('[data-idCocktail]');
    favbuttons.forEach(favbutton => {
        favbutton.addEventListener("click", function (e) {
            const cocktailId = e.target.getAttribute("data-idCocktail");
            fetch(`/favorite/${cocktailId}`, {
                method: 'POST'
            }).then(response => {
                if (response.ok) {
                    console.log(`boisson n° ${cocktailId} ajoutée aux favoris`);
                    // make the heart filled and red
                    e.target.classList.remove("bi-heart");
                    e.target.classList.add("bi-heart-fill");
                    e.target.style.color = "red";
                } else {
                    console.log("not ok");
                    // make a toast to say that the user is not logged in
                    const toast = document.createElement("div");
                    toast.classList.add("toast");
                    toast.setAttribute("role", "alert");
                    toast.setAttribute("aria-live", "assertive");
                    toast.setAttribute("aria-atomic", "true");
                    toast.setAttribute("data-delay", "3000");
                    toast.innerHTML = `
                        <div class="toast-header">
                            <strong class="me-auto">Erreur</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body
                            Une erreur est survenue.
                        </div>
                    `;
                    document.body.appendChild(toast);
                    const toastEl = new bootstrap.Toast(toast);
                    toastEl.show();
                }
            });
        });
    });
});