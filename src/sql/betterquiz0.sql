
CREATE TABLE IF NOT EXISTS user (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  fullname varchar(250) NOT NULL,
  email varchar(100) DEFAULT NULL,
  mobile varchar(100) DEFAULT NULL,
  hash varchar(256) NOT NULL,
  regdate datetime NOT NULL,
  merge_with bigint(20) DEFAULT NULL,
  is_admin tinyint(1) DEFAULT 0,
  reset_request_code varchar(40) DEFAULT NULL,
  reset_request_valid datetime DEFAULT NULL,
  PRIMARY KEY (id),
  KEY merge_with (merge_with),
  CONSTRAINT user_ibfk_1 FOREIGN KEY (merge_with) REFERENCES user (id) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quiz (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  title varchar(1024) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS question (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  quiz_id bigint(20) NOT NULL,
  question_text text NOT NULL,
  question_number int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY quiz_id (quiz_id),
  CONSTRAINT question_ibfk_1 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS exam (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  quiz_id bigint(20) NOT NULL,
  user_id bigint(20) NOT NULL,
  startdate datetime NOT NULL,
  enddate datetime DEFAULT NULL,
  submitted tinyint(4) DEFAULT 0,
  PRIMARY KEY (id),
  KEY quiz_id (quiz_id),
  KEY user_id (user_id),
  CONSTRAINT exam_ibfk_1 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE,
  CONSTRAINT exam_ibfk_2 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS options (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  question_id bigint(20) NOT NULL,
  option_text text NOT NULL,
  option_number int(11) NOT NULL,
  correct tinyint(4) DEFAULT 0,
  PRIMARY KEY (id),
  KEY question_id (question_id),
  CONSTRAINT options_ibfk_1 FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS answer (
  exam_id bigint(20) NOT NULL,
  option_id bigint(20) NOT NULL,
  PRIMARY KEY (exam_id,option_id),
  KEY option_id (option_id),
  CONSTRAINT answer_ibfk_1 FOREIGN KEY (exam_id) REFERENCES exam (id) ON DELETE CASCADE,
  CONSTRAINT answer_ibfk_2 FOREIGN KEY (option_id) REFERENCES options (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quiz_meta (
  quiz_id bigint(20) NOT NULL,
  meta_key varchar(100) NOT NULL,
  meta_value varchar(100) NOT NULL,
  PRIMARY KEY (quiz_id,meta_key),
  CONSTRAINT quiz_meta_ibfk_1 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_forgot (
  uid bigint(20) NOT NULL,
  email varchar(100) DEFAULT NULL,
  mobile varchar(100) DEFAULT NULL,
  hash varchar(256) NOT NULL,
  expiry datetime NOT NULL,
  KEY uf_uid (uid),
  KEY uf_email (email,expiry),
  KEY uf_mobile (mobile,expiry)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
