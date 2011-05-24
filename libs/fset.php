<?
/**
 * Clase fset (FastSet)
 * Nota: en el ERP hay que usar siempre que se pueda esta clase en lugar de Set
 * Alternativa de uso más rápido a libs/Set de cakephp, sirve para operaciones con arrays aunque está más limitada.
 * A diferencia de Set de cakephp, esta clase opera con valores por referencia, por lo que se consume menos memoria
 * y se ejecutan las llamadas de forma más rápida.
 * @name fset
 * @author Carlos Gant <carlos@aicor.com>
 * Más información:
 * Para las operaciones de obtener un valor de un array usnado la notación de separador por puntos, este algoritmo
 * es más de 10 veces más rápido que set::classicExtract de cakephp, pero solo soporta rutas completas.
 *		Clave válida: Clientes.1.nombre
 *		Clave no válida: {n}.Cliente.nombre (y en general cualquier otra notación que no sea como la clave válida)
 *		Clave no válida: /Cliente/id (esta clase no soporta XPATH, ni tampoco lo pretende)
 **/
class fset {

	function get(array &$data, $path){
		$keys = explode('.', $path);
		foreach($keys as $k){
			if(isset($data[$k])){
				$data =& $data[$k];
			}else{
				return null;
			}
		}
		return $data;
	}

	function set(array &$data, $path, $value){
		$keys = explode('.', $path);
		$last = array_pop($keys);
		foreach($keys as $k){
			if(isset($data[$k]) && is_array($data[$k])){
				$data =& $data[$k];
			}else{
				$data[$k] = array();
				$data =& $data[$k];
			}
		}
		$data[$last] = $value;
	}

	function count(array &$data, $path){
		$keys = explode('.', $path);
		$last = array_pop($keys);
		foreach($keys as $k){
			if(isset($data[$k]) && is_array($data[$k])){
				$data =& $data[$k];
			}else{
				return null;
			}
		}
		return isset($data[$last]) && is_array($data[$last]) ? count($data[$last]) : null;
	}

	function del(array &$data, $path){
		$keys = explode('.', $path);
		$last = array_pop($keys);
		foreach($keys as $k){
			if(isset($data[$k]) && is_array($data[$k])){
				$data =& $data[$k];
			}else{
				return;
			}
		}
		unset($data[$last]);
	}

	static function is_set(array &$data, $path){
		$keys = explode('.', $path);
		$last = array_pop($keys);
		foreach($keys as $k){
			if(isset($data[$k]) && is_array($data[$k])){
				$data =& $data[$k];
			}else{
				return false;
			}
		}
		return isset($data[$last]);
	}

	static function is_empty(array &$data, $path){
		$keys = explode('.', $path);
		$last = array_pop($keys);
		foreach($keys as $k){
			if(isset($data[$k]) && is_array($data[$k])){
				$data =& $data[$k];
			}else{
				return true;
			}
		}
		return empty($data[$last]);
	}

}