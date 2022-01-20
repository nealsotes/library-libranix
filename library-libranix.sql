-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2021 at 07:30 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library-libranix`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_due_list` ()  NO SQL
SELECT I.issue_id, M.email, B.isbn, B.title
FROM bookissue I INNER JOIN memberrecords M on I.member_id = M.member_id INNER JOIN books B ON I.book_id = B.book_id
WHERE DATEDIFF(CURRENT_DATE, I.due_date) >= 0 AND DATEDIFF(CURRENT_DATE, I.due_date) % 5 = 0 AND (I.last_reminded IS NULL OR DATEDIFF(I.last_reminded, CURRENT_DATE) <> 0)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `name`) VALUES
(2, 'Amit Garg'),
(17, 'Austen, Jane'),
(29, 'Austen, Jane  4.2'),
(34, 'Brown, Sandra'),
(36, 'Crais, Robert'),
(35, 'Roberts, Nora'),
(1, 'Rowling, J. K.'),
(3, 'Sharad Kumar Verma ');

-- --------------------------------------------------------

--
-- Table structure for table `bookissue`
--

CREATE TABLE `bookissue` (
  `issue_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `last_reminded` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookissue`
--

INSERT INTO `bookissue` (`issue_id`, `member_id`, `book_id`, `due_date`, `last_reminded`) VALUES
(10, 5, 4, '2021-12-03', '2021-12-03'),
(23, 14, 4, '2021-12-04', NULL),
(24, 14, 3, '2021-12-04', NULL),
(25, 14, 3, '2021-12-04', NULL),
(26, 14, 2, '2021-12-04', NULL),
(27, 14, 2, '2021-12-04', NULL),
(28, 14, 2, '2021-12-04', NULL),
(29, 14, 2, '2021-12-04', NULL),
(30, 14, 5, '2021-12-04', NULL),
(31, 17, 3, '2021-12-04', NULL),
(32, 15, 2, '2021-12-04', NULL),
(33, 14, 5, '2021-12-04', NULL),
(34, 15, 5, '2021-12-02', '2021-12-02'),
(35, 22, 2, '2021-12-03', '2021-12-03'),
(41, 18, 2, '2021-12-04', NULL);

--
-- Triggers `bookissue`
--
DELIMITER $$
CREATE TRIGGER `issue_book` BEFORE INSERT ON `bookissue` FOR EACH ROW BEGIN
	SET NEW.due_date = DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY);
    UPDATE memberrecords SET balance = balance - (SELECT rentalPrice FROM books WHERE book_id = NEW.book_id) WHERE member_id = NEW.member_id;
    UPDATE books SET numberOfCopies = numberOfCopies - 1 WHERE book_id = NEW.book_id;
    DELETE FROM pendingbookrequests WHERE member_id = NEW.member_id AND book_id = NEW.book_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `return_book` BEFORE DELETE ON `bookissue` FOR EACH ROW BEGIN
    UPDATE memberrecords SET balance = balance + (SELECT rentalPrice FROM books WHERE book_id = OLD.book_id) WHERE member_id = OLD.member_id;
    UPDATE books SET numberOfCopies = numberOfCopies + 1 WHERE book_id = OLD.book_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `language` varchar(80) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `rentalPrice` decimal(10,2) DEFAULT NULL,
  `numberOfCopies` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `author_id`, `publisher_id`, `category_id`, `title`, `isbn`, `language`, `price`, `rentalPrice`, `numberOfCopies`) VALUES
(2, 1, 1, 1, 'Harry Potter and the Order of the Phoenix', '9780747551003', 'English', '2129.00', '20.00', 2),
(3, 2, 2, 2, 'Data Structure Using C', '9789350195611', 'English', '2000.00', '15.00', 0),
(4, 3, 3, 2, 'Data Structure Using C', '9789351633891', 'English', '2322.00', '15.00', 0),
(5, 3, 4, 2, 'Client Server Computing', '9789380674322', 'English', '2000.00', '20.00', 0),
(32, 17, 16, 9, 'Pride and Prejudice', '9780061964367', 'English', '3000.00', '20.00', 2),
(34, 34, 34, 34, 'Fat Tuesday', '9780446516327', 'English', '1000.00', '15.00', 2),
(36, 35, 35, 34, 'Blue Dahlia', '9780515138559', 'English', '250.00', '15.00', 2),
(38, 36, 36, 36, 'The Two Minute Rule', '9780743281614', 'English', '520.00', '15.00', 1);

