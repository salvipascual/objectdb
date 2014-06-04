CREATE TABLE student (
	id_student INT(11) KEY AUTO_INCREMENT,
	identification_number VARCHAR(11) NOT NULL,
	name varchar(50) NOT NULL,
	age INT(2) NULL,
	preferences TEXT
);

CREATE TABLE teacher (
	id_teacher INT(11) KEY AUTO_INCREMENT,
	professorship_number VARCHAR(11) NOT NULL,
	name varchar(50) NOT NULL,
	experience_time INT(2) NULL,
	marital_status TINYINT(1),
	last_university_payment_date DATE
);

CREATE TABLE relation_student_teacher (
	id_relation_student_teacher INT(11) KEY AUTO_INCREMENT,
	id_student INT(11),
	id_teacher INT(11)
)