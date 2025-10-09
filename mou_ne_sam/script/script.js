const login = document.getElementsByName ("Login")[0];
const password = document.getElementsByName ("Password")[0];
const error = document.querySelector (".Error");
document.querySelector("button").addEventListener("click", (event) =>{
    if (login.value === "" || passwaord.value === "") {
        error.innerHTML="Заполните поля пж";
        event.preventDefault();
    }
}

)