--
-- Triggers `books`
--
DELIMITER $$
CREATE TRIGGER `set_rentalprice` BEFORE INSERT ON `books` FOR EACH ROW begin
	    IF(new.price > 500.00)
	    THEN
			SET new.rentalPrice=30;
        ELSE
        	SET new.rentalPrice=20;
	    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(14, 'Classics'),
(9, 'Comic Book or Graphic Novel'),
(2, 'Education'),
(1, 'Fantasy'),
(36, 'Non-fiction'),
(34, 'Romance');

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`id`, `username`, `password`) VALUES
(1, 'genesis', '93c768d0152f72bc8d5e782c0b585acc35fb0442'),
(2, 'neal', 'd033e22ae348aeb5660fc2140aec35850c4da997'),
(3, 'admin', 'neal');

-- --------------------------------------------------------

--
-- Table structure for table `memberrecords`
--

CREATE TABLE `memberrecords` (
  `member_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(80) NOT NULL,
  `lastname` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `address` varchar(255) NOT NULL,
  `program` varchar(100) NOT NULL,
  `balance` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memberrecords`
--

INSERT INTO `memberrecords` (`member_id`, `username`, `password`, `firstname`, `lastname`, `email`, `phone`, `address`, `program`, `balance`) VALUES
(5, 'uclm-13607174', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'neal', 'sotes', 'nelsotes18@gmail.com', '09323322313', 'Babag', 'BSEE', '20.69'),
(10, 'uclm-13607173', '$2y$10$zH.xln5si7CVDBVYPG2pWeeWplEHJ9Vk4', 'Bogart', 'Ko', 'bogartko@gmail.com', '09322233233', 'Cebu', 'BSCS', '200.00'),
(11, 'uclm-13607176', '$2y$10$qTfhupYmrKuIIYezU01snuOu6YfgnblqU', 'John', 'Doe', 'johnDoe@gmai.com', '98322332332', 'Babag', 'BSIS', '2333.00'),
(12, 'uclm-13607170', '$2y$10$KlgYzevZuedxnLxOln27Qesbt/EjCVmkk', 'Gigi', 'De leon', 'gigi@gmail.com', '09123232333', 'Cebu', 'BSECE', '150.00'),
(13, 'uclm-13609090', '$2y$04$cvvBxKlhLRMcOI9DhDqm0ejCtSZmRY8Ov', 'Mike', 'De La Cruz', 'mike@gmail.com', '09323332331', 'Cebu', 'BSIS', '200.00'),
(14, 'uclm-13607179', '$2y$04$6V70hB7n11AWme6NsabIzuUEJZl3zB5IDr0CEL8ZGQmgb8ENq/oW.', 'Hord', 'Ersdsd', 'neasdas@gmail.com', '09323232132', 'Cebu', 'BSECE', '0.00'),
(15, 'uclm-13606161', '$2y$04$FZKxKHlsu6EAaMcS70VYvOLe/e7DVbbVZgYpv.PDX.eJl3SsnKW/y', 'Ginger', 'Sotes', 'ginger@gmail.com', '09092369093', 'Timpolok', 'BSECE', '20.00'),
(16, 'uclm-13606162', '$2y$04$F.DmrWrYS7fojRqblXyJ8esCHiRt.2JvI8tHAan0144nBV7Gn2gOS', 'Jester ', 'Delota', 'jesterdelota@gmail.com', '09323232132', 'Cebu', 'BSIT', '60.00'),
(17, 'uclm-13607123', '$2y$04$63nODou7Qz56CKD0vdusx.DbXT5042adjHfMxwgsezUYKbcgqHHeG', 'Jaynee', 'Uy', 'jaynee@gmail.com', '09312323331', 'Cebu', 'BSCS', '0.00'),
(18, 'uclm-13607171', '$2y$04$IUjwcsjD70tARS3MpPaQVeEG7SonGoiuVo9l1nUQwyMpTSBw.7Avm', 'Nealford ', 'Sotes', 'nealfordsotes@gmail.com', '09092369093', 'Timpolok', 'BSIT', '180.00'),
(19, 'uclm-13606163', '$2y$04$4P3DC17AICCvKTNgRhCXyuqx.9wHzCEv7P8.OgQ2XZYzC5qjoLW2G', 'Mica', 'Go', 'uhjhugtbwmssfxfnui@bvhrk.com', '09323332331', 'Cebu', 'BSIT', '200.00'),
(20, 'test', '$2y$04$ejHWu6M6nP4mNl1BJZbXD.PEY6XQEvsvNxL1DTWXdMY.gOg4FFEI.', 'test', 'test', 'wzl97622@cuoly.com', '09323332331', 'test', 'BSCS', '200.00'),
(21, 'test1', '$2y$04$86yxeRycOJHdqQ5dxCl4weJxBhA/.ah2yZj/5Nbd9iS.dY8msfYia', 'test', 'test', 'rhn69633@zwoho.com', '09323232132', 'Cebu', 'BSIT', '222.00'),
(22, 'admin', '$2y$04$ASnJ.ENzL1N9JDl3vqoYpOFm9YCcry5vC2eOGPKwJ3EqSBvPLJXOO', 'neadasd', 'asd', 'wfy41943@cuoly.com', '09123232333', 'Babag', 'BSIT', '2203.00');

--
-- Triggers `memberrecords`
--
DELIMITER $$
CREATE TRIGGER `add_member` AFTER INSERT ON `memberrecords` FOR EACH ROW DELETE FROM pendingusers WHERE username = NEW.username
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `remove_member` AFTER DELETE ON `memberrecords` FOR EACH ROW DELETE FROM pendingbookrequests WHERE member_id = OLD.member_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_temp`
--

CREATE TABLE `password_reset_temp` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `password_reset_temp`
--
DELIMITER $$
CREATE TRIGGER `remove_temp_member` AFTER UPDATE ON `password_reset_temp` FOR EACH ROW BEGIN 
	DELETE FROM password_reset_temp WHERE 	old.id = new.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pendingbookrequests`
--

CREATE TABLE `pendingbookrequests` (
  `request_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `dateRequested` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pendingusers`
--

CREATE TABLE `pendingusers` (
  `reg_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(80) NOT NULL,
  `lastname` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `address` varchar(255) NOT NULL,
  `program` varchar(100) NOT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `reg_date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE `publishers` (
  `publisher_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`publisher_id`, `name`) VALUES
(1, ' Scholastic Inc '),
(16, 'HarperTeen'),
(35, 'Jove'),
(2, 'Reader\'s Zone'),
(36, 'Simon & Schuster'),
(4, 'Sun India Publications, New Delhi'),
(3, 'Thakur Publications Lucknow'),
(34, 'Warner Books');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `bookissue`
--
ALTER TABLE `bookissue`
  ADD PRIMARY KEY (`issue_id`,`member_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`,`isbn`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `publisher_id` (`publisher_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `librarian`
--
ALTER TABLE `librarian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `memberrecords`
--
ALTER TABLE `memberrecords`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `password_reset_temp`
--
ALTER TABLE `password_reset_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendingbookrequests`
--
ALTER TABLE `pendingbookrequests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `pendingusers`
--
ALTER TABLE `pendingusers`
  ADD PRIMARY KEY (`reg_id`,`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`publisher_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `bookissue`
--
ALTER TABLE `bookissue`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `librarian`
--
ALTER TABLE `librarian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `memberrecords`
--
ALTER TABLE `memberrecords`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `password_reset_temp`
--
ALTER TABLE `password_reset_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pendingbookrequests`
--
ALTER TABLE `pendingbookrequests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `pendingusers`
--
ALTER TABLE `pendingusers`
  MODIFY `reg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `publishers`
--
ALTER TABLE `publishers`
  MODIFY `publisher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookissue`
--
ALTER TABLE `bookissue`
  ADD CONSTRAINT `bookissue_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `memberrecords` (`member_id`),
  ADD CONSTRAINT `bookissue_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`publisher_id`),
  ADD CONSTRAINT `books_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `pendingbookrequests`
--
ALTER TABLE `pendingbookrequests`
  ADD CONSTRAINT `pendingbookrequests_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `memberrecords` (`member_id`),
  ADD CONSTRAINT `pendingbookrequests_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
