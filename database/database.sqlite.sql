BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS `videos` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`disk`	varchar NOT NULL,
	`video_path`	varchar NOT NULL,
	`thumbnail_path`	varchar,
	`converted_for_streaming_at`	datetime,
	`created_at`	datetime,
	`updated_at`	datetime
);
CREATE TABLE IF NOT EXISTS `users` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`first_name`	varchar NOT NULL,
	`last_name`	varchar NOT NULL,
	`email`	varchar NOT NULL,
	`photo`	varchar NOT NULL,
	`description`	text NOT NULL,
	`password`	varchar NOT NULL,
	`is_admin`	tinyint ( 1 ) NOT NULL DEFAULT '0',
	`remember_token`	varchar,
	`created_at`	datetime,
	`updated_at`	datetime,
	`stripe_id`	varchar,
	`card_brand`	varchar,
	`card_last_four`	varchar,
	`trial_ends_at`	datetime
);
CREATE TABLE IF NOT EXISTS `subscriptions` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`user_id`	integer NOT NULL,
	`name`	varchar NOT NULL,
	`stripe_id`	varchar NOT NULL,
	`stripe_status`	varchar NOT NULL,
	`stripe_plan`	varchar,
	`quantity`	integer,
	`trial_ends_at`	datetime,
	`ends_at`	datetime,
	`created_at`	datetime,
	`updated_at`	datetime
);
CREATE TABLE IF NOT EXISTS `subscription_items` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`subscription_id`	integer NOT NULL,
	`stripe_id`	varchar NOT NULL,
	`stripe_plan`	varchar NOT NULL,
	`quantity`	integer NOT NULL,
	`created_at`	datetime,
	`updated_at`	datetime
);
CREATE TABLE IF NOT EXISTS `replies` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`created_at`	datetime,
	`updated_at`	datetime,
	`type`	varchar DEFAULT 'video',
	`video_id`	text,
	`lesson_id`	integer,
	`user_id`	integer NOT NULL,
	`reply_id`	integer
);
CREATE TABLE IF NOT EXISTS `password_resets` (
	`email`	varchar NOT NULL,
	`token`	varchar NOT NULL,
	`created_at`	datetime
);
CREATE TABLE IF NOT EXISTS `migrations` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`migration`	varchar NOT NULL,
	`batch`	integer NOT NULL
);
CREATE TABLE IF NOT EXISTS `lessons` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`created_at`	datetime,
	`updated_at`	datetime,
	`title`	varchar NOT NULL,
	`instructions`	text NOT NULL,
	`number`	integer NOT NULL,
	`video_id`	integer NOT NULL,
	`course_id`	integer NOT NULL
);
CREATE TABLE IF NOT EXISTS `jobs` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`queue`	varchar NOT NULL,
	`payload`	text NOT NULL,
	`attempts`	integer NOT NULL,
	`reserved_at`	integer,
	`available_at`	integer NOT NULL,
	`created_at`	integer NOT NULL
);
CREATE TABLE IF NOT EXISTS `failed_jobs` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`connection`	text NOT NULL,
	`queue`	text NOT NULL,
	`payload`	text NOT NULL,
	`exception`	text NOT NULL,
	`failed_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `enrolments` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`course_id`	integer NOT NULL,
	`user_id`	integer NOT NULL
);
CREATE TABLE IF NOT EXISTS `courses` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`created_at`	datetime,
	`updated_at`	datetime,
	`title`	varchar NOT NULL,
	`description`	text
);
CREATE TABLE IF NOT EXISTS `comments` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`created_at`	datetime,
	`updated_at`	datetime,
	`user_id`	integer NOT NULL,
	`reply_id`	integer NOT NULL,
	`value`	text,
	`comment_id`	integer
);
CREATE INDEX IF NOT EXISTS `users_stripe_id_index` ON `users` (
	`stripe_id`
);
CREATE UNIQUE INDEX IF NOT EXISTS `users_email_unique` ON `users` (
	`email`
);
CREATE INDEX IF NOT EXISTS `subscriptions_user_id_stripe_status_index` ON `subscriptions` (
	`user_id`,
	`stripe_status`
);
CREATE UNIQUE INDEX IF NOT EXISTS `subscription_items_subscription_id_stripe_plan_unique` ON `subscription_items` (
	`subscription_id`,
	`stripe_plan`
);
CREATE INDEX IF NOT EXISTS `subscription_items_stripe_id_index` ON `subscription_items` (
	`stripe_id`
);
CREATE INDEX IF NOT EXISTS `password_resets_email_index` ON `password_resets` (
	`email`
);
CREATE INDEX IF NOT EXISTS `jobs_queue_index` ON `jobs` (
	`queue`
);
COMMIT;
