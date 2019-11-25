<form action="" method="post">
    <?= $form->input('name','Nom') ?>
    <?= $form->input('slug','URL') ?>
    <?= $form->textarea('content','Contenu') ?>
    <?= $form->input('created_at','Date de création') ?>
    <?php if ($post->getId() !== null): ?>
        <button class="btn btn-primary" type="submit">Modifier</button>
    <?php else: ?>
        <button class="btn btn-primary" type="submit">Créer</button>
    <?php endif ?>
</form>