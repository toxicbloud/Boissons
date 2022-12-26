window.addEventListener("load", () => {
    const FavCountSpan = document.getElementById("favcount");
    fetch("/favorite/count", {
        method: "GET"
    }).then(response => {
        if (response.ok) {
            response.json().then(data => {
                FavCountSpan.textContent = data;
            });
        }
    }
    );
});