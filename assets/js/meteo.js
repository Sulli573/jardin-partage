const meteoDiv = document.querySelector("#meteo");
const loadingDiv = document.querySelector(".loading");

async function displayHtml() {
    const meteo = await getMeteo();
    hideLoading();
    createParagraphe("Météo",meteoDiv,["title", "m-0"]);
    createDays(meteo);
}

function hideLoading() {
    loadingDiv.style.display = 'none';
}

function createDays(meteo){
    meteo["days"].forEach(data => {
        // Va créer une balise <p>
        let dayContainer = document.createElement("div");
        dayContainer.classList.add("day");
        createParagraphe(formatageDate(data.day), dayContainer, ["jour", "data"], "Date");
        createParagraphe(data.temperatureMax + " °C", dayContainer, ["tempMax", "data"], "Température maximum");
        createParagraphe(data.temperatureMin + " °C", dayContainer, ["tempMin", "data"], "Température minimum");
        createParagraphe(data.precipitation + " mm", dayContainer, ["precipitation", "data"], "Précipitation moyenne");
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
//classes représente chacun élément de la météo (le jour, température min et max, précipitation ...)
function createParagraphe(texte, parent, classes, toolType) {
    let paragraphe = document.createElement("p");
    //Mettre le texte de la journée dans le <p>:
    paragraphe.textContent = texte;
    // ... va exploser le tableau
    paragraphe.classList.add(...classes);
    //Pour créer un texte qui va s'afficher au survol des données météo
    paragraphe.setAttribute("title",toolType)
    //à gauche c'est le parent, à droite l'enfant
    parent.appendChild(paragraphe);
}

function formatageDate(dateOfDay) {
    //mise de la date dans un objet Date
    const dateObject = new Date(dateOfDay);
    //Converti la date en français avec le jour et le mois avec 2 chiffres
    const jourMois = dateObject.toLocaleDateString("fr-FR", {
        day:"2-digit",
        month:"2-digit"
    })
    return jourMois;
}