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

Date: 2018-09-04 15:04:06
*/


-- ----------------------------
-- Table structure for PROGRAM_STUDI
-- ----------------------------
DROP TABLE "EIS"."PROGRAM_STUDI";
CREATE TABLE "EIS"."PROGRAM_STUDI" (
"NOMOR" NUMBER(10) NOT NULL ,
"PROGRAM" NUMBER(10) NOT NULL ,
"JURUSAN" NUMBER(10) NOT NULL ,
"KEPALA" NUMBER(10) NULL ,
"KODE_EPSBED" VARCHAR2(5 BYTE) NULL ,
"DEPARTEMEN" NUMBER(10) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of PROGRAM_STUDI
-- ----------------------------
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('51', '3', '81', null, '54447', '9');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('52', '3', '80', null, '54443', '10');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('57', '3', '95', null, '54444', '11');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('63', '3', '77', null, '54471', '12');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('61', '4', '78', null, '41311', '13');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('66', '4', '91', null, '22313', '10');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('69', '3', '89', null, '54471', '9');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('70', '4', '86', null, '54445', '13');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('67', '3', '82', null, '38402', '10');
INSERT INTO "EIS"."PROGRAM_STUDI" VALUES ('68', '4', '90', null, '63311', '13');

-- ----------------------------
-- Uniques structure for table PROGRAM_STUDI
-- ----------------------------
ALTER TABLE "EIS"."PROGRAM_STUDI" ADD UNIQUE ("PROGRAM", "JURUSAN");

-- ----------------------------
-- Checks structure for table PROGRAM_STUDI
-- ----------------------------
ALTER TABLE "EIS"."PROGRAM_STUDI" ADD CHECK ("NOMOR" IS NOT NULL);
ALTER TABLE "EIS"."PROGRAM_STUDI" ADD CHECK ("PROGRAM" IS NOT NULL);
ALTER TABLE "EIS"."PROGRAM_STUDI" ADD CHECK ("JURUSAN" IS NOT NULL);
