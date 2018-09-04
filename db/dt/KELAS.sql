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

Date: 2018-09-04 15:00:50
*/


-- ----------------------------
-- Table structure for KELAS
-- ----------------------------
DROP TABLE "EIS"."KELAS";
CREATE TABLE "EIS"."KELAS" (
"NOMOR" NUMBER(8) NOT NULL ,
"PROGRAM" NUMBER(2) NULL ,
"JURUSAN" NUMBER(2) NULL ,
"KELAS" NUMBER(2) NULL ,
"PARAREL" VARCHAR2(5 BYTE) NULL ,
"KODE" VARCHAR2(15 BYTE) NULL ,
"KODE_KELAS_ABSEN" VARCHAR2(4 BYTE) NULL ,
"KODE_EPSBED" VARCHAR2(1 BYTE) NULL ,
"KONSENTRASI" NUMBER(10) NULL ,
"WALI_KELAS" NUMBER(10) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of KELAS
-- ----------------------------
INSERT INTO "EIS"."KELAS" VALUES ('5', '3', '80', '2', 'A', '2PIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('2', '3', '89', '2', 'A', '2BPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('3', '3', '89', '3', 'A', '3BPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('6', '3', '80', '3', 'A', '3PIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('11', '3', '77', '2', 'A', '2BTPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('12', '3', '77', '3', 'A', '3BTPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('13', '3', '81', '1', 'A', '1AGPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('14', '3', '81', '2', 'A', '2AGPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('15', '3', '81', '3', 'A', '3AGPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('17', '4', '78', '2', 'A', '2AGIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('18', '4', '78', '3', 'A', '3AGIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('19', '4', '78', '4', 'A', '4AGIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('20', '3', '82', '1', 'A', '1TKA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('21', '3', '82', '2', 'A', '2TKA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('24', '4', '91', '2', 'A', '2PPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('25', '4', '91', '3', 'A', '3PPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('26', '4', '91', '4', 'A', '4PPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('29', '4', '90', '3', 'A', '3ABA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('30', '4', '90', '4', 'A', '4ABA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('31', '4', '86', '1', 'A', '1APTA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('32', '4', '86', '2', 'A', '2APTA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('33', '4', '86', '3', 'A', '3APTA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('34', '4', '86', '4', 'A', '4APTA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('77', '3', '97', '2', 'A', '2BPBNA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('78', '3', '97', '3', 'A', '3BPBNA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('80', '3', '98', '1', 'A', '1BPBSA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('7', '3', '95', '1', 'A', '1TPHA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('1', '3', '89', '1', 'A', '1BPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('4', '3', '80', '1', 'A', '1PIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('8', '3', '95', '2', 'A', '2TPHA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('9', '3', '95', '3', 'A', '3TPHA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('10', '3', '77', '1', 'A', '1BTPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('16', '4', '78', '1', 'A', '1AGIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('38', '3', '81', '1', 'B', '1AGPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('39', '3', '81', '2', 'B', '2AGPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('41', '4', '86', '1', 'B', '1APTB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('42', '4', '86', '2', 'B', '2APTB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('43', '4', '86', '3', 'B', '3APTB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('45', '4', '78', '1', 'B', '1AGIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('48', '4', '78', '4', 'B', '4AGIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('49', '3', '77', '1', 'B', '1BTPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('54', '3', '80', '3', 'B', '3PIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('56', '4', '91', '2', 'B', '2PPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('57', '4', '91', '3', 'B', '3PPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('58', '4', '91', '4', 'B', '4PPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('61', '3', '95', '3', 'B', '3TPHB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('62', '3', '89', '3', 'B', '3BPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('23', '4', '91', '1', 'A', '1PPA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('27', '4', '90', '1', 'A', '1ABA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('22', '3', '82', '3', 'A', '3TKA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('28', '4', '90', '2', 'A', '2ABA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('37', '3', '89', '1', 'B', '1BPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('76', '3', '97', '1', 'A', '1BPBNA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('81', '3', '98', '2', 'A', '2BPBSA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('82', '3', '98', '3', 'A', '3BPBSA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('67', '4', '87', '2', 'B', '2TPIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('63', '4', '87', '1', 'A', '1TPIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('64', '4', '87', '1', 'B', '1TPIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('68', '4', '87', '3', 'A', '3TPIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('69', '4', '87', '3', 'B', '3TPIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('72', '4', '90', '1', 'B', '1ABB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('75', '4', '90', '4', 'B', '4ABB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('40', '3', '81', '3', 'B', '3AGPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('44', '4', '86', '4', 'B', '4APTB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('35', '3', '89', '2', 'B', '2BPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('46', '4', '78', '2', 'B', '2AGIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('47', '4', '78', '3', 'B', '3AGIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('50', '3', '77', '2', 'B', '2BTPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('51', '3', '77', '3', 'B', '3BTPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('52', '3', '80', '1', 'B', '1PIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('53', '3', '80', '2', 'B', '2PIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('55', '4', '91', '1', 'B', '1PPB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('59', '3', '95', '1', 'B', '1TPHB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('60', '3', '95', '2', 'B', '2TPHB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('65', '4', '87', '2', 'A', '2TPIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('70', '4', '87', '4', 'A', '4TPIA', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('71', '4', '87', '4', 'B', '4TPIB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('73', '4', '90', '2', 'B', '2ABB', null, null, null, null);
INSERT INTO "EIS"."KELAS" VALUES ('74', '4', '90', '3', 'B', '3ABB', null, null, null, null);

-- ----------------------------
-- Indexes structure for table KELAS
-- ----------------------------

-- ----------------------------
-- Triggers structure for table KELAS
-- ----------------------------
CREATE OR REPLACE TRIGGER "EIS"."TKELAS_DOSEN_WALI" AFTER UPDATE OF "WALI_KELAS" ON "EIS"."KELAS" REFERENCING OLD AS "OLD" NEW AS "NEW" FOR EACH ROW ENABLE
declare
  -- local variables here
begin
 if :new.wali_kelas is not null and :old.wali_kelas is not null then
    update pegawai set hak=256 where nomor=:new.wali_kelas and (hak is null or hak=0);
    update mahasiswa set dosen_wali=:new.wali_kelas where kelas=:new.nomor;
   
    update pegawai set hak=0 where nomor=:old.wali_kelas 
    and nomor not in (select dosen_wali from mahasiswa where dosen_wali=:old.wali_kelas 
    and subkampus is null);
  elsif :new.wali_kelas is not null and :old.wali_kelas is null then
    update pegawai set hak=256 where nomor=:new.wali_kelas and (hak is null or hak=0);
    update mahasiswa set dosen_wali=:new.wali_kelas where kelas=:new.nomor;    
  else    
     update mahasiswa set dosen_wali=null where kelas=:new.nomor;
     update pegawai set hak=0 where nomor=:old.wali_kelas 
    and nomor not in (select dosen_wali from mahasiswa where dosen_wali=:old.wali_kelas 
    and subkampus is null);
  end if;
end;
-- ----------------------------
-- Uniques structure for table KELAS
-- ----------------------------
ALTER TABLE "EIS"."KELAS" ADD UNIQUE ("KODE");
ALTER TABLE "EIS"."KELAS" ADD UNIQUE ("PROGRAM", "JURUSAN", "KELAS", "PARAREL");
ALTER TABLE "EIS"."KELAS" ADD UNIQUE ("KODE_KELAS_ABSEN");

-- ----------------------------
-- Primary Key structure for table KELAS
-- ----------------------------
ALTER TABLE "EIS"."KELAS" ADD PRIMARY KEY ("NOMOR");
