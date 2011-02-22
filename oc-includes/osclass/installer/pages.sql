INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (1, 'email_item_inquiry', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (2, 'email_user_validation', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (3, 'email_user_registration', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (4, 'email_send_friend', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (5, 'alert_email_hourly', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (6, 'alert_email_daily', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (7, 'alert_email_weekly', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (8, 'alert_email_instant', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (9, 'email_new_comment_admin', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (10, 'email_new_item_non_register_user', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (11, 'email_item_validation', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (12, 'email_admin_new_item', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (13, 'email_user_forgot_password', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (14, 'email_new_email', 1, NOW() );


INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (1, 'en_US', 'Someone has a question about your item', '<p>Hi {CONTACT_NAME}!</p>\r\n<p>{USER_NAME} with e-mail {USER_EMAIL} and phone number {USER_PHONE} left you this message about your item {ITEM_NAME}:</p>\r\n<p>{COMMENT}</p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (2, 'en_US', 'You must validate your account', '<p>Hi {USER_NAME},</p>\r\n<p>Your registration must be validated. Please click the following link:\r\n{VALIDATION_LINK}</p>\r\n\r\n<p>Thank you!</p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (3, 'en_US', 'Successful registration!', '<p>Hi {USER_NAME},</p>\r\n<p> </p>\r\n<p>This email confirms you the successful registration in {WEB_TITLE}.</p>\r\n<p> </p>\r\n<p>Thank you!</p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (4, 'en_US', 'Look at what I discovered at {WEB_TITLE}', '<p>Hi {FRIEND_NAME},</p>\r\n<p> </p>\r\n<p>Your friend {USER_NAME} wants to share this item with you:</p>\r\n<p>{ITEM_URL}</p>\r\n<p>Message:</p>\r\n<p>{COMMENT}</p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (5, 'en_US', 'There are new ads!', '<p>Hi {USER_NAME}!</p>\r\n<p> </p>\r\n<p>There are new ads since the last hour. Take a look at them :</p>\r\n<p> </p>\r\n<p>{ADS}</p>\r\n<p> </p><p><br />-------------</p><p>If you want to unsubscribe from this alert, click on : {UNSUB_LINK}</p></p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (6, 'en_US', 'There are new ads!', '<p>Hi {USER_NAME}!</p>\r\n<p> </p>\r\n<p>There are new ads since the last day. Take a look at them :</p>\r\n<p> </p>\r\n<p>{ADS}</p>\r\n<p> </p><p><br />-------------</p><p>If you want to unsubscribe from this alert, click on : {UNSUB_LINK}</p></p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (7, 'en_US', 'There are new ads!', '<p>Hi {USER_NAME}!</p>\r\n<p> </p>\r\n<p>There are new ads since the last week. Take a look at them :</p>\r\n<p> </p>\r\n<p>{ADS}</p>\r\n<p> </p><p><br />-------------</p><p>If you want to unsubscribe from this alert, click on : {UNSUB_LINK}</p></p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (8, 'en_US', 'There are new ads!', '<p>Hi {USER_NAME}!</p>\r\n<p> </p>\r\n<p>There is a new ad, take a look at it:</p>\r\n<p> </p>\r\n<p>{ADS}</p>\r\n<p> </p><p><br />-------------</p><p>If you want to unsubscribe from this alert, click on : {UNSUB_LINK}</p></p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (9, 'en_US', '[ {WEB_TITLE} ] New comment on the ad with ID. {ITEM_ID} ', '<p>There\'s a new comment on the ad with ID. {ITEM_ID} <br />URL: {ITEM_URL}</p>\r\n<p>Title: {COMMENT_TITLE}<br />Comment: {COMMENT_TEXT}<br />Author: {COMMENT_AUTHOR}<br />Author\'s email: {COMMENT_EMAIL}</p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (10, 'en_US', '[ {WEB_TITLE} ] Edit options with the ad with ID. {ITEM_ID} ', '<p> </p>\r\n<div>\r\n<p>Hi {USER_NAME},</p>\r\n<p>You''re not registered at {WEB_TITLE}, but you could still edit or delete the item: {ITEM_TITLE} for a short period of time.</p>\r\n<p> </p>\r\n<p>You could edit your item following this link : {EDIT_LINK}</p>\r\n<p>You could delete your item following this link : {DELETE_LINK}</p>\r\n<p> </p>\r\n<p>You could have full access to editing options if you register as a user to post items.</p>\r\n<p> </p>\r\n<p>Regards</p>\r\n</div>\r\n<p> </p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (11, 'en_US', '[ {WEB_TITLE} ] Validate your ad', '<p>Dear {USER_NAME}</p>\r\n<p>You''re receiving this email because an Ad is being placed at {WEB_TITLE}. You are requested to validate this item with the link at the end of the email. If you didn''t place this ad, please ignore this email. Details of the ad:</p>\r\n<p>Contact name: {USER_NAME}<br />Contact E-mail: {USER_EMAIL}</p>\r\n<p> </p>\r\n<p>{ITEM_DESCRIPTION_ALL_LANGUAGES}</p>\r\n<p>Price: {ITEM_PRICE}<br />Country: {ITEM_COUNTRY}<br />Region: {ITEM_REGION}<br />City: {ITEM_CITY}<br />Url: {ITEM_URL}<br /><br />You can validate you ad in this url : {VALIDATION_LINK}</p>\r\n<p> </p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (12, 'en_US', '[ {WEB_TITLE} ] New item', '<p> </p>\r\n<div>\r\n<p>Dear admin of {WEB_TITLE},</p>\r\n<p>You''re receiving this email because an Ad is being placed at {WEB_TITLE}. Details of the ad:</p>\r\n<p>Contact name: {USER_NAME}<br />Contact E-mail: {USER_EMAIL}</p>\r\n<p> </p>\r\n<p>{ITEM_DESCRIPTION_ALL_LANGUAGES}</p>\r\n<p>Price: {ITEM_PRICE}<br />Country: {ITEM_COUNTRY}<br />Region: {ITEM_REGION}<br />City: {ITEM_CITY}<br />Url: {ITEM_URL}<br /><br />You can edit this item with the following link : {EDIT_LINK}</p>\r\n</div>\r\n<p> </p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (13, 'en_US', '{WEB_TITLE} Recover your password', '<p>Hi {USER_NAME},</p>\r\n<p> </p>\r\n<p>We sent this e-mail because you forgot your password. Follow the link to recover it : {PASSWORD_LINK}</p>\r\n<p>The link will be disabled in 24 hours.</p>\r\n<p> </p>\r\n<p>If you didn''t forget your password, ignore this message. This petition was made from IP : {IP_ADDRESS} on {DATE_TIME}</p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (14, 'en_US', '[ {WEB_TITLE} ] You requested to change your email', '<p>\r\n<p>Dear {USER_NAME}</p>\r\n<p>You''re receiving this email because you requested to change your e-mail. You need to confirm this new e-mail address by clicking on the following validation link : {VALIDATION_LINK}</p>\r\n</p>');

