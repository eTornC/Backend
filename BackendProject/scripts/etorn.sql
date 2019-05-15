
CREATE DATABASE IF NOT EXISTS etorn;

USE etorn;

-- DELETING
DROP TABLE IF EXISTS turns;
DROP TABLE IF EXISTS tills;
DROP TABLE IF EXISTS buckets;
DROP TABLE IF EXISTS queues;
DROP TABLE IF EXISTS stores;
DROP TABLE IF EXISTS configs;
DROP TABLE IF EXISTS layouts;
DROP TABLE IF EXISTS publicity;

-- CREATING TABLES

CREATE TABLE stores (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(30) NOT NULL UNIQUE,
  photo_path VARCHAR(255) DEFAULT '',
  active BOOLEAN DEFAULT 1,
  config TEXT,
  created_at DATETIME DEFAULT NOW(),
  updated_at DATETIME DEFAULT NOW()
);

CREATE TABLE tills (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  id_store INTEGER NOT NULL, -- FK
  name VARCHAR(30) NOT NULL,
  active BOOLEAN NOT NULL,
  created_at DATETIME DEFAULT NOW(),
  updated_at DATETIME DEFAULT NOW()
);

CREATE TABLE queues (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  id_store INTEGER NOT NULL,
  type VARCHAR(50) NOT NULL,
  created_at DATETIME DEFAULT NOW(),
  updated_at DATETIME DEFAULT NOW()
);
CREATE TABLE buckets (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  id_queue INTEGER NOT NULL,
  hour_start DATETIME NOT NULL,
  hour_final DATETIME NOT NULL,
  quantity INTEGER(1) NOT NULL,
  filled BOOLEAN NOT NULL,
  created_at DATETIME DEFAULT NOW(),
  updated_at DATETIME DEFAULT NOW()
);

CREATE TABLE turns (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  number INTEGER,
  id_bucket INTEGER,
  state VARCHAR(10) NOT NULL,
  id_till INTEGER,
  type VARCHAR(15) NOT NULL,
  created_at DATETIME DEFAULT NOW(),
  ended_at DATETIME,
  updated_at DATETIME DEFAULT NOW()
-- atended_at?
);

CREATE TABLE configs (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  `key` VARCHAR(50) NOT NULL,
  value VARCHAR(10) NOT NULL,
  created_at DATETIME DEFAULT NOW(),
  updated_at DATETIME DEFAULT NOW()
);

CREATE TABLE layouts (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  description VARCHAR(50) NOT NULL,
  layout TEXT NOT NULL,
  type VARCHAR(15) NOT NULL,
  created_at DATETIME DEFAULT NOW(),
  updated_at DATETIME DEFAULT NOW()
);

CREATE TABLE publicity (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  description VARCHAR(50) NOT NULL,
  html TEXT NOT NULL,
  created_at DATETIME DEFAULT NOW(),
  updated_at DATETIME DEFAULT NOW()
);


-- FK --

ALTER TABLE tills ADD FOREIGN KEY (id_store) REFERENCES stores(id) ON DELETE CASCADE;

ALTER TABLE queues ADD FOREIGN KEY (id_store) REFERENCES stores(id) ON DELETE CASCADE;

ALTER TABLE buckets ADD FOREIGN KEY (id_queue) REFERENCES queues(id) ON DELETE CASCADE;

ALTER TABLE turns ADD FOREIGN KEY (id_bucket) REFERENCES buckets(id) ON DELETE CASCADE;
ALTER TABLE turns ADD FOREIGN KEY (id_till) REFERENCES tills(id) ON DELETE CASCADE;

