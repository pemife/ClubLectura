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
  , editorial           VARCHAR(255)        NOT NULL
  , isbn                VARCHAR(13)         NOT NULL
  , fecha_publicacion   DATE                NOT NULL
  , descripcion         TEXT
);

DROP TABLE IF EXISTS peliculas CASCADE;

CREATE TABLE peliculas
(
    id                  BIGSERIAL           PRIMARY KEY
  , titulo              VARCHAR(255)        NOT NULL
  , director            VARCHAR(255)        NOT NULL
  , guionistas          VARCHAR(255)        NOT NULL
  , productores         VARCHAR(255)        NOT NULL
  , principales_actores TEXT
  , descripcion         TEXT
);

DROP TABLE IF EXISTS seleccion CASCADE;

CREATE TABLE seleccion
(
    usuario_id          BIGINT              NOT NULL
  , libro_id            BIGINT              NOT NULL
  , PRIMARY KEY(usuario_id, libro_id)
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
  , CONSTRAINT ck_alternar_valores_nulos CHECK (
        (libro_id IS NOT NULL AND pelicula_id IS NULL)
        OR
        (libro_id IS NULL AND pelicula_id IS NOT NULL)
);