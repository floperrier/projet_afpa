<form action="" method="post">
    <?= $form->input('name','Nom') ?>
    <?= $form->input('slug','URL') ?>
    <?php if ($category->getId() !== null): ?>
        <button class="btn btn-primary" type="submit">Modifier</button>
    <?php else: ?>
        <button class="btn btn-primary" type="submit">Créer</button>
    <?php endif ?>
</form>