<?php 
require_once '../includes/_db.php';
require_once '../includes/_funcionessvc.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Jekyll v3.8.5">
  <title>Dashboard Template · Bootstrap</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="../css/estilo.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Company name</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <a class="nav-link" href="#">Sign out</a>
      </li>
    </ul>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="home"></span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../usuarios/">
                <span data-feather="file"></span>
                Usuarios<span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="../servicios/">
                <span data-feather="file"></span>
                Servicios<span class="sr-only">(current)</span>
              </a>
            </li>
              <li class="nav-item">
              <a class="nav-link" href="../meet/">
                <span data-feather="file"></span>
                Meet<span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../skills/">
                <span data-feather="file"></span>
                Skills<span class="sr-only">(current)</span>
              </a>
            </li>
             <li class="nav-item">
              <a class="nav-link" href="../portafolio/">
                <span data-feather="file"></span>
                Portafolio<span class="sr-only">(current)</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <main id="main" role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

        <h2>Servicios
          <button type="button" id="btn_nuevo" class="btn btn-outline-primary">Nuevo</button>
        </h2>
        <div class="table-responsive view" id="mostrar_datos">
          <table class="table table-striped table-sm" id="table_datos">
            <thead>
              <tr>
                <th>Servicios</th>
                <th>Descripcion</th>
                <th>Accion</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $servicios = $db->select("servicios","*",["status_svc" => 1]);
              foreach ($servicios as $servicios => $usr) {
                ?>
                <tr>
                  <td><?php echo $usr["nombre_svc"]; ?></td>
                  <td><?php echo $usr["descripcion_svc"]; ?></td>
                  <td>
                    <a href="#" class="editar_registro"data-id="<?php echo $usr["id_svc"]; ?>">Editar</a>
                    <a href="#" class="eliminar_registro" data-id="<?php echo $usr["id_svc"]; ?>">Eliminar</a></td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
          </div>
          <div id="formulario_datos" class="view">
            <form action="#" id="frm_datos">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre">
                  </div>
                  <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion">
                  </div>
                  <div class="form-group">
                    <label for="foto">Foto</label>
                    <input type="text" class="form-control" name="foto" id="foto">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <button type="button" class="btn btn-outline-danger cancelar">Cancelar</button>
                  <button type="button" class="btn btn-outline-success" id="registrar">Guardar</button>
                  
                </div>
              </div>
            </form>
          </div>
        </main>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
                
      change_view();
      function change_view(vista = "mostrar_datos"){
        $("#main").find(".view").each(function(){
          $(this).addClass('d-none');
          let id = $(this).attr("id");
          if(id == vista){
            $(this).removeClass("d-none");
          }
        });
      }
        
      $("#btn_nuevo").click(function(){
        change_view("formulario_datos");
      });
      $("#main").find(".cancelar").click(function(){
        $("#frm_datos")[0].reset();
        change_view();
      });
        $("#main").on("click",".editar_registro", function(e){
        e.preventDefault();
        change_view("formulario_datos");
        let id=$(this).data("id")
        let obj={
            "accion" : "consulta_individual",
            "registro" : $(this).data("id")
        }
         $.post("../includes/_funcionessvc.php", obj, function(data){
             $("#nombre").val(data.nombre_svc);
             $("#descripcion").val(data.descripcion_svc);
             $("#foto").val(data.foto_svc)
         }, "JSON");
        
        $("#registrar").text("Actualizar").data("edicion", 1).data("registro", id);
    });
         $("#frm_datos").find("input").keyup(function(){
          $(this).removeClass("error");
        });
      $("#registrar").click(function(){
          
        let obj = {
          "accion" : "insertar_servicios"
            
        };
          
       
        $("#frm_datos").find("input").each(function(){
          $(this).removeClass("error");
          if($(this).val() == ""){
            $(this).addClass("error").focus();
            return false;
          }else{
            obj[$(this).prop("name")] = $(this).val();
          }
          
        });
           if($(this).data("edicion")==1){
                obj["accion"]="editar_servicios";
                obj["registro"]=$(this).data("registro");
              $(this).text("Guardar").removeData("edicion").removeData("registro");
             }
          
              $.post("../includes/_funcionessvc.php", obj, function(data){
                alert(data);
                  change_view(); 
              mostrar_servicios();
                   $("#frm_datos")[0].reset();  
              });
              
      });
      $("#main").on("click",".eliminar_registro",function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let obj = {
          "accion" : "eliminar_servicios",
          "servicios" : id
        }
        $.post("../includes/_funcionessvc.php",obj, function(data){
          mostrar_servicios();
        });
      });
      function mostrar_servicios(){
        let obj = {
          "accion" : "mostrar_servicios"
        }
        
        $.post("../includes/_funcionessvc.php",obj, function(data){
          let template = ``; 
          $.each(data, function(e,elem){
            template += `
            <tr>
            <td>${elem.nombre_svc}</td>
            <td>${elem.descripcion_svc}</td>
            <td>
            <a href="#" class="editar_registro"data-id="${elem.id_svc}">Editar</a>
            <a href="#" class="eliminar_registro" data-id="${elem.id_svc}">Eliminar</a></td>
            </tr>
            `;
          });
          $("#table_datos tbody").html(template);
        },"JSON");      
      }
    </script>
    </html>