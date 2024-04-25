-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2023 at 06:48 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"st_alphonse_primary_school\",\"table\":\"usersessions\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"teacher\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"subject\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"pupil\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"parents\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"classsubject\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"class\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"attendance\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"results\"},{\"db\":\"st_alphonse_primary_school\",\"table\":\"exam\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

--
-- Dumping data for table `pma__table_info`
--

INSERT INTO `pma__table_info` (`db_name`, `table_name`, `display_field`) VALUES
('st alphonse primary school', 'attendance', 'Attendance_Status'),
('st alphonse primary school', 'class', 'Class_Name'),
('st alphonse primary school', 'department', 'Department_Name'),
('st alphonse primary school', 'exam_board', 'ExamBoard_Name'),
('st alphonse primary school', 'headmaster', 'Headmaster_Name'),
('st alphonse primary school', 'parents', 'Parent_Name'),
('st alphonse primary school', 'subject', 'Subject_Name'),
('st alphonse primary school', 'teachers', 'Teacher_Name'),
('st_alphonse_primary_school', 'class', 'Class_Name'),
('st_alphonse_primary_school', 'parents', 'Parent_Name'),
('st_alphonse_primary_school', 'pupil', 'Pupil_Name'),
('st_alphonse_primary_school', 'subject', 'Subject_Name'),
('st_alphonse_primary_school', 'teacher', 'Teacher_Name');

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2023-12-15 17:48:01', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `st_alphonse_primary_school`
--
CREATE DATABASE IF NOT EXISTS `st_alphonse_primary_school` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `st_alphonse_primary_school`;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `Attendance_Date` date NOT NULL,
  `Attendance_Status` text NOT NULL,
  `PupilID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `Attendance_Date`, `Attendance_Status`, `PupilID`) VALUES
(25, '2023-10-23', 'Present', 18),
(26, '2023-10-26', 'Absent', 20),
(27, '2023-10-06', 'Late', 21);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `ClassID` int(11) NOT NULL,
  `Class_Name` varchar(255) NOT NULL,
  `Class_Capacity` int(11) NOT NULL,
  `Class_Room` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`ClassID`, `Class_Name`, `Class_Capacity`, `Class_Room`) VALUES
(1, 'RECEPTION YEAR', 2, 'A-1'),
(2, 'YEAR 1', 2, 'A-2'),
(3, 'YEAR 2', 2, 'A-3'),
(4, 'YEAR 3', 2, 'A-4'),
(5, 'YEAR 4', 2, 'A-5'),
(6, 'YEAR 5', 2, 'A-6'),
(7, 'YEAR 6', 2, 'A-7');

-- --------------------------------------------------------

--
-- Table structure for table `classsubject`
--

CREATE TABLE `classsubject` (
  `ClassSubjectID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classsubject`
--

INSERT INTO `classsubject` (`ClassSubjectID`, `SubjectID`, `ClassID`) VALUES
(1, 1, 2),
(2, 2, 2),
(3, 3, 2),
(4, 4, 2),
(5, 5, 2),
(6, 1, 3),
(7, 2, 3),
(8, 3, 3),
(9, 4, 3),
(10, 5, 3),
(11, 1, 4),
(12, 2, 4),
(13, 3, 4),
(14, 4, 4),
(15, 5, 4),
(16, 1, 5),
(17, 2, 5),
(18, 3, 5),
(19, 4, 5),
(20, 5, 5),
(21, 1, 6),
(22, 2, 6),
(23, 3, 6),
(24, 4, 6),
(25, 5, 6),
(26, 1, 7),
(27, 2, 7),
(28, 3, 7),
(29, 4, 7),
(30, 5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `ParentID` int(11) NOT NULL,
  `Parent_Name` varchar(255) NOT NULL,
  `Parent_Address` varchar(255) NOT NULL,
  `Parent_Email` varchar(255) NOT NULL,
  `Parent_PhoneNumber` int(11) NOT NULL,
  `Parent_DateOfBirth` date NOT NULL,
  `Parent_Gender` varchar(255) NOT NULL,
  `PupilID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`ParentID`, `Parent_Name`, `Parent_Address`, `Parent_Email`, `Parent_PhoneNumber`, `Parent_DateOfBirth`, `Parent_Gender`, `PupilID`) VALUES
