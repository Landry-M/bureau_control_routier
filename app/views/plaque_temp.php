<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plaque d'immatriculation provisoire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            margin: 20px;
        }

        .container {
            width: 800px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 95%;
        }

        .header-top .hole {
            width: 20px;
            height: 20px;
            border: 1px solid #000;
            border-radius: 50%;
        }

        .content {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 30px;
        }
        
        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .logo-and-motto {
            display: flex;
            align-items: center;
        }

        .logo {
            font-weight: bold;
            font-size: 14px;
            line-height: 1.2;
            margin-right: 10px;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            font-size: 10px;
        }

        .logo-text .slogan {
            font-style: italic;
        }

        .quebec-logo {
            display: flex;
            align-items: center;
            margin-left: 10px;
        }

        .quebec-logo .plus {
            font-size: 20px;
            font-weight: bold;
            color: #0080ff;
        }

        .quebec-logo .cross {
            width: 15px;
            height: 15px;
            background-color: #0080ff;
            margin: 0 2px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .validity-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
        }

        .important-note {
            width: 60%;
            font-size: 10px;
            line-height: 1.4;
        }

        .dates {
            width: 35%;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            text-align: center;
        }
        
        .date-box {
            border: 1px solid #000;
            padding: 5px;
            flex-grow: 1;
            margin-left: 5px;
        }

        .date-label {
            font-weight: bold;
        }

        .date-value {
            font-size: 14px;
            margin-top: 5px;
        }

        .license-plate {
            text-align: center;
            padding: 40px 0;
            font-size: 80px;
            font-weight: bold;
            letter-spacing: 5px;
            border-bottom: 1px solid #000;
            margin-top: 20px;
            color: #444;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }
        
        .contact-note {
            font-size: 10px;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .contact-info {
            font-size: 10px;
            margin-bottom: 10px;
        }

        .contact-info span {
            margin: 0 5px;
        }

        .slogan-footer {
            font-size: 10px;
            text-align: left;
        }

        .form-id {
            font-size: 8px;
            text-align: left;
            margin-top: 5px;
        }
        
    </style>
</head>
<body>

    <div class="container">
        <div class="header-top">
            <div class="hole"></div>
            <div class="hole"></div>
        </div>

        <div class="content">
            <div class="header-section">
                <div class="logo-and-motto">
                    <div class="logo">
                        Bureau de Control Routier
                    </div>
                    <!-- <div class="quebec-logo">
                        <span class="plus">+</span>
                        <div class="cross"></div>
                        <span class="plus">+</span>
                        <div class="cross"></div>
                        <span class="plus">+</span>
                    </div> -->
                    <!-- <div class="logo-text">
                        <span>Québec</span>
                        <span class="slogan">Au cœur de votre sécurité</span>
                    </div> -->
                </div>
                <div class="title">
                    Plaque d'immatriculation provisoire
                </div>
            </div>
            <div class="validity-section">
                <div class="important-note">
                    <strong>IMPORTANT :</strong> Pour pouvoir circuler, vous devez apposer ce document dans la partie supérieure gauche <br> de la lunette arrière de votre véhicule jusqu'à la réception de votre plaque d'immatriculation.
                </div>
                <div class="dates">
                    <div class="date-box">
                        <div class="date-label">Valide du</div>
                        <div class="date-value">
                            <br>Année-Mois-Jour
                        </div>
                    </div>
                    <div class="date-box">
                        <div class="date-label">Expire le</div>
                        <div class="date-value">
                            <br>Année-Mois-Jour
                        </div>
                    </div>
                </div>
            </div>
            <div class="license-plate">
                12ZZAA
            </div>
            <div class="footer">
                <div class="contact-note">
                    Si vous n'avez pas reçu votre plaque d'immatriculation d'ici le 2024-04-21, vous pouvez vérifier le statut <br> de l'envoi de votre pièce en accédant à votre dossier en ligne ou en composant l'un des numéros suivants :
                </div>
                <div class="contact-info">
                    <strong>Région de Québec :</strong> 418 643-7620 <span>•</span> <strong>Région de Montréal :</strong> 514 873-7620 <span>•</span> <strong>Sans-frais :</strong> 1 800 361-7620 (Québec, Canada, États-Unis)
                </div>
                <div class="slogan-footer">
                    Société de l'assurance automobile du Québec
                </div>
                <div class="form-id">
                    E298 00 (2020-05)
                </div>
            </div>
        </div>
    </div>

</body>
</html>