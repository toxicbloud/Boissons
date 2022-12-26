window.addEventListener("load", () => {
    const form = document.getElementById("form");
    const info = document.getElementById("info");
    const deleteButton = document.getElementById("deleteButton");
    deleteButton.addEventListener("click", () => {
        form.reset();
    });
    const confMDP = document.getElementById("confMDPText");
    const MDP = document.getElementById("mdpText");
    confMDP.addEventListener("input", () => {
        if (confMDP.value != MDP.value) {
            MDP.classList.add("border-danger");
            confMDP.classList.add("border-danger");
            showInfo(info, "Les mots de passe ne correspondent pas");
        } else {
            hideInfo(info);
            MDP.classList.remove("border-danger");
            confMDP.classList.remove("border-danger");
        }
    });
    form.onsubmit = (e) => {
        e.preventDefault();
        let error = false;
        const pseudo = document.getElementById("pseudoText");
        if (MDP.value != confMDP.value) {
            error = true;
        }
        if (!verifyPasswordStrength(MDP.value)) {
            error = true;
            showInfo(info, "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre");
        }
        if (pseudo.value == "") {
            error = true;
            pseudo.classList.add("border-danger");
        } else {
            pseudo.classList.remove("border-danger");
        }
        // on envoie le formulaire avec un fetch si il n'y a pas d'erreur au format FormData
        if (!error) {
            const formData = new FormData(form);
            fetch("/register", {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok) {
                    response.json().then(data => {
                        if (data.success) {
                            window.location.href = "/";
                        } else {
                            showInfo(info, data.message);
                        }
                    });
                }
            });
        }
    }
});
/**
 * methode qui vérifie la force d'un mot de passe
 * avec les conditions suivantes:
 * - au moins 8 caractères
 * - au moins 1 majuscule
 * - au moins 1 minuscule
 * - au moins 1 chiffre
 * @param {String} password mot de passe à vérifier
 * @returns un booléen indiquant si le mot de passe est assez fort
 */
const verifyPasswordStrength = (password) => {
    const regex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
    return regex.test(password);
}
/**
 * fonction qui affiche le message passé en paramètre dans l'élément info
 * @param {Element} info 
 * @param {String} message 
 */
const showInfo = (info, message) => {
    info.classList.remove("d-none");
    info.textContent = message;
}
/**
 * fonction qui cache l'élément info passé en paramètre
 * @param {Element} info 
 */
const hideInfo = (info) => {
    info.classList.add("d-none");
}