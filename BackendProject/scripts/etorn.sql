
CREATE TABLE STORE (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  NAME VARCHAR(30) NOT NULL UNIQUE,
  PHOTO_PATH VARCHAR(100) DEFAULT '',
  DATE_CREATED DATETIME  NOT NULL
);

CREATE TABLE TILL (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  ID_STORE INTEGER NOT NULL, -- FK
  NAME VARCHAR(30) NOT NULL,
  ACTIVE BOOLEAN NOT NULL,
  DATE_CREATED DATETIME NOT NULL
);

CREATE TABLE QUEUE (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  ID_STORE INTEGER NOT NULL,
  QUEUE_TYPE VARCHAR(14) NOT NULL,
  PRIORITY INTEGER(1) NOT NULL
);

CREATE TABLE BUCKET_QUEUE (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  ID_STORE INTEGER NOT NULL, -- FK
  ID_DESTINATION_QUEUE INTEGER NOT NULL,
  QUEUE_TYPE VARCHAR(10) NOT NULL,
  PRIORITY INTEGER(1) NOT NULL
);

CREATE TABLE BUCKET (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  ID_BUCKET_QUEUE INTEGER NOT NULL,
  HOUR_START TIME NOT NULL,
  HOUR_FINAL TIME NOT NULL,
  DATE_CREATED DATETIME DEFAULT NOW(),
  QUANTITY INTEGER(1) NOT NULL
);

CREATE TABLE TURN (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  NUMBER INTEGER NOT NULL,
  ID_BUCKET INTEGER,
  ID_USER INTEGER,
  ID_QUEUE INTEGER,
  DATE_TURN DATETIME NOT NULL,
  STATE VARCHAR(10) NOT NULL,
  ID_TILL INTEGER
);

CREATE TABLE CONFIG (
  ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  KEY VARCHAR(50) NOT NULL,
  VALUE VARCHAR(10) NOT NULL
);


-- FK --

ALTER TABLE TILL ADD FOREIGN KEY (ID_STORE) REFERENCES STORE(ID);

ALTER TABLE QUEUE ADD FOREIGN KEY (ID_STORE) REFERENCES STORE(ID);

ALTER TABLE BUCKET_QUEUE ADD FOREIGN KEY (ID_STORE) REFERENCES STORE(ID);
ALTER TABLE BUCKET_QUEUE ADD FOREIGN KEY (ID_DESTINATION_QUEUE) REFERENCES QUEUE(ID);

ALTER TABLE BUCKET ADD FOREIGN KEY (ID_BUCKET_QUEUE) REFERENCES BUCKET_QUEUE(ID);

ALTER TABLE TURN ADD FOREIGN KEY (ID_BUCKET) REFERENCES BUCKET(ID);
ALTER TABLE TURN ADD FOREIGN KEY (ID_QUEUE) REFERENCES QUEUE(ID);
ALTER TABLE TURN ADD FOREIGN KEY (ID_TILL) REFERENCES TILL(ID);

-- CONFIG DATA
INSERT INTO CONFIG (NAME, VALUE) VALUES ('MIN_DURATION_BUCKETS', 5);
INSERT INTO CONFIG (NAME, VALUE) VALUES ('HOUR_START_ALL_BUCKETS', 9);
INSERT INTO CONFIG (NAME, VALUE) VALUES ('HOUR_FINAL_ALL_BUCKETS', 22);

-- STORE DATA --

INSERT INTO STORE (NAME, DATE_CREATED) VALUES ('Carnisseria', NOW());
INSERT INTO STORE (NAME, DATE_CREATED) VALUES ('Peixateria', NOW());
INSERT INTO STORE (NAME, DATE_CREATED) VALUES ('Sushi', NOW());
INSERT INTO STORE (NAME, DATE_CREATED) VALUES ('Xarcuteria', NOW());
INSERT INTO STORE (NAME, DATE_CREATED) VALUES ('Forn de pa', NOW());

-- QUEUE DATA --

INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (1, 'VIP', 0);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (1, 'BUCKET_QUEUE', 1);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (1, 'NORMAL', 2);

INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (2, 'VIP', 0);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (2, 'BUCKET_QUEUE', 1);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (2, 'NORMAL', 2);

INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (3, 'VIP', 0);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (3, 'BUCKET_QUEUE', 1);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (3, 'NORMAL', 2);

INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (4, 'VIP', 0);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (4, 'BUCKET_QUEUE', 1);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (4, 'NORMAL', 2);

INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (5, 'VIP', 0);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (5, 'BUCKET_QUEUE', 1);
INSERT INTO QUEUE (ID_STORE, QUEUE_TYPE, PRIORITY) VALUES (5, 'NORMAL', 2);

-- TURNS DATA --

INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES (1, NULL, 0, 3, NOW(), 'WAITING');
INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES (2, NULL, 0, 3, NOW(), 'WAITING');
INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES (3, NULL, 0, 3, NOW(), 'WAITING');
INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES (4, NULL, 0, 3, NOW(), 'WAITING');

INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES (1, NULL, 0, 2, NOW(), 'WAITING');
INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES (2, NULL, 0, 2, NOW(), 'WAITING');

INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES (1, NULL, 0, 1, NOW(), 'WAITING');
INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES (2, NULL, 0, 1, NOW(), 'WAITING');

-- TILL DATA --

INSERT INTO TILL (ID_STORE, NAME, ACTIVE) VALUES (3, 'TILL1', FALSE);
INSERT INTO TILL (ID_STORE, NAME, ACTIVE) VALUES (3, 'TILL2', FALSE);
INSERT INTO TILL (ID_STORE, NAME, ACTIVE) VALUES (3, 'TILL3', FALSE);

-- BUCKET_QUEUE

INSERT INTO BUCKET_QUEUE (ID_STORE, ID_DESTINATION_QUEUE, QUEUE_TYPE, PRIORITY) VALUES (1, 2, 'BUCKET_MOBILES_TURNS', 2);

-- BUCKETS

INSERT INTO BUCKET (ID_BUCKET_QUEUE, HOUR_START, HOUR_FINAL, QUANTITY) VALUES (1, '00:00:00', '00:05:00', 3);

-- INTERESTING QUERIES

-- GET DE TURNS IN ORDER OF PRIORITY
SELECT *
FROM TURN T JOIN QUEUE Q ON T.ID_QUEUE=Q.ID
WHERE T.STATE LIKE 'WAITING'
ORDER BY Q.PRIORITY, T.DATE_TURN, T.ID;


-- DELETING

DROP TABLE TURN;
DROP TABLE TILL;
DROP TABLE BUCKET;
DROP TABLE BUCKET_QUEUE;
DROP TABLE QUEUE;
DROP TABLE STORE;
