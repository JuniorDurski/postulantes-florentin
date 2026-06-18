<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;

        background-image: url('images/menu.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    header {
        display: none; /* Ya no usamos el header */
    }

    nav {
        background: rgba(255, 255, 255, 0.85);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        backdrop-filter: blur(3px);
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center; /* Centra verticalmente texto e imagen */
        gap: 15px;
    }

    nav li {
        padding: 5px 25px;
    }

    nav a {
        text-decoration: none;
        color: #333;
        font-weight: bold;
        transition: color 0.3s;
    }

    nav a:hover {
        color: #004aad;
    }

    /* Título dentro del menú */
    .titulo-menu {
        font-size: 22px;
        font-weight: bold;
        color: #004aad;
        display: flex;
        align-items: center;
        gap: 10px;
        vertical-align: top !important; 
    }
</style>
</head>

<body>

    <nav>
        <ul>
            <!-- TÍTULO + IMAGEN -->
            <li class="titulo-menu">
                <a href="<?php echo __dir__; ?>">
                    <img src="images/icono_menu.png" alt="Logo" width="300"></img>
                </a>
            </li>

            <!-- OPCIONES DEL MENÚ -->
            <li><a href="designacion.php">DESIGNACIONES</a></li>
            <li><a href="estudios.php">ESTUDIOS</a></li>
            <li><a href="pacientes.php">PACIENTES</a></li>
            <li><a href="reporte_laboratorios.php">IMPRIMIR LABORATORIO</a></li>
        </ul>
    </nav>

</body>
