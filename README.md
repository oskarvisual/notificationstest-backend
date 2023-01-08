# Configuration file

You must add a config.php file in the root of the project, you can copy or rename the config.sample.php file.

You must add the PDO connection data of the database.

`define('APP_DIR', __DIR__);`
`define('BASE_URL', '/basepath');`
`define('DB_HOST', 'localhost');`
`define('DB_NAME', 'database');`
`define('DB_USERNAME', 'user');`
`define('DB_PASSWORD', 'password');`

Si el proyecto se accede desde la raiz de la URL, debe dejar la constante BASE_URL en blanco, caso contrario debe ingresar el directorio en la costante, comenzando con un "/"

## Database

Create the tables and enter the sample data

`CREATE TABLE categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
);`

`INSERT INTO categories (id, title, content) VALUES 
(NULL, 'Sports', 'Sports category'), 
(NULL, 'Finances', 'Finances category'), 
(NULL, 'Movies', 'Movies category');`

`CREATE TABLE articles (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category_id INT(11) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);`

`CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone_number VARCHAR(255) NOT NULL,
    subscribed longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
    channels longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
);`

`INSERT INTO users (id, name, email, phone_number, subscribed, channels) VALUES
(1, 'Juan Perez', 'juan@gmail.com', '+999999999', '[1,2]', '[\"email\",\"sms\"]'),
(2, 'Jose Lopez', 'jose@gmail.com', '+9999999999', '[2,3]', '[\"email\"]'),
(3, 'Carolina Smith', 'carolina@gmail.com', '+999999999', '[3]', '[\"push\"]');`

`CREATE TABLE notifications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    channel VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NULL,
    sent_at DATETIME(1) NULL DEFAULT NULL,
    user_id INT(11) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);`