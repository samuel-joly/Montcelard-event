CREATE TABLE event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(400) NOT NULL,

    orga_name VARCHAR(150) NOT NULL,
    orga_mail VARCHAR(200) NOT NULL,
    orga_tel VARCHAR(10) NOT NULL,

    host_name VARCHAR(500) NOT NULL,
    guests INT NOT NULL ,

    start_date DATE NOT NULL,
    end_date DATE NOT NULL,

    pause_date INT default 0 ,
    # Contains how many day are between start_date and each pause_date.
    # Format "13" means: there is 2 pauses:
    # - first pause is 1 day after the start_date
    # - second pause is 3 days after the start_date

    start_hour VARCHAR(5) NOT NULL,
    end_hour VARCHAR(5) NOT NULL,
    start_hour_offset VARCHAR(34) NOT NULL,
    end_hour_offset VARCHAR(34) NOT NULL,
    # Format "1+3.25;2-2.5;3-1;5+0.5" means there is 3 date with
    # modified start/end hour:
    # - "1+3.25" means the first date end with 3.25 hours more
    #   than end_hour 
    # - "2-2" means the second date end with 2 hours less 
    # - "3-1" means the third date end with 1 hour less 

    room_configuration VARCHAR(30) NOT NULL,
    configuration_size INT,
    configuration_quantity INT,
    room_configuration_precision VARCHAR(400),

    host_table BOOLEAN NOT NULL DEFAULT TRUE,
    paperboard INT NOT NULL DEFAULT 0,
    chair_sup INT NOT NULL DEFAULT 0 ,
    table_sup INT NOT NULL DEFAULT 0 ,
    pen BOOLEAN NOT NULL DEFAULT FALSE,
    paper BOOLEAN NOT NULL DEFAULT FALSE,
    scissors BOOLEAN NOT NULL DEFAULT FALSE,
    scotch BOOLEAN NOT NULL DEFAULT FALSE,
    post_it_xl BOOLEAN NOT NULL DEFAULT FALSE,
    paper_a1 BOOLEAN NOT NULL DEFAULT FALSE,
    bloc_note BOOLEAN NOT NULL DEFAULT FALSE,
    gomette BOOLEAN NOT NULL DEFAULT FALSE,
    post_it BOOLEAN NOT NULL DEFAULT FALSE,

    coffee_groom INT NOT NULL DEFAULT 0,
    meal INT NOT NULL DEFAULT 0,
    meal_precision VARCHAR(400),
    morning_coffee INT NOT NULL DEFAULT 0,
    afternoon_coffee INT NOT NULL DEFAULT 0,
    coktail INT NOT NULL DEFAULT 0,
    vegetarian INT NOT NULL DEFAULT 0,
    gluten_free INT NOT NULL DEFAULT 0,

    CONSTRAINT date_in_order CHECK (start_date < end_date),
    CONSTRAINT guest_not_zero CHECK (guests>0),
    CONSTRAINT pause_date_limit CHECK (pause_date<124),
    CONSTRAINT chair_sup_limit CHECK (chair_sup<=6),
    CONSTRAINT table_sup_limit CHECK (table_sup<=4)
)
