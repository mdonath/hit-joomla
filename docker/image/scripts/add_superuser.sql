-- add the user table record
INSERT INTO hitjoomla4.j_users
(name, username, email, password, block, sendEmail, registerDate, lastvisitDate, activation, params, lastResetTime, resetCount, otpKey, otep, requireReset)
VALUES
('Super User', 'admin', 'martijn.donath@scouting.nl', MD5('adminadminadmin'), 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', 0, '', '', 0);

-- add to the usergroup so we're superadmins
INSERT INTO hitjoomla4.j_user_usergroup_map (user_id,group_id) VALUES (1,8);

