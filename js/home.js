window.onload = init
var btn_shopcar = null;
var span_shopcar = null;
async function init(){
    const products = document.getElementById("products");
    btn_shopcar = document.getElementById("btn_shopcar");
    span_shopcar = document.getElementById("span_shopcar");
    let temp = ""
    
    const req = await fetch("./api.php");
    const res = await req.json();

    res.forEach(product => {
        temp += `    
    <div class="product" data="${product[0]};${product[1]}">
        <a class="img-product" href="/${product[0]}/${product[1].replace(" ","-")}">
            <img src="./img/${product[4]}" alt="">
        </a>
        <div class="product-info">
            <p class="description">${product[2]}</p>
            <p class="price">S/${product[3]}</p>
        </div>
        <div class="addcar">
            <i class='bx bxs-cart-add'></i>
        </div>
    </div>` 
    });
    products.innerHTML += temp;    

    const imgProducts = document.getElementsByClassName(" bxs-cart-add");
    for(let i=0; i<imgProducts.length; i++){
        imgProducts[i].addEventListener("click", addCar)
    }
    fetch("/cant", {method: "POST"}).then(res=>res.json()).then(data=>{
        data.cant>0 ? btn_shopcar.removeAttribute("style"): null;
        data.cant>0 ? span_shopcar.innerText = data.cant : null;
    })
    
}
function addCar(e){
    this.classList.remove('btn_shop_animated')
    const parent = this.parentElement.parentElement
    const data = parent.getAttribute("data").split(";")
    fetch("./shop/car.php",{
        method: "POST",
        body: JSON.stringify({
            "id": data[0],
            "name": data[1]
        })
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status === "ok"){
            console.log(data)
            btn_shopcar.removeAttribute("style");
            this.classList.add('btn_shop_animated')
            span_shopcar.innerText = data.cant
        }else{
            console.log("error");
        }
    });
}
