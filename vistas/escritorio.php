<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

 
require 'header.php';

if ($_SESSION['escritorio']==1) {
$user_id=$_SESSION["idusuario"];


 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
    <div class="box">
    <div class="box-header with-border">
      <h1 class="box-title"> Comentarios de Futbol <button class="btn btn-success" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
    </div>
    
    </div>

  <div class="panel-body" style="height: 400px;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Comentario</label>
      <input class="form-control" type="hidden" name="idcomentario" id="idcomentario">
      <input type="hidden" id="idusuario" name="idusuario" value="<?php echo $_SESSION["idusuario"];?>">
      <input class="form-control" type="text" name="comentario" id="comentario" maxlength="50" placeholder="comentario" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
</div>
<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <script src="scripts/escritorio.js"></script>
 <?php 
}

ob_end_flush();
  ?>

