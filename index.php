<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programación Web Avanzada</title>
    <style>
        * {
            margin: 50;
            radius: 25;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            font-size: 2.5em;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 40px;
            font-size: 1.1em;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .info-section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .info-section p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .ejercicios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .ejercicio-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .ejercicio-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .ejercicio-card h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .ejercicio-card p {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            color: #666;
            font-size: 0.9em;
        }

        .badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Tarea 4</h1>
        <p class="subtitle">Marco Vinicio Chochos Astudillo</p>
        <p class="subtitle">Programación Web Avanzada - UBE</p>

        <div class="info-section">
            <h2>📌 Proyecto</h2>
            <p><strong>Tema:</strong> Desarrollo de Sistemas Web con HTML, CSS, JavaScript y PHP</p>
            <p><strong>Objetivo:</strong> Desarrollar habilidades fundamentales para el diseño e implementación de sistemas web interactivos y funcionales.</p>
            <p><strong>Principales:</strong> HTML5, CSS3, JavaScript, PHP, MySQL</p>
        </div>

        <h2 style="color: #333; margin-bottom: 20px; text-align: center;">📚 Ejercicios</h2>

        <div class="ejercicios-grid">
            <a href="ejercicio01/" class="ejercicio-card">
                <h3>📝 Ejercicio 01</h3>
                <p>Lista de Tareas Interactiva</p>
                <span class="badge">Completado</span>
            </a>

            <a href="ejercicio02/" class="ejercicio-card" style="opacity: 0.8; cursor: not-allowed;">
                <h3>📋 Ejercicio 02</h3>
                <p>Siguiente tarea</p>
                <span <continue class="badge">En Progreso</span>
            
            </a>

            <a href="ejercicio03/" class="ejercicio-card" style="opacity: 0.7; cursor: not-allowed;">
                <h3>📊 Ejercicio 03</h3>
                <p>Siguiente tarea</p>
            </a>

            <a href="ejercicio04/" class="ejercicio-card" style="opacity: 0.5; cursor: not-allowed;">
                <h3>🔧 Ejercicio 04</h3>
                <p>Siguiente tarea</p>
            </a>

        </div>

        <div class="footer">
            <p>Desarrollado para la materia de Programación Web Avanzada</p>
            <p>Universidad Bolivariana del Ecuador - <?php echo date('Y'); ?></p>
        </div>
    </div>
</body>
</html>