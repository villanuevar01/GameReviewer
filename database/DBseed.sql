USE GameReviewer;

-- DEVELOPERS
INSERT INTO Developers (DeveloperName, Country)
SELECT 'Rockstar Games', 'USA'
WHERE NOT EXISTS (
    SELECT 1 FROM Developers WHERE DeveloperName = 'Rockstar Games'
);

INSERT INTO Developers (DeveloperName, Country)
SELECT 'Santa Monica Studio', 'USA'
WHERE NOT EXISTS (
    SELECT 1 FROM Developers WHERE DeveloperName = 'Santa Monica Studio'
);

INSERT INTO Developers (DeveloperName, Country)
SELECT 'Tarsier Studios', 'Sweden'
WHERE NOT EXISTS (
    SELECT 1 FROM Developers WHERE DeveloperName = 'Tarsier Studios'
);

-- CATEGORIES
INSERT IGNORE INTO Categories (CategoryName)
VALUES ('Action-Adventure'), ('Horror');

-- GAMES
INSERT INTO Games
(Title, Description, Price, ReleaseDate, DeveloperID, CategoryID)
SELECT
    'Red Dead Redemption 2',
    'Open-world western action-adventure game',
    59.99,
    '2018-10-26',
    (SELECT DeveloperID FROM Developers WHERE DeveloperName = 'Rockstar Games' LIMIT 1),
    (SELECT CategoryID FROM Categories WHERE CategoryName = 'Action-Adventure')
WHERE NOT EXISTS (
    SELECT 1 FROM Games WHERE Title = 'Red Dead Redemption 2'
);

INSERT INTO Games
(Title, Description, Price, ReleaseDate, DeveloperID, CategoryID)
SELECT
    'God of War',
    'Action-adventure game based on Norse mythology',
    39.99,
    '2018-04-20',
    (SELECT DeveloperID FROM Developers WHERE DeveloperName = 'Santa Monica Studio' LIMIT 1),
    (SELECT CategoryID FROM Categories WHERE CategoryName = 'Action-Adventure')
WHERE NOT EXISTS (
    SELECT 1 FROM Games WHERE Title = 'God of War'
);

INSERT INTO Games
(Title, Description, Price, ReleaseDate, DeveloperID, CategoryID)
SELECT
    'Little Nightmares II',
    'Puzzle-platform horror adventure game',
    29.99,
    '2021-02-11',
    (SELECT DeveloperID FROM Developers WHERE DeveloperName = 'Tarsier Studios' LIMIT 1),
    (SELECT CategoryID FROM Categories WHERE CategoryName = 'Horror')
WHERE NOT EXISTS (
    SELECT 1 FROM Games WHERE Title = 'Little Nightmares II'
);

-- VERIFY
SELECT * FROM Games;
