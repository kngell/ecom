<!DOCTYPE html>
<html lang="fr">

<head>
</head>

<body>
    <div class="wrapper">
        <h3>Password reset</h3>
        <p>
            Merci de vous être enregistré sur notre site <a href="http://www.kngell.com"><span>kngell.com.</span> </a>
            <br>
            Pour changer votre mot de passe, veuillez cliquer sur le lien ci-dessous.
        </p>
        <a href="{{URLROOT}}/users/resetpass/{{email}}/{{salt}}">{{URLROOT}}/users/resetpass/{{email}}/{{salt}}&nbsp;KnGELL!
        </a>
        <p class="lead"><i class="small">Pour des raisons de sécurité, veuillez noter que ce lien ne sera actif que
                pendant les 30 prochaines minutes.
                Au delà, vous devrez demander un nouveau lien pour changer votre mot de passe.</i></p>
    </div>

</body>

</html>