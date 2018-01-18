DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_trx_amortizacion` $$
CREATE PROCEDURE sp_trx_amortizacion (IN xml_input MEDIUMTEXT)

BEGIN
  DECLARE lafecha, fecha DATE;
  DECLARE elano INT;
  DECLARE lahora char(8);
  DECLARE eltipo varchar(2);
  DECLARE insertado BIGINT;
  DECLARE fcedula, nrocuenta, nombre, ipgenerada, codigo, cedula, ip  VARCHAR(100);
  DECLARE palabrapaso varchar(100);
  DECLARE mitrx BLOB;
  DECLARE k INT UNSIGNED DEFAULT 0;
  DECLARE row_count INT UNSIGNED;
  DECLARE xpath TEXT;

  SELECT extractValue(xml_input, '//order/@codigo') INTO codigo;
  SELECT extractValue(xml_input, '//order/@cedula') INTO cedula;
  SELECT extractValue(xml_input, '//order/@ip') INTO ip;
  SELECT extractValue(xml_input, '//order/@fecha') INTO fecha;
  SELECT SUBSTR(NOW(),1,4), NOW(), SUBSTR(NOW(),11,8) INTO elano, lafecha, lahora;
  SELECT ced_prof, ctan_prof, CONCAT(ape_prof,', ',nombr_prof)
    FROM CAPPOUCLA_sgcaf200 
    WHERE cod_prof=codigo 
    INTO fcedula, nrocuenta,  nombre;

  SET row_count := extractValue(xml_input,'count(//items/trx)');

  WHILE k < row_count DO
   SET k := k + 1;
   SET xpath := concat('//items/trx[', k, ']');
   -- select xpath;
-- select extractValue(xml_input, concat(xpath,'/@nropre'));

-- select extractValue(xml_input, concat(xpath,'/@saldo'));
   INSERT INTO CAPPOUCLA_sgcaamor
     (fecha, codsoc, nropre, cedula, nombre,
     saldo, capital, interes, cuota, codpre,
     cuent_p, cuent_i, cuent_d, ip, nrocuota,
     proceso, nrocta, abonado, ip_abono, tipo,
     pos310, semanal, diferido) VALUES
     (fecha, codigo, extractValue(xml_input, concat(xpath,'/@nropre')), cedula, nombre,
      extractValue(xml_input, concat(xpath,'/@saldo')), extractValue(xml_input, concat(xpath,'/@capital')), extractValue(xml_input, concat(xpath,'/@interes')), extractValue(xml_input, concat(xpath,'/@cuota')), extractValue(xml_input, concat(xpath,'/@codpre')),
      extractValue(xml_input, concat(xpath,'/@cuent_p')), extractValue(xml_input, concat(xpath,'/@cuent_i')), extractValue(xml_input, concat(xpath,'/@cuent_d')), ip, extractValue(xml_input, concat(xpath,'/@nrocuota')),
      1, nrocuenta, lafecha, '', extractValue(xml_input, concat(xpath,'/@tipo')),
      extractValue(xml_input, concat(xpath,'/@pos310')), extractValue(xml_input, concat(xpath,'/@semanal')), extractValue(xml_input, concat(xpath,'/@diferido'))
      );
  END WHILE;

  -- detalles de los descuentos
  SET row_count := extractValue(xml_input,'count(//prestamos/trxp)');
/*
  WHILE k < row_count DO
   SET k := k + 1;
*/
   SET xpath := concat('//prestamos/trxp');
--   select xpath;
-- select extractValue(xml_input, concat(xpath,'/@nropre'));

-- select extractValue(xml_input, concat(xpath,'/@saldo'));
/*
   IF (k = 1) THEN
   BEGIN
*/

   INSERT INTO CAPPOUCLA_sgcanopr
     (fecha, cedula, codigo, nombre, ip, proceso, nrocta) VALUES
     (fecha, cedula, codigo, nombre, ip, 1, nrocuenta);
    SET insertado = LAST_INSERT_ID();
  select insertado;

    UPDATE CAPPOUCLA_sgcanopr SET
      colnro1 = extractValue(xml_input, concat(xpath,'/@colnro',1)),
      colnro2 = extractValue(xml_input, concat(xpath,'/@colnro',2)),
      colnro3 = extractValue(xml_input, concat(xpath,'/@colnro',3)),
      colnro4 = extractValue(xml_input, concat(xpath,'/@colnro',4)),
      colnro5 = extractValue(xml_input, concat(xpath,'/@colnro',5)),
      colnro6 = extractValue(xml_input, concat(xpath,'/@colnro',6)),
      colnro7 = extractValue(xml_input, concat(xpath,'/@colnro',7)),
      colnro8 = extractValue(xml_input, concat(xpath,'/@colnro',8)),
      colnro9 = extractValue(xml_input, concat(xpath,'/@colnro',9)),
      colnro10 = extractValue(xml_input, concat(xpath,'/@colnro',10)),
      colnro11 = extractValue(xml_input, concat(xpath,'/@colnro',11)),
      colnro12 = extractValue(xml_input, concat(xpath,'/@colnro',12)),
      colnro13 = extractValue(xml_input, concat(xpath,'/@colnro',13)),
      colnro14 = extractValue(xml_input, concat(xpath,'/@colnro',14)),
      colnro15 = extractValue(xml_input, concat(xpath,'/@colnro',15)),
      colnro16 = extractValue(xml_input, concat(xpath,'/@colnro',16)),
      colnro17 = extractValue(xml_input, concat(xpath,'/@colnro',17)),
      colnro18 = extractValue(xml_input, concat(xpath,'/@colnro',18)),
      colnro19 = extractValue(xml_input, concat(xpath,'/@colnro',19)),
      colnro20 = extractValue(xml_input, concat(xpath,'/@colnro',20)),
      colnro21 = extractValue(xml_input, concat(xpath,'/@colnro',21)),
      colnro22 = extractValue(xml_input, concat(xpath,'/@colnro',22)),
      colnro23 = extractValue(xml_input, concat(xpath,'/@colnro',23)),
      colnro24 = extractValue(xml_input, concat(xpath,'/@colnro',24)),
      colnro25 = extractValue(xml_input, concat(xpath,'/@colnro',25)),
      colnro26 = extractValue(xml_input, concat(xpath,'/@colnro',26)),
      colnro27 = extractValue(xml_input, concat(xpath,'/@colnro',27)),
      colnro28 = extractValue(xml_input, concat(xpath,'/@colnro',28)),
      colnro29 = extractValue(xml_input, concat(xpath,'/@colnro',29)),
      colnro30 = extractValue(xml_input, concat(xpath,'/@colnro',30)),
      colnro31 = extractValue(xml_input, concat(xpath,'/@colnro',31)),
      colnro32 = extractValue(xml_input, concat(xpath,'/@colnro',32)),
      colnro33 = extractValue(xml_input, concat(xpath,'/@colnro',33)),
      colnro34 = extractValue(xml_input, concat(xpath,'/@colnro',34)),
      colnro35 = extractValue(xml_input, concat(xpath,'/@colnro',35)),
      colnro36 = extractValue(xml_input, concat(xpath,'/@colnro',36)),
      colnro37 = extractValue(xml_input, concat(xpath,'/@colnro',37)),
      colnro38 = extractValue(xml_input, concat(xpath,'/@colnro',38)),
      colnro39 = extractValue(xml_input, concat(xpath,'/@colnro',39)),
      colnro40 = extractValue(xml_input, concat(xpath,'/@colnro',40)),
      colnro41 = extractValue(xml_input, concat(xpath,'/@colnro',41)),
      colnro42 = extractValue(xml_input, concat(xpath,'/@colnro',42)),
      colnro43 = extractValue(xml_input, concat(xpath,'/@colnro',43)),
      colnro44 = extractValue(xml_input, concat(xpath,'/@colnro',44)),
      colnro45 = extractValue(xml_input, concat(xpath,'/@colnro',45)),
      colnro46 = extractValue(xml_input, concat(xpath,'/@colnro',46)),
      colnro47 = extractValue(xml_input, concat(xpath,'/@colnro',47)),
      colnro48 = extractValue(xml_input, concat(xpath,'/@colnro',48)),
      colnro49 = extractValue(xml_input, concat(xpath,'/@colnro',49))
    WHERE registro = insertado;

    UPDATE CAPPOUCLA_sgcanopr SET
      colpre1 = extractValue(xml_input, concat(xpath,'/@colpre',1)),
      colpre2 = extractValue(xml_input, concat(xpath,'/@colpre',2)),
      colpre3 = extractValue(xml_input, concat(xpath,'/@colpre',3)),
      colpre4 = extractValue(xml_input, concat(xpath,'/@colpre',4)),
      colpre5 = extractValue(xml_input, concat(xpath,'/@colpre',5)),
      colpre6 = extractValue(xml_input, concat(xpath,'/@colpre',6)),
      colpre7 = extractValue(xml_input, concat(xpath,'/@colpre',7)),
      colpre8 = extractValue(xml_input, concat(xpath,'/@colpre',8)),
      colpre9 = extractValue(xml_input, concat(xpath,'/@colpre',9))
    WHERE registro = insertado;

    UPDATE CAPPOUCLA_sgcanopr SET
      colpre10 = extractValue(xml_input, concat(xpath,'/@colpre',10)),
      colpre11 = extractValue(xml_input, concat(xpath,'/@colpre',11)),
      colpre12 = extractValue(xml_input, concat(xpath,'/@colpre',12)),
      colpre13 = extractValue(xml_input, concat(xpath,'/@colpre',13)),
      colpre14 = extractValue(xml_input, concat(xpath,'/@colpre',14)),
      colpre15 = extractValue(xml_input, concat(xpath,'/@colpre',15)),
      colpre16 = extractValue(xml_input, concat(xpath,'/@colpre',16)),
      colpre17 = extractValue(xml_input, concat(xpath,'/@colpre',17)),
      colpre18 = extractValue(xml_input, concat(xpath,'/@colpre',18)),
      colpre19 = extractValue(xml_input, concat(xpath,'/@colpre',19))
    WHERE registro = insertado;

    UPDATE CAPPOUCLA_sgcanopr SET
      colpre20 = extractValue(xml_input, concat(xpath,'/@colpre',20)),
      colpre21 = extractValue(xml_input, concat(xpath,'/@colpre',21)),
      colpre22 = extractValue(xml_input, concat(xpath,'/@colpre',22)),
      colpre23 = extractValue(xml_input, concat(xpath,'/@colpre',23)),
      colpre24 = extractValue(xml_input, concat(xpath,'/@colpre',24)),
      colpre25 = extractValue(xml_input, concat(xpath,'/@colpre',25)),
      colpre26 = extractValue(xml_input, concat(xpath,'/@colpre',26)),
      colpre27 = extractValue(xml_input, concat(xpath,'/@colpre',27)),
      colpre28 = extractValue(xml_input, concat(xpath,'/@colpre',28)),
      colpre29 = extractValue(xml_input, concat(xpath,'/@colpre',29))
    WHERE registro = insertado;

    UPDATE CAPPOUCLA_sgcanopr SET
      colpre30 = extractValue(xml_input, concat(xpath,'/@colpre',30)),
      colpre31 = extractValue(xml_input, concat(xpath,'/@colpre',31)),
      colpre32 = extractValue(xml_input, concat(xpath,'/@colpre',32)),
      colpre33 = extractValue(xml_input, concat(xpath,'/@colpre',33)),
      colpre34 = extractValue(xml_input, concat(xpath,'/@colpre',34)),
      colpre35 = extractValue(xml_input, concat(xpath,'/@colpre',35)),
      colpre36 = extractValue(xml_input, concat(xpath,'/@colpre',36)),
      colpre37 = extractValue(xml_input, concat(xpath,'/@colpre',37)),
      colpre38 = extractValue(xml_input, concat(xpath,'/@colpre',38)),
      colpre39 = extractValue(xml_input, concat(xpath,'/@colpre',39))
    WHERE registro = insertado;


    UPDATE CAPPOUCLA_sgcanopr SET
      colpre40 = extractValue(xml_input, concat(xpath,'/@colpre',40)),
      colpre41 = extractValue(xml_input, concat(xpath,'/@colpre',41)),
      colpre42 = extractValue(xml_input, concat(xpath,'/@colpre',42)),
      colpre43 = extractValue(xml_input, concat(xpath,'/@colpre',43)),
      colpre44 = extractValue(xml_input, concat(xpath,'/@colpre',44)),
      colpre45 = extractValue(xml_input, concat(xpath,'/@colpre',45)),
      colpre46 = extractValue(xml_input, concat(xpath,'/@colpre',46)),
      colpre47 = extractValue(xml_input, concat(xpath,'/@colpre',47)),
      colpre48 = extractValue(xml_input, concat(xpath,'/@colpre',48)),
      colpre49 = extractValue(xml_input, concat(xpath,'/@colpre',49))
    WHERE registro = insertado;

/*
      colnro1 = extractValue(xml_input, concat(xpath,'/@colnro1'))
--      colpre1 = extractValue(xml_input, concat(xpath,'/@colpre',1))
   END
   ELSE
    UPDATE CAPPOUCLA_sgcanopr SET
     concat('colnro',k) = extractValue(xml_input, concat(xpath,'/@colnro',k),
     concat('colpre',k) = extractValue(xml_input, concat(xpath,'/@colpre',k),
    WHERE registro = insertado;
  END WHILE;
*/
END $$

DELIMITER ;




/*
ejemplo llamado mysql
call sp_trx_amortizacion('<?xml version="1.0" encoding="UTF-8"?>
<batch>
  <order codigo="02208" cedula="V-00.673.822" ip="192.168.0.29" fecha="2017-04-14">
  <items>
    <trx
      nropre="02208022"
      saldo="5325.26"
      capital="500.00"
      interes="10.24"
      cuota="500.00"
      codpre="004"
      cuent_p="1-01-02-01-08-02-2208"
      cuent_i="2-01-01-02-01-01-2208"
      cuent_d="NO TIENE"
      nrocuota="3"
      tipo="Comercial"
      pos310="195078"
      semanal="1"
      diferido="1" >
    </trx>
    <trx
      nropre="12208022"
      saldo="15325.26"
      capital="500.00"
      interes="10.24"
      cuota="500.00"
      codpre="004"
      cuent_p="1-01-02-01-08-02-2208"
      cuent_i="2-01-01-02-01-01-2208"
      cuent_d="NO TIENE"
      nrocuota="3"
      tipo="Comercial"
      pos310="195078"
      semanal="1"
      diferido="1" >
    </trx>
  </items>
  <prestamos>
  <trxp
    colnro1="" colpre1="1"
    colnro2="" colpre2="2"
    colnro3="" colpre3="3"
    colnro4="02208022" colpre4="500"
    colnro5="" colpre5="5"
    colnro6="" colpre6="6"
    colnro7="" colpre7="7"
    colnro8="" colpre8="8"
    colnro9="" colpre9="9"
    colnro10="" colpre10="10"
    colnro11="" colpre11="11"
    colnro12="" colpre12="12"
    colnro13="" colpre13="13"
    colnro14="" colpre14="14"
    colnro15="" colpre15="15"
    colnro16="" colpre16="16"
    colnro17="" colpre17="17"
    colnro18="" colpre18="18"
    colnro19="" colpre19="19"
    colnro20="" colpre20="20"
    colnro21="" colpre21="210"
    colnro22="" colpre22="202"
    colnro23="" colpre23="203"
    colnro24="" colpre24="04"
    colnro25="" colpre25="05"
    colnro26="" colpre26="06"
    colnro27="" colpre27="07"
    colnro28="" colpre28="08"
    colnro29="" colpre29="09"
    colnro30="" colpre30="30"
    colnro31="" colpre31="01"
    colnro32="" colpre32="02"
    colnro33="" colpre33="03"
    colnro34="" colpre34="04"
    colnro35="" colpre35="05"
    colnro36="" colpre36="06"
    colnro37="" colpre37="07"
    colnro38="" colpre38="08"
    colnro39="" colpre39="09"
    colnro40="02208021" colpre40="449.25"
    colnro41="" colpre41="01"
    colnro42="" colpre42="02"
    colnro43="" colpre43="03"
    colnro44="" colpre44="04"
    colnro45="" colpre45="05"
    colnro46="" colpre46="06"
   colnro47="" colpre47="07"
   colnro48="" colpre48="08"
   colnro49="" colpre49="09"
    >
  </trxp>
  </prestamos>
  </order>
</batch>
')
*/
