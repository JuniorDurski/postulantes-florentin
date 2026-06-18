CREATE TABLE designaciones (
    id_designacion INT AUTO_INCREMENT PRIMARY KEY,
    designacion VARCHAR(150) NOT NULL,
    fecha DATE NOT NULL
);

CREATE TABLE estudios (
    id_estudio INT AUTO_INCREMENT PRIMARY KEY,
    estudio VARCHAR(150) NOT NULL,
    fecha DATE NOT NULL
);

CREATE TABLE requisitos (
    id_requisito INT AUTO_INCREMENT PRIMARY KEY,
    id_designacion INT NOT NULL,
    id_estudio INT NOT NULL,
    FOREIGN KEY (id_designacion) REFERENCES designaciones (id_designacion),
    FOREIGN KEY (id_estudio) REFERENCES estudios (id_estudio)
);

CREATE TABLE secciones (
    id_seccion INT AUTO_INCREMENT PRIMARY KEY,
    id_estudio INT NOT NULL,
    seccion VARCHAR(150) NOT NULL,
    FOREIGN KEY (id_estudio) REFERENCES estudios (id_estudio)
);

CREATE TABLE examen (
    id_examen INT AUTO_INCREMENT PRIMARY KEY,
    id_seccion INT NOT NULL,
    examen VARCHAR(150) NOT NULL,
    unidad VARCHAR(50) NOT NULL,
    referencial VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_seccion) REFERENCES secciones (id_seccion)
);

CREATE TABLE postulantes (
    id_postulante INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    ci VARCHAR(20) NOT NULL,
    edad INT NOT NULL,
    sexo VARCHAR(10) NOT NULL,
    fecha DATE not null,
    id_designacion INT NOT NULL,
    FOREIGN KEY (id_designacion) REFERENCES designaciones (id_designacion)
);

CREATE TABLE resultados (
    id_resultado INT AUTO_INCREMENT PRIMARY KEY,
    id_examen INT NOT NULL,
    id_postulante INT NOT NULL,
    resultado VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_examen) REFERENCES examen (id_examen),
    FOREIGN KEY (id_postulante) REFERENCES postulantes (id_postulante)
);
