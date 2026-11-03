<?php
ob_start();
require_once __DIR__ . '/ejercicio_f1.php';
$ejemplo = trim(ob_get_clean());

$pilotos = [
    ['piloto' => new F1('Marco Velasco', 'Española', 16, 'España'), 'posicion' => 1, 'vueltaRapida' => true],
    ['piloto' => new F1('Leo Serrano', 'Española', 44, 'España'), 'posicion' => 2, 'vueltaRapida' => false],
    ['piloto' => new F1('Dario Montero', 'Italiana', 1, 'Italia'), 'posicion' => 3, 'vueltaRapida' => false],
    ['piloto' => new F1('Bruno Alcázar', 'Española', 4, 'España'), 'posicion' => 4, 'vueltaRapida' => false],
    ['piloto' => new F2('Adrián Casero', 'Española', 7), 'posicion' => 1, 'vueltaRapida' => true],
    ['piloto' => new F2('Hugo Beltrán', 'Española', 10), 'posicion' => 2, 'vueltaRapida' => false],
    ['piloto' => new F2('Nico Aranda', 'Española', 11), 'posicion' => 3, 'vueltaRapida' => false],
    ['piloto' => new F2('Eric Molina', 'Española', 3), 'posicion' => 4, 'vueltaRapida' => false],
    ['piloto' => new F3('Joel Ríos', 'Española', 1, 'Academia Norte'), 'posicion' => 1, 'vueltaRapida' => false],
    ['piloto' => new F3('Pablo Luján', 'Española', 12, 'Academia Sur'), 'posicion' => 2, 'vueltaRapida' => false],
    ['piloto' => new F3('Gael Navarro', 'Española', 5, 'Academia Este'), 'posicion' => 3, 'vueltaRapida' => false],
    ['piloto' => new F3('Sergio Robles', 'Española', 6, 'Academia Oeste'), 'posicion' => 4, 'vueltaRapida' => false],
    ['piloto' => new F4('Iván Cordero', 'Española', 21, 'Equipo Azul'), 'posicion' => 1, 'vueltaRapida' => false],
    ['piloto' => new F4('Mario Salvatierra', 'Española', 88, 'Equipo Rojo'), 'posicion' => 2, 'vueltaRapida' => false],
    ['piloto' => new F4('Enzo Figueroa', 'Española', 22, 'Equipo Verde'), 'posicion' => 3, 'vueltaRapida' => false],
    ['piloto' => new F4('Raúl Mena', 'Española', 3, 'Equipo Blanco'), 'posicion' => 4, 'vueltaRapida' => false],
    ['piloto' => new FAcademy('Laura Campos', 'Española', 9, 160), 'posicion' => 1, 'vueltaRapida' => false],
    ['piloto' => new FAcademy('Alba Ferrer', 'Española', 28, 160), 'posicion' => 2, 'vueltaRapida' => false],
    ['piloto' => new FAcademy('Carla Mendoza', 'Española', 64, 160), 'posicion' => 3, 'vueltaRapida' => false],
    ['piloto' => new FAcademy('Noa Varela', 'Española', 5, 160), 'posicion' => 4, 'vueltaRapida' => false],
];

foreach ($pilotos as $fila) {
    $fila['piloto']->otorgarPuntos($fila['posicion'], $fila['vueltaRapida']);
}

$categorias = ['F1', 'F2', 'F3', 'F4', 'F Academy'];
$pilotosApi = consultarPilotosOpenF1();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio de F1</title>
    <style>
        body { margin: 0; background: #f3f4f6; color: #20242a; font-family: Arial, sans-serif; }
        main { width: min(900px, 92%); margin: 35px auto; }
        h1 { margin-bottom: 8px; color: #c62828; }
        .panel { margin: 20px 0; padding: 20px; background: white; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f8e9e9; }
        .nota { color: #555; }
    </style>
</head>
<body>
<main>
    <h1>Ejercicio de Fórmula 1</h1>
    <p class="nota">Ejemplo sencillo de pilotos, categorías y puntos.</p>

    <section class="panel">
        <h2>Ejemplo ejecutado</h2>
        <p><?= htmlspecialchars($ejemplo, ENT_QUOTES, 'UTF-8') ?></p>
    </section>

    <section class="panel">
        <h2>Pilotos de ejemplo y puntos</h2>
        <table>
            <thead><tr><th>Piloto</th><th>Categoría</th><th>Posición</th><th>Puntos</th></tr></thead>
            <tbody>
            <?php foreach ($pilotos as $fila): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['piloto']->nombrePiloto ?? $fila['piloto']->nombre, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['piloto']->categoria, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $fila['posicion'] ?></td>
                    <td><?= $fila['piloto']->puntos ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="panel">
        <h2>Puntos según la posición</h2>
        <table>
            <thead><tr><th>Categoría</th><th>1.º</th><th>2.º</th><th>3.º</th><th>4.º</th><th>5.º</th></tr></thead>
            <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8') ?></td>
                    <?php for ($posicion = 1; $posicion <= 5; $posicion++): ?>
                        <td><?= puntosPorPosicion($posicion, $categoria) ?></td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="panel">
        <h2>Datos de OpenF1</h2>
        <p class="nota">Pilotos con los dorsales 16 y 44 en la sesión más reciente.</p>
        <?php if ($pilotosApi === null): ?>
            <p>No se pudo conectar con OpenF1. Comprueba la conexión y vuelve a cargar la página.</p>
        <?php elseif (count($pilotosApi) === 0): ?>
            <p>La API no devolvió esos pilotos para la sesión más reciente.</p>
        <?php else: ?>
            <table>
                <thead><tr><th>Dorsal</th><th>Piloto</th><th>Equipo</th></tr></thead>
                <tbody>
                <?php foreach ($pilotosApi as $pilotoApi): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$pilotoApi['driver_number'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($pilotoApi['full_name'] ?? 'Sin nombre', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($pilotoApi['team_name'] ?? 'Sin equipo', ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
