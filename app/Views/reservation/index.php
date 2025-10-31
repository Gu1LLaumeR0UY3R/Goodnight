
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver - <?php echo htmlspecialchars($bien['designation_bien']); ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main class="container">
        <h1>Réserver le bien : <?php echo htmlspecialchars($bien['designation_bien']); ?></h1>
        <p>Adresse : <?php echo htmlspecialchars($bien['rue_biens']); ?></p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/reservation/store" method="POST" class="form-reservation">
            <input type="hidden" name="id_biens" value="<?php echo htmlspecialchars($bien['id_biens']); ?>">
            
            <div class="form-group">
                <label for="date_debut">Date de début :</label>
                <input type="date" id="date_debut" name="date_debut" required 
                       value="<?php echo htmlspecialchars($old_input['date_debut'] ?? date('Y-m-d')); ?>"
                       min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="date_fin">Date de fin :</label>
                <input type="date" id="date_fin" name="date_fin" required 
                       value="<?php echo htmlspecialchars($old_input['date_fin'] ?? date('Y-m-d', strtotime('+7 days'))); ?>"
                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
        </form>
    </main>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
