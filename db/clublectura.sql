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
  , fecha_publicación   DATE                NOT NULL
);

DROP TABLE IF EXISTS peliculas CASCADE;

CREATE TABLE peliculas
(

);