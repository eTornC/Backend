
CREATE TABLE STORE (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  NAME VARCHAR(30) NOT NULL,
  DATE_CREATED DATETIME  NOT NULL
);

CREATE TABLE QUEUE (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  ID_STORE INTEGER NOT NULL,
  QUEUE_TYPE VARCHAR(10) NOT NULL
);

CREATE TABLE BUCKET (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  ID_QUEUE INTEGER NOT NULL,
  HOUR_FINAL DATETIME NOT NULL,
  QUANTITY INTEGER(1) NOT NULL
);

CREATE TABLE TURN (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  NUMBER INTEGER NOT NULL,
  ID_BUCKET INTEGER,
  ID_USER INTEGER,
  ID_QUEUE INTEGER,
  DATE_TURN DATETIME NOT NULL,
  STATE VARCHAR(10) NOT NULL
);

ALTER TABLE QUEUE ADD FOREIGN KEY (ID_STORE) REFERENCES STORE(ID);
ALTER TABLE BUCKET ADD FOREIGN KEY (ID_QUEUE) REFERENCES QUEUE(ID);

ALTER TABLE TURN ADD FOREIGN KEY (ID_BUCKET) REFERENCES BUCKET(ID);
ALTER TABLE TURN ADD FOREIGN KEY (ID_QUEUE) REFERENCES QUEUE(ID);