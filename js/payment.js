var checkout = null;
window.onload = init;
async function init(){
    const post = new FormData();
    post.append("method", "id")
    const req = await fetch("./shop/payment.php", {
        method: "POST",
        body: post
    });
    const data = await req.json();
    const res = await data;
    const formEnv = document.getElementById("form_envio");
    if(res.value){
        formEnv.parentElement.removeAttribute("style");
        formEnv.addEventListener("change", validar);
    }else{
        formEnv.parentElement.parentElement.removeChild(formEnv.parentElement);
    }
}
function hiddeBtn(){
    const btnPago = document.getElementById("btn_pago");
    if(btnPago.childElementCount>0){
        console.log(btnPago.childNodes)
        btnPago.removeChild(btnPago.childNodes[0])
    }
}

async function pay(){
    const formEnv = document.getElementById("form_envio");
    const dataPost = new FormData(formEnv);
    dataPost.append("method","payment");
    const req = await fetch("./shop/payment.php", {
        method: "POST",
        body: dataPost
    });
    const data = await req.json();
    const res = await data;
    const mp = new MercadoPago('TEST-ad7bb5f4-2ad8-4ee1-a23a-37f77dc5a927', {
        locale: 'es-PE'
    });
    checkout = mp.checkout({
        preference: {
            id: res.id
        }
    })
    checkout.open();
}

function validar(e){
    e.preventDefault();
    for(const elem of this){
        const tagName = elem.tagName;
        if(tagName === "INPUT"){
            if(elem.type=="checkbox"){
                if(!elem.checked) return hiddeBtn();
            }else{
                if(!elem.value.trim()) return hiddeBtn();
            }
        }else if(tagName==="SELECT"){
            if(elem.value.toLowerCase()=="ciudad") return hiddeBtn();
        }
    }
    const btnPago = document.getElementById("btn_pago");
    btnPago.innerHTML = `<button class="btn btn-primary" type="button" onclick="pay()">Pagar</button>`
}