(233457, 'Guillermo Garcia', '21 Arroyo Street', 'Guillermo@gmail.com', 2147483647, '1997-02-05', 'Male', 18),
(233461, 'Valerie Navarro', '22 Arroyo Street', 'Valerie@gmail.com', 2147483647, '2000-12-05', 'Female', 20),
(233462, 'Hashim Leach', '23 Arroyo Street', 'Hashim@gmail.com', 2147483647, '2000-06-05', 'Male', 21),
(233463, 'Cassandra Escobar', '24 Arroyo Street', 'Cassandra@gmail.com', 2147483647, '1998-06-13', 'Female', 22),
(233464, 'Tanya Harris', '25 Arroyo Street', 'Tanya@gmail.com', 2147483647, '1985-04-05', 'Female', 23),
(233465, 'Rueben Huber', '26 Arroyo Street', 'Rueben@gmail.com', 2147483647, '1997-12-05', 'Male', 24),
(233466, 'Madison Steward', '27 Arroyo Street', 'Madison@gmail.com', 2147483647, '1996-04-06', 'Female', 25),
(233467, 'Kane Figueroa', '28 Arroyo Street', 'Kane@gmail.com', 2147483647, '2001-04-08', 'Male', 28),
(233468, 'Kaitlyn Torres', '29 Arroyo Street', 'Kaitlyn@gmail.com', 2147483647, '1233-03-12', 'Female', 29),
(233469, 'Jose Reeves', '30 Arroyo Street', 'Jose@gmail.com', 2147483647, '1975-05-06', 'Male', 30),
(233470, 'Josh Tray', '31 Arroyo Street', 'Josh@gmail.com', 2147483647, '1998-05-06', 'Male', 31),
(233473, 'Leuan Lindsey', '32 Arroyo Street', 'Leuan@gmail.com', 2147483647, '1984-05-12', 'Female', 32),
(233477, 'Alia Sutton', '33 Arroyo Street', 'Alia@gmail.com', 2147483647, '1987-03-05', 'Female', 34),
(233489, 'Josh Tray', '31 Arroyo Street', 'Josh@gmail.com', 2147483647, '1998-05-06', 'Male', 31),
(233490, 'Guillermo Garcia', '21 Arroyo Street', 'Guillermo@gmail.com', 2147483647, '1997-02-05', 'Male', 18);

-- --------------------------------------------------------

--
-- Table structure for table `pupil`
--

CREATE TABLE `pupil` (
  `PupilID` int(11) NOT NULL,
  `Pupil_Name` varchar(255) NOT NULL,
  `Pupil_Address` varchar(255) NOT NULL,
  `Pupil_PhoneNumber` int(11) NOT NULL,
  `Pupil_DateOfBirth` date NOT NULL,
  `Pupil_MedicalInformation` varchar(255) NOT NULL,
  `Pupil_Grade` varchar(50) NOT NULL,
  `ClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pupil`
--

INSERT INTO `pupil` (`PupilID`, `Pupil_Name`, `Pupil_Address`, `Pupil_PhoneNumber`, `Pupil_DateOfBirth`, `Pupil_MedicalInformation`, `Pupil_Grade`, `ClassID`) VALUES
(18, 'Anna Thomas', '1 Arroyo Street', 2147483647, '2014-03-12', 'No Problem', '67', 1),
(20, 'Sara Sears', '2 Arroyo Street', 2147483647, '2014-03-12', 'No Problem', '57', 2),
(21, 'Natasha Torres', '3 Arroyo Street', 2147483647, '2014-03-12', 'Flu', '89', 2),
(22, 'Joshua Mcgee', '4 Arroyo Street', 2147483647, '2014-06-04', 'No Problem', '87', 3),
(23, 'Alfred Ortega', '5 Arroyo Street', 2147483647, '2014-12-04', 'No Problem', '50', 3),
(24, 'Eleni Houston', '6 Arroyo Street', 2147483647, '2014-05-08', 'No Problem', '76', 4),
(25, 'Rosalie Baker', '7 Arroyo Street', 2147483647, '2014-06-15', 'No Problem', '78', 4),
(28, 'Rosalind Cardenas', '8 Arroyo Street', 2147483647, '2014-05-06', 'No Problem', '76', 5),
(29, 'Poppy Acevedo', '9 Arroyo Street', 2147483647, '2014-12-05', 'Asthma', '87', 5),
(30, 'Doris Price', '10 Arroyo Street', 2147483647, '2014-02-04', 'No Problem', '73', 6),
(31, 'Libbie Taylor', '11 Arroyo Street', 2147483647, '2014-06-04', 'No Problem', '23', 6),
(32, 'Esme Brandt', '12 Arroyo Street', 2147483647, '2014-09-04', 'No Problem', '45', 7),
(34, 'Shivam Mcleod', '13 Arroyo Street', 2147483647, '2014-08-06', 'No Problem', '45', 7);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `SubjectID` int(11) NOT NULL,
  `Subject_Name` varchar(255) NOT NULL,
  `Subject_Timetable` varchar(255) NOT NULL,
  `Subject_StartDate` date NOT NULL,
  `Subject_EndDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`SubjectID`, `Subject_Name`, `Subject_Timetable`, `Subject_StartDate`, `Subject_EndDate`) VALUES
(1, 'MATHEMATICS', 'MONDAY TO FRIDAY 9:00AM-10:00AM', '2023-09-17', '2024-06-21'),
(2, 'ENGLISH', 'MONDAY TO FRIDAY 10:00AM-11:00AM', '2023-09-17', '2024-06-21'),
(3, 'SCIENCE', 'MONDAY TO FRIDAY 11:00AM-12:00PM', '2023-09-17', '2024-06-21'),
(4, 'PHYSICAL EDUCATION', 'MONDAY TO FRIDAY 12:00PM-1:00PM', '2023-09-17', '2024-06-21'),
(5, 'GEOGRAPHY', 'MONDAY TO FRIDAY 1:00PM-2:00PM', '2023-09-17', '2024-06-21');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `TeacherID` int(11) NOT NULL,
  `Teacher_Name` varchar(255) NOT NULL,
  `Teacher_Address` varchar(255) NOT NULL,
  `Teacher_Email` varchar(255) NOT NULL,
  `Teacher_PhoneNumber` int(11) NOT NULL,
  `Teacher_DateOfBirth` date NOT NULL,
  `Teacher_AnnualSalary` varchar(50) NOT NULL,
  `Teacher_BackgroundCheck` varchar(255) NOT NULL,
  `ClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`TeacherID`, `Teacher_Name`, `Teacher_Address`, `Teacher_Email`, `Teacher_PhoneNumber`, `Teacher_DateOfBirth`, `Teacher_AnnualSalary`, `Teacher_BackgroundCheck`, `ClassID`) VALUES
