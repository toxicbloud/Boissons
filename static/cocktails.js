window.addEventListener("load", async () => {
    console.log("cocktails.js loaded");
    const favbuttons = document.querySelectorAll('[data-idCocktail]');
    fetch("/favorite/list").then(response => {
        if (response.ok) {
            response.json().then(data => {
                const favs = data;
                console.log(favs);
                favbuttons.forEach(favbutton => {
                    const cocktailId = favbutton.getAttribute("data-idCocktail");
                    if (favs.includes(parseInt(cocktailId))) {
                        favbutton.classList.remove("bi-heart");
                        favbutton.classList.add("bi-heart-fill");
                        favbutton.style.color = "red";
                    }
                });
            });
        } else {
            console.error("impossible de récupérer la liste des favoris");
        }
    });
    favbuttons.forEach(favbutton => {
        favbutton.addEventListener("click", async (e) => {
            const cocktailId = e.target.getAttribute("data-idCocktail");
            if (isFilled(e.target)) {
                fetch(`/favorite/${cocktailId}`, {
                    method: 'DELETE'
                }).then(async response => {
                    if (response.ok) {
                        console.log(`boisson n° ${cocktailId} supprimée des favoris`);
                        emptyHeart(e.target);
                        updateFavCountWithResponse(response);
                    } else {
                        console.error(`impossible de supprimer la boisson n° ${cocktailId} des favoris`);
                    }
                });
            } else {
                fetch(`/favorite/${cocktailId}`, {
                    method: 'POST'
                }).then(async response => {
                    if (response.ok) {
                        console.log(`boisson n° ${cocktailId} ajoutée aux favoris`);
                        fillHeart(e.target);
                        updateFavCountWithResponse(response);
                    } else {
                        console.error(`impossible d'ajouter la boisson n° ${cocktailId} aux favoris`);
                    }
                });
            }
        });
    });
});

/**
 *  fonction qui permet de savoir si l'element coeur est rempli ou non
 * @param {Element} element 
 * @returns booleen qui indique si l'element est rempli ou non
 */
const isFilled = (element) => {
    return element.classList.contains("bi-heart-fill");
}
/**
 * 
 * @param {Element} element 
 */
const fillHeart = (element) => {
    element.classList.remove("bi-heart");
    element.classList.add("bi-heart-fill");
    element.style.color = "red";
}
/**
 * 
 * @param {Element} element 
 */
const emptyHeart = (element) => {
    element.classList.remove("bi-heart-fill");
    element.classList.add("bi-heart");
    element.style.color = "black";
}
/**
 * fonction qui met à jour le nombre de favoris dans la navbar
 * en refaisant une requête
 */
const updateFavCount = () => {
    const FavCountSpan = document.getElementById("favcount");
    fetch("/favorite/count", {
        method: "GET"
    }).then(response => {
        if (response.ok) {
            response.json().then(data => {
                FavCountSpan.textContent = data;
            });
        }
    });
}
/**
 * fonction qui met à jour le nombre de favoris dans la navbar
 * sans avoir à refaire une requête
 * @param {Response} response 
 */
const updateFavCountWithResponse = (response) => {
    if (response.ok) {
        response.json().then(data => {
            const FavCountSpan = document.getElementById("favcount");
            FavCountSpan.textContent = data;
        });
    }
}