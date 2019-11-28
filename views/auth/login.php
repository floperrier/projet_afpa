<?php

use App\Connection;
use App\HTML\Form;
use App\Model\User;
use App\Table\Exception\NotFoundException;
use App\Table\UserTable;

$user = new User();

if (!empty($_POST)) {
    $error = "Nom d'utilisateur ou mot de passe incorrect";
    $user->setUsername($_POST['username']);

    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $table = new UserTable(Connection::getPDO());
        try {
            $userDb = $table->findByUsername($_POST['username']);
            if (password_verify($_POST['password'], $userDb->getPassword())) {
                session_start();
                $_SESSION['auth'] = $userDb->getId();
                header('Location: ' . $router->url('admin_posts') . '?login=1');
                exit();
            }
        } catch (NotFoundException $e) {}
    }
}

$form = new Form($user, []);
?>

<?php if (isset($_GET['forbidden'])): ?>
    <div class="alert alert-danger">
        Accès interdit, veuillez vous connecter pour accéder à l'administration
    </div>
<?php endif ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?= $error ?>
    </div>
<?php endif ?>

<h1>Se connecter</h1>
<form action="" method="post">
    <?= $form->input("username","Nom d'utilisateur") ?>
    <?= $form->input("password","Mot de passe") ?>
    <button type="submit" class="btn btn-primary">Valider</button>
</form>