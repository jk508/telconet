<?php 
session_start();
require_once "../modelos/Usuario.php";

$usuario=new Usuario();

$idusuarioc=isset($_POST["idusuarioc"])? limpiarCadena($_POST["idusuarioc"]):"";
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$clave=isset($_POST["password"])? limpiarCadena($_POST["password"]):"";


switch ($_GET["op"]) {
	case 'guardaryeditar':

		
		//Hash SHA256 para la contraseña
		$clavehash=hash("SHA256", $clave);

		if (empty($idusuario)) {
			$rspta=$usuario->insertar($nombre,$email,$cargo,$clavehash,$_POST['permiso']);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
		}
		else {
			$rspta=$usuario->editar($idusuario,$nombre,$email,$cargo,$clavehash,$_POST['permiso']);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
	break;
	

	case 'desactivar':
		$rspta=$usuario->desactivar($idusuario);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
		$rspta=$usuario->activar($idusuario);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
	case 'mostrar':
		$rspta=$usuario->mostrar($idusuario);
		echo json_encode($rspta);
	break;

	
	case 'listar':
		$rspta=$usuario->listar();
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>($reg->condicion)?
				'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.' 
				'.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.' 
				'.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->nombre,
			    "2"=>$reg->email,
				"3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	case 'permisos':
		//obtenemos toodos los permisos de la tabla permisos
		require_once "../modelos/Permiso.php";
		$permiso=new Permiso();
		$rspta=$permiso->listar();

		//obtener permisos asigandos
		$id=$_GET['id'];
		$marcados=$usuario->listarmarcados($id);
		//declaramos el array para almacenar todos los permisos marcados
		$valores=array();

		//almacenar permisos asigandos
		while ($per = $marcados->fetch_object()) 
			{
				array_push($valores, $per->idpermiso);
			}

		//mostramos la lista de permisos
		while ($reg=$rspta->fetch_object()) 
			{
				$sw=in_array($reg->idpermiso,$valores)?'checked':'';
				echo '<li><input type="checkbox" '.$sw.' name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</li>';
			}
	break;

	case 'verificar':
		//validar si el usuario tiene acceso al sistema
		$logina=$_POST['logina'];
		$clavea=$_POST['clavea'];

		//Hash SHA256 en la contraseña
		$clavehash=hash("SHA256", $clavea);
	
		$rspta=$usuario->verificar($logina, $clavehash);

		$fetch=$rspta->fetch_object();

		if (isset($fetch)) 
		{
			# Declaramos la variables de sesion
			$_SESSION['idusuario']=$fetch->idusuario;
			$_SESSION['nombre']=$fetch->nombre;
			$_SESSION['email']=$fetch->email;
			$_SESSION['cargo']=$fetch->cargo;

			//obtenemos los permisos
			$marcados = $usuario->listarmarcados($fetch->idusuario);

			//declaramos el array para almacenar todos los permisos
			$valores=array();

			//almacenamos los permisos marcados en al array
			while ($per = $marcados->fetch_object()) 
			{
				array_push($valores, $per->idpermiso);
			}

			//determinamos lo accesos al usuario
			in_array(1, $valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
			in_array(2, $valores)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;


		}
		echo json_encode($fetch);

	break;

	case 'salir':
		//Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");

	break;
}
?>