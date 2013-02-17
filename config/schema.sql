CREATE  TABLE IF NOT EXISTS `locations` (
  `id`          INT           NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255)  NOT NULL,
  `adress`      VARCHAR(255)  NOT NULL,
  `zip_code`    INT(5)        NOT NULL,
  `city`        VARCHAR(255)  NOT NULL,
  `phone`       VARCHAR(10)   NULL,
  `description` TEXT          NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE  TABLE IF NOT EXISTS `parties` (
  `id`          INT           NOT NULL AUTO_INCREMENT,
  `location_id` INT           NOT NULL,
  `name`        VARCHAR(255)  NOT NULL,
  `date`        DATETIME      NOT NULL,
  `message`     TEXT          NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_location_party_idx` (`location_id` ASC),
  CONSTRAINT `fk_location_party`
    FOREIGN KEY (`location_id` )
    REFERENCES `locations` (`id` )
    ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE  TABLE IF NOT EXISTS `comments` (
  `id`          INT           NOT NULL AUTO_INCREMENT,
  `location_id` INT           NOT NULL,
  `username`    VARCHAR(255)  NOT NULL,
  `body`        TEXT          NOT NULL,
  `created_at`  DATETIME      NULL,
  PRIMARY KEY (`id`) ,
  INDEX `fk_location_idx` (`location_id` ASC) ,
  CONSTRAINT `fk_location`
    FOREIGN KEY (`location_id` )
    REFERENCES `locations` (`id` )
    ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8;
