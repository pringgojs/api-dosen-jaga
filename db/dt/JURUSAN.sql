/*
Navicat Oracle Data Transfer
Oracle Client Version : 10.2.0.5.0

Source Server         : EIS
Source Server Version : 110200
Source Host           : localhost:1521
Source Schema         : EIS

Target Server Type    : ORACLE
Target Server Version : 110200
File Encoding         : 65001

Date: 2018-09-04 14:59:58
*/


-- ----------------------------
-- Table structure for JURUSAN
-- ----------------------------
DROP TABLE "EIS"."JURUSAN";
CREATE TABLE "EIS"."JURUSAN" (
"NOMOR" NUMBER(2) NOT NULL ,
"JURUSAN" VARCHAR2(200 BYTE) NOT NULL ,
"KAJUR" NUMBER(8) NULL ,
"SEKJUR" NUMBER(8) NULL ,
"ALIAS" VARCHAR2(10 BYTE) NULL ,
"JURUSAN_INGGRIS" VARCHAR2(50 BYTE) NULL ,
"JURUSAN_LENGKAP" VARCHAR2(200 BYTE) NULL ,
"KONSENTRASI" VARCHAR2(255 BYTE) NULL ,
"URUT" NUMBER(4) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of JURUSAN
-- ----------------------------
INSERT INTO "EIS"."JURUSAN" VALUES ('97', 'Budidaya Perikanan (Pembenihan)', '61', null, 'BDY', 'Aquaculture', null, 'Pembenihan', null);
INSERT INTO "EIS"."JURUSAN" VALUES ('98', 'Budidaya Perikanan (Pembesaran)', '61', null, 'BDY', 'Aquaculture', null, 'Pembesaran', null);
INSERT INTO "EIS"."JURUSAN" VALUES ('89', 'Budidaya Perikanan', '61', '106', 'BDY', 'Aquaculture', null, null, '1');
INSERT INTO "EIS"."JURUSAN" VALUES ('77', 'Budidaya Tanaman Perkebunan', '102', '44', 'BTP', 'Plant Cultivation', null, null, '4');
INSERT INTO "EIS"."JURUSAN" VALUES ('78', 'Agroindustri', '137', null, 'AGRO', 'Agro Industry', null, null, '6');
INSERT INTO "EIS"."JURUSAN" VALUES ('80', 'Penangkapan Ikan', '60', '134', 'PI', null, null, null, '2');
INSERT INTO "EIS"."JURUSAN" VALUES ('81', 'Agribisnis Perikanan', '101', '111', 'AGRI', 'Agribusiness fisheries', null, null, '5');
INSERT INTO "EIS"."JURUSAN" VALUES ('82', 'Teknik Kelautan', '124', null, 'TKL', 'Marine Engineering', null, null, '7');
INSERT INTO "EIS"."JURUSAN" VALUES ('86', 'Agribisnis Peternakan', '98', null, 'APET', null, null, null, '10');
INSERT INTO "EIS"."JURUSAN" VALUES ('87', 'Teknologi Pembenihan Ikan', null, null, 'TPI', 'Fish Hatchery Technology', null, null, '11');
INSERT INTO "EIS"."JURUSAN" VALUES ('91', 'Pengelolaan Pelabuhan Perikanan', '123', null, 'PPP', 'Management of Fishery Ports', null, null, '8');
INSERT INTO "EIS"."JURUSAN" VALUES ('90', 'Administrasi Bisnis Internasional', '113', null, 'ABI', 'International Business Administration', null, null, '9');
INSERT INTO "EIS"."JURUSAN" VALUES ('95', 'Teknologi Pengolahan Hasil Perikanan', '23', '79', 'TPHP', 'Processing technology of fishery products', null, null, '3');

-- ----------------------------
-- Indexes structure for table JURUSAN
-- ----------------------------

-- ----------------------------
-- Triggers structure for table JURUSAN
-- ----------------------------
CREATE OR REPLACE TRIGGER "EIS"."TKAJUR" AFTER UPDATE OF "KAJUR" ON "EIS"."JURUSAN" REFERENCING OLD AS "OLD" NEW AS "NEW" FOR EACH ROW ENABLE
declare
  -- local variables here
begin
--  update ruang set kepala='' where kepala=:new.kajur;
--  update jabatan set pegawai='' where pegawai=:new.kajur;
  update pegawai set jabatan=null where nomor=:old.kajur;
  update pegawai set jabatan=2 where nomor=:new.kajur;
end tkajur;CREATE OR REPLACE TRIGGER "EIS"."TSEKJUR" AFTER UPDATE OF "SEKJUR" ON "EIS"."JURUSAN" REFERENCING OLD AS "OLD" NEW AS "NEW" FOR EACH ROW DISABLE
declare
  -- local variables here
begin
--  update ruang set kepala='' where kepala=:new.sekjur;
--  update jabatan set pegawai='' where pegawai=:new.sekjur;
  update pegawai set jabatan='' where nomor=:old.sekjur;
  update pegawai set jabatan='Sekjur '||:new.jurusan where nomor=:new.sekjur;
end tsekjur;
-- ----------------------------
-- Checks structure for table JURUSAN
-- ----------------------------
ALTER TABLE "EIS"."JURUSAN" ADD CHECK (kajur<>sekjur) ENABLE NOVALIDATE;
ALTER TABLE "EIS"."JURUSAN" ADD CHECK ("NOMOR" IS NOT NULL);
ALTER TABLE "EIS"."JURUSAN" ADD CHECK ("JURUSAN" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table JURUSAN
-- ----------------------------
ALTER TABLE "EIS"."JURUSAN" ADD PRIMARY KEY ("NOMOR");
