<?php

/**
 * Ejercicio de gestión de monoplazas.
 * Se puede ejecutar con: php ejercicio_f1.php
 */

class Monoplaza
{
    public string $nombrePiloto;
    public string $nacionalidad;
    public int $numero;
    public int $puntos;
    public string $categoria;

    public function __construct(string $nombrePiloto, string $nacionalidad, int $numero, string $categoria)
    {
        $this->nombrePiloto = $nombrePiloto;
        $this->nacionalidad = $nacionalidad;
        $this->numero = $numero;
        $this->categoria = $categoria;
        $this->puntos = 0;
    }

    public function otorgarPuntos(int $posicion, bool $vueltaRapida = false): void
    {
        if (!posicionValida($posicion, $this->categoria)) {
            echo "La posición no es válida para {$this->nombrePiloto}.\n";
            return;
        }

        $this->puntos += puntosPorPosicion($posicion, $this->categoria);

        // La vuelta rápida da un punto en F1 y F2, si el piloto acaba entre los diez primeros.
        if ($vueltaRapida && $posicion <= 10 && in_array($this->categoria, ['F1', 'F2'], true)) {
            $this->puntos++;
        }
    }

    public function subirCategoria(bool $tienePuntosSuficientes = false): ?Monoplaza
    {
        $siguiente = [
            'F Academy' => 'F3',
            'F3' => 'F2',
            'F2' => 'F1',
        ];

        if (!isset($siguiente[$this->categoria])) {
            echo "{$this->categoria} ya es la categoría más alta.\n";
            return null;
        }

        if ($this->categoria === 'F2' && !$tienePuntosSuficientes) {
            echo "No tiene los puntos de superlicencia necesarios para subir a F1.\n";
            return null;
        }

        return new Monoplaza($this->nombrePiloto, $this->nacionalidad, $this->numero, $siguiente[$this->categoria]);
    }
}

class F1 extends Monoplaza
{
    public string $pais;

    public function __construct(string $nombrePiloto, string $nacionalidad, int $numero, string $pais)
    {
        parent::__construct($nombrePiloto, $nacionalidad, $numero, 'F1');
        $this->pais = $pais;
    }
}

class F3 extends Monoplaza
{
    public string $academia;

    public function __construct(string $nombrePiloto, string $nacionalidad, int $numero, string $academia)
    {
        parent::__construct($nombrePiloto, $nacionalidad, $numero, 'F3');
        $this->academia = $academia;
    }
}

class F2 extends Monoplaza
{
    public bool $tieneSuperlicencia;

    public function __construct(string $nombrePiloto, string $nacionalidad, int $numero, bool $tieneSuperlicencia = false)
    {
        parent::__construct($nombrePiloto, $nacionalidad, $numero, 'F2');
        $this->tieneSuperlicencia = $tieneSuperlicencia;
    }
}

class F4 extends Monoplaza
{
    public string $patrocinador;

    public function __construct(string $nombrePiloto, string $nacionalidad, int $numero, string $patrocinador)
    {
        parent::__construct($nombrePiloto, $nacionalidad, $numero, 'F4');
        $this->patrocinador = $patrocinador;
    }
}

class FAcademy extends Monoplaza
{
    public int $potenciaMotor;

    public function __construct(string $nombrePiloto, string $nacionalidad, int $numero, int $potenciaMotor)
    {
        parent::__construct($nombrePiloto, $nacionalidad, $numero, 'F Academy');
        $this->potenciaMotor = $potenciaMotor;
    }
}

function puntosPorPosicion(int $posicion, string $categoria): int
{
    $tablas = [
        'F1' => [25, 18, 15, 12, 10, 8, 6, 4, 2, 1],
        'F4' => [25, 18, 15, 12, 10, 8, 6, 4, 2, 1],
        'F2' => [10, 8, 7, 6, 5, 4, 3, 2, 1],
        'F3' => [10, 8, 7, 6, 5, 4, 3, 2, 1],
        'F Academy' => [18, 15, 12, 10, 8, 6, 4, 2, 1],
    ];

    return $tablas[$categoria][$posicion - 1] ?? 0;
}

function posicionValida(int $posicion, string $categoria): bool
{
    $pilotos = [
        'F1' => 22,
        'F2' => 24,
        'F3' => 30,
        'F4' => 30,
        'F Academy' => 18,
    ];

    return isset($pilotos[$categoria]) && $posicion >= 1 && $posicion <= $pilotos[$categoria];
}

function consultarPilotosOpenF1(): ?array
{
    // La sesión latest corresponde a la sesión más reciente de la temporada.
    $url = 'https://api.openf1.org/v1/drivers?session_key=latest';
    $opciones = stream_context_create(['http' => ['timeout' => 5]]);
    $respuesta = @file_get_contents($url, false, $opciones);

    if ($respuesta === false) {
        return null;
    }

    $datos = json_decode($respuesta, true);
    if (!is_array($datos)) {
        return null;
    }

    $pilotos = array_filter($datos, function ($piloto) {
        return in_array($piloto['driver_number'] ?? 0, [16, 44], true);
    });

    return array_values($pilotos);
}

// Ejemplo sencillo de uso
$piloto = new F1('Charles Leclerc', 'Monegasca', 16, 'Mónaco');
$piloto->otorgarPuntos(1, true);
echo $piloto->nombrePiloto . ' tiene ' . $piloto->puntos . " puntos.\n";

// Para consultar la API, descomenta estas líneas:
// $pilotos = consultarPilotosOpenF1();
// if ($pilotos !== null) {
//     print_r($pilotos);
// }

