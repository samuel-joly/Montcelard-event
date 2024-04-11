CREATE TABLE event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roomId INT NULL,
    name VARCHAR(400) NOT NULL,
    orgaName VARCHAR(150) NOT NULL,
    orgaMail VARCHAR(200) NOT NULL,
    orgaTel VARCHAR(10) NOT NULL,
    hostName VARCHAR(500) NOT NULL,
    guests INT NOT NULL ,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    pauseDate INT default 0 ,
    # Contains how many day are between start_date and each pause_date.
    # Format "13" means: there is 2 pauses:
    # - first pause is 1 day after the start_date
    # - second pause is 3 days after the start_date
    startHour VARCHAR(5) NOT NULL,
    endHour VARCHAR(5) NOT NULL,
    startHourOffset VARCHAR(34) NOT NULL,
    endHourOffset VARCHAR(34) NOT NULL,
    # Format "1+3.25;2-2.5;3-1;5+0.5" means there is 3 date with
    # modified start/end hour:
    # - "1+3.25" means the first date end with 3.25 hours more
    #   than end_hour 
    # - "2-2" means the second date end with 2 hours less 
    # - "3-1" means the third date end with 1 hour less 
    roomConfiguration VARCHAR(30) NOT NULL,
    configurationSize INT,
    configurationQuantity INT NULL,
    roomConfigurationPrecision VARCHAR(400),
    hostTable BOOLEAN NOT NULL DEFAULT TRUE,
    paperboard INT NOT NULL DEFAULT 0,
    chairSup INT NOT NULL DEFAULT 0 ,
    tableSup INT NOT NULL DEFAULT 0 ,
    pen BOOLEAN NOT NULL DEFAULT FALSE,
    paper BOOLEAN NOT NULL DEFAULT FALSE,
    scissors BOOLEAN NOT NULL DEFAULT FALSE,
    scotch BOOLEAN NOT NULL DEFAULT FALSE,
    postItXl BOOLEAN NOT NULL DEFAULT FALSE,
    paperA1 BOOLEAN NOT NULL DEFAULT FALSE,
    blocNote BOOLEAN NOT NULL DEFAULT FALSE,
    gomette BOOLEAN NOT NULL DEFAULT FALSE,
    postIt BOOLEAN NOT NULL DEFAULT FALSE,
    coffeeGroom INT NOT NULL DEFAULT 0,
    meal INT NOT NULL DEFAULT 0,
    mealPrecision VARCHAR(400),
    morningCoffee INT NOT NULL DEFAULT 0,
    afternoonCoffee INT NOT NULL DEFAULT 0,
    coktail INT NOT NULL DEFAULT 0,
    vegetarian INT NOT NULL DEFAULT 0,
    glutenFree INT NOT NULL DEFAULT 0,

    FOREIGN KEY (roomId) REFERENCES room(id),
    CONSTRAINT dateInOrder CHECK (startDate <= endDate),
    CONSTRAINT guestNotZero CHECK (guests>0),
    CONSTRAINT pauseDateLimit CHECK (pauseDate<124),
    CONSTRAINT chairSupLimit CHECK (chairSup<=6),
    CONSTRAINT tableSupLimit CHECK (tableSup<=4)
);

