ALTER TABLE users
ADD COLUMN email_verified_at TIMESTAMP DEFAULT NULL,
    ADD COLUMN first_login_at TIMESTAMP DEFAULT NULL,
    ADD COLUMN profile_img VARCHAR(255) DEFAULT NULL,
    ADD COLUMN created_by INT DEFAULT NULL,
    ADD COLUMN updated_by INT DEFAULT NULL;
-- Add foreign key constraints for self-referencing columns
ALTER TABLE users
ADD CONSTRAINT fk_created_by FOREIGN KEY (created_by) REFERENCES users(user_id),
    ADD CONSTRAINT fk_updated_by FOREIGN KEY (updated_by) REFERENCES users(user_id);