-- config DATA
INSERT INTO configs (`key`, value) VALUES ('MIN_DURATION_BUCKETS', 5);
INSERT INTO configs (`key`, value) VALUES ('HOUR_START_ALL_BUCKETS', 9);
INSERT INTO configs (`key`, value) VALUES ('HOUR_FINAL_ALL_BUCKETS', 22);
-- INSERT INTO config (name, VALUE) VALUES ('bucket_QUANITTY)



-- Layout Template Data --
INSERT INTO `layouts` (`id`, `name`, `description`, `layout`, `type`, `created_at`, `updated_at`) VALUES (1, 'template1', 'template1', '{\"height\":\"100\",\"cols\":[{\"height\":\"100\",\"width\":12,\"rows\":[{\"height\":\"100\",\"cols\":[{\"height\":\"100\",\"width\":6,\"id\":1},{\"height\":\"100\",\"width\":6,\"id\":1}]}]}]}', 'TEMPLATE', NOW(), NOW());
INSERT INTO `layouts` (`id`, `name`, `description`, `layout`, `type`, `created_at`, `updated_at`) VALUES (2, 'template2', 'template2', '{\"height\":\"100\",\"cols\":[{\"height\":\"100\",\"width\":12,\"rows\":[{\"height\":\"50\",\"cols\":[{\"height\":\"100\",\"width\":6,\"id\":1},{\"height\":\"100\",\"width\":6,\"id\":1}]},{\"height\":\"50\",\"cols\":[{\"height\":\"100\",\"width\":6,\"id\":1},{\"height\":\"100\",\"width\":6,\"id\":1}]}]}]}', 'TEMPLATE', NOW(), NOW());
INSERT INTO `layouts` (`id`, `name`, `description`, `layout`, `type`, `created_at`, `updated_at`) VALUES (5, 'template3', 'template3', '{\"height\":\"100\",\"cols\":[{\"height\":\"100\",\"width\":12,\"rows\":[{\"height\":\"100\",\"cols\":[{\"height\":\"100\",\"width\":6,\"id\":1},{\"height\":\"100\",\"width\":6,\"rows\":[{\"height\":\"50\",\"width\":6,\"id\":1},{\"height\":\"50\",\"width\":6,\"id\":1}]}]}]}]}', 'TEMPLATE', NOW(), NOW());


-- store DATA --

-- INSERT INTO store (name, created_at) VALUES ('Carnisseria', NOW());
-- INSERT INTO store (name, created_at) VALUES ('Peixateria', NOW());
-- INSERT INTO store (name, created_at) VALUES ('Sushi', NOW());
-- INSERT INTO store (name, created_at) VALUES ('Xarcuteria', NOW());
-- INSERT INTO store (name, created_at) VALUES ('Forn de pa', NOW());

-- queue DATA --

-- INSERT INTO queue (id_store, queue_type, priority) VALUES (1, 'VIP', 0);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (1, 'BUCKET_QUEUE', 1);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (1, 'NORMAL', 2);

-- INSERT INTO queue (id_store, queue_type, priority) VALUES (2, 'VIP', 0);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (2, 'bucket_queue', 1);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (2, 'NORMAL', 2);

-- INSERT INTO queue (id_store, queue_type, priority) VALUES (3, 'VIP', 0);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (3, 'bucket_queue', 1);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (3, 'NORMAL', 2);

-- INSERT INTO queue (id_store, queue_type, priority) VALUES (4, 'VIP', 0);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (4, 'bucket_queue', 1);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (4, 'NORMAL', 2);

-- INSERT INTO queue (id_store, queue_type, priority) VALUES (5, 'VIP', 0);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (5, 'bucket_queue', 1);
-- INSERT INTO queue (id_store, queue_type, priority) VALUES (5, 'NORMAL', 2);

-- turnS DATA --

-- INSERT INTO turn (NUMBER, id_bucket, id_USER, id_queue, DATE_turn, STATE) VALUES (1, NULL, 0, 3, NOW(), 'WAITING');
-- INSERT INTO turn (NUMBER, id_bucket, id_USER, id_queue, DATE_turn, STATE) VALUES (2, NULL, 0, 3, NOW(), 'WAITING');
-- INSERT INTO turn (NUMBER, id_bucket, id_USER, id_queue, DATE_turn, STATE) VALUES (3, NULL, 0, 3, NOW(), 'WAITING');
-- INSERT INTO turn (NUMBER, id_bucket, id_USER, id_queue, DATE_turn, STATE) VALUES (4, NULL, 0, 3, NOW(), 'WAITING');

-- INSERT INTO turn (NUMBER, id_bucket, id_USER, id_queue, DATE_turn, STATE) VALUES (1, NULL, 0, 2, NOW(), 'WAITING');
-- INSERT INTO turn (NUMBER, id_bucket, id_USER, id_queue, DATE_turn, STATE) VALUES (2, NULL, 0, 2, NOW(), 'WAITING');

-- INSERT INTO turn (NUMBER, id_bucket, id_USER, id_queue, DATE_turn, STATE) VALUES (1, NULL, 0, 1, NOW(), 'WAITING');
-- INSERT INTO turn (NUMBER, id_bucket, id_USER, id_queue, DATE_turn, STATE) VALUES (2, NULL, 0, 1, NOW(), 'WAITING');

-- till DATA --

-- INSERT INTO till (id_store, name, active) VALUES (3, 'till1', FALSE);
-- INSERT INTO till (id_store, name, active) VALUES (3, 'till2', FALSE);
-- INSERT INTO till (id_store, name, active) VALUES (3, 'till3', FALSE);

-- bucket_queue

-- INSERT INTO bucket_queue (id_store, id_destination_queue, queue_type, priority) VALUES (1, 2, 'bucket_MOBILES_turnS', 2);

-- bucketS

-- INSERT INTO bucket (id_bucket_queue, hour_start, hour_final, quantity) VALUES (1, '00:00:00', '00:05:00', 3);

-- INTERESTING QUERIES

-- -- GET DE turnS IN ORDER OF priority
-- SELECT *
-- FROM turn T JOIN queue Q ON T.id_queue=Q.id
-- WHERE T.STATE LIKE 'WAITING'
-- ORDER BY Q.priority, T.DATE_turn, T.id;

-- LAST bucket IN TIME

/*
SELECT *
FROM bucket
WHERE hour_start = (SELECT MAX(hour_start)
                    FROM bucket
                    WHERE id_bucket_queue = 1)
      AND hour_final = (SELECT MAX(hour_final)
                        FROM bucket
                        WHERE id_bucket_queue = 1)
      AND bucket.id_bucket_queue = 1;


*/

