DROP TYPE PP_LANG CASCADE;
DROP TYPE PP_ACL_RULE_TYPE CASCADE;
DROP TYPE PP_USER_STATUS CASCADE;

DROP TYPE PP_BEER_MALT CASCADE;
DROP TYPE PP_BEER_TYPE CASCADE;
DROP TYPE PP_BEER_FILTERED CASCADE;
DROP TYPE PP_BEER_PASTEURIZED CASCADE;
DROP TYPE PP_BEER_FLAVORED CASCADE;
DROP TYPE PP_BEER_PLACEOFBREW CASCADE;
DROP TYPE PP_BEER_STATUS;
DROP TYPE PP_BEER_IMAGE_STATUS;
DROP TYPE PP_BEER_MANUFACTURER_IMAGE_STATUS;

DROP TYPE PP_NEWSLETTER;

DROP TYPE PP_ACL_RULE_INFO;

CREATE TYPE PP_LANG AS ENUM ('EN', 'PL');
CREATE TYPE PP_ACL_RULE_TYPE AS ENUM ('ALLOWED', 'DENIED');
CREATE TYPE PP_USER_STATUS AS ENUM ('INACTIVE', 'ACTIVE', 'BANNED');

CREATE TYPE PP_BEER_MALT AS ENUM ('JECZMIENNY', 'PSZENNY', 'INNY');
CREATE TYPE PP_BEER_TYPE AS ENUM ('BEZALKOHOLOWE', 'LEKKIE', 'PELNE', 'MOCNE');
CREATE TYPE PP_BEER_FILTERED AS ENUM ('NIEWIEM', 'NIE', 'TAK');
CREATE TYPE PP_BEER_PASTEURIZED AS ENUM ('NIEWIEM', 'NIE', 'TAK');
CREATE TYPE PP_BEER_FLAVORED AS ENUM ('NIEWIEM', 'NIE', 'TAK');
CREATE TYPE PP_BEER_PLACEOFBREW AS ENUM ('BROWAR', 'RESTAURACJA', 'DOM');
CREATE TYPE PP_BEER_STATUS AS ENUM ('AKTYWNY', 'NIEAKTYWNY', 'DO_ZATWIERDZENIA', 'ZAWIESZONY', 'USUNIETY');
CREATE TYPE PP_BEER_IMAGE_STATUS AS ENUM ('WIDOCZNY', 'NIEWIDOCZNY');
CREATE TYPE PP_BEER_MANUFACTURER_IMAGE_STATUS AS ENUM ('WIDOCZNY', 'NIEWIDOCZNY');

CREATE TYPE PP_NEWSLETTER_STATUS AS ENUM ('AKTYWNY', 'NIEAKTYWNY', 'USUNIETY');

