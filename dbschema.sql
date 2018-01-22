CREATE DATABASE IF NOT EXISTS  hospital_db;

USE hospital_db;


CREATE TABLE IF NOT EXISTS religion(
  religion_id INT (2) UNSIGNED NOT NULL AUTO_INCREMENT,
  religion VARCHAR(20) NOT NULL ,

  PRIMARY KEY (religion_id)
);

CREATE TABLE IF NOT EXISTS gender(
  gender_id INT (1) UNSIGNED NOT NULL AUTO_INCREMENT,
  gender VARCHAR(10) NOT NULL ,

  PRIMARY KEY (gender_id)
);

CREATE TABLE IF NOT EXISTS patient_profile(
  patient_id INT (20) UNSIGNED NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(25) NULL,
  lastname VARCHAR(25) NULL,
  age INT (3)  UNSIGNED NULL,
  gender_id INT (1)  UNSIGNED NULL,
  marital_status VARCHAR (20) NULL,
  no_of_children INT(2)  UNSIGNED ,
  religion_id INT (2) UNSIGNED NOT NULL ,
  allergy VARCHAR (100),
  reoccuring_sickness VARCHAR (100)  NULL  ,
  lineage_health_issues VARCHAR (100) NULL  ,
  disability VARCHAR (10)  NULL  ,
  occupation VARCHAR (25)  NULL,
  email VARCHAR (50)  NOT NULL  ,
  password VARCHAR (300)  NOT NULL ,
  created TIMESTAMP NOT NULL,
  modified TIMESTAMP NOT NULL,

  PRIMARY KEY (patient_id),
  CONSTRAINT fk_patient_profile_religion_id FOREIGN KEY (religion_id) REFERENCES religion(religion_id)
);


CREATE TABLE IF NOT EXISTS hospital(
  hospital_id INT(11) UNSIGNED UNIQUE NOT NULL,
  hospital_name VARCHAR(50) NULL ,
  hospital_add VARCHAR(100) NULL ,
  hospital_lga VARCHAR(50) NULL ,
  hospital_state VARCHAR(30) NULL,
  hospital_email VARCHAR(50) NOT NULL ,
  hospital_password VARCHAR(300) NOT NULL,
  created TIMESTAMP NOT NULL,
  modified TIMESTAMP NOT NULL,

  PRIMARY KEY (hospital_id)
);




CREATE TABLE IF NOT EXISTS doctor_rank(
  doctor_rank_id INT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  rank VARCHAR(50) NOT NULL ,

  PRIMARY KEY (doctor_rank_id)
);


CREATE TABLE IF NOT EXISTS doctor(
  doctor_id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  doctor_firstname VARCHAR(25) NULL ,
  doctor_lastname VARCHAR(25) NULL ,
  doctor_rank_id INT(3) UNSIGNED NULL ,
  doctor_gender_id INT(1) NULL ,
  hospital_id INT(20) UNSIGNED NULL ,
  doctor_email VARCHAR(50) NOT NULL ,
  doctor_password VARCHAR(300) NOT NULL,
  created TIMESTAMP NOT NULL,
  modified TIMESTAMP NOT NULL,

  PRIMARY KEY (doctor_id),
  CONSTRAINT fk_doctor_hospital_id FOREIGN KEY (hospital_id) REFERENCES hospital(hospital_id),
  CONSTRAINT fk_doctor_doctor_rank_id FOREIGN KEY (doctor_rank_id) REFERENCES doctor_rank(doctor_rank_id)
);


CREATE TABLE IF NOT EXISTS patient_record(
  patient_id INT (20) UNSIGNED NOT NULL,
  report_doctor TEXT(300) NOT NULL,
  doctor_id INT(20) UNSIGNED NOT NULL ,
  created TIMESTAMP NOT NULL,
  modified TIMESTAMP NOT NULL,

  CONSTRAINT fk_patient_record_patient_id FOREIGN KEY (patient_id) REFERENCES patient_profile(patient_id),
  CONSTRAINT fk_patient_record_doctor_id FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id)
);


CREATE TABLE IF NOT EXISTS doctor_shifts(
  doctor_id INT(20) UNSIGNED NOT NULL ,
  shift_status VARCHAR(20) DEFAULT('NO'),
  created TIMESTAMP NOT NULL,
  modified TIMESTAMP NULL,

  CONSTRAINT fk_doctor_shifts_doctor_id FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id)
);



