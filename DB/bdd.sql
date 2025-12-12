CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_match_id INT NOT NULL UNIQUE,
    home_team INT,
    away_team INT,
    score_home INT DEFAULT NULL,
    score_away INT DEFAULT NULL,
    status VARCHAR(50),
    match_date DATETIME,
    competition_id INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_team_id INT NOT NULL UNIQUE,
    name VARCHAR(100),
    short_name VARCHAR(50),
    tla VARCHAR(10),
    crest_url VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE competitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_competition_id INT NOT NULL UNIQUE,
    code VARCHAR(10),
    name VARCHAR(100),
    emblem_url VARCHAR(255),
    area_name VARCHAR(100),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);