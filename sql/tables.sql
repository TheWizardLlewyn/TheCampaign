CREATE TABLE `users` (
 `user_id` int(11) NOT NULL AUTO_INCREMENT,
 `email` varchar(255) NOT NULL,
 `password` varchar(255) NOT NULL,
 `name` varchar(255) NOT NULL,
 `strength` int(11) NOT NULL,
 `creativity` int(11) NOT NULL,
 `stealth` int(11) NOT NULL,
 `integrity` int(11) NOT NULL,
 `investigation` int(11) NOT NULL,
 `charm` int(11) NOT NULL,
 `fundraising` int(11) NOT NULL,
 `intimidation` int(11) NOT NULL,
 `manipulation` int(11) NOT NULL,
 `job_id` int(11) NOT NULL,
 `party_id` int(11) NOT NULL,
 PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8

CREATE TABLE `the_campaign`.`jobs` (
`job_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`job_name` VARCHAR( 255 ) NOT NULL ,
`job_description` TEXT NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `the_campaign`.`parties` (
`party_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`party_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`party_description` TEXT NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `the_campaign`.`scenarios` (
`scenario_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user_id` INT NOT NULL ,
`job_id` INT NOT NULL ,
`description` VARCHAR( 2047 ) NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL ,
`scenario_status` INT NOT NULL ,
`scenario_date` DATE NOT NULL ,
INDEX ( `user_id` )
) ENGINE = MYISAM ;

CREATE TABLE `the_campaign`.`scenario_options` (
`scenario_option_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`scenario_id` INT NOT NULL ,
`scenario_option_description` VARCHAR( 255 ) NOT NULL ,
`scenario_option_result` TEXT NOT NULL ,
INDEX ( `scenario_id` )
) ENGINE = MYISAM ;

CREATE TABLE `the_campaign`.`scenario_descriptions` (
`scenario_description_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`severity_scale` TINYINT NOT NULL ,
`scenario_description` VARCHAR( 512 ) NOT NULL
) ENGINE = MYISAM ;