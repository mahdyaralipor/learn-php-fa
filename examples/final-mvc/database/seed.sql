-- کاربر پیش‌فرض: admin / secret
-- هش با password_hash('secret', PASSWORD_DEFAULT) ساخته شده
INSERT OR IGNORE INTO users (username, password_hash)
VALUES (
    'admin',
    '$2y$12$d6.dTqgmYMeh7ePl7Vg8lOmCIUkDBW3RonEolqs6LNYvujU7B.Xb6'
);

INSERT OR IGNORE INTO posts (title, body, user_id)
VALUES (
    'اولین پست نمونه',
    'این یک پست آزمایشی است. می‌توانید پست‌های جدید بسازید یا این را حذف کنید.',
    1
);
