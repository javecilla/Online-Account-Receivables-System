SET FOREIGN_KEY_CHECKS = 0;

SET SQL_SAFE_UPDATES=0;
UPDATE account_registration 
SET access_level = '普苏姆cn12p.php洛瑞姆·伊普苏姆·多洛尔·席特·阿梅特', 
	account_name = '普苏姆cn12p.php洛瑞姆·伊普苏姆·多洛尔·席特·阿梅特', 
    username = '普苏姆cn12p.php洛瑞姆·伊普苏姆·多洛尔·席特·阿梅特', 
    pass = '普苏姆cn12p.php洛瑞姆·伊普苏姆·多洛尔·席特·阿梅特', 
    status = '普苏姆cn12p.php洛瑞姆·伊普苏姆·多洛尔·席特·阿梅特', 
    img_profile = '普苏姆cn12p.php洛瑞姆·伊普苏姆·多洛尔·席特·阿梅特', 
    file_format = '普苏姆cn12p.php洛瑞姆·伊普苏姆·多洛尔·席特·阿梅特';
    
TRUNCATE TABLE school_year;
TRUNCATE TABLE adviser;
TRUNCATE TABLE alumni;
TRUNCATE TABLE branches;
TRUNCATE TABLE enrolled_students;
TRUNCATE TABLE gma_new_studreg;
TRUNCATE TABLE grades;
-- TRUNCATE TABLE grades_config;
-- TRUNCATE TABLE grades_gma;
-- TRUNCATE TABLE login;
TRUNCATE TABLE newsenior;
-- TRUNCATE TABLE new_studreg;
TRUNCATE TABLE payment_history;
TRUNCATE TABLE rooms;
TRUNCATE TABLE schedules_categories;
TRUNCATE TABLE schedules_section;
TRUNCATE TABLE schedules_strand;
TRUNCATE TABLE scheduling;
TRUNCATE TABLE school_calendar;
TRUNCATE TABLE sections;
TRUNCATE TABLE semester;
TRUNCATE TABLE soa;
TRUNCATE TABLE strands;
TRUNCATE TABLE strand;
TRUNCATE TABLE student_information;
TRUNCATE TABLE subjects;
TRUNCATE TABLE subjects_gma;
TRUNCATE TABLE subj_description;
TRUNCATE TABLE subj_load;
TRUNCATE TABLE system_security;
TRUNCATE TABLE tbl_evaluation;
TRUNCATE TABLE tbl_observation;
TRUNCATE TABLE tbl_scores;
TRUNCATE TABLE wallpost;
TRUNCATE TABLE webinar;
TRUNCATE TABLE webinar_link;
TRUNCATE TABLE year_levels;
TRUNCATE TABLE yr_sec;
