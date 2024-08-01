<?php include_once 'Views/template/header-principal.php'; ?>

<link rel="stylesheet" href="modal-services/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- banner section start -->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="assets\principal\images\img_slider.jpg" alt="First slide">
      <div class="carousel-caption d-none d-md-block">
        <h3 class="banner_taital">Adquiere tus productos favoritos</h3>
        <div class="buynow_bt"><a href="#categoria_1">Ver más</a></div>
      </div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="assets\principal\images\parlante.jpg" alt="Second slide">
      <div class="carousel-caption d-none d-md-block">
        <h3 class="banner_taital">¿Necesitas un mantenimiento?</h3>
        <div class="buynow_bt"><a href="#">Ver más</a></div>
      </div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="assets\principal\images\img_slider.jpg" alt="Third slide">
      <div class="carousel-caption d-none d-md-block">
        <h3 class="banner_taital">Conocenos</h3>
        <div class="buynow_bt"><a href="#">Ver más</a></div>
      </div>
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<!-- banner section end -->

</div>
<!-- banner bg main end -->

<!-- fashion section start -->
<?php foreach ($data['categorias'] as $categoria) { ?>
  <div class="fashion_section">
    <div class="container" id="categoria_<?php echo $categoria['id']; ?>">
      <h1 class="fashion_taital text-uppercase"><?php echo $categoria['categoria']; ?></h1>
      <div class="row <?php echo (count($categoria['productos']) > 0) ? 'multiple-items' : ''; ?>">
        <?php foreach ($categoria['productos'] as $producto) { ?>
          <div class="<?php echo (count($categoria['productos']) > 2) ? 'col-lg-3' : 'col-lg-12'; ?>">
            <div class="box_main">
              <h4 class="shirt_text"><?php echo $producto['nombre']; ?></h4>
              <img class="imgP" src="<?php echo BASE_URL . $producto['imagen']; ?>" />
              <p class="price_text">Precio <span style="color: #262626;">$ <?php echo $producto['precio']; ?></span></p>
              <div class="btn_main">
                <div class="buy_bt"><a href="#" class="btnAddcarrito" prod="<?php echo $producto['id']; ?>">Añadir</a></div>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal_<?php echo $producto['id']; ?>">
                  Ver más
                </button>
                <!-- Modal -->
                <div class="modal fade modal-dialog-scrollable" id="exampleModal_<?php echo $producto['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModal_<?php echo $producto['id']; ?>_title" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h2 class="modal-title" id="exampleModal_<?php echo $producto['id']; ?>_title"><?php echo $producto['nombre']; ?></h2>
                      </div>
                      <div class="modal-body">
                        <img class="imgModal" src="<?php echo BASE_URL . $producto['imagen']; ?>" />
                        <div class="cositas">
                          <h5 id="descripcion"><?php echo $producto['descripcion']; ?></h5>
                          <h5 id="precio">Precio $<?php echo $producto['precio']; ?></h5>
                        </div>
                      </div>
                      <div class="modal-footer" id="elputomodal">
                        <div class="comentar">
                          <?php if (empty($_SESSION['nombreCliente'])) {
                            echo '<li><a href="#" data-toggle="modal" data-target="#modalLogin">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span class="padding_10">Inicia sesión para poder comentar:)</span></a>
                              </li>';
                          } else { ?>
                            <form id="frmComentariosProd" method="POST" action="Controllers/Comentarios.php/ingresarComentariosProd">
                              <table>
                                <tr>
                                  <input type="hidden" id="nombre" name="nombre" value="<?php echo $_SESSION['nombreCliente']; ?>">
                                  <input type="hidden" id="id_producto" name="id_producto" value="<?php echo $producto['id']; ?>">
                                </tr>
                                <tr>
                                  <!-- Acuerdese de cambiar en style.css en vez de colocar la clase de form-control(barra search) un id. Tambien asi con los modales, 
                                   en estilos no manejar clase sino id especifcos y que no se tire todo los modales y ajustar el archivo original de bootstrap con los modales. -->
                                  <input class="form-control form-control-sm" id="mensaje" name="mensaje" type="text" placeholder="Escribe tu comentario..." aria-label=".form-control-sm example">
                                  <input type="submit" name="comentarEnviar" value="Enviar" id="comentarEnviar" class="btn-comentar">
                                </tr>
                              </table>
                            </form>
                          <?php } ?>
                        </div>

                        <!-- esto no funciona. Ush. -->
                        <div class="comentarios">
                          <?php if (!empty($producto['comentarios'])) : ?>
                            <ul>
                              <?php foreach ($producto['comentarios'] as $comentario) : ?>
                                <li>
                                  <strong><?php echo htmlspecialchars($comentario['nombre'], ENT_QUOTES, 'UTF-8'); ?>:</strong>
                                  <p><?php echo htmlspecialchars($comentario['mensaje'], ENT_QUOTES, 'UTF-8'); ?></p>
                                  <small><?php echo htmlspecialchars($comentario['fecha'], ENT_QUOTES, 'UTF-8'); ?></small>
                                </li>
                              <?php endforeach; ?>
                            </ul>
                          <?php else : ?>
                            <p>No hay comentarios para este producto.</p>
                          <?php endif; ?>
                        </div>
                        <!-- esto no funciona. Ush. -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
<?php } ?>
<?php
?>

<!-- Modales Citas -->
<div class="container text-center mt-5" style="padding-bottom: 50px;">
  <div class="row">
    <!-- Columna para Agendar Citas -->
    <div class="col-md-6">
      <div class="p-3 border bg-light">
        <h3>Aquí puedes agendar citas con nosotros</h3>
        <button id="openModalBtn" class="btn btn-primary mt-3" data-toggle="modal" data-target="#modalCita">Agendar Citas</button>
        <?php include_once 'modal-services/modal-cita.php'; ?>
      </div>
    </div>
    <!-- Columna para Cancelar Citas -->
    <div class="col-md-6">
      <div class="p-3 border bg-light">
        <h3>¿Ya tienes una cita? Cancelala aquí</h3>
        <button type="button" class="btn btn-warning mt-3" data-toggle="modal" data-target="#cancelarCitaModal">Cancelar Citas</button>
        <?php include_once 'modal-services/modal-cancelar-cita.php'; ?>
      </div>
    </div>
  </div>
</div>

<?php include_once 'Views/template/footer-principal.php'; ?>

<script>
  const myModal = document.getElementById('myModal')
  const myInput = document.getElementById('myInput')

  myModal.addEventListener('shown.bs.modal', () => {
    myInput.focus()
  })
</script>

<!-- Script Modal Servicio -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script src="Views/modal-services/script.js"></script>
</body>

</html>