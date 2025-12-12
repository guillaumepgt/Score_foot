DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS estMembre;
DROP TABLE IF EXISTS conversation;
DROP TABLE IF EXISTS article;
DROP TABLE IF EXISTS estAmis;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS etudiant;
DROP TABLE IF EXISTS anneeScolaire;

CREATE TABLE anneeScolaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

CREATE TABLE etudiant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    photo VARCHAR(255),
    dateInscription DATE NOT NULL,
    dateModification DATE,
    description TEXT,
    idAnneeScolaire INT,
    FOREIGN KEY (idAnneeScolaire) REFERENCES anneeScolaire(id)
);

CREATE TABLE notification (
    idNotification INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('nouveau message', 'demande ajout', 'nouveau groupe', 'nouvel utilisateur') NOT NULL,
    statutLecture ENUM('oui', 'non') DEFAULT 'non',
    statutSuppression ENUM('oui', 'non') DEFAULT 'non',
    dateAjout DATETIME NOT NULL,
    idEtudiant INT,
    FOREIGN KEY (idEtudiant) REFERENCES etudiant(id)
);

CREATE TABLE estAmis (
    idEtudiant1 INT NOT NULL,
    idEtudiant2 INT NOT NULL,
    statut ENUM('en attente', 'valide', 'refuse') NOT NULL,
    dateAjout DATE,
    PRIMARY KEY (idEtudiant1, idEtudiant2),
    FOREIGN KEY (idEtudiant1) REFERENCES etudiant(id),
    FOREIGN KEY (idEtudiant2) REFERENCES etudiant(id)
);

CREATE TABLE article (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    media VARCHAR(255),
    dateCreation DATE NOT NULL,
    visibilite ENUM('public', 'ami') NOT NULL,
    idAuteur INT NOT NULL,
    FOREIGN KEY (idAuteur) REFERENCES etudiant(id)
);

CREATE TABLE conversation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    dateCreation DATE NOT NULL,
    image VARCHAR(255)
);

CREATE TABLE estMembre (
    idConversation INT NOT NULL,
    idEtudiant INT NOT NULL,
    PRIMARY KEY (idConversation, idEtudiant),
    FOREIGN KEY (idConversation) REFERENCES conversation(id),
    FOREIGN KEY (idEtudiant) REFERENCES etudiant(id)
);

CREATE TABLE message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    dateEnvoi DATETIME NOT NULL,
    idAuteur INT NOT NULL,
    idConversation INT NOT NULL,
    FOREIGN KEY (idAuteur) REFERENCES etudiant(id),
    FOREIGN KEY (idConversation) REFERENCES conversation(id)
);

INSERT INTO anneeScolaire(nom) 
values ('E1'), ('E2'), ('E3e'), ('E4e'), ('E5e'), ('E3a'), ('E4a'), ('E5a'), ('B1'), ('B2'), ('B3');

INSERT INTO etudiant(nom, prenom, photo, dateInscription, idAnneeScolaire)
VALUES 
('Morin', 'Basile', 'basile_morin.jpg', '2023-09-01', 3),
('Peloin', 'Titouan', 'titouan_peloin.jpg', '2023-09-01', 3),
('Prigent', 'Guillaume', 'guillaume_prigent.jpg', '2023-09-01',  3),
('Mainguet', 'Marius' , 'marius_mainguet.jpg', '2023-09-01', 3),
('Darde', 'Romain', 'romain_darde.jpg', '2025-09-01', 3),
('Malard', 'Lenny', 'lenny_malard.jpg', '2022-09-01', 3),
('Peyrat', 'Maxime', 'maxime_peyrat.jpg', '2023-09-01', 6),
('Quere', 'Quentin', 'quentin_quere.jpg', '2023-09-01', 6);

INSERT INTO article (contenu, media, dateCreation, visibilite, idAuteur)
VALUES
('Première publication sur le réseau. Bienvenue à tous !', NULL, '2025-01-20', 'public', 1),
('Un petit message pour prévenir que je travaille sur un projet SQL.', 'projet_sql.png', '2025-01-22', 'public', 1),
('Super séance de sport aujourd''hui !', NULL, '2025-01-25', 'public', 2),
('Quelqu''un pour un café à l''ESEO ?', NULL, '2025-01-26', 'ami', 3);

INSERT INTO estAmis (idEtudiant1, idEtudiant2, statut, dateAjout)
VALUES 
    (5, 1, 'refuse',     '2025-01-20'),
    (2, 1, 'en attente', '2025-01-21'),
    (4, 2, 'en attente', '2025-01-21'),
    (5, 3, 'en attente', '2025-01-22'),
    (1, 4, 'valide',     '2025-01-23'),
    (4, 5, 'valide',     '2025-01-23');

INSERT INTO notification (type, statutLecture, statutSuppression, dateAjout, idEtudiant)
VALUES
    ('demande ajout', 'oui', 'oui', '2025-01-27 14:30:00', 1),
    ('demande ajout', 'non', 'non', '2025-01-28 09:12:00', 1),
    ('demande ajout', 'non', 'non', '2025-01-28 09:30:00', 2),
    ('demande ajout', 'oui', 'oui', '2025-01-28 10:05:00', 3),
    ('demande ajout', 'oui', 'oui', '2025-01-28 11:20:00', 4),
    ('demande ajout', 'oui', 'non', '2025-01-28 12:45:00', 5);

INSERT INTO conversation (nom, dateCreation, image)
VALUES ('Gourmandise', '2025-01-28', NULL);

INSERT INTO estMembre (idConversation, idEtudiant)
VALUES
    (1, 1),
    (1, 2),
    (1, 3);

