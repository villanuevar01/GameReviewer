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

    Developers ||--o< Games : develops
    Categories ||--o< Games : categorizes
    Users ||--o< Reviews : writes
    Games ||--o< Reviews : receives
    Users ||--o< Purchases : makes
    Games ||--o< Purchases : purchased_in
```
  
