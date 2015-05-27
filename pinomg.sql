-- The SQL file for the determinator server database

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure `FriendsWith`
--

CREATE TABLE IF NOT EXISTS `FriendsWith` (
  `userOne` varchar(255) DEFAULT NULL,
  `userTwo` varchar(255) DEFAULT NULL,
  KEY `userOne` (`userOne`),
  KEY `userTwo` (`userTwo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure `Polls`
--

CREATE TABLE IF NOT EXISTS `Polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `alternative_one` varchar(255) NOT NULL,
  `alternative_two` varchar(255) NOT NULL,
  `result` int(11) DEFAULT NULL,
  `questioner` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questioner` (`questioner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure `PollsAskedToUsers`
--

CREATE TABLE IF NOT EXISTS `PollsAskedToUsers` (
  `user` varchar(255) NOT NULL DEFAULT '',
  `poll` int(11) NOT NULL DEFAULT '0',
  `answer` int(11) DEFAULT NULL,
  PRIMARY KEY (`user`,`poll`),
  KEY `poll` (`poll`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Restrictions for tables
--

--
-- Restriction for table `FriendsWith`
--
ALTER TABLE `FriendsWith`
  ADD CONSTRAINT `FriendsWith_ibfk_1` FOREIGN KEY (`userOne`) REFERENCES `Users` (`username`),
  ADD CONSTRAINT `FriendsWith_ibfk_2` FOREIGN KEY (`userTwo`) REFERENCES `Users` (`username`);

--
-- Restriction for table `Polls`
--
ALTER TABLE `Polls`
  ADD CONSTRAINT `Polls_ibfk_1` FOREIGN KEY (`questioner`) REFERENCES `Users` (`username`);

--
-- Restriction for table `PollsAskedToUsers`
--
ALTER TABLE `PollsAskedToUsers`
  ADD CONSTRAINT `PollsAskedToUsers_ibfk_1` FOREIGN KEY (`user`) REFERENCES `Users` (`username`),
  ADD CONSTRAINT `PollsAskedToUsers_ibfk_2` FOREIGN KEY (`poll`) REFERENCES `Polls` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
