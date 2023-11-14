<?php
// Je vérifie si le formulaire est soumis comme d'habitude
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Securité en php
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'public/uploads/';
    // le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
    // Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // Les extensions autorisées
    $authorizedExtensions = ['jpg', 'gif', 'png', 'webp'];
    // Le poids max géré par PHP par défaut est de 2M
    $maxFileSize = 1000000;

    // Je sécurise et effectue mes tests

    /****** Si l'extension est autorisée *************/
    if ((!in_array($extension, $authorizedExtensions))) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Gif ou webp ou Png !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
        $errors[] = "Votre fichier doit faire moins de 1M !";
    }

    if (in_array($extension, $authorizedExtensions) && filesize($_FILES['avatar']['tmp_name']) < $maxFileSize) {

        $uniqueName = uniqid("", true);

        $fileName = $uniqueName . basename($_FILES['avatar']['name']);

        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $fileName);
    }

    /****** Si je n'ai pas d"erreur alors j'upload *************/
    /**
     */
    if (!isset($errors)) {
        $success = 'Votre fichier a bien été uploadé !';
    }
}
?>
<form method="post" enctype="multipart/form-data">
    <label for="imageUpload">Upload an profile image</label>
    <input type="file" name="avatar" id="imageUpload" />
    <?php
    if (isset($errors)) {
        foreach ($errors as $error) {
            echo $error;
        }
    }
    if (isset($success)) {
        echo $success;
    }
    ?>
    <button name="send">Send</button>
</form>