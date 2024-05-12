// Projet réalisé par DU Alexandre

const OpenNAV = document.querySelector(".icon1");
const CloseNAV = document.querySelector(".fermer");
const Menu = document.querySelector(".menu");

const PositionMenu = Menu.getBoundingClientRect().left;

OpenNAV.addEventListener('click', ()=>{
    if (PositionMenu < 0){
        Menu.classList.add("montrer")
    }
})

CloseNAV.addEventListener('click', ()=>{
    if (PositionMenu < 0){
        Menu.classList.remove("montrer")
    }
})

