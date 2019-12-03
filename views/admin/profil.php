<?php

use App\Security\Auth;
use App\Connection;
use App\HTML\Form;
use App\Helper\ObjectHelper;
use App\Table\UserTable;

Auth::check();

$pdo = Connection::getPDO();
$table = new UserTable($pdo);
$user = $table->find($_SESSION['auth']);
$success = false;
$errors = [];

if (!empty($_POST)) {
    if (isset($_POST["username"], $_POST["old_password"], $_POST['new_password'])) {
        if (password_verify($_POST['old_password'],$user->getPassword())) {
            $newPassword = password_hash($_POST['new_password'],PASSWORD_BCRYPT);
            $table->update(["username" => $_POST['username'], "password" => $newPassword], $user->getId());
            ObjectHelper::hydrate($user, $_POST,['username'], 'username');
            $success = true;
        } else {
            $errors['old_password'] = "Mot de passe actuel incorrect";
        }
    }
}

$form = new Form($user,$errors);
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        Les informations de connexion ont bien été modifié
    </div>
<?php endif ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        Des erreurs ont été trouvé, merci de les corriger pour continuer
    </div>
<?php endif ?>

<h1 class="mb-4">Informations de connexion</h1>
<form action="" method="post">
    <p>Pseudo actuel : <span class="font-weight-bold"><?= $user->getUsername() ?></span></p>
    <?= $form->input('old_password', 'Mot de passe actuel') ?>
    <hr>
    <?= $form->input('username', 'Nouveau pseudo') ?>
    <?= $form->input('new_password', 'Nouveau mot de passe') ?>
    <button class="btn btn-primary" type="submit">Valider</button>
</form>