CREATE TABLE IF NOT EXISTS doctor_allocation(
  doctor_id INT(20) UNSIGNED NOT NULL ,
  patient_id INT (20) UNSIGNED NOT NULL,
  hospital_id INT(20) UNSIGNED NOT NULL,
  created TIMESTAMP NOT NULL,
  expires TIMESTAMP NOT NULL,

  CONSTRAINT fk_doctor_allocation_patient_id FOREIGN KEY (patient_id) REFERENCES patient_profile(patient_id),
  CONSTRAINT fk_doctor_allocation_doctor_id FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id),
    CONSTRAINT fk_doctor_allocation_hospital_id FOREIGN KEY (hospital_id) REFERENCES hospital(hospital_id)
);

CREATE TABLE IF NOT EXISTS symptoms(
  symptom_id INT (3) UNSIGNED NOT NULL AUTO_INCREMENT,
  symptom VARCHAR(100) NOT NULL ,

  PRIMARY KEY (symptom_id)
);

CREATE TABLE IF NOT EXISTS complaint(
  patient_id INT (20) UNSIGNED NOT NULL,
  symptom_id INT (3) UNSIGNED NULL,
  symptom_id2 INT (3) UNSIGNED NULL,
  symptom_id3 INT (3) UNSIGNED NULL,
  symptom_id4 INT (3) UNSIGNED NULL,
  symptom_id5 INT (3) UNSIGNED NULL,
  symptom_id6 INT (3) UNSIGNED NULL,
  symptom_id7 INT (3) UNSIGNED NULL,
  symptom_id8 INT (3) UNSIGNED NULL,
  others VARCHAR(200) ,
  created TIMESTAMP NOT NULL,

  CONSTRAINT fk_complaint_patient_id FOREIGN KEY (patient_id) REFERENCES patient_profile(patient_id),

  CONSTRAINT fk_complaint_symptom_id FOREIGN KEY (symptom_id) REFERENCES symptoms(symptom_id),
  CONSTRAINT fk_complaint_symptom_id2 FOREIGN KEY (symptom_id2) REFERENCES symptoms(symptom_id),
  CONSTRAINT fk_complaint_symptom_id3 FOREIGN KEY (symptom_id3) REFERENCES symptoms(symptom_id),
  CONSTRAINT fk_complaint_symptom_id4 FOREIGN KEY (symptom_id4) REFERENCES symptoms(symptom_id),
  CONSTRAINT fk_complaint_symptom_id5 FOREIGN KEY (symptom_id5) REFERENCES symptoms(symptom_id),
  CONSTRAINT fk_complaint_symptom_id6 FOREIGN KEY (symptom_id6) REFERENCES symptoms(symptom_id),
  CONSTRAINT fk_complaint_symptom_id7 FOREIGN KEY (symptom_id7) REFERENCES symptoms(symptom_id),
  CONSTRAINT fk_complaint_symptom_id8 FOREIGN KEY (symptom_id8) REFERENCES symptoms(symptom_id)
);

CREATE TABLE IF NOT EXISTS patient_complaint_history(
  patient_id INT (20) UNSIGNED NOT NULL,
  symptom_id INT (3) UNSIGNED NOT NULL,
  created TIMESTAMP NOT NULL,

  CONSTRAINT fk_patient_complaint_history_patient_id FOREIGN KEY (patient_id) REFERENCES patient_profile(patient_id),
  CONSTRAINT fk_patient_complaint_history_symptom_id FOREIGN KEY (symptom_id) REFERENCES symptoms(symptom_id)
);

CREATE TABLE IF NOT EXISTS states(
  state_id INT (20) UNSIGNED NOT NULL AUTO_INCREMENT,
  state VARCHAR(30) NOT NULL,

  PRIMARY KEY (state_id)
);

CREATE TABLE IF NOT EXISTS state_lga(
  state_id INT (20) UNSIGNED NOT NULL,
  lga VARCHAR(30) NOT NULL,

  CONSTRAINT fk_state_lga_state_id FOREIGN KEY (state_id) REFERENCES states(state_id)
);

