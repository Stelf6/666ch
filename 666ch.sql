SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



CREATE TABLE `100000001` (
  `id` int(255) NOT NULL,
  `threadId` int(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `repliesTo` varchar(255) NOT NULL,
  `repliesFrom` varchar(255) NOT NULL,
  `date` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `100000001` (`id`, `threadId`, `text`, `repliesTo`, `repliesFrom`, `date`) VALUES
(1, 100000001, 'new', '', '100000002,100000003', '11/08/19 18:59:05'),
(2, 100000002, 'reply <a href=#100000001>100000001</a>', '100000001', '', '11/08/19 18:59:16'),
(3, 100000003, 'second reply  <a href=#100000001>100000001</a>', '100000001', '', '11/08/19 18:59:38');

CREATE TABLE `boards` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `threadList` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `boards` (`id`, `name`, `threadList`) VALUES
(1, 'Music', '100000001'),
(2, 'Litterature', ''),
(3, 'VisualNovel', '');

CREATE TABLE `thread` (
  `id` int(11) NOT NULL,
  `threadId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `thread` (`id`, `threadId`) VALUES
(1, 100000003);

ALTER TABLE `100000001`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `boards`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `thread`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `100000001`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `thread`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
