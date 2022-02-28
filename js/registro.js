window.onload = init

function init(){
    const formRegistro = document.getElementById("form_registro")
    formRegistro.addEventListener("submit", async function(e){
        e.preventDefault();
        for(const elem of formRegistro){
            if(elem.tagName==="INPUT" && elem.type!="checkbox"){
                if(elem.value===""){
                    alert("Lelene todos los campos");
                    return;
                }else if(elem.name=="phone"){
                    if(elem.value.trim().length!=9 && elem.trim().value[0]!="9"&&!isNaN(elem.value.trim())){
                        alert("número inválido")
                        return
                    }
                }else if(elem.name=="dni"){
                    if(elem.value.trim().length>9) {
                        alert("DNI inválido (no coloque símbolos)")
                        return
                    }
                }else if(elem.name=="email"){
                    if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(elem.value)){
                        alert("Email incorrecto");
                        return
                    }
                }
            }
            const terminosCondiciones = document.getElementById("termCond")
            if(!terminosCondiciones.checked){
                alert("Acepte los terminos y condiciones :)")
                return;
            }
        }
        const dataForm = new FormData(formRegistro);
        const req = await fetch("./registro.php",{
            method:"POST",
            body: dataForm
        });
        const res = await req.json();
        const data = await res;
        if(res.status==0){
            alert(res.message)
        }else{
            window.location.href = "./";
        }
    })
}
