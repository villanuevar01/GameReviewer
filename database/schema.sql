-- GameReviewer Database Schema
-- Video Game Store and Review Website

CREATE DATABASE IF NOT EXISTS GameReviewer;
USE GameReviewer;

CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Email VARCHAR(100) NOT NULL UNIQUE,
    DateJoined DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Developers (
    DeveloperID INT AUTO_INCREMENT PRIMARY KEY,
    DeveloperName VARCHAR(100) NOT NULL,
    Country VARCHAR(50),
    DeveloperEmail VARCHAR(100)
);

CREATE TABLE Categories (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryName VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE Games (
    GameID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(8,2) NOT NULL,
    ReleaseDate DATE,
    DeveloperID INT NOT NULL,
    CategoryID INT NOT NULL,
    FOREIGN KEY (DeveloperID)
        REFERENCES Developers(DeveloperID),
    FOREIGN KEY (CategoryID)
        REFERENCES Categories(CategoryID),
    CHECK (Price >= 0)
);

CREATE TABLE Reviews (
    ReviewID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    GameID INT NOT NULL,
    Rating INT NOT NULL,
    ReviewText TEXT,
    ReviewDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID)
        REFERENCES Users(UserID),
    FOREIGN KEY (GameID)
        REFERENCES Games(GameID),
    CHECK (Rating BETWEEN 1 AND 10)
);

CREATE TABLE Purchases (
    PurchaseID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    GameID INT NOT NULL,
    PurchaseDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TotalPrice DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (UserID)
        REFERENCES Users(UserID),
    FOREIGN KEY (GameID)
        REFERENCES Games(GameID),
    CHECK (TotalPrice >= 0)
);
