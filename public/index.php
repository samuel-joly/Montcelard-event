<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Fich-o-matic</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <form>
            <div id="organisateur">
                <span>
                    <input type="text" name="nom-organisateur-1" required>
                    <input type="text" name="tel-organisateur-1" required>
                </span>
                <span>
                    <input type="text" name="nom-organisateur-2">
                    <input type="text" name="tel-organisateur-2">
                </span>
            </div>
            <input type="text" name="nom-evenement" required>
            <select name="forfait" required>
                <option default>Ébène</option>
                <option>Acajou</option>
                <option>Frêne</option>
                <option>Secquoia</option>
            </select>
        </form>
    </body>
    <footer>
    </footer>
</html>
