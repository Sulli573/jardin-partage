const meteoDiv = document.querySelector("#meteo");

async function displayHtml() {
    const meteo = await getMeteo();
    meteo["days"].forEach(data => {
        // Va créer une balise <p>
        let dayContainer = document.createElement("div");
        dayContainer.classList.add("card");
        createParagraphe(data.day, dayContainer);
        createParagraphe(data.temperatureMax, dayContainer);
        createParagraphe(data.temperatureMin, dayContainer);
        createParagraphe(data.precipitation, dayContainer);
        //La mettre dans la div meteo
        meteoDiv.appendChild(dayContainer);
    });
}

async function getMeteo() {
    //ce qu'on va recevoir (la réponse de la page) en objet js (Response) dedant il y a le Json.
    const response = await fetch("/meteo");
    //extrait le json de l'objet Response.
    const meteo = await response.json();
    console.log(meteo);
    return meteo;
}

displayHtml();

function createParagraphe(texte, parent) {
    let paragraphe = document.createElement("p");
    //Mettre le texte de la journée dans le <p>:
    paragraphe.textContent = texte;
    //à gauche c'est le parent, à droite l'enfant
    parent.appendChild(paragraphe);
}