CREATE TYPE PP_ACL_RULE_INFO AS (
	rule_id INTEGER,
	action PP_ACL_RULE_TYPE
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "acl_resources" (
  "resk_1_id" SERIAL PRIMARY KEY,
  "res_name" VARCHAR(50) NOT NULL
);

CREATE UNIQUE INDEX "un_acl_resources_res_name" ON "acl_resources" ("res_name");

INSERT INTO "acl_resources" ("resk_1_id", "res_name") VALUES
(1,'__index'),
(2,'admin__account'),
(3,'admin__beer'),
(4,'admin__beerdistributor'),
(5,'admin__beerfamily'),
(6,'admin__beerimage'),
(7,'admin__beermanufacturer'),
(8,'admin__blocker'),
(9,'admin__index'),
(10,'admin__page'),
(11,'admin__settings'),
(12,'admin__users'),
(13,'admin__currency'),
(14,'admin__currencyexchange'),
(15,'rest_beer_info'),
(16,'rest_beer_list'),
(17,'rest__error'),
(18,'rest_beer_images'),
(19,'rest_beer_lastadded'),
(20,'rest_beer_search'),
(21,'rest_beer_topranking'),
(22,'rest_beer_translation'),
(23,'rest_beerdistributor_list'),
(24,'rest_beerfamily_list'),
(25,'rest_beerfamily_beers'),
(26,'rest_beermanufacturer_beers'),
(27,'rest_beermanufacturer_info'),
(28,'rest_beermanufacturer_list'),
(29,'rest_beermanufacturer_translation'),
(30,'rest_city_beers'),
(31,'rest_city_list'),
(32,'rest_country_beers'),
(33,'rest_country_list'),
(34,'rest_region_beers'),
(35,'rest_region_list'),
(36,'rest_beerimage_info'),
(37,'rest_beerimage_prev'),
(38,'rest_beerimage_next'),
(39, 'admin__beermanufacturerimage');

ALTER SEQUENCE acl_resources_resk_1_id_seq RESTART WITH 39;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "acl_privileges" (
  "privk_1_id" SERIAL PRIMARY KEY,
  "priv_name" VARCHAR(30) NOT NULL,
  "res_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_acl_privileges_res_1_id" FOREIGN KEY ("res_1_id")
	REFERENCES "acl_resources" ("resk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_acl_privileges_priv_name" ON "acl_privileges" ("priv_name");

INSERT INTO acl_privileges (privk_1_id, priv_name, res_1_id) VALUES
(1, 'login', 9),
(2, 'logout', 9);  

ALTER SEQUENCE acl_privileges_privk_1_id_seq RESTART WITH 3;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "acl_resgroups" (
  "resgk_1_id" SERIAL PRIMARY KEY,
  "resg_name" VARCHAR(30) NOT NULL
);

CREATE UNIQUE INDEX "un_acl_resgroups_resg_name" ON "acl_resgroups" ("resg_name");

INSERT INTO "acl_resgroups" ("resgk_1_id", "resg_name") VALUES
(1, 'default'),
(2, 'admin'),
(3, 'rest');

ALTER SEQUENCE acl_resgroups_resgk_1_id_seq RESTART WITH 4;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "acl_resources_resgroups" (
  "rsgpk_1_id" SERIAL PRIMARY KEY,
  "res_1_id" INTEGER NOT NULL,
  "resg_2_id" INTEGER NOT NULL,
  CONSTRAINT "fk_acl_resources_resgroups_res_1_id" FOREIGN KEY ("res_1_id")
	REFERENCES "acl_resources" ("resk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_acl_resources_resgroups_resg_2_id" FOREIGN KEY ("resg_2_id")
	REFERENCES "acl_resgroups" ("resgk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO "acl_resources_resgroups" ("res_1_id", "resg_2_id") VALUES
(1, 1),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 3),
(16, 3),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(25, 3),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 3),
(35, 3),
(36, 3),
(37, 3),
(38, 3),
(39, 3);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "acl_roles" (
  "rolk_1_id" SERIAL PRIMARY KEY,
  "rol_name" VARCHAR(30) NOT NULL
);

CREATE UNIQUE INDEX "un_acl_roles_rol_name" ON "acl_roles" ("rol_name");

INSERT INTO "acl_roles" ("rolk_1_id", "rol_name") VALUES
(1, 'Guest'),
(2, 'User'),
(3, 'Piwosz'),
(4, 'Admin'),
(5, 'Tester'),
(6, 'Programmer');

ALTER SEQUENCE acl_roles_rolk_1_id_seq RESTART WITH 7;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "acl_role_parents" (
  "rolpk_1_id" SERIAL PRIMARY KEY,
  "rol_1_id" INTEGER NOT NULL,
  "rol_2_id_parent" INTEGER NOT NULL,
  CONSTRAINT "fk_acl_role_parents_rol_1_id" FOREIGN KEY ("rol_1_id")
	REFERENCES "acl_roles" ("rolk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_acl_role_parents_rol_2_id_parent" FOREIGN KEY ("rol_2_id_parent")
	REFERENCES "acl_roles" ("rolk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO "acl_role_parents" ("rol_1_id", "rol_2_id_parent") VALUES
(2, 1),
(3, 1),
(3, 2),
(4, 3),
(5, 3),
(6, 4),
(6, 5);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "acl_rules" (
  "rulk_1_id" SERIAL PRIMARY KEY,
  "rul_action" PP_ACL_RULE_TYPE NOT NULL,
  "rul_priority" INTEGER NOT NULL,
  "rol_1_id" INTEGER DEFAULT NULL,
  "res_2_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_acl_rules_rol_1_id" FOREIGN KEY ("rol_1_id")
	REFERENCES "acl_roles" ("rolk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_acl_rules_res_2_id" FOREIGN KEY ("res_2_id")
	REFERENCES "acl_resources" ("resk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_acl_rules_rul_priority" ON "acl_rules" ("rul_priority");

INSERT INTO acl_rules ("rulk_1_id", "rul_action", "rul_priority","rol_1_id", "res_2_id") VALUES
(1, 'ALLOWED', 1, 1, 1),  /* 1. GUEST -> __index */
(2, 'ALLOWED', 1, 3, 2),  /* 2. PIWOSZ -> admin__account */
(3, 'ALLOWED', 1, 3, 3),  /* 3. PIWOSZ -> admin__beer */
(4, 'ALLOWED', 1, 3, 4),  /* 4. PIWOSZ -> admin__beerdistributor */
(5, 'ALLOWED', 1, 4, 5),  /* 5. ADMIN -> admin__beerfamily */
(6, 'ALLOWED', 1, 5, 5),  /* 6. TESTER -> admin__beerfamily */
(7, 'ALLOWED', 1, 3, 6),  /* 7. PIWOSZ -> admin__beerimage */
(8, 'ALLOWED', 1, 3, 7),  /* 8. PIWOSZ -> admin__beermanufacturer */
(9, 'ALLOWED', 1, 4, 8),  /* 9. ADMIN -> admin__blocker */
(10, 'ALLOWED', 1, 5, 8),  /* 10.TESTER -> admin__blocker */
(11, 'ALLOWED', 1, 1, 9),  /* 11.GUEST -> admin__index -> login/logout */
(12, 'ALLOWED', 1, 3, 9),  /* 12.PIWOSZ -> admin__index */
(13, 'ALLOWED', 1, 4, 10), /* 13.ADMIN -> admin__page */
(14, 'ALLOWED', 1, 5, 10), /* 14.TESTER -> admin__page */
(15, 'ALLOWED', 1, 4, 11), /* 15.ADMIN -> admin__settings */
(16, 'ALLOWED', 1, 5, 11), /* 16.TESTER -> admin__settings */
(17, 'ALLOWED', 1, 4, 12), /* 17.ADMIN -> admin__users */
(18, 'ALLOWED', 1, 5, 12), /* 18.TESTER -> admin__users */
(19, 'DENIED', 2, 3, 9),  /* 19.PIWOSZ -> admin__index */
(20, 'ALLOWED', 1, 4, 13), /* 20.ADMIN -> admin__currency */
(21, 'ALLOWED', 1, 4, 14), /* 21.ADMIN -> admin__currency */
(22, 'ALLOWED', 1, 1, 15), /* 22.GUEST -> rest_beer_info */
(23, 'ALLOWED', 1, 1, 16), /* 23.GUEST -> rest_beer_list */
(24, 'ALLOWED', 1, 1, 17), /* 24.GUEST -> rest__error */
(25, 'ALLOWED', 1, 1, 18), /* 25.GUEST -> rest_beer_images */
(26, 'ALLOWED', 1, 1, 19), /* 26.GUEST -> rest_beer_lastadded */
(27, 'ALLOWED', 1, 1, 20), /* 27.GUEST -> rest_beer_search */
(28, 'ALLOWED', 1, 1, 21), /* 28.GUEST -> rest_beer_topranking */
(29, 'ALLOWED', 1, 1, 22), /* 29.GUEST -> rest_beer_translation */
(30, 'ALLOWED', 1, 1, 23), /* 30.GUEST -> rest_beerdistributor_list */
(31, 'ALLOWED', 1, 1, 24), /* 31.GUEST -> rest_beerfamily_list */
(32, 'ALLOWED', 1, 1, 25), /* 32.GUEST -> rest_beerfamily_beers */
(33, 'ALLOWED', 1, 1, 26), /* 33.GUEST -> rest_beermanufacturer_beers */
(34, 'ALLOWED', 1, 1, 27), /* 34.GUEST -> rest_beermanufacturer_info */
(35, 'ALLOWED', 1, 1, 28), /* 35.GUEST -> rest_beermanufacturer_list */
(36, 'ALLOWED', 1, 1, 29), /* 36.GUEST -> rest_beermanufacturer_translation */
(37, 'ALLOWED', 1, 1, 30), /* 37.GUEST -> rest_city_beers */
(38, 'ALLOWED', 1, 1, 31), /* 38.GUEST -> rest_city_list */
(39, 'ALLOWED', 1, 1, 32), /* 39.GUEST -> rest_country_beers */
(40, 'ALLOWED', 1, 1, 33), /* 40.GUEST -> rest_country_list */
(41, 'ALLOWED', 1, 1, 34), /* 41.GUEST -> rest_region_beers */
(42, 'ALLOWED', 1, 1, 35), /* 42.GUEST -> rest_region_list */
(43, 'ALLOWED', 1, 1, 36), /* 43.GUEST -> rest_beerimage_info */
(44, 'ALLOWED', 1, 1, 37), /* 44.GUEST -> rest_beerimage_prev */
(45, 'ALLOWED', 1, 1, 38), /* 45.GUEST -> rest_beerimage_next */
(46, 'ALLOWED', 1, 1, 39); /* 45.GUEST -> admin__beermanufacturerimage */
/*
1.  __index
2.  admin__account
3.  admin__beer
4.  admin__beerdistributor
5.  admin__beerfamily
6.  admin__beerimage
7.  admin__beermanufacturer
8.  admin__blocker
9.  admin__index
10. admin__page
11. admin__settings
12. admin__users
13. admin__currency
14. admin__currencyexchange
15. rest_beer_info
16. rest_beer_list
17. rest__error
18. rest_beer_images
19. rest_beer_lastadded
20. rest_beer_search
21. rest_beer_topranking
22. rest_beer_translation
23. rest_beerdistributor_list
24. rest_beerfamily_list
25. rest_beerfamily_beers
26. rest_beermanufacturer_beers
27. rest_beermanufacturer_info
28. rest_beermanufacturer_list
29. rest_beermanufacturer_translation
30. rest_city_beers
31. rest_city_list
32. rest_country_beers
33. rest_country_list
34. rest_region_beers
35. rest_region_list
36. rest_beerimage_info
37. rest_beerimage_prev
38. rest_beerimage_next
39. admin__beermanufacturerimage
*/

ALTER SEQUENCE acl_rules_rulk_1_id_seq RESTART WITH 46;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "acl_rules_privileges" (
  "rulpk_1_id" SERIAL PRIMARY KEY,
  "rul_1_id" INTEGER NOT NULL,
  "priv_2_id" INTEGER NOT NULL,
  CONSTRAINT "fk_acl_rules_privileges_rul_1_id" FOREIGN KEY ("rul_1_id")
	REFERENCES "acl_rules" ("rulk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_acl_rules_privileges_priv_2_id" FOREIGN KEY ("priv_2_id")
	REFERENCES "acl_privileges" ("privk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO acl_rules_privileges (rul_1_id, priv_2_id) VALUES
(11, 1),
(11, 2),
(19, 1);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "countries" (
  "couk_1_id" SERIAL PRIMARY KEY,
  "cou_name" VARCHAR(50) NOT NULL,
  "cou_urlname" VARCHAR(50) DEFAULT NULL,
  "cou_iso2code" VARCHAR(2) NOT NULL,
  "cou_iso3code" VARCHAR(3) NOT NULL
);

CREATE INDEX "in_countries_cou_name" ON "countries" ("cou_name");
CREATE INDEX "in_countries_cou_iso2code" ON "countries" ("cou_iso2code");
CREATE INDEX "in_countries_cou_iso3code" ON "countries" ("cou_iso3code");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "regions" (
  "regk_1_id" SERIAL PRIMARY KEY,
  "reg_name" VARCHAR(100) NOT NULL,
  "reg_urlname" VARCHAR(100) DEFAULT NULL,
  "reg_fipscode" VARCHAR(2) NOT NULL,
  "cou_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_regions_cou_1_id" FOREIGN KEY ("cou_1_id")
	REFERENCES "countries" ("couk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_regions_reg_name" ON "regions" ("reg_name");
CREATE INDEX "in_regions_reg_fipscode" ON "regions" ("reg_fipscode");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "cities" (
  "citk_1_id" SERIAL PRIMARY KEY,
  "cit_name" VARCHAR(100) DEFAULT NULL,
  "cit_urlname" VARCHAR(100) DEFAULT NULL,
  "cit_accentcityname" VARCHAR(100) DEFAULT NULL,
  "cit_latitude" DECIMAL(8,5) DEFAULT NULL,
  "cit_longitude" DECIMAL(8,5) DEFAULT NULL,
  "cit_population" INTEGER DEFAULT NULL,
  "reg_1_id" INTEGER NOT NULL,
  "cou_2_id" INTEGER NOT NULL,
  CONSTRAINT "fk_cities_reg_1_id" FOREIGN KEY ("reg_1_id")
	REFERENCES "regions" ("regk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_cities_cou_2_id" FOREIGN KEY ("cou_2_id")
	REFERENCES "countries" ("couk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_cities_cit_name" ON "cities" ("cit_name");
CREATE INDEX "in_cities_cit_accentcityname" ON "cities" ("cit_accentcityname");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_distributors" (
    "beedk_1_id" SERIAL PRIMARY KEY,
    "beed_name" VARCHAR(50) NOT NULL,
    "beed_urlname" VARCHAR(50) NOT NULL
);

CREATE UNIQUE INDEX "un_beer_distributors_beed_name" ON "beer_distributors" ("beed_name");

INSERT INTO "beer_distributors" VALUES
(1, 'Barlan Beverages', 'barlan+beverages'),
(2, 'Grupa Żywiec', 'grupa+zywiec'),
(3, 'Haus Cramer KG', 'haus+cramer+kg'),
(4, 'Kompania Piwowarska', 'kompania+piwowarska'),
(5, 'Kraina Piwa', 'kraina+piwa'),
(6, 'Pilsweiser S.A.', 'pilsweiser+s.a'),
(7, 'SA Damm', 'sa+damm'),
(8, 'SaqartveloSi', 'saqartvelosi'),
(9, 'UDH s.c.', 'udh+s.c.'),
(10,'VAN PUR S.A.', 'van+pur+s.a');

ALTER SEQUENCE beer_distributors_beedk_1_id_seq RESTART WITH 10;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_manufacturers" (
  "beemk_1_id" SERIAL PRIMARY KEY,
  "beem_name" VARCHAR(50) NOT NULL,
  "beem_urlname" VARCHAR(50) NOT NULL,
  "beem_email" VARCHAR(50) DEFAULT NULL,
  "beem_website" VARCHAR(100) DEFAULT NULL,
  "beem_address" VARCHAR(100) DEFAULT NULL,
  "beem_latitude" DECIMAL(8,5) DEFAULT NULL,
  "beem_longitude" DECIMAL(8,5) DEFAULT NULL,
  "beed_1_id" INTEGER NOT NULL,
  "cit_2_id" INTEGER DEFAULT NULL,
  "reg_3_id" INTEGER DEFAULT NULL,
  "cou_4_id" INTEGER DEFAULT NULL,
  "bemi_5_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_beer_manufacturers_beed_1_id" FOREIGN KEY ("beed_1_id")
	REFERENCES "beer_distributors" ("beedk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_manufacturers_cit_2_id" FOREIGN KEY ("cit_2_id")
	REFERENCES "cities" ("citk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_manufacturers_reg_3_id" FOREIGN KEY ("reg_3_id")
	REFERENCES "regions" ("regk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_manufacturers_cou_4_id" FOREIGN KEY ("cou_4_id")
	REFERENCES "countries" ("couk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE
);

CREATE UNIQUE INDEX "un_beer_manufacturers_beem_name__beed_1_id" ON "beer_manufacturers" ("beem_name", "beed_1_id");

INSERT INTO "beer_manufacturers" ("beemk_1_id", "beem_name", "beem_urlname", "beed_1_id") VALUES
(1, 'Browar Prazdroj', 'browar+prazdroj', 1),
(2, 'Browar Żywiec', 'browar+zywiec', 2),
(3, 'Browar Tyskie', 'browar+tyskie', 1),
(4, 'Browar Pinta', 'browar+pinta', 3),
(5, 'Browar VAN PUR S.A. Koszalin', 'browar+van+pur+s.a+koszalin', 5),
(6, 'Bavaria Brewery', 'bavaria+brewery', 6),
(7, 'Browar WARSTEINER', 'browar+warsteiner', 7),
(8, 'Browar VAN PUR S.A. Rakszawa', 'browar+van+pur+s.a+rakszawa', 5),
(9, 'Beck GmbH', 'beck+gmbh', 9),
(10, 'Lvivska Brewery', 'lvivska+brewery', 10),
(11, 'Pilsweiser', 'pilsweiser', 10),
(12, 'SA Damm', 'sa+damm', 4);

ALTER SEQUENCE beer_manufacturers_beemk_1_id_seq RESTART WITH 13;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_manufacturer_images" (
  "bemik_1_id" SERIAL PRIMARY KEY,
  "bemi_title" VARCHAR(200) DEFAULT NULL,
  "bemi_path" VARCHAR(50) NOT NULL,
  "bemi_position" INTEGER DEFAULT 1,
  "bemi_dateadded" TIMESTAMP NOT NULL,
  "bemi_status" PP_BEER_MANUFACTURER_IMAGE_STATUS DEFAULT NULL,
  "beem_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_beer_manufacturer_images_beem_1_id" FOREIGN KEY ("beem_1_id")
	REFERENCES "beer_manufacturers" ("beemk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

ALTER TABLE beer_manufacturers ADD CONSTRAINT fk_beer_manufacturers_bemi_5_id 
    FOREIGN KEY (bemi_5_id) REFERENCES beer_manufacturer_images(bemik_1_id);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_manufacturer_translations" (
  "bemtk_1_id" SERIAL PRIMARY KEY,
  "bemt_description" TEXT NOT NULL,
  "bemt_lang" PP_LANG NOT NULL,
  "beem_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_beer_translations_beem_1_id" FOREIGN KEY ("beem_1_id")
	REFERENCES "beer_manufacturers" ("beemk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_beer_manufacturer_translations_bemt_lang" ON "beer_manufacturer_translations" ("bemt_lang");

INSERT INTO "beer_manufacturer_translations" ("bemt_description", "bemt_lang", "beem_1_id") VALUES
('', 'PL', 1),
('', 'EN', 1),
('', 'PL', 2),
('', 'EN', 2),
('', 'PL', 3),
('', 'EN', 3),
('', 'PL', 4),
('', 'EN', 4),
('', 'PL', 5),
('', 'EN', 5),
('', 'PL', 6),
('', 'EN', 6),
('', 'PL', 7),
('', 'EN', 7),
('', 'PL', 8),
('', 'EN', 8),
('', 'PL', 9),
('', 'EN', 9),
('', 'PL', 10),
('', 'EN', 10),
('', 'PL', 11),
('', 'EN', 11),
('', 'PL', 12),
('', 'EN', 12);
/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_families" (
  "beefk_1_id" SERIAL PRIMARY KEY,
  "beef_name" VARCHAR(50) NOT NULL,
  "beef_urlname" VARCHAR(50) NOT NULL
);

CREATE INDEX "in_beer_families_beef_name" ON "beer_families" ("beef_name");

INSERT INTO "beer_families" VALUES
(1, 'Ales', 'ales'),
(2, 'American Lager', 'american+lager'),
(3, 'Cream Ale', 'cream+ale'),
(4, 'German Lager', 'german+lager'),
(5, 'Lagers', 'lagers'),
(6, 'Lambic', 'lambic'),
(7, 'Pale Ale', 'pale+ale');

ALTER SEQUENCE beer_families_beefk_1_id_seq RESTART WITH 8;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_family_parents" (
  "befpk_1_id" SERIAL PRIMARY KEY,
  "beef_1_id" INTEGER NOT NULL,
  "beef_2_id_parent" INTEGER NOT NULL,
  CONSTRAINT "fk_beer_family_parents_beef_1_id" FOREIGN KEY ("beef_1_id")
	REFERENCES "beer_families" ("beefk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_family_parents_beef_2_id_parent" FOREIGN KEY ("beef_2_id_parent")
	REFERENCES "beer_families" ("beefk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO "beer_family_parents" ("beef_1_id", "beef_2_id_parent") VALUES
(3, 1),
(4, 1),
(5, 1),
(5, 2),
(6, 2);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beers" (
  "beek_1_id" SERIAL PRIMARY KEY,
  "bee_name" VARCHAR(50) NOT NULL,
  "bee_urlname" VARCHAR(50) NOT NULL,
  "bee_alcohol" DECIMAL(4,2) DEFAULT NULL,
  "bee_extract" DECIMAL(4,2) DEFAULT NULL,
  "bee_malt" PP_BEER_MALT DEFAULT NULL, 
  "bee_type" PP_BEER_TYPE DEFAULT NULL,
  "bee_filtered" PP_BEER_FILTERED NOT NULL,
  "bee_pasteurized" PP_BEER_PASTEURIZED NOT NULL,
  "bee_flavored" PP_BEER_FLAVORED NOT NULL,
  "bee_placeofbrew" PP_BEER_PLACEOFBREW DEFAULT NULL,
  "bee_rankingtotal" DECIMAL(12,1) DEFAULT NULL,
  "bee_rankingcounter" INTEGER DEFAULT NULL,
  "bee_rankingavg" DECIMAL(4,3) DEFAULT NULL,
  "bee_rankingweightedavg" DECIMAL(4,3) DEFAULT NULL,
  "bee_dateadded" TIMESTAMP NOT NULL,
  "bee_status" PP_BEER_STATUS NOT NULL,
  "beef_1_id" INTEGER DEFAULT NULL,
  "beed_2_id" INTEGER DEFAULT NULL,
  "beem_3_id" INTEGER DEFAULT NULL,
  "cou_4_id" INTEGER DEFAULT NULL,
  "reg_5_id" INTEGER DEFAULT NULL,
  "cit_6_id" INTEGER DEFAULT NULL,
  "beei_7_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_beers_beef_1_id" FOREIGN KEY ("beef_1_id")
	REFERENCES "beer_families" ("beefk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beers_beed_2_id" FOREIGN KEY ("beed_2_id")
	REFERENCES "beer_distributors" ("beedk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beers_beem_3_id" FOREIGN KEY ("beem_3_id")
	REFERENCES "beer_manufacturers" ("beemk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beers_cou_4_id" FOREIGN KEY ("cou_4_id")
	REFERENCES "countries" ("couk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beers_reg_5_id" FOREIGN KEY ("reg_5_id")
	REFERENCES "regions" ("regk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beers_cit_6_id" FOREIGN KEY ("cit_6_id")
	REFERENCES "cities" ("citk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE
);

CREATE UNIQUE INDEX "un_beers_bee_name" ON "beers" ("bee_name");
CREATE INDEX "un_beers_bee_rankingavg" ON "beers" ("bee_rankingavg");


INSERT INTO beers VALUES (4, 'Piwo 4', 'piwo+4', NULL, NULL, NULL, NULL, 'NIEWIEM', 'NIEWIEM', 'NIEWIEM', NULL, 0.0, 0, NULL, NULL, '2013-01-20 20:30:17', 'AKTYWNY', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO beers VALUES (5, 'Piwo 5', 'piwo+5', NULL, NULL, NULL, NULL, 'NIEWIEM', 'NIEWIEM', 'NIEWIEM', NULL, 0.0, 0, 0.00, NULL, '2013-01-20 20:30:36', 'AKTYWNY', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO beers VALUES (3, 'Piwo 3', 'piwo+3', NULL, NULL, NULL, NULL, 'NIEWIEM', 'NIEWIEM', 'NIEWIEM', NULL, 0.0, 0, 0.00, NULL, '2013-01-20 20:29:33', 'AKTYWNY', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO beers VALUES (6, 'Piwo 6', 'piwo+6', NULL, NULL, NULL, NULL, 'NIEWIEM', 'NIEWIEM', 'NIEWIEM', NULL, 0.0, 0, NULL, NULL, '2013-01-23 22:18:10', 'AKTYWNY', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO beers VALUES (2, 'Piwo 2', 'piwo+2', NULL, NULL, NULL, NULL, 'NIEWIEM', 'NIEWIEM', 'NIEWIEM', NULL, 0.0, 0, 0.00, NULL, '2013-01-20 20:29:01', 'AKTYWNY', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO beers VALUES (1, 'Piwo 1', 'piwo+1', NULL, NULL, NULL, NULL, 'NIEWIEM', 'NIEWIEM', 'NIEWIEM', NULL, 0.0, 0, 0.00, NULL, '2013-01-20 20:28:41', 'AKTYWNY', NULL, NULL, NULL, NULL, NULL, NULL);

ALTER SEQUENCE beers_beek_1_id_seq RESTART WITH 7;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "users" (
  "usrk_1_id" SERIAL PRIMARY KEY,
  "usr_username" VARCHAR(50) NOT NULL,
  "usr_email" VARCHAR(50) NOT NULL,
  "usr_password" VARCHAR(64) NOT NULL,
  "usr_visiblename" VARCHAR(50) NOT NULL,
  "usr_salt" VARCHAR(10) NOT NULL,
  "usr_status" PP_USER_STATUS NOT NULL,
  "rol_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_users_rol_1_id" FOREIGN KEY ("rol_1_id")
	REFERENCES "acl_roles" ("rolk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE UNIQUE INDEX "un_users_usr_visiblename" ON "users" ("usr_visiblename");
CREATE UNIQUE INDEX "un_users_usr_username" ON "users" ("usr_username");
CREATE UNIQUE INDEX "un_users_usr_email" ON "users" ("usr_email");
CREATE INDEX "in_users_usr_status" ON "users" ("usr_status");

INSERT INTO "users" ("usr_username", "usr_email", "usr_password", "usr_visiblename", "usr_salt", "usr_status", "rol_1_id") VALUES
('kormichu', 'mdeallas@gmail.com', 'e7612b94763b3d5660f1e918d704639249a270581083ab7e825409a015d1af46', 'kormichu', '6&ha8kM4qP', 'ACTIVE', 6),
('mateusz', 'mateusz.palka@gmail.com', 'bd791bef3142d7950ab67d0c350a270e34059cee3c7c19cef0a12367137cbd8d', 'mateusz', 'Hz8rYSbwQp', 'INACTIVE', 4),
('pawel', 'pawel.kowalski@gmail.com', 'f8550e5fc6f4f0288f443026c6d46748cbc36665bfb69ed11f2afd81c897e4d9', 'pawel', 'USR5J3jm5T', 'BANNED', 4);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_searches" (
    "beesk_1_id" SERIAL PRIMARY KEY,
    "bees_uid" VARCHAR(32) NOT NULL,
    "bees_query" VARCHAR(50),
    "bees_rankingavg_min" DECIMAL(4,3) DEFAULT NULL,
    "bees_rankingavg_max" DECIMAL(4,3) DEFAULT NULL,
    "bees_alcohol_min" DECIMAL(4,2) DEFAULT NULL,
    "bees_alcohol_max" DECIMAL(4,2) DEFAULT NULL,
    "bees_extract_min" DECIMAL(4,2) DEFAULT NULL,
    "bees_extract_max" DECIMAL(4,2) DEFAULT NULL,
    "bees_malt" PP_BEER_MALT DEFAULT NULL, 
    "bees_type" PP_BEER_TYPE DEFAULT NULL,
    "bees_filtered" PP_BEER_FILTERED NOT NULL,
    "bees_pasteurized" PP_BEER_PASTEURIZED NOT NULL,
    "bees_flavored" PP_BEER_FLAVORED NOT NULL,
    "bees_placeofbrew" PP_BEER_PLACEOFBREW DEFAULT NULL,
    "bees_dateadded" TIMESTAMP NOT NULL,
    "usr_1_id" INTEGER DEFAULT NULL,
    "beef_2_id" INTEGER DEFAULT NULL,
    "beed_3_id" INTEGER DEFAULT NULL,
    "beem_4_id" INTEGER DEFAULT NULL,
    "cou_5_id" INTEGER DEFAULT NULL,
    "reg_6_id" INTEGER DEFAULT NULL,
    "cit_7_id" INTEGER DEFAULT NULL,
    CONSTRAINT "fk_beer_searches_usr_1_id" FOREIGN KEY ("usr_1_id")
        REFERENCES "users" ("usrk_1_id")
        ON DELETE SET NULL
        ON UPDATE SET NULL,
    CONSTRAINT "fk_beer_searches_beef_2_id" FOREIGN KEY ("beef_2_id")
          REFERENCES "beer_families" ("beefk_1_id")
          ON DELETE SET NULL
          ON UPDATE CASCADE,
    CONSTRAINT "fk_beer_searches_beed_3_id" FOREIGN KEY ("beed_3_id")
          REFERENCES "beer_distributors" ("beedk_1_id")
          ON DELETE SET NULL
          ON UPDATE CASCADE,
    CONSTRAINT "fk_beer_searches_beem_4_id" FOREIGN KEY ("beem_4_id")
          REFERENCES "beer_manufacturers" ("beemk_1_id")
          ON DELETE SET NULL
          ON UPDATE CASCADE,
    CONSTRAINT "fk_beer_searches_cou_5_id" FOREIGN KEY ("cou_5_id")
          REFERENCES "countries" ("couk_1_id")
          ON DELETE SET NULL
          ON UPDATE CASCADE,
    CONSTRAINT "fk_beer_searches_reg_6_id" FOREIGN KEY ("reg_6_id")
          REFERENCES "regions" ("regk_1_id")
          ON DELETE SET NULL
          ON UPDATE CASCADE,
    CONSTRAINT "fk_beer_searches_cit_7_id" FOREIGN KEY ("cit_7_id")
          REFERENCES "cities" ("citk_1_id")
          ON DELETE SET NULL
          ON UPDATE CASCADE
);

CREATE UNIQUE INDEX "un_beer_searches_bees_uid" ON "beer_searches" ("bees_uid");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_search_connections" (
    "besck_1_id" SERIAL PRIMARY KEY,
    "bees_1_id" INTEGER NOT NULL,
    "bee_2_id" INTEGER NOT NULL,
    CONSTRAINT "fk_beer_search_connections_bees_1_id" FOREIGN KEY ("bees_1_id")
        REFERENCES "beer_searches" ("beesk_1_id")
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT "fk_beer_search_connections_bee_2_id" FOREIGN KEY ("bee_2_id")
        REFERENCES "beers" ("beek_1_id")
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_comments" (
  "beeck_1_id" SERIAL PRIMARY KEY,
  "beec_value" TEXT NOT NULL,
  "beec_useragent" VARCHAR(200) NOT NULL,
  "beec_ip" VARCHAR(39) NOT NULL,
  "beec_dateadded" TIMESTAMP NOT NULL,
  "beec_status" SMALLINT DEFAULT NULL,
  "beec_lang" PP_LANG DEFAULT NULL,
  "usr_1_id" INTEGER DEFAULT NULL,
  "bee_2_id" INTEGER NOT NULL,
  CONSTRAINT "fk_beer_comments_usr_1_id" FOREIGN KEY ("usr_1_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_comments_bee_2_id" FOREIGN KEY ("bee_2_id")
	REFERENCES "beers" ("beek_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_beer_comments_beec_lang" ON "beer_comments" ("beec_lang");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_favorites" (
  "befvk_1_id" SERIAL PRIMARY KEY,
  "bee_1_id" INTEGER NOT NULL,
  "usr_2_id" INTEGER NOT NULL,
  CONSTRAINT "fk_beer_favorites_bee_1_id" FOREIGN KEY ("bee_1_id")
	REFERENCES "beers" ("beek_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_favorites_usr_2_id" FOREIGN KEY ("usr_2_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_images" (
  "beeik_1_id" SERIAL PRIMARY KEY,
  "beei_title" VARCHAR(200) DEFAULT NULL,
  "beei_path" VARCHAR(50) NOT NULL,
  "beei_position" INTEGER DEFAULT 1,
  "beei_dateadded" TIMESTAMP NOT NULL,
  "beei_status" PP_BEER_IMAGE_STATUS DEFAULT NULL,
  "bee_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_beer_images_bee_1_id" FOREIGN KEY ("bee_1_id")
	REFERENCES "beers" ("beek_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

ALTER TABLE beers ADD CONSTRAINT fk_beers_beei_7_id 
    FOREIGN KEY (beei_7_id) REFERENCES beer_images(beeik_1_id);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "currencies" (
  "curk_1_id" SERIAL PRIMARY KEY,
  "cur_name" VARCHAR(50) NOT NULL,
  "cur_shortcut" VARCHAR(3) NOT NULL,
  "cur_symbol" VARCHAR(3) NOT NULL
);

CREATE INDEX "in_currencies_cur_name" ON "currencies" ("cur_name");

INSERT INTO "currencies" ("curk_1_id", "cur_name", "cur_shortcut", "cur_symbol") VALUES
(1, 'Złoty', 'PLN', 'zł'),
(2, 'Euro', 'EUR', '€'),
(3, 'Dolar', 'USD', '$'),
(4, 'Pound Sterling', 'GBP', '£');

ALTER SEQUENCE currencies_curk_1_id_seq RESTART WITH 5;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "currency_exchanges" (
  "curek_1_id" SERIAL PRIMARY KEY,
  "cure_multiplier" DECIMAL(8,4) DEFAULT NULL,
  "cure_lastupdated" TIMESTAMP DEFAULT NULL,
  "cur_1_id" INTEGER NOT NULL,
  "cur_2_id" INTEGER NOT NULL,
  CONSTRAINT "fk_currency_exchanges_cur_1_id" FOREIGN KEY ("cur_1_id")
	REFERENCES "currencies" ("curk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_currency_exchanges_cur_2_id" FOREIGN KEY ("cur_2_id")
	REFERENCES "currencies" ("curk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO "currency_exchanges" ("cure_multiplier", "cure_lastupdated", "cur_1_id", "cur_2_id") VALUES
(4.1754, NULL, 1, 2),
(3.1427, NULL, 1, 3),
(4.9776, NULL, 1, 4),
(0.2395, NULL, 2, 1),
(0.7529, NULL, 2, 3),
(1.1926, NULL, 2, 4),
(0.3182, NULL, 3, 1),
(1.3282, NULL, 3, 2),
(1.5840, NULL, 3, 4),
(0.2009, NULL, 4, 1),
(0.8385, NULL, 4, 2),
(0.6313, NULL, 4, 3);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_prices" (
  "beepk_1_id" SERIAL PRIMARY KEY,
  "beep_sizeofbottle" INTEGER NOT NULL,
  "beep_value" DECIMAL(5,2) NOT NULL,
  "bee_1_id" INTEGER NOT NULL,
  "cur_2_id" INTEGER NOT NULL,
  CONSTRAINT "fk_beer_prices_bee_1_id" FOREIGN KEY ("bee_1_id")
	REFERENCES "beers" ("beek_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_prices_cur_2_id" FOREIGN KEY ("cur_2_id")
	REFERENCES "currencies" ("curk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO beer_prices VALUES (1, 250, 2.50, 6, 3);
INSERT INTO beer_prices VALUES (2, 500, 3.80, 6, 1);
INSERT INTO beer_prices VALUES (3, 500, 2.50, 2, 3);
INSERT INTO beer_prices VALUES (4, 1000, 5.20, 2, 1);
INSERT INTO beer_prices VALUES (5, 500, 5.10, 1, 1);
INSERT INTO beer_prices VALUES (6, 500, 2.00, 1, 3);

ALTER SEQUENCE beer_prices_beepk_1_id_seq RESTART WITH 7;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_rankings" (
  "beerk_1_id" SERIAL PRIMARY KEY,
  "beer_value" DECIMAL(2,1) NOT NULL,
  "bee_1_id" INTEGER NOT NULL,
  "usr_2_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_beer_rankings_bee_1_id" FOREIGN KEY ("bee_1_id")
	REFERENCES "beers" ("beek_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_rankings_usr_2_id" FOREIGN KEY ("usr_2_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_reviews" (
  "berek_1_id" SERIAL PRIMARY KEY,
  "bere_description" TEXT NOT NULL,
  "bere_foam" VARCHAR(200) DEFAULT NULL,
  "bere_flavor" VARCHAR(200) DEFAULT NULL,
  "bere_color" VARCHAR(30) DEFAULT NULL,
  "bere_status" SMALLINT NOT NULL,
  "bere_lang" PP_LANG NOT NULL,
  "bere_rankingtotal" INTEGER DEFAULT '0',
  "bee_1_id" INTEGER NOT NULL,
  "usr_2_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_beer_reviews_bee_1_id" FOREIGN KEY ("bee_1_id")
	REFERENCES "beers" ("beek_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_reviews_usr_2_id" FOREIGN KEY ("usr_2_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_review_rankings" (
  "brrak_1_id" SERIAL PRIMARY KEY,
  "bere_1_id" INTEGER NOT NULL,
  "usr_2_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_beer_review_rankings_bere_1_id" FOREIGN KEY ("bere_1_id")
	REFERENCES "beer_reviews" ("berek_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_beer_review_rankings_usr_2_id" FOREIGN KEY ("usr_2_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "beer_translations" (
  "beetk_1_id" SERIAL PRIMARY KEY,
  "beet_description" TEXT NOT NULL,
  "beet_lang" PP_LANG NOT NULL,
  "bee_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_beer_translations_bee_1_id" FOREIGN KEY ("bee_1_id")
	REFERENCES "beers" ("beek_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_beer_translations_beet_lang" ON "beer_translations" ("beet_lang");

INSERT INTO beer_translations VALUES (3, 'sdfgdsf', 'EN', 2);
INSERT INTO beer_translations VALUES (4, 'asdfasdf', 'PL', 2);
INSERT INTO beer_translations VALUES (5, 'wertwert', 'EN', 3);
INSERT INTO beer_translations VALUES (6, 'fghg', 'PL', 3);
INSERT INTO beer_translations VALUES (7, 'asdfsdf', 'EN', 4);
INSERT INTO beer_translations VALUES (8, 'dgfsdfg', 'PL', 4);
INSERT INTO beer_translations VALUES (9, 'sdfadf', 'EN', 5);
INSERT INTO beer_translations VALUES (10, 'dfgdfg', 'PL', 5);
INSERT INTO beer_translations VALUES (12, 'asdfa', 'PL', 6);
INSERT INTO beer_translations VALUES (1, 'Suspendisse eget sapien risus, id convallis lacus. Integer auctor nisl in orci rhoncus condimentum accumsan eros aliquet. Vestibulum blandit, nibh sed facilisis malesuada, turpis odio pulvinar nulla, a euismod metus erat id neque. Aliquam dui turpis, ultricies ut congue vel, lobortis nec sem. Fusce eleifend massa in massa aliquet dapibus. Etiam bibendum, mauris eget hendrerit blandit, nisi lacus ultrices mi, ut porta leo magna id sem. Cras ultrices sollicitudin odio, vel consectetur felis fringilla a. Pellentesque accumsan ornare neque ut eleifend. Ut elementum lorem tellus, non viverra mi. Nulla posuere pretium magna sed elementum. Pellentesque id quam at ante accumsan porttitor.

Nam vehicula tincidunt elit, ut elementum mauris dictum vel. Mauris mauris leo, sodales vitae posuere quis, egestas sed nunc. Mauris sodales cursus tortor, imperdiet consectetur nulla sagittis nec. Donec vehicula eleifend mi nec elementum. Pellentesque volutpat rutrum purus. Ut diam turpis, convallis et vehicula vitae, dapibus vitae leo. Nullam sit amet arcu eu massa sollicitudin viverra. Etiam condimentum pharetra metus, ac venenatis sem sagittis et. Curabitur nec tincidunt libero. Ut scelerisque tortor id elit aliquam pretium. Nam feugiat pulvinar nisi. Vivamus vitae est vel erat consectetur viverra.

Nunc tincidunt nisi vel enim iaculis pellentesque. Proin a magna risus. Fusce accumsan velit nec lacus semper scelerisque. Duis rutrum, nisl nec porta consequat, ipsum lacus aliquet justo, nec aliquet sapien nunc nec ligula. Praesent viverra nibh et sapien pharetra fringilla. Donec quis tristique nulla. Integer ac libero ligula, ut feugiat magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi iaculis ultricies mauris sed bibendum. Vivamus ac imperdiet odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;', 'EN', 1);
INSERT INTO beer_translations VALUES (2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sodales justo at purus varius congue ut non lectus. Praesent non mauris eget sem elementum laoreet ut at ligula. Nunc rhoncus egestas pharetra. Nulla aliquet sem in neque porttitor eget interdum arcu ullamcorper. Aliquam posuere aliquam leo sed facilisis. Sed pellentesque, neque ac accumsan iaculis, lacus sapien sollicitudin lacus, vel sagittis dolor eros non est. Vestibulum consequat hendrerit urna. Pellentesque ullamcorper pretium metus a iaculis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In faucibus fermentum nisl, ac bibendum erat scelerisque sit amet. Ut tristique rhoncus lectus, eget dignissim est iaculis eget.

Aliquam commodo tempus ipsum vel fermentum. Vestibulum lacinia, nisi ac tincidunt cursus, mauris urna adipiscing nunc, placerat condimentum sapien nibh eget est. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam at diam leo. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In purus risus, tempus at elementum at, tempor et nisl. Nunc et nunc magna. Donec pretium fermentum elit, in fringilla risus convallis at. Proin euismod porta orci, a pellentesque leo bibendum sit amet.', 'PL', 1);
INSERT INTO beer_translations VALUES (11, 'asdf', 'EN', 6);

ALTER SEQUENCE beer_translations_beetk_1_id_seq RESTART WITH 13;

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "blocker_attempts" (
  "bloak_1_id" SERIAL PRIMARY KEY,
  "bloa_ip" VARCHAR(39) NOT NULL,
  "bloa_attempts" INTEGER NOT NULL,
  "bloa_dateexpired" TIMESTAMP NOT NULL,
  "resg_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_blocker_attempts_resg_1_id" FOREIGN KEY ("resg_1_id")
	REFERENCES "acl_resgroups" ("resgk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE UNIQUE INDEX "un_blocker_attempts_bloa_ip" ON "blocker_attempts" ("bloa_ip", "resg_1_id");
CREATE INDEX "in_blocker_attempts_bloa_dateexpired" ON "blocker_attempts" ("bloa_dateexpired");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "blocker_rules" (
  "blork_1_id" SERIAL PRIMARY KEY,
  "blor_ip" VARCHAR(39) NOT NULL,
  "blor_datecreated" TIMESTAMP NOT NULL,
  "blor_dateexpired" TIMESTAMP DEFAULT NULL,
  "blor_message" TEXT,
  "blor_priority" INTEGER NOT NULL,
  "resg_1_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_blocker_attempts_resg_1_id" FOREIGN KEY ("resg_1_id")
	REFERENCES "acl_resgroups" ("resgk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE UNIQUE INDEX "un_blocker_rules_blor_ip" ON "blocker_rules" ("blor_ip", "resg_1_id");
CREATE INDEX "in_blocker_rules_blor_datecreated__blor_dateexpired" ON "blocker_rules" ("blor_datecreated","blor_dateexpired");
CREATE INDEX "in_blocker_rules_blor_priority" ON "blocker_rules" ("blor_priority");

INSERT INTO "blocker_rules" ("blor_ip", "blor_datecreated", "blor_dateexpired", "blor_message", "blor_priority", "resg_1_id") VALUES 
('182.252.120.31', '2013-01-09 17:19:44', '2014-05-12 23:11:00', 'Bo tak...', 1, NULL),
('179.154.122.51', '2013-01-07 10:30:04', '2014-08-20 08:05:00', 'Spamer', 100, 1),
('192.168.123.50', '2013-01-09 18:50:04', NULL, 'Spamuje poczte', 200, NULL);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "news" (
  "newsk_1_id" SERIAL PRIMARY KEY,
  "news_dateadded" TIMESTAMP NOT NULL,
  "news_status" SMALLINT DEFAULT NULL,
  "usr_1_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_beer_review_rankings_usr_1_id" FOREIGN KEY ("usr_1_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE
);

CREATE INDEX "in_news_news_dateadded" ON "news" ("news_dateadded");
CREATE INDEX "in_news_news_status" ON "news" ("news_status");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "newsletter" (
  "newlk_1_id" SERIAL PRIMARY KEY,
  "newl_email" VARCHAR(200) NOT NULL,
  "newl_status" PP_NEWSLETTER_STATUS DEFAULT NULL,
  "newl_dateadded" TIMESTAMP NOT NULL
);

CREATE UNIQUE INDEX "un_newsletter_newl_email" ON "newsletter" ("newl_email");
CREATE INDEX "in_newsletter_newl_status" ON "newsletter" ("newl_status");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "news_comments" (
  "newck_1_id" SERIAL PRIMARY KEY,
  "newc_value" TEXT NOT NULL,
  "newc_useragent" VARCHAR(200) NOT NULL,
  "newc_ip" VARCHAR(39) NOT NULL,
  "newc_dateadded" TIMESTAMP NOT NULL,
  "newc_status" SMALLINT DEFAULT NULL,
  "usr_1_id" INTEGER DEFAULT NULL,
  "news_2_id" INTEGER NOT NULL,
  "newc_3_id_parent" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_news_comments_usr_1_id" FOREIGN KEY ("usr_1_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE,
  CONSTRAINT "fk_news_comments_news_2_id" FOREIGN KEY ("news_2_id")
	REFERENCES "news" ("newsk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_news_comments_newc_3_id_parent" FOREIGN KEY ("newc_3_id_parent")
	REFERENCES "news_comments" ("newck_1_id")
	ON DELETE SET NULL
	ON UPDATE CASCADE
);

CREATE INDEX "in_news_comments_newc_status" ON "news_comments" ("newc_status");
CREATE INDEX "in_news_comments_newc_dateadded" ON "news_comments" ("newc_dateadded");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "news_tags" (
  "netgk_1_id" SERIAL PRIMARY KEY,
  "netg_name" VARCHAR(30) NOT NULL
);

CREATE UNIQUE INDEX "un_news_tags_netg_name" ON "news_tags" ("netg_name");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "news_translations" (
  "newtk_1_id" SERIAL PRIMARY KEY,
  "newt_name" VARCHAR(50) NOT NULL,
  "newt_description" VARCHAR(200) NOT NULL,
  "newt_text" TEXT NOT NULL,
  "newt_lang" PP_LANG NOT NULL,
  "news_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_news_translations_news_1_id" FOREIGN KEY ("news_1_id")
	REFERENCES "news" ("newsk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_news_translations_NEWS_Lang" ON "news_translations" ("newt_lang");
CREATE INDEX "in_news_translations_NEWS_Name" ON "news_translations" ("newt_name");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "news_tags_connections" (
  "ntgck_1_id" SERIAL PRIMARY KEY,
  "newt_1_id" INTEGER NOT NULL,
  "netg_2_id" INTEGER NOT NULL,
  CONSTRAINT "fk_news_tags_connections_newt_1_id" FOREIGN KEY ("newt_1_id")
	REFERENCES "news_translations" ("newtk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  CONSTRAINT "fk_news_tags_connections_netg_2_id" FOREIGN KEY ("netg_2_id")
	REFERENCES "news_tags" ("netgk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "sessions" (
  "sesk_1_id" SERIAL PRIMARY KEY,
  "ses_key" VARCHAR(32) NOT NULL,
  "ses_value" TEXT,
  "ses_datecreated" TIMESTAMP NOT NULL,
  "ses_dateexpired" TIMESTAMP NOT NULL,
  "ses_ip" VARCHAR(39) NOT NULL,
  "ses_useragent" VARCHAR(150) NOT NULL,
  "usr_1_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_sessions_usr_1_id" FOREIGN KEY ("usr_1_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_sessions_ses_datecreated__ses_dateexpired" ON "sessions" ("ses_datecreated","ses_dateexpired");
CREATE INDEX "in_sessions_ses_Ip" ON "sessions" ("ses_ip");
CREATE INDEX "in_sessions_ses_useragent" ON "sessions" ("ses_useragent");
CREATE UNIQUE INDEX "un_sessions_ses_key" ON "sessions" ("ses_key");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "sites" (
  "sitk_1_id" SERIAL PRIMARY KEY
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "site_translations" (
  "sittk_1_id" SERIAL PRIMARY KEY,
  "sitt_name" VARCHAR(50) NOT NULL,
  "sitt_description" VARCHAR(200) NOT NULL,
  "sitt_text" TEXT NOT NULL,
  "sitt_lang" PP_LANG NOT NULL,
  "sit_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_site_translations_sit_1_id" FOREIGN KEY ("sit_1_id")
	REFERENCES "sites" ("sitk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_site_translations_sitt_name" ON "site_translations" ("sitt_name");
CREATE INDEX "in_site_translations_sitt_lang" ON "site_translations" ("sitt_lang");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "sph_counter" (
  "sphck_1_id" SERIAL PRIMARY KEY,
  "sphc_maxdocid" INTEGER NOT NULL
);

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "user_activations" (
  "usrak_1_id" SERIAL PRIMARY KEY,
  "usra_dateadded" TIMESTAMP NOT NULL,
  "usra_dateexpired" TIMESTAMP NOT NULL,
  "usra_uuid" VARCHAR(36) NOT NULL,
  "usra_type" SMALLINT NOT NULL,
  "usr_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_user_activations_usr_1_id" FOREIGN KEY ("usr_1_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_user_activations_usra_dateadded__usra_dateexpired" ON "user_activations" ("usra_dateadded", "usra_dateexpired");
CREATE UNIQUE INDEX "in_user_activations_usra_uuid" ON "user_activations" ("usra_uuid");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "user_logs" (
  "usrlk_1_id" SERIAL PRIMARY KEY,
  "usrl_type" SMALLINT NOT NULL,
  "usrl_value" TEXT NOT NULL,
  "usrl_dateadded" TIMESTAMP NOT NULL,
  "usr_1_id" INTEGER DEFAULT NULL,
  CONSTRAINT "fk_user_logs_usr_1_id" FOREIGN KEY ("usr_1_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_user_logs_usrl_type" ON "user_logs" ("usrl_type");
CREATE INDEX "in_user_logs_usrl_dateadded" ON "user_logs" ("usrl_dateadded");

/* ------------------------------------------------------------------------------ */

CREATE TABLE IF NOT EXISTS "user_metas" (
  "usrmk_1_id" SERIAL PRIMARY KEY,
  "usrm_key" VARCHAR(30) NOT NULL,
  "usrm_value" TEXT,
  "usr_1_id" INTEGER NOT NULL,
  CONSTRAINT "fk_user_metas_usr_1_id" FOREIGN KEY ("usr_1_id")
	REFERENCES "users" ("usrk_1_id")
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE INDEX "in_user_metas_usrm_key" ON "user_metas" ("usrm_key");

INSERT INTO "user_metas" ("usrm_key", "usrm_value", "usr_1_id") VALUES
('name', 'Michał', 1),
('surname', 'Korus', 1),
('theme', 'bootstrap', 1),
('name', 'Mateusz', 2),
('surname', 'Palka', 2),
('theme', 'bootstrap', 2),
('name', 'Paweł', 3),
('surname', 'Kowalski', 3),
('theme', 'bootstrap', 3);

/* ------------------------------------------------------------------------------ */
/* ------------------------------------------------------------------------------ */

CREATE OR REPLACE FUNCTION getRoleParentsByRoleId(acl_roles.rolk_1_id%TYPE) 
RETURNS TABLE (id acl_roles.rolk_1_id%TYPE, name acl_roles.rol_name%TYPE)
AS $$
	WITH RECURSIVE path(id, name) AS (
		SELECT rolk_1_id as id, rol_name as name FROM acl_roles WHERE rolk_1_id = $1
  		UNION
  		SELECT
			rol.rolk_1_id,
    			rol.rol_name
  		FROM acl_roles as rol,
		     acl_role_parents as parentRole,
		     path as parentPath
  		WHERE parentRole.rol_1_id = parentPath.id 
			AND rol.rolk_1_id = parentRole.rol_2_id_parent
	)
	SELECT * FROM path;
$$ LANGUAGE 'sql';

/* ------------------------------------------------------------------------------ */

CREATE OR REPLACE FUNCTION isChildRoleByRoleId(acl_roles.rolk_1_id%TYPE, 
					       acl_roles.rolk_1_id%TYPE) 
RETURNS BOOLEAN
AS $$
	WITH RECURSIVE path(id, name) AS (
		SELECT rolk_1_id as id, rol_name as name 
			FROM acl_roles 
			WHERE rolk_1_id = $2
  		UNION
  		SELECT
			rol.rolk_1_id,
    			rol.rol_name
  		FROM acl_roles as rol,
		     acl_role_parents as parentRole,
		     path as parentPath
  		WHERE parentRole.rol_1_id = parentPath.id 
			AND rol.rolk_1_id = parentRole.rol_2_id_parent
			AND rol.rolk_1_id != 1
	)
	SELECT CASE WHEN EXISTS (SELECT * FROM path WHERE id=$1 AND id!=$2)
		THEN TRUE
		ELSE FALSE
	END;
$$ LANGUAGE 'sql';

/* ------------------------------------------------------------------------------ */

CREATE OR REPLACE FUNCTION isAllowed(acl_resources.res_name%TYPE, 
				     acl_privileges.priv_name%TYPE,
				     acl_roles.rolk_1_id%TYPE) 
RETURNS BOOLEAN
AS $$
DECLARE
	r_rule PP_ACL_RULE_INFO;
	i_privilege_id INTEGER;	
	i_count_privileges INTEGER;
BEGIN
	SELECT ruls.rulk_1_id, ruls.rul_action INTO r_rule
		  FROM acl_rules ruls
		  INNER JOIN getRoleParentsByRoleId($3) tree ON ruls.rol_1_id=tree.id
		  LEFT JOIN acl_roles rol ON rol.rolk_1_id=ruls.rol_1_id
		  LEFT JOIN acl_resources res ON ruls.res_2_id=res.resk_1_id
		  WHERE res.res_name=$1
		  ORDER BY ruls.rul_priority DESC
		  LIMIT 1;

	IF r_rule.action='DENIED' THEN
		SELECT COUNT(p.privk_1_Id) INTO i_count_privileges 
			FROM acl_privileges p
			LEFT JOIN acl_rules_privileges rp ON rp.priv_2_id = p.privk_1_id
			WHERE rp.rul_1_id = r_rule.rule_id;
			
		IF i_count_privileges=0 THEN
			RETURN FALSE;
		END IF;

		SELECT p.privk_1_id INTO i_privilege_id 
			FROM acl_privileges p
			LEFT JOIN acl_rules_privileges rp ON rp.priv_2_Id=p.privk_1_id
			WHERE rp.rul_1_id=r_rule.rule_id
				AND p.priv_name=$2;	
	
		IF i_privilege_id IS NULL THEN
			RETURN TRUE;
		END IF;	

		RETURN FALSE;
	ELSIF r_rule.action='ALLOWED' THEN
		SELECT COUNT(p.privk_1_Id) INTO i_count_privileges 
			FROM acl_privileges p
			LEFT JOIN acl_rules_privileges rp ON rp.priv_2_id = p.privk_1_id
			WHERE rp.rul_1_id = r_rule.rule_id;
			
		IF i_count_privileges=0 THEN
			RETURN TRUE;
		END IF;

		SELECT p.privk_1_id INTO i_privilege_id 
			FROM acl_privileges p
			LEFT JOIN acl_rules_privileges rp ON rp.priv_2_Id=p.privk_1_id
			WHERE rp.rul_1_id=r_rule.rule_id
				AND p.priv_name=$2;	
	
		IF i_privilege_id IS NULL THEN
			RETURN FALSE;
		END IF;
		
		RETURN TRUE;
	ELSE
		RETURN FALSE;
	END IF;
END;	
$$ LANGUAGE 'plpgsql';

/* ------------------------------------------------------------------------------ */

CREATE OR REPLACE FUNCTION updateSphinxCounter(key INT, data INT) RETURNS VOID AS
$$
BEGIN
    LOOP
        UPDATE sph_counter SET sphc_maxdocid = data WHERE sphck_1_id = key;
        IF found THEN
            RETURN;
        END IF;
        BEGIN
            INSERT INTO sph_counter(sphck_1_id, sphc_maxdocid) VALUES (key, data);
            RETURN;
        EXCEPTION WHEN unique_violation THEN
        END;
    END LOOP;
END;
$$ LANGUAGE 'plpgsql';

/* ------------------------------------------------------------------------------ */

/* 
    średnia ważona = (g / (g+m)) *s + (m / (g+m)) * S 
    gdzie:

    s - średnia ocena dla danego piwa
    g - liczba oddanych głosów na piwo
    m - minimalna liczba głosów wymagana do uwzględnienia w TOP
    S - średnia ocen dla wszystkich piw
*/

CREATE OR REPLACE FUNCTION updateRankingWeightAvg(minNumRankingVotes INT) RETURNS VOID AS
$$
DECLARE
    _beer RECORD;
    _totalAvg DECIMAL;
    _f_minNumRankingVotes DECIMAL;
BEGIN
    UPDATE beers SET bee_rankingweightedavg = NULL WHERE bee_rankingcounter < minNumRankingVotes;

    SELECT AVG(bee_rankingavg) INTO _totalAvg FROM beers;
    _f_minNumRankingVotes := minNumRankingVotes::int::float;

    FOR _beer IN SELECT * FROM beers AS b WHERE b.bee_status = 'AKTYWNY' AND b.bee_rankingcounter >= minNumRankingVotes 
    LOOP
        IF _beer.bee_rankingcounter > 0 THEN
            UPDATE beers 
                SET bee_rankingweightedavg = (_beer.bee_rankingcounter / (_beer.bee_rankingcounter + _f_minNumRankingVotes)) * _beer.bee_rankingavg + (_f_minNumRankingVotes / (_beer.bee_rankingcounter + _f_minNumRankingVotes)) * _totalAvg
                WHERE beers.beek_1_id = _beer.beek_1_id;
        END IF;
    END LOOP;
END;
$$ LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION getPrimaryImageForBeerId(_beerId INT) RETURNS RECORD AS
$$
DECLARE
    _beerPrimaryImage beer_images%ROWTYPE;
BEGIN
    SELECT bi.* INTO _beerPrimaryImage FROM beers AS b
        LEFT JOIN beer_images AS bi ON b.beei_7_id = bi.beeik_1_id
            WHERE b.beek_1_id = _beerId;

    RETURN _beerPrimaryImage;
END;
$$ LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION getPrimaryImageForBeerManufacturerId(_beerManId INT) RETURNS RECORD AS
$$
DECLARE
    _beerManufacturerPrimaryImage beer_manufacturer_images%ROWTYPE;
BEGIN
    SELECT bi.* INTO _beerManufacturerPrimaryImage FROM beer_manufacturers AS bm
        LEFT JOIN beer_manufacturer_images AS bi ON bm.bemi_5_id = bi.bemik_1_id
            WHERE bm.beemk_1_id = _beerManId;

    RETURN _beerManufacturerPrimaryImage;
END;
$$ LANGUAGE 'plpgsql';

/* ------------------------------------------------------------------------------ */

CREATE OR REPLACE FUNCTION triggerBIBeers() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.bee_dateadded IS NULL THEN
        NEW.bee_dateadded = NOW();
    END IF;

    IF NEW.bee_rankingavg IS NULL THEN
        NEW.bee_rankingavg = 0.0;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIUBeers() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.bee_status IS NULL THEN
        NEW.bee_status = 'AKTYWNY';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIBeerImages() RETURNS TRIGGER AS
$$
DECLARE
    _maxBeerImagePosition INTEGER;
BEGIN
    IF NEW.beei_dateadded IS NULL THEN
        NEW.beei_dateadded = NOW();
    END IF;

    IF NEW.beei_position IS NULL THEN
        SELECT MAX(beei_position) INTO _maxBeerImagePosition 
            FROM beer_images WHERE bee_1_id = NEW.bee_1_id;
            
        IF _maxBeerImagePosition IS NULL THEN
            NEW.beei_position = 1;
        ELSE
            NEW.beei_position = _maxBeerImagePosition+1;
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerAIBeerImages() RETURNS TRIGGER AS
$$
DECLARE
    _beerPrimaryImage beer_images%ROWTYPE;
BEGIN
    _beerPrimaryImage := getPrimaryImageForBeerId(NEW.bee_1_id);
    
    IF _beerPrimaryImage IS NULL THEN
        UPDATE beers SET beei_7_id = NEW.beeik_1_id
            WHERE beek_1_id = NEW.bee_1_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIUBeerImages() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.beei_status IS NULL THEN
        NEW.beei_status = 'NIEWIDOCZNY';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBDBeerImages() RETURNS TRIGGER AS
$$
DECLARE
    _beerImage beer_images%ROWTYPE;
    _counter INTEGER;
    _beerPrimaryImage beer_images%ROWTYPE;
BEGIN
    _beerPrimaryImage := getPrimaryImageForBeerId(OLD.bee_1_id);

    IF _beerPrimaryImage IS NOT NULL THEN
        IF _beerPrimaryImage.beeik_1_id = OLD.beeik_1_id THEN
            SELECT bi.* INTO _beerImage FROM beer_images as bi
                WHERE bi.beeik_1_id != OLD.beeik_1_id
                    AND bi.bee_1_id = OLD.bee_1_id
                ORDER BY bi.beei_position;

            UPDATE beers SET beei_7_id = _beerImage.beeik_1_id
                WHERE beek_1_id = OLD.bee_1_id;
        END IF;
    END IF;

    _counter := 1;
    FOR _beerImage IN 
        SELECT * FROM beer_images AS bi
            WHERE bi.bee_1_id = OLD.bee_1_id 
                AND bi.beeik_1_id != OLD.beeik_1_id
            ORDER BY bi.beei_position
    LOOP
        UPDATE beer_images SET beei_position = _counter 
            WHERE beeik_1_id = _beerImage.beeik_1_id;
        _counter := _counter + 1;
    END LOOP;

    RETURN OLD;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIBeerComments() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.beec_dateadded IS NULL THEN
        NEW.beec_dateadded = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIBeerSearches() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.bees_dateadded IS NULL THEN
        NEW.bees_dateadded = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIBlockerRules() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.blor_datecreated IS NULL THEN
        NEW.blor_datecreated = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIUBlockerRules() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.blor_priority IS NULL THEN
        NEW.blor_priority = 1;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIUCurrencyExchanges() RETURNS TRIGGER AS
$$
BEGIN
    NEW.cure_lastupdated = NOW();

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBINews() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.news_dateadded IS NULL THEN
        NEW.news_dateadded = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBINewsComments() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.newc_dateadded IS NULL THEN
        NEW.newc_dateadded = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBINewsletter() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.newl_dateadded IS NULL THEN
        NEW.newl_dateadded = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBISessions() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.ses_datecreated IS NULL THEN
        NEW.ses_datecreated = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIUserActivations() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.usra_dateadded IS NULL THEN
        NEW.usra_dateadded = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIUserLogs() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.usrl_dateadded IS NULL THEN
        NEW.usrl_dateadded = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIBeerManufacturerImages() RETURNS TRIGGER AS
$$
DECLARE
    _maxBeerManufacturerImagePosition INTEGER;
BEGIN
    IF NEW.bemi_dateadded IS NULL THEN
        NEW.bemi_dateadded = NOW();
    END IF;

    IF NEW.bemi_position IS NULL THEN
        SELECT MAX(bemi_position) INTO _maxBeerManufacturerImagePosition 
            FROM beer_manufacturer_images WHERE beem_1_id = NEW.beem_1_id;
            
        IF _maxBeerManufacturerImagePosition IS NULL THEN
            NEW.bemi_position = 1;
        ELSE
            NEW.bemi_position = _maxBeerManufacturerImagePosition+1;
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerAIBeerManufacturerImages() RETURNS TRIGGER AS
$$
DECLARE
    _beerManufacturerPrimaryImage beer_manufacturer_images%ROWTYPE;
BEGIN
    _beerManufacturerPrimaryImage := getPrimaryImageForBeerManufacturerId(NEW.beem_1_id);
    
    IF _beerManufacturerPrimaryImage IS NULL THEN
        UPDATE beer_manufacturers SET bemi_5_id = NEW.bemik_1_id
            WHERE beemk_1_id = NEW.beem_1_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBIUBeerManufacturerImages() RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.bemi_status IS NULL THEN
        NEW.bemi_status = 'NIEWIDOCZNY';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

/* -------- */

CREATE OR REPLACE FUNCTION triggerBDBeerManufacturerImages() RETURNS TRIGGER AS
$$
DECLARE
    _beerManufacturerImage beer_manufacturer_images%ROWTYPE;
    _counter INTEGER;
    _beerManufacturerPrimaryImage beer_manufacturer_images%ROWTYPE;
BEGIN
    _beerManufacturerPrimaryImage := getPrimaryImageForBeerManufacturerId(OLD.beem_1_id);

    IF _beerManufacturerPrimaryImage IS NOT NULL THEN
        IF _beerManufacturerPrimaryImage.bemik_1_id = OLD.bemik_1_id THEN
            SELECT bi.* INTO _beerManufacturerImage FROM beer_manufacturer_images as bi
                WHERE bi.bemik_1_id != OLD.bemik_1_id
                    AND bi.beem_1_id = OLD.beem_1_id
                ORDER BY bi.bemi_position;

            UPDATE beer_manufacturers SET bemi_5_id = _beerManufacturerImage.bemik_1_id
                WHERE beemk_1_id = OLD.beem_1_id;
        END IF;
    END IF;

    _counter := 1;
    FOR _beerManufacturerImage IN 
        SELECT * FROM beer_manufacturer_images AS bi
            WHERE bi.beem_1_id = OLD.beem_1_id 
                AND bi.bemik_1_id != OLD.bemik_1_id
            ORDER BY bi.bemi_position
    LOOP
        UPDATE beer_manufacturer_images SET bemi_position = _counter 
            WHERE bemik_1_id = _beerManufacturerImage.bemik_1_id;
        _counter := _counter + 1;
    END LOOP;

    RETURN OLD;
END;
$$ LANGUAGE 'plpgsql';

/* ------------------------------------------------------------------------------ */

CREATE TRIGGER bi_beers BEFORE INSERT ON beers
    FOR EACH ROW EXECUTE PROCEDURE triggerBIBeers();

CREATE TRIGGER biu_beers BEFORE INSERT OR UPDATE ON beers
    FOR EACH ROW EXECUTE PROCEDURE triggerBIUBeers();

CREATE TRIGGER bi_beer_images BEFORE INSERT ON beer_images 
    FOR EACH ROW EXECUTE PROCEDURE triggerBIBeerImages();

CREATE TRIGGER ai_beer_images AFTER INSERT ON beer_images 
    FOR EACH ROW EXECUTE PROCEDURE triggerAIBeerImages();

CREATE TRIGGER biu_beer_images BEFORE INSERT OR UPDATE ON beer_images
    FOR EACH ROW EXECUTE PROCEDURE triggerBIUBeerImages();

CREATE TRIGGER bd_beer_images BEFORE DELETE ON beer_images 
    FOR EACH ROW EXECUTE PROCEDURE triggerBDBeerImages();

CREATE TRIGGER bi_beer_comments BEFORE INSERT ON beer_comments
    FOR EACH ROW EXECUTE PROCEDURE triggerBIBeerComments();

CREATE TRIGGER bi_beer_searches BEFORE INSERT ON beer_searches
    FOR EACH ROW EXECUTE PROCEDURE triggerBIBeerSearches();

CREATE TRIGGER bi_blocker_rules BEFORE INSERT ON blocker_rules
    FOR EACH ROW EXECUTE PROCEDURE triggerBIBlockerRules();

CREATE TRIGGER biu_blocker_rules BEFORE INSERT OR UPDATE ON blocker_rules
    FOR EACH ROW EXECUTE PROCEDURE triggerBIUBlockerRules();

CREATE TRIGGER biu_currency_exchanges BEFORE INSERT OR UPDATE ON currency_exchanges
    FOR EACH ROW EXECUTE PROCEDURE triggerBIUCurrencyExchanges();

CREATE TRIGGER bi_news BEFORE INSERT ON news
    FOR EACH ROW EXECUTE PROCEDURE triggerBINews();

CREATE TRIGGER bi_news_comments BEFORE INSERT ON news_comments
    FOR EACH ROW EXECUTE PROCEDURE triggerBINewsComments();

CREATE TRIGGER bi_newsletter BEFORE INSERT ON newsletter
    FOR EACH ROW EXECUTE PROCEDURE triggerBINewsletter();

CREATE TRIGGER bi_sessions BEFORE INSERT ON sessions
    FOR EACH ROW EXECUTE PROCEDURE triggerBISessions();

CREATE TRIGGER bi_user_activations BEFORE INSERT ON user_activations
    FOR EACH ROW EXECUTE PROCEDURE triggerBIUserActivations();

CREATE TRIGGER bi_user_logs BEFORE INSERT ON user_logs
    FOR EACH ROW EXECUTE PROCEDURE triggerBIUserLogs();

CREATE TRIGGER bi_beer_manufacturer_images BEFORE INSERT ON beer_manufacturer_images 
    FOR EACH ROW EXECUTE PROCEDURE triggerBIBeerManufacturerImages();

CREATE TRIGGER ai_beer_manufacturer_images AFTER INSERT ON beer_manufacturer_images 
    FOR EACH ROW EXECUTE PROCEDURE triggerAIBeerManufacturerImages();

CREATE TRIGGER biu_beer_manufacturer_images BEFORE INSERT OR UPDATE ON beer_manufacturer_images
    FOR EACH ROW EXECUTE PROCEDURE triggerBIUBeerManufacturerImages();

CREATE TRIGGER bd_beer_manufacturer_images BEFORE DELETE ON beer_manufacturer_images 
    FOR EACH ROW EXECUTE PROCEDURE triggerBDBeerManufacturerImages();
/* 
------------------------------------------------------------------------------
TESTY JEDNOSTKOWE
------------------------------------------------------------------------------ 
*/
/*
SELECT 	isChildRoleByRoleId(1, 2) = FALSE,
	isChildRoleByRoleId(1, 3) = FALSE,
	isChildRoleByRoleId(2, 6) = TRUE,
	isChildRoleByRoleId(5, 6) = TRUE,
	isChildRoleByRoleId(4, 5) = FALSE,
	isChildRoleByRoleId(6, 6) = FALSE;

SELECT	(isAllowed('default__index', 'index', 6) = TRUE),
	(isAllowed('admin__index', 'index', 1) = FALSE),
	(isAllowed('admin__index', 'login', 1) = TRUE),
	(isAllowed('admin__index', 'logout', 1) = TRUE),
	(isAllowed('admin__index', 'login', 3) = FALSE),
	(isAllowed('admin__index', 'logout', 3) = TRUE),
	(isAllowed('admin__account', 'index', 2) = FALSE),
	(isAllowed('admin__account', 'index', 3) = TRUE),
	(isAllowed('admin__users', 'index', 3) = FALSE),
	(isAllowed('admin__users', 'index', 4) = TRUE);
*/
/* ------------------------------------------------------------------------------ */
