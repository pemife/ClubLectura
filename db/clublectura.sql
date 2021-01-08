------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id                  BIGSERIAL       PRIMARY KEY
  , nombre              VARCHAR(32)     NOT NULL UNIQUE
                                        CONSTRAINT ck_nombre_sin_espacios
                                        CHECK (nombre NOT ILIKE '% %')
  , password            VARCHAR(60)     NOT NULL
  , created_at          DATE            NOT NULL DEFAULT CURRENT_DATE
  , requested_at        TIMESTAMP(0)    DEFAULT CURRENT_TIMESTAMP
  , token               VARCHAR(32)
  , email               VARCHAR(255)    NOT NULL UNIQUE
  , biografia           TEXT
  , fechanac            DATE            CHECK (fechanac < CURRENT_DATE)
  --, es_critico          BOOLEAN         DEFAULT false
  --, img_key             VARCHAR(255)
  --, fondo_key           VARCHAR(255)
);

DROP TABLE IF EXISTS libros CASCADE;

CREATE TABLE libros
(
    id                  BIGSERIAL           PRIMARY KEY
  , titulo              VARCHAR(255)        NOT NULL
  , autor               VARCHAR(100)        NOT NULL
  , editorial           VARCHAR(255)
  , isbn                NUMERIC(13)         NOT NULL
  , fecha_publicacion   DATE
  , fecha_1a_edicion    DATE
  , descripcion         TEXT
  , n_paginas           NUMERIC(4)
  , img_key             VARCHAR(255)
);

DROP TABLE IF EXISTS peliculas CASCADE;

CREATE TABLE peliculas
(
    id                  BIGSERIAL           PRIMARY KEY
  , titulo              VARCHAR(255)        NOT NULL
  , director            VARCHAR(255)        NOT NULL
  , guionistas          VARCHAR(255)        NOT NULL
  , productores         VARCHAR(255)
  , principales_actores TEXT
  , descripcion         TEXT
  --, img_key             VARCHAR(255)
);

DROP TABLE IF EXISTS seleccion CASCADE;

CREATE TABLE seleccion
(
    orden               NUMERIC(2)          NOT NULL
  , usuario_id          BIGINT              REFERENCES usuarios(id)
                                            ON DELETE CASCADE
                                            ON UPDATE CASCADE
  , libro_id            BIGINT              REFERENCES libros(id)
                                            
                                            ON DELETE SET NULL
                                            ON UPDATE CASCADE
  , PRIMARY KEY(usuario_id, libro_id)
  , CONSTRAINT uq_seleccion_libro  UNIQUE (libro_id)
);

DROP TABLE IF EXISTS criticas CASCADE;

CREATE TABLE criticas
(
    id                  BIGSERIAL           PRIMARY KEY
  , created_at          DATE                NOT NULL
                                            DEFAULT CURRENT_TIMESTAMP
  , texto               TEXT                NOT NULL
  , usuario_id          BIGINT              NOT NULL
                                            REFERENCES usuarios(id)
                                            ON DELETE SET NULL
                                            ON UPDATE CASCADE
  , libro_id            BIGINT              REFERENCES libros(id)
                                            ON DELETE SET NULL
                                            ON UPDATE CASCADE
  , pelicula_id         BIGINT              REFERENCES peliculas(id)
                                            ON DELETE SET NULL
                                            ON UPDATE CASCADE
  , CONSTRAINT uq_usuario_libro  UNIQUE (usuario_id, libro_id)
  , CONSTRAINT uq_usuario_pelicula  UNIQUE (usuario_id, pelicula_id)
  , CONSTRAINT ck_alternar_valores_nulos_criticas CHECK (
            (libro_id IS NOT NULL AND pelicula_id IS NULL)
            OR
            (libro_id IS NULL AND pelicula_id IS NOT NULL)
    )
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
    id                  BIGSERIAL           PRIMARY KEY
  , created_at          TIMESTAMPTZ(0)      NOT NULL
                                            DEFAULT CURRENT_TIMESTAMP
  , texto               TEXT                NOT NULL
  , usuario_id          BIGINT              REFERENCES usuarios(id)
                                            ON DELETE SET NULL
                                            ON UPDATE CASCADE
  , pelicula_id         BIGINT              NOT NULL
                                            REFERENCES peliculas(id)
                                            ON DELETE CASCADE
                                            ON UPDATE CASCADE
  , libro_id            BIGINT              NOT NULL
                                            REFERENCES libros(id)
                                            ON DELETE CASCADE
                                            ON UPDATE CASCADE
  , CONSTRAINT ck_alternar_valores_nulos_comentarios CHECK (
        (libro_id IS NOT NULL AND pelicula_id IS NULL)
        OR
        (libro_id IS NULL AND pelicula_id IS NOT NULL)
    )
);

