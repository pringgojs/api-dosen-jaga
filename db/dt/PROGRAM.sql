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

Date: 2018-09-04 15:04:35
*/


-- ----------------------------
-- Table structure for PROGRAM
-- ----------------------------
DROP TABLE "EIS"."PROGRAM";
CREATE TABLE "EIS"."PROGRAM" (
"NOMOR" NUMBER(2) NOT NULL ,
"PROGRAM" VARCHAR2(50 BYTE) NOT NULL ,
"KETERANGAN" VARCHAR2(100 BYTE) NULL ,
"LAMA_STUDI" NUMBER(3,1) NULL ,
"KODE_EPSBED" NUMBER(2) NULL ,
"GELAR" VARCHAR2(100 BYTE) NULL ,
"GELAR_INGGRIS" VARCHAR2(100 BYTE) NULL ,
"MAX_KELAS" NUMBER(1) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of PROGRAM
-- ----------------------------
INSERT INTO "EIS"."PROGRAM" VALUES ('10', 'S3', 'S3', '2', null, null, null, null);
INSERT INTO "EIS"."PROGRAM" VALUES ('2', 'D2', 'Pendidikan Diploma Dua (D1)', '2', '21', 'AHLI MUDA (A.Ma.)', null, '2');
INSERT INTO "EIS"."PROGRAM" VALUES ('4', 'D4', 'Sarjana Terapan (DIV)', '4', '23', 'SARJANA SAINS TERAPAN (S.ST.)', null, '4');
INSERT INTO "EIS"."PROGRAM" VALUES ('5', 'D4LJ', 'Sarjana Terapan (DIV)', '1', '23', 'SARJANA SAINS TERAPAN (S.ST.)', null, '1');
INSERT INTO "EIS"."PROGRAM" VALUES ('7', 'D3LJ', 'Pendidikan Diploma Tiga (D3)', '1', '22', 'AHLI MADYA (A.Md)', null, '3');
INSERT INTO "EIS"."PROGRAM" VALUES ('1', 'D1', 'Pendidikan Diploma Satu (D1)', '1', '20', 'AHLI PRATAMA (A.P.)', null, '1');
INSERT INTO "EIS"."PROGRAM" VALUES ('3', 'D3', 'Pendidikan Diploma Tiga (D3)', '3', '22', 'AHLI MADYA (A.Md.)', null, '3');
INSERT INTO "EIS"."PROGRAM" VALUES ('6', 'S2', 'Program Pasca Sarjana', '2', '35', 'MAGISTER SAINS TERAPAN (M.ST.)', null, '2');

-- ----------------------------
-- Indexes structure for table PROGRAM
-- ----------------------------

-- ----------------------------
-- Checks structure for table PROGRAM
-- ----------------------------
ALTER TABLE "EIS"."PROGRAM" ADD CHECK ("NOMOR" IS NOT NULL);
ALTER TABLE "EIS"."PROGRAM" ADD CHECK ("PROGRAM" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table PROGRAM
-- ----------------------------
ALTER TABLE "EIS"."PROGRAM" ADD PRIMARY KEY ("NOMOR");
