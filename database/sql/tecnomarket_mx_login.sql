-- ============================================================
-- tecnomarket_mx: preparar la tabla `cliente` para el login
-- Ejecutar UNA sola vez, DESPUES de importar tecnomarket_mx.sql
-- (phpMyAdmin -> seleccionar la base tecnomarket_mx -> Importar)
-- ============================================================

-- 1) Rol del usuario: 'admin' o 'cliente'
ALTER TABLE `cliente`
  ADD COLUMN `rol` varchar(10) NOT NULL DEFAULT 'cliente' AFTER `contrasena`;

-- 2) El correo identifica al usuario, no debe repetirse
ALTER TABLE `cliente`
  ADD UNIQUE KEY `uq_cliente_correo` (`correo`);

-- 3) Contrasenas encriptadas con bcrypt (antes estaban en texto plano).
--    Las contrasenas para entrar siguen siendo las mismas de siempre.
UPDATE `cliente` SET `contrasena` = '$2y$12$vvtNjGi7x.unoYTSMA/ZaOK.8y.4m.0k1vm8DwROYvtb0QaevorUi' WHERE `id_cliente` = 1;
UPDATE `cliente` SET `contrasena` = '$2y$12$EnEJcZ25IBVKmrNSnIN4ROpNJWzAOwqRewKYhhEHr2nVa0GlVniQ.' WHERE `id_cliente` = 2;
UPDATE `cliente` SET `contrasena` = '$2y$12$lrFK5R8UsA0XBrk2HhjQxOKrxK.6jJrvxQHpa/V7kaBXqBYCtf0T.' WHERE `id_cliente` = 3;
UPDATE `cliente` SET `contrasena` = '$2y$12$dIj30Dbw/g1VNbh5y/5kDOD9kfasU6QoImK5N5eK8h1OBfnF89XpK' WHERE `id_cliente` = 4;
UPDATE `cliente` SET `contrasena` = '$2y$12$FDrKwjrhLoWBiOfY.YW3vOiBHC/HTccJT0ptaJuD87U/ZOOyms4mq' WHERE `id_cliente` = 5;
UPDATE `cliente` SET `contrasena` = '$2y$12$7PPJkn/6NGgdsNe5.XpLPOJxGxcA5GMWJ2t.1y/NN/xzxxk2TyHs.' WHERE `id_cliente` = 6;
UPDATE `cliente` SET `contrasena` = '$2y$12$gxYbE6JVPUpIG/0MNqh8yehT9TEbPeIHCWQJs8AqVDuG.ty1W9Vh.' WHERE `id_cliente` = 7;
UPDATE `cliente` SET `contrasena` = '$2y$12$5bQRqh7iOJ1qqGBgAB4VHOEt5QmsyRPPrNV7v6taLp9dyZQmV6ksy' WHERE `id_cliente` = 8;
UPDATE `cliente` SET `contrasena` = '$2y$12$bLnQ.uzEPGhSLdVG7c3bSuGY4jZ7W5EiQDKvsFGG.KkcOalEqvVN2' WHERE `id_cliente` = 9;
UPDATE `cliente` SET `contrasena` = '$2y$12$4WFXEAon75okjDzEeSinPeY2J9nIO8dPl2eaa.CvDDEeBmQKVBVCK' WHERE `id_cliente` = 10;

-- 4) Usuario administrador  (correo: admin@tecnomarket.mx  contrasena: admin123)
INSERT INTO `cliente` (`nombre`, `apellido_p`, `apellido_m`, `correo`, `telefono`, `contrasena`, `rol`) VALUES
('Administrador', 'TecnoMarket', NULL, 'admin@tecnomarket.mx', NULL, '$2y$12$kmWnTdN7UH6gwNC1qZKWlOC1Uw.Niw1PYU4JnI9AM.c61BnlkbvoS', 'admin');
