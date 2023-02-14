<?php

require 'flight/Flight.php';//cargo el framework

//conecto con la base de datos
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=api','root',''));

//llamo el framework el cual llama una funcion que es 
//la primera que se ejecuta

/*
 * crear una nueva tarea		POST		/tasks
 * borrar una tarea existente	DELETE		/tasks/{id}
 * traer una tarea especifica	GET		/tasks/{id}
 * buscar tarea			GET		/tasks
 * actualizar una tarea existente	PUT		/tasks/{id}
 *
*/

//  GET es un método de solicitud
// luego del get es la URL

// LLAMAR INFORMACION

Flight::route('GET /alumnos', function () {
    //Error posible en las comillas simples, es: Alt Gr la tecla de cierre de llaves
    $sentencia = Flight :: db()->prepare("SELECT * FROM `alumnos`");         
    $sentencia->execute();
    $datos = $sentencia->fetchALL();

    //con esto mostrará los datos en formato json
    Flight::json($datos);

});

// RECEPCIONA E INSERTA LOS DATOS
Flight::route('POST /alumnos', function () {
    $nombres = (Flight::request()->data->nombres);
    $apellidos = (Flight::request()->data->apellidos);

    $sql = "INSERT INTO alumnos (nombres,apellidos) VALUES(?,?)";
    //aquí lo ejecutamos a la sentencia sql
    $sentencia = Flight :: db()->prepare($sql);
    //agregamos los elementos que son el ?,?
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->execute();

    //enviamos un json
    Flight::jsonp(["Alumno agregado correctamente"]);
});

//BORRAR DATOS
Flight::route('DELETE /alumnos', function () {
    $id = (Flight::request()->data->id);

    $sql = "DELETE FROM alumnos WHERE id = ?";
    $sentencia = Flight :: db()->prepare($sql);
    //agregamos los elementos que son el ?,?
    $sentencia->bindParam(1,$id);
    $sentencia->execute();
    Flight::jsonp(["Alumno borrado"]);

});

//ACTUALIZAR BASE DE DATOS
Flight::route('PUT /alumnos', function () {
    $id = (Flight::request()->data->id);
    $nombres = (Flight::request()->data->nombres);
    $apellidos = (Flight::request()->data->apellidos);

    $sql = "UPDATE alumnos SET nombres=?, apellidos=? WHERE id=?";
    //aquí lo ejecutamos a la sentencia sql
    $sentencia = Flight :: db()->prepare($sql);
    
    //los numeros es en el orden en que esta escrita la sentencia
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->bindParam(3,$id);

    $sentencia->execute();

    print_r($id);
    print_r($nombres);
    print_r($apellidos);
    Flight::jsonp(["Tabla actualizada correctamente"]);


});

//LECTURA DE UN REGISTRO DETERMINADO
Flight::route('GET /alumnos/@id', function ($id) {

    $sentencia = Flight :: db()->prepare("SELECT * FROM `alumnos` WHERE id=?");
    $sentencia->bindParam(1,$id); 

    $sentencia->execute();
    $datos = $sentencia->fetchALL();
    Flight::json($datos);
});
Flight::start();
