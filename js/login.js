window.onload = init

function init(){
    const formLogin = document.getElementById("form_login");
    formLogin.addEventListener("submit", async function(e){
        e.preventDefault();
        const data = new FormData(formLogin);

        const req = await fetch("./login.php",{
            method: "POST",
            body: data
        })
        const res = await req.json()
        const msg = await res;
        if(msg.status == 0){
            alert(msg.message);
            return;
        }
        window.location.href = "./"
    })
}