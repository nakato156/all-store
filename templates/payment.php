<link rel="stylesheet" href="../css/payment.css">
<section class="shopcar">
    <div class="shopcar-table">
        <table class="table align-middle table-striped caption-top">
        <caption>Carrito de compras</caption>
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Producto</th>
                <th scope="col">Precio</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if(isset($_SESSION["shopcar"])){
                    $car = $_SESSION["shopcar"];
                    for($i=0; $i<count($car); $i++){
                    ?>
                    <tr>
                        <th scope="row"><?=1+$i?></th>
                        <td><?=$car[$i]["name"]?></td>
                        <td><?=$car[$i]["price"]?></td>
                        <td><?=$car[$i]["cant"]?></td>
                        <td><?=$car[$i]["cant"]*$car[$i]["price"]?></td>
                    </tr>
                    <?php
                    }
                }
            ?>
        </tbody>
        </table>
    </div>
    <div class="client-form" style="display:none;">
        <form id="form_envio">
            <div class="mb-3">
                <label class="form-label">Correo Electronico</label>
                <input name="email" type="email" class="form-control" id="eamil">
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input name="nombre" type="text" class="form-control" id="eamil">
            </div>
            <div class="mb-3">
                <label class="form-label">Ciudad</label>
                <select name="ciudad" class="form-select" aria-label="Default select example">
                <option selected>Ciudad</option>
                <option value="Lima">Lima</option>
                <option value="Trujillo">Trujillo</option>
                <option value="Cajamarca">Cajamarca</option>
                <option value="Piura">Piura</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input name="direccion" type="text" class="form-control" id="eamil">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1"><a href="../terminos-y-condiciones">Acepto los terminos y condiciones</a></label>
            </div>
            <div id="btn_pago" class="d-grid gap-2"></div>
        </form>
    </div>
</section>
</body>
<script src="https://www.mercadopago.com/v2/security.js" view="home"></script>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script src="./js/payment.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>