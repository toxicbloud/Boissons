const choix = new Map();

/**
 * 
 * @param {Node} inp 
 * @param {Array} arr 
 */
function autocomplete(inp, arr) {
    var currentFocus;
    inp.addEventListener("input", function (e) {
        var a, b, i, val = this.value;
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        this.parentNode.appendChild(a);
        for (i = 0; i < arr.length; i++) {
            if (arr[i].name.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                b = document.createElement("DIV");
                b.innerHTML = "<strong>" + arr[i].name.substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].name.substr(val.length);
                b.innerHTML += "<input type='hidden' value='" + arr[i].name + "'>";
                b.addEventListener("click", function (e) {
                    inp.value = this.getElementsByTagName("input")[0].value;
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            currentFocus++;
            addActive(x);
        } else if (e.keyCode == 38) {
            currentFocus--;
            addActive(x);
        } else if (e.keyCode == 13) {
            e.preventDefault();
            if (currentFocus > -1) {
                if (x) x[currentFocus].click();
            }
            // si la liste d'auto complétion n'est pas affichée
            if (!x) {
                if (!Array.from(choix.values()).includes(inp.value) && inp.value != "") {
                    const ingredient = arr.find(ingredient => ingredient.name == inp.value);
                    choix.set(ingredient.id, ingredient.name);
                    const listIngredients = document.getElementById("listIngredients");
                    const li = document.createElement("span");
                    li.className = "badge bg-warning";
                    const button = document.createElement("button");
                    button.className = "btn-close";
                    button.setAttribute("type", "button");
                    button.setAttribute("aria-label", "Close");
                    button.addEventListener("click", function (e) {
                        const string = e.target.parentNode.textContent;
                        choix.forEach((value, key) => {
                            if (value == string) {
                                choix.delete(key);
                                return;
                            }
                        });
                        li.style.transform = "rotate(360deg)";
                        li.style.transition = "transform 1s";
                        var opacity = 1;
                        const n = setInterval(function () {
                            li.style.opacity = opacity -= 0.1;
                        }, 50);
                        setTimeout(function () {
                            clearInterval(n);
                            li.remove();
                        }, 600);
                    });
                    li.textContent = inp.value;
                    li.appendChild(button);
                    listIngredients.appendChild(li);
                } else {
                    $("#Myingredients").effect("shake", 200);
                }
                inp.value = "";
            }
        }
    });

    function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

window.onload = async function () {
    const ingredients = new Array();
    const res = await fetch('/aliments')
    const data = await res.json();
    data.forEach(ingredient => {
        ingredients.push(ingredient);
    });
    autocomplete(document.getElementById("Myingredients"), ingredients);
    const searchButton = document.getElementById("searchButton");
    const form = document.getElementById("form");
    searchButton.addEventListener("click", function (e) {
        e.preventDefault();
        const loading = document.getElementById("loading");
        loading.style.display = "block";
        // post all the ingredients to the server
        fetch('/cocktails', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: '{"ingredients":' + JSON.stringify(Array.from(choix.keys())) + '}'
        }).then(res => res.json()).then(data => {
            const listCocktails = document.getElementById("listCocktails");
            listCocktails.innerHTML = "";
            loading.style.display = "none";
            const result = document.getElementById("result");
            result.style.display = "block";
            result.textContent = "Résultats : " + data.length;
            data.forEach(cocktail => {
                const li = document.createElement("li");
                const a = document.createElement("a");
                a.setAttribute("href", "/cocktail/" + cocktail.id);
                a.textContent = cocktail.name;
                li.appendChild(a);
                listCocktails.appendChild(li);
            });
        });
    });
}