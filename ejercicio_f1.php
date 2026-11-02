<?php

// Clases sencillas para guardar los datos de cada piloto.
class Monoplaza
{
    public $nombre;
    public $nacionalidad;
    public $numero;
    public $puntos = 0;
    public $categoria;

    public function __construct($nombre, $nacionalidad, $numero, $categoria)
    {
        $this->nombre = $nombre;
        $this->nacionalidad = $nacionalidad;
        $this->numero = $numero;
        $this->categoria = $categoria;
    }

    public function otorgarPuntos($posicion, $vueltaRapida = false)
    {
        if (!posicionValida($posicion, $this->categoria)) {
            echo "Posición no válida.\n";
            return;
        }
        $this->puntos += puntosPorPosicion($posicion, $this->categoria);
        // La vuelta rápida suma un punto en F1 y F2 si queda entre los diez primeros.
        if ($vueltaRapida && $posicion <= 10 && ($this->categoria == 'F1' || $this->categoria == 'F2')) {
            $this->puntos++;
        }
    }

    public function subirCategoria($tienePuntosSuficientes = false)
    {
        $categorias = ['F Academy' => 'F3', 'F3' => 'F2', 'F2' => 'F1'];
        if (!isset($categorias[$this->categoria])) return null;
        if ($this->categoria == 'F2' && !$tienePuntosSuficientes) return null;
        return new Monoplaza($this->nombre, $this->nacionalidad, $this->numero, $categorias[$this->categoria]);
    }
}

class F1 extends Monoplaza
{
    public $pais;
    public function __construct($nombre, $nacionalidad, $numero, $pais)
    {
        parent::__construct($nombre, $nacionalidad, $numero, 'F1');
        $this->pais = $pais;
    }
}

class F2 extends Monoplaza
{
    public $tieneSuperlicencia;
    public function __construct($nombre, $nacionalidad, $numero, $tieneSuperlicencia = false)
    {
        parent::__construct($nombre, $nacionalidad, $numero, 'F2');
        $this->tieneSuperlicencia = $tieneSuperlicencia;
    }
}

class F3 extends Monoplaza
{
    public $academia;
    public function __construct($nombre, $nacionalidad, $numero, $academia)
    {
        parent::__construct($nombre, $nacionalidad, $numero, 'F3');
        $this->academia = $academia;
    }
}

class F4 extends Monoplaza
{
    public $patrocinador;
    public function __construct($nombre, $nacionalidad, $numero, $patrocinador)
    {
        parent::__construct($nombre, $nacionalidad, $numero, 'F4');
        $this->patrocinador = $patrocinador;
    }
}

class FAcademy extends Monoplaza
{
    public $potenciaMotor;
    public function __construct($nombre, $nacionalidad, $numero, $potenciaMotor)
    {
        parent::__construct($nombre, $nacionalidad, $numero, 'F Academy');
        $this->potenciaMotor = $potenciaMotor;
    }
}

function puntosPorPosicion($posicion, $categoria)
{
    $puntos = [
        'F1' => [25, 18, 15, 12, 10, 8, 6, 4, 2, 1],
        'F4' => [25, 18, 15, 12, 10, 8, 6, 4, 2, 1],
        'F2' => [10, 8, 7, 6, 5, 4, 3, 2, 1],
        'F3' => [10, 8, 7, 6, 5, 4, 3, 2, 1],
        'F Academy' => [18, 15, 12, 10, 8, 6, 4, 2, 1]
    ];
    return $puntos[$categoria][$posicion - 1] ?? 0;
}

function posicionValida($posicion, $categoria)
{
    $maximos = ['F1' => 22, 'F2' => 24, 'F3' => 30, 'F4' => 30, 'F Academy' => 18];
    return isset($maximos[$categoria]) && $posicion >= 1 && $posicion <= $maximos[$categoria];
}

function consultarPilotosOpenF1()
{
    $url = 'https://api.openf1.org/v1/drivers?driver_number=16&session_key=latest';
    $respuesta = file_get_contents($url);
    if ($respuesta === false) return null;
    return json_decode($respuesta, true);
}

// Ejemplo de uso
$piloto = new F1('Charles Leclerc', 'Monegasca', 16, 'Mónaco');
$piloto->otorgarPuntos(1, true);
echo $piloto->nombre . ' tiene ' . $piloto->puntos . " puntos.\n";

// Para probar la API, descomenta estas líneas:
// $pilotos = consultarPilotosOpenF1();
// print_r($pilotos);
