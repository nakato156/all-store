<link rel="stylesheet" href="../css/product.css">
<section class="container" style="padding-top: 20px;">
    <div class="row">
        <div class="col-md-6 ms-auto">
            <img src="../img/<?=$img?>" class="img-product img-fluid" alt="image-<?=$img;?>">
        </div>
        <div class="col-md-6 ms-auto align-self-center">
            <h4><?=$name;?></h4>
            <!-- <i class="bx bx-star"></i><i class="bx bx-star"></i><i class="bx bx-star"></i><i class="bx bx-star"></i><i class="bx bx-star"></i> -->
            <p class="lead"><?=$description;?></p>
            <button type="button" class="btn btn-outline-primary">Añadir S/.<?=$price;?></button>
        </div>
    </div>
</section>
<div class="accordion accordion-flush" id="moreinformation" style="margin-top: 20px;">
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Más información
            </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#moreinformation">
            <div class="accordion-body">....</div>
        </div>
    </div>
</div>
<form class="row g-2" id="formComment" style="padding-left:15px;width:100%;">
    <div class="mb-3">
        <label for="newComment" class="form-label">Añadir comentario</label>
        <input type="text" class="form-control" name="comment" id="newComment" placeholder="Añade un comentario">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Añadir</button>
    </div>
</form>
<section class="comments" id="sectionComments"></section>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Comentario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="message-text" class="col-form-label responding"></label>
        <textarea class="form-control" id="message-text"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Enviar</button>
      </div>
    </div>
  </div>
</div>
<script src="../js/product.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>