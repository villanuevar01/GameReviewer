# GameReviewer
A video game store website where users can browse, purchase, and review video games.


## Data Model

```mermaid
erDiagram
    Users {
        INT UserID PK
        VARCHAR Username UK
        VARCHAR Email UK
        DATETIME DateJoined
    }
    Developers {
        INT DeveloperID PK
        VARCHAR DeveloperName
        VARCHAR Country
        VARCHAR DeveloperEmail
    }
    Categories {
        INT CategoryID PK
        VARCHAR CategoryName UK
    }
    Games {
        INT GameID PK
        VARCHAR Title
        TEXT Description
        DECIMAL Price
        DATE ReleaseDate
        INT DeveloperID FK
        INT CategoryID FK
    }
    Reviews {
        INT ReviewID PK
        INT UserID FK
        INT GameID FK
        INT Rating
        TEXT ReviewText
        DATETIME ReviewDate
    }
    Purchases {
        INT PurchaseID PK
        INT UserID FK
        INT GameID FK
        DATETIME PurchaseDate
        DECIMAL TotalPrice
    }

    Developers ||--o{ Games : develops
    Categories ||--o{ Games : categorizes
    Users ||--o{ Reviews : writes
    Games ||--o{ Reviews : receives
    Users ||--o{ Purchases : makes
    Games ||--o{ Purchases : purchased_in
```
  
## Project Check-In 1 — November 9, 2026

GameReviewer is a PHP and MariaDB website where users can browse, purchase, and review video games.

### Current Features
- Apache, PHP, and MariaDB are running.
- PHP connects to MariaDB using PDO.
- Homepage displays games from the database.
- Six database tables with primary and foreign keys.

### Database Schema
The database contains six tables: Users, Games, Developers, Categories, Reviews, and Purchases.

The Mermaid ERD above and [schema.sql](database/schema.sql) represent the current database structure. No structural changes since Week 9.

### Screenshots
- [Database Tables](screenshots/Database.png)
- [Game Records in MariaDB](screenshots/Database_Games.png)
- [Working Homepage and PDO Connection](screenshots/Homepage.png)

### Progress Summary

**Completed:**
- Database setup and PHP connection using PDO.
- Dynamic game display on the homepage.

**Incomplete:**
- User accounts, reviews, purchases, individual game pages, and styling.

**Known Issues / Blockers:**
- No known blockers. Additional CRUD features are needed.

**Next Steps:**
- Implement game pages, user accounts, reviews, purchases, and website styling.