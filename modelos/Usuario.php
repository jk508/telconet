<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$email,$cargo,$clave,$permisos){
	$sql="INSERT INTO usuario (nombre,email,cargo,clave,condicion) VALUES ('$nombre','$email','$cargo','$clave','$imagen','1')";
	//return ejecutarConsulta($sql);
	 $idusuarionew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($permisos)) {

	 	$sql_detalle="INSERT INTO usuario_permiso (idusuario,idpermiso) VALUES('$idusuarionew','$permisos[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 return $sw;
}

public function editar($idusuario,$nombre,$email,$cargo,$clave,$permisos){
	$sql="UPDATE usuario SET nombre='$nombre',email='$email',cargo='$cargo',clave='$clave'
	WHERE idusuario='$idusuario'";
	 ejecutarConsulta($sql);

	 //eliminar permisos asignados
	 $sqldel="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
	 ejecutarConsulta($sqldel);

	$num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($permisos)) {

	 	$sql_detalle="INSERT INTO usuario_permiso (idusuario,idpermiso) VALUES('$idusuario','$permisos[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 return $sw;
}

public function desactivar($idusuario){
	$sql="UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
public function activar($idusuario){
	$sql="UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idusuario){
	$sql="SELECT * FROM usuario WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM usuario";
	return ejecutarConsulta($sql);
}

//metodo para listar permmisos marcados de un usuario especifico
public function listarmarcados($idusuario){
	$sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}

//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
    {
    	$sql="SELECT idusuario,nombre,email,cargo FROM usuario WHERE email='$login' AND clave='$clave' AND condicion='1'"; 
    	return ejecutarConsulta($sql);  
    }
}

 ?>
