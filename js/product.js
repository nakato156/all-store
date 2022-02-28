window.onload = init

function init(){
  const id_product = window.location.href.split("/")[3];
  const exampleModal = document.getElementById('exampleModal')
  exampleModal.addEventListener('show.bs.modal', function (event) {
    let person = event.relatedTarget.parentNode.parentNode.firstChild.nextSibling.innerText  
    let modalResponding = exampleModal.querySelector('.responding')
    modalResponding.innerHTML = `Respondiendo a @${person}`
  });

  const sectionComments = document.getElementById("sectionComments");
  fetch(`/get-comments`,{
    method: "POST",
    body: JSON.stringify({id: id_product})
  }).then(res=>res.json())
  .then(comments=>{
    console.log(comments)
    if(comments[0]){
      temp = "";
      comments.forEach(comment => {
        temp += `<div class="comment card">
          <div class="card-body">
              <h5 class="card-title">${comment[0]} </h5>
              ${comment[1]}
              <div class="more-actions">
                  <i class="bx bx-like"></i>
                  <i class="bx bx-dislike"></i>
                  <i class="bx bx-comment-add" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
              </div>
          </div>
        </div>`
      });
      sectionComments.innerHTML = temp;
    }
  })

  const formComment = document.getElementById("formComment");
  formComment.addEventListener("submit", function(e){
    e.preventDefault();

    const dataPost = new FormData(formComment);
    dataPost.append("id_product", id_product)
    fetch("../comment", {
      method: "POST",
      body: dataPost
    }).then(res=>res.json())
    .then(data=>{
      console.log(data)
      if(data.name){
        sectionComments.innerHTML = `
        <div class="comment card">
          <div class="card-body">
              <h5 class="card-title">${data.name} </h5>
              ${dataPost.get("comment")}
              <div class="more-actions">
                  <i class="bx bx-like"></i>
                  <i class="bx bx-dislike"></i>
                  <i class="bx bx-comment-add" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
              </div>
          </div>
        </div>`+sectionComments.outerHTML
      }
    })
  })
}
