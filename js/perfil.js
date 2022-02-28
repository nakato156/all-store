const html = document.documentElement;
const body = document.body;
const managePerfil = document.getElementsByClassName("manage");
const menuLinks = document.querySelectorAll(".admin-menu a");
const collapseBtn = document.querySelector(".admin-menu .collapse-btn");
const toggleMobileMenu = document.querySelector(".toggle-mob-menu");
const switchInput = document.querySelector(".switch input");
const switchLabel = document.querySelector(".switch label");
const switchLabelText = switchLabel.querySelector("span:last-child");
const collapsedClass = "collapsed";
const lightModeClass = "light-mode";

/*TOGGLE HEADER STATE*/
collapseBtn.addEventListener("click", function () {
  body.classList.toggle(collapsedClass);
  this.getAttribute("aria-expanded") == "true"
    ? this.setAttribute("aria-expanded", "false")
    : this.setAttribute("aria-expanded", "true");
  this.getAttribute("aria-label") == "collapse menu"
    ? this.setAttribute("aria-label", "expand menu")
    : this.setAttribute("aria-label", "collapse menu");
});

/*TOGGLE MOBILE MENU*/
toggleMobileMenu.addEventListener("click", function () {
  body.classList.toggle("mob-menu-opened");
  this.getAttribute("aria-expanded") == "true"
    ? this.setAttribute("aria-expanded", "false")
    : this.setAttribute("aria-expanded", "true");
  this.getAttribute("aria-label") == "open menu"
    ? this.setAttribute("aria-label", "close menu")
    : this.setAttribute("aria-label", "open menu");
});

/*SHOW TOOLTIP ON MENU LINK HOVER*/
for (const link of menuLinks) {
  link.addEventListener("mouseenter", function () {
    if (
      body.classList.contains(collapsedClass) &&
      window.matchMedia("(min-width: 768px)").matches
    ) {
      const tooltip = this.querySelector("span").textContent;
      this.setAttribute("title", tooltip);
    } else {
      this.removeAttribute("title");
    }
  });
}

/*TOGGLE LIGHT/DARK MODE*/
if (localStorage.getItem("dark-mode") === "false") {
  html.classList.add(lightModeClass);
  switchInput.checked = false;
  switchLabelText.textContent = "Light";
}

switchInput.addEventListener("input", function () {
  html.classList.toggle(lightModeClass);
  if (html.classList.contains(lightModeClass)) {
    switchLabelText.textContent = "Light";
    localStorage.setItem("dark-mode", "false");
  } else {
    switchLabelText.textContent = "Dark";
    localStorage.setItem("dark-mode", "true");
  }
});

const contenedor = document.getElementById("contenedor");
for(const element of managePerfil){
  element.addEventListener("click", async function (e){
    let temp = this.href.split("#")[1].trim()
    const func = this.getAttribute("func")
    const req = await fetch(`./templates/perfil/${temp}.html`)
    const data = await req.text()
    const content = await data;
    contenedor.innerHTML = content;
    window[func]();
  })
}

async function get_compras(){
  const cont_table = document.getElementById("table_body")
  let temp = "";

  const data = new FormData();
  data.append("op", "get");
  const req = await fetch("./perfil.php",{
    method: "POST",
    body: data
  });
  const res = await req.json()
  const info= await res;

  if(res.status==0){
    cont_table.parentElement.parentElement.innerHTML = "<h3>Sin compras</h3>";
    return;
  }
  let i=1;
  info.forEach(pd => {
    temp +=`<tr>
      <th scope="row">${i}</th>
      <td>${pd[0]}</td>
      <td>${pd[1]}</td>
      <td>${pd[2]}</td>
    </tr>`
    i++;
  });
  cont_table.innerHTML = temp;
  cont_table.parentElement.removeAttribute("style");
}

async function get_tracking(){
  const cont_table = document.getElementById("table_body")
  let temp = "";
  const data = new FormData();
  data.append("op", "tracking");
  const req = await fetch("./perfil.php",{
    method: "POST",
    body: data
  });
  const res = await req.json()
  const info= await res;
  if(info.status==0){
    cont_table.parentElement.parentElement.innerHTML = "<h1>Sin compras que monitorear</h1>";
    return;
  }
  let i=1;
  info.forEach(pd => {
    let status= pd[1]===0 ? "En proceso" : "Despachando";
    temp +=`<tr>
      <th scope="row">${i}</th>
      <td>${pd[0]}</td>
      <td>${status}</td>
    </tr>`
    i++;
  });
  cont_table.innerHTML = temp;
  cont_table.parentElement.removeAttribute("style");
}