INSERT INTO message (contenu, dateEnvoi, idAuteur, idConversation)
VALUES
    ('Salut tout le monde, vous avancez sur le projet ?', '2025-01-28 14:02:00', 1, 1),
    ('Oui ça va, j''avance plus que toi parce que tu es un neuille.', '2025-01-28 14:03:10', 2, 1),
    ('Parfait, ON pourra partager nos requêtes ce soir ?', '2025-01-28 14:04:00', 1, 1),
    ('Oui, et les pafs aussi', '2025-01-28 14:05:30', 3, 1),
    ('neuille frère tu es gourmand', '2025-01-28 14:06:10', 2, 1);

INSERT INTO notification (type, statutLecture, statutSuppression, dateAjout, idEtudiant)
VALUES
    ('nouveau groupe', 'non', 'non', '2025-01-28 14:00:30', 1),
    ('nouveau groupe', 'oui', 'non', '2025-01-28 14:00:32', 2),
    ('nouveau groupe', 'oui', 'oui', '2025-01-28 14:00:35', 3),

    ('nouveau message', 'non', 'non', '2025-01-28 14:02:05', 2),
    ('nouveau message', 'oui', 'non', '2025-01-28 14:02:07', 3),

    ('nouveau message', 'non', 'non', '2025-01-28 14:03:15', 1),
    ('nouveau message', 'oui', 'oui', '2025-01-28 14:03:17', 3),

    ('nouveau message', 'non', 'non', '2025-01-28 14:04:05', 2),
    ('nouveau message', 'oui', 'non', '2025-01-28 14:04:07', 3),

    ('nouveau message', 'oui', 'non', '2025-01-28 14:05:35', 1),
    ('nouveau message', 'non', 'non', '2025-01-28 14:05:37', 2),

    ('nouveau message', 'non', 'non', '2025-01-28 14:06:15', 1),
    ('nouveau message', 'oui', 'non', '2025-01-28 14:06:17', 3);

INSERT INTO conversation (nom, dateCreation, image)
VALUES ('Discussion Projet Réseau', '2025-01-29', NULL);

INSERT INTO estMembre (idConversation, idEtudiant)
VALUES
    (2, 1),
    (2, 5),
    (2, 6);

INSERT INTO notification (type, statutLecture, statutSuppression, dateAjout, idEtudiant)
VALUES
    ('nouveau groupe', 'non', 'non', '2025-01-29 10:00:00', 1),
    ('nouveau groupe', 'oui', 'non', '2025-01-29 10:00:02', 5),
    ('nouveau groupe', 'oui', 'oui', '2025-01-29 10:00:04', 6);

INSERT INTO message (contenu, dateEnvoi, idAuteur, idConversation)
VALUES
    ('Salut, on fait le point sur le TP réseau ?', '2025-01-29 10:05:00', 1, 2),
    ('Oui, j''ai commencé la partie adressage IP.', '2025-01-29 10:06:10', 5, 2),
    ('Parfait, je m''occupe de la partie VLAN.', '2025-01-29 10:07:20', 6, 2);

INSERT INTO notification (type, statutLecture, statutSuppression, dateAjout, idEtudiant)
VALUES
('nouveau message', 'non', 'non', '2025-01-29 10:05:05', 5),
('nouveau message', 'oui', 'non', '2025-01-29 10:05:07', 6),

('nouveau message', 'non', 'non', '2025-01-29 10:06:15', 1),
('nouveau message', 'oui', 'oui', '2025-01-29 10:06:18', 6),

('nouveau message', 'non', 'non', '2025-01-29 10:07:25', 1),
('nouveau message', 'oui', 'non', '2025-01-29 10:07:27', 5);

SELECT * FROM etudiant AS e
JOIN anneeScolaire AS a ON e.idAnneeScolaire = a.id
WHERE a.nom = 'E3e';

SELECT ar.contenu, ar.media, e.prenom, e.nom, a.nom AS annee FROM etudiant AS e
JOIN anneescolaire AS a ON a.id=e.idAnneeScolaire
JOIN article AS ar ON ar.idAuteur=e.id
WHERE ar.visibilite='public'
ORDER BY e.nom, e.prenom;

SELECT ar.contenu, ar.media, e.prenom, e.nom, a.nom AS annee, ar.dateCreation, ar.visibilite FROM ARTICLE AS ar
JOIN etudiant AS e ON ar.idAuteur=e.id
JOIN anneescolaire AS a ON a.id=e.idAnneeScolaire
ORDER BY e.nom, ar.dateCreation DESC;

SELECT count(*) as nombre FROM etudiant;

SELECT e.id, e.prenom, e.nom, a.dateAjout
FROM estAmis AS a
JOIN etudiant AS e 
    ON a.idEtudiant1 = 4 AND a.idEtudiant2 = e.id
    OR a.idEtudiant2 = 4 AND a.idEtudiant1 = e.id
WHERE a.statut = 'valide'
ORDER BY a.dateAjout ASC;

SELECT n.* FROM notification AS n
JOIN etudiant AS e ON e.id = n.idEtudiant
WHERE idEtudiant=1
ORDER BY n.type, n.dateAjout;

SELECT m.dateEnvoi, m.contenu, e.prenom, c.nom
FROM message AS m
JOIN etudiant AS e ON m.idAuteur = e.id
JOIN conversation AS c ON m.idConversation = c.id
ORDER BY m.idConversation, m.dateEnvoi;


SELECT c.id, c.nom, c.dateCreation, COUNT(*) AS nombre_membres
FROM conversation AS c
JOIN estMembre AS em ON em.idConversation = c.id
GROUP BY c.id;