(14, 'Lilly Bowers', '14 Arroyo Street', 'Lilly@gmail.com', 2147483647, '1995-04-05', '25000', 'Clear', 1),
(15, 'Dana Mccall', '15 Arroyo Street', 'Dana@gmail.com', 2147483647, '1996-05-06', '25000', 'Clear', 2),
(16, 'Martin Bridges', '16 Arroyo Street', 'Martin@gmail.com', 2147483647, '2000-05-06', '20000', 'Pending', 3),
(21, 'Tariq Berg', '17 Arroyo Street', 'Tariq@gmail.com', 2147483647, '1998-05-06', '30000', 'Not Clear', 4),
(22, 'Ayla Solomon', '18 Arroyo Street', 'Ayla@gmail.com', 2147483647, '1985-05-16', '35000', 'Clear', 5),
(23, 'Margie Russell', '19 Arroyo Street', 'Margie@gmail.com', 2147483647, '1975-05-06', '40000', 'Clear', 6),
(24, 'Jemma Estes', '20 Arroyo Street', 'Jemma@gmail.com', 2147483647, '1980-05-06', '45000', 'Clear', 7);

-- --------------------------------------------------------

--
-- Table structure for table `usersessions`
--

CREATE TABLE `usersessions` (
  `UserSessionsID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `User_Name` varchar(255) NOT NULL,
  `User_Password` varchar(255) NOT NULL,
  `PermissionLevel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usersessions`
--

INSERT INTO `usersessions` (`UserSessionsID`, `UserID`, `User_Name`, `User_Password`, `PermissionLevel`) VALUES
(28, 8926735, 'Valerie', '$2y$10$TpupwvZ0g22ZDJUhVpq8zOESNNZhx6CTV.phKUkjfS1k8cxSUp77C', 'parent'),
(29, 19356840, 'Libbie', '$2y$10$ojq6Ns5RCxy.x/5YnYyNMeVeD/Yc/puVNeKs.w6snGLT9HHl9GzOy', 'pupil'),
(30, 73680419, 'Margie', '$2y$10$mR9T2IcoXvQTu//FP90lN..Pvknk1JnRgaDwD5GhfrvZakYbhIi2q', 'teacher'),
(33, 5869423, 'Sam', '$2y$10$R0eePWEBlS5lCJm68udGRe/QxVH7fzqp1AyTtomsLI9yV76BVTl2C', 'administration');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `attendance_ibfk_1` (`PupilID`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`ClassID`);

--
-- Indexes for table `classsubject`
--
ALTER TABLE `classsubject`
  ADD PRIMARY KEY (`ClassSubjectID`),
  ADD KEY `classsubject_ibfk_1` (`SubjectID`),
  ADD KEY `classsubject_ibfk_2` (`ClassID`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`ParentID`),
  ADD KEY `ParentID` (`ParentID`),
  ADD KEY `parents_ibfk_1` (`PupilID`);

--
-- Indexes for table `pupil`
--
ALTER TABLE `pupil`
  ADD PRIMARY KEY (`PupilID`),
  ADD KEY `pupil_ibfk_1` (`ClassID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`SubjectID`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`TeacherID`),
  ADD KEY `teacher_ibfk_1` (`ClassID`);

--
-- Indexes for table `usersessions`
--
ALTER TABLE `usersessions`
  ADD PRIMARY KEY (`UserSessionsID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `classsubject`
--
ALTER TABLE `classsubject`
  MODIFY `ClassSubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `ParentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233491;

--
-- AUTO_INCREMENT for table `pupil`
--
ALTER TABLE `pupil`
  MODIFY `PupilID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `TeacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `usersessions`
--
ALTER TABLE `usersessions`
  MODIFY `UserSessionsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`PupilID`) REFERENCES `pupil` (`PupilID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classsubject`
--
ALTER TABLE `classsubject`
  ADD CONSTRAINT `classsubject_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `subject` (`SubjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classsubject_ibfk_2` FOREIGN KEY (`ClassID`) REFERENCES `class` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_ibfk_1` FOREIGN KEY (`PupilID`) REFERENCES `pupil` (`PupilID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pupil`
--
ALTER TABLE `pupil`
  ADD CONSTRAINT `pupil_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `class` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `class` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