DROP TABLE IF EXISTS libro_usuario CASCADE;

CREATE TABLE libro_usuario
(
    usuario_id      BIGINT          NOT NULL
                                    REFERENCES usuarios(id)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE
  , libro_id        BIGINT          NOT NULL
                                    REFERENCES libros(id)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE
  , created_at      TIMESTAMP       NOT NULL
                                    DEFAULT CURRENT_TIMESTAMP
);

--   INSERTS   --

INSERT INTO usuarios (nombre, password, email, fechanac)
VALUES ('admin', crypt('hnmpl', gen_salt('bf', 10)), 'gamesandfriends2@gmail.com', '1987-01-01'),
('pepe', crypt('pepe', gen_salt('bf', 10)), 'jose.millan@iesdonana.org', '1995-12-03'),
('potaita', crypt('potaita', gen_salt('bf', 10)), 'astutapotaita@gmail.com', '1990-11-16');

INSERT INTO libros (titulo, autor, editorial, isbn, fecha_publicacion, fecha_1a_edicion, descripcion, n_paginas)
VALUES ('La historia interminable', 'Michael Ende', 'Santillana', 9788491220787, '2016-1-1', '1979-1-1', null, 256),
('El retrato de Dorian Gray', 'Oscar Wilde', 'Siruela', 9788417860134, '2019-1-1', '1890-6-20', null, 280),
('Dorian Gray (Comic)', 'Enrique Corominas', 'Diabolo', 9788415153498, '2015-1-1', '2012-4-1', null, 90),
('El retrato de Dorian Gray (Marvel)', 'Roy Thomasoscar Wilde', 'Panini', 9788490242414, '2012-1-1', null, null, 160),
('Charlie y la fábrica de chocolate', 'Roald Dahl', 'Santillana', 9788491221166, '2016-1-1', '1964-1-1', null, 240),
('El guardián invisible', 'Dolores Redondo', 'Destino', 9788423350995, '2016-1-1', '2012-1-1', null, 432),
('Stardust', 'Neil Gaiman', 'Rocabolsillo', 9788496940888, '2010-1-1', '1999-2-1', null, 228);

INSERT INTO peliculas (titulo, director, guionistas, productores, principales_actores, descripcion)
VALUES ('La historia interminable', 'Wolfgang Petersen', 'Herman Weigel y Wolfgang Petersen', null, 'Noah Hathaway, Barret Oliver, Tami Stronach, etc.', null),
('El retrato de Dorian Gray', 'Albert Lewin', 'Albert Lewin', null, 'George Sanders, Hurd Hatfield, Donna Reed, etc.', null),
('Un mundo de fantasía', 'Mel Stuart', 'Roald Dahl', null, 'Gene Wilder, Jack Albertson, Peter Ostrum, etc.', null),
('Charlie y la fábrica de chocolate', 'Tim Burton', 'John August', null, 'Johnny Depp, Freddie Highmore, David Kelly, etc.', null),
('El guardián invisible', 'Fernando González Molina', 'Luiso Berdejo', null, 'Marta Etura, Elvira Mínguez, Nene, etc.', null);