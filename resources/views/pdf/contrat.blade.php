<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contrat de location de chambre</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
rel="stylesheet">
<style>
body {
padding: 20px;
font-family: Arial, sans-serif;
}
.header {
text-align: center;
margin-bottom: 40px;
}
.section-title, .section-title1, .section-title2 {
margin-top: 20px;
font-weight: bold;
font-size: 1.2em;
}
.logo {
max-width: 100px;
margin-bottom: 20px;
}
.container p, .container ul {
margin-bottom: 20px;
}
.container ul {
list-style-type: disc;
margin-left: 20px;
}
.d-flex {
display: flex;
justify-content: space-between;
align-items: center;
}
.section-title1 {
text-align: left;
}
.section-title2 {
text-align: right;
}
</style>
</head>
<body>
<div class="container">  
<div class="header">
    <div>
        <img src="{{ asset('http://127.0.0.1/ImmobilierApk/storage/app/public/logo/logo_1729456662.png') }}"  width="100">
    </div>


<h1 style="color: #012970;"><u>CONTRAT DE LOCATION DE CHAMBRE</u></h1>
</div>
<p>ENTRE LES SOUSSIGNÉS :</p>
<div class="section-title">L’AGENCE IMMOBILIÈRE</div>
<p>All Digital Agency immobilière]<br>
alldigitalagency90@gmmail.com<br>
[SIRET de l'agence immobilière]</p>
<p>Représentée par Albert TCHEGNON, en qualité de CEO,<br>
Ci-après dénommée « l'Agence »,</p>
<p class="section-title">ET</p>
<div class="section-title">LE LOCATAIRE</div>
<p>M./Mme {{ $data->nom ?? null}} {{ $data->prenom ?? null}} <br>Profession: {{ $data->profession ?? null }}<br>
Téléphone: {{ $data->telephone ?? null }}<br></p>
<p>Ci-après dénommé « le Locataire »,</p>
<p>IL A ÉTÉ CONVENU ET ARRÊTÉ CE QUI SUIT :</p>
<div class="section-title">1. OBJET DU CONTRAT</div>
<p>L'Agence loue au Locataire une chambre située à {{ $data->quartier ?? null }}, ci-après dénommée « la Chambre ».</p>
<div class="section-title">2. DETAILS SUR LA CHAMBRE</div>
<p>Ci-dessus les détails de la chambre:</p>
<ul>
    <li>Nom de la maison: {{ $data->nom_maison ?? null}}</li>
    <li>Type de chambre: {{ $data->type_chambre ?? null}}</li>
    <li>Numéro de la chambre: {{ $data->numero_chambre ?? null}}</li>
    <li>Caution élétricité: {{ $data->caution_courant ?? null}} F cfa</li>
    <li>Caution eau: {{ $data->caution_eau ?? null}} F cfa</li>
</ul>

<div class="section-title">3. NOMBRE D'AVANCE POUR LA LOCATION</div>
<p>Le nomre d'avance pour la location est consentie pour {{ $data->nombre_avance_consomme ?? null}} mois, à compter du {{ strftime("%d %B %Y", strtotime($data->date_entree ?? null)) }}.</p>
<div class="section-title">4. LOYER</div>
<p>Le loyer mensuel est fixé à {{ $data->prix_mois ?? null}} F cfa, payable
au plus tard la date 5 de chaque mois.</p>
<div class="section-title">5. DÉPÔT DE GARANTIE (AVANCE SUR CONSOMMATION)</div>
<p>Le Locataire verse à l'Agence un dépôt de garantie d'un montant de
[montant en euros] F cfa à titre d'avance sur consommation garantie des éventuels dégâts ou
manquements aux obligations locatives. Ce dépôt sera restitué dans un
délai maximum de [délai] après la fin du contrat, déduction faite des
éventuelles retenues justifiées.</p>
<div class="section-title">6. OBLIGATIONS DU LOCATAIRE</div>
<p>Le Locataire s'engage à :</p>
<ul>
<li>Utiliser la chambre conformément à sa destination.</li>
<li>Respecter le règlement intérieur de la maison.</li>
<li>Maintenir la chambre en bon état de propreté et de réparation locative.</li>
<li>Ne pas sous-louer la chambre sans l'accord écrit de l'Agence.</li>
<li>Signaler immédiatement à l'Agence toute dégradation ou dysfonctionnement.</li>
</ul>
<div class="section-title">7. RÉSILIATION</div>
<p>Le contrat peut être résilié par chacune des parties moyennant un
préavis, par lettre recommandée avec accusé de réception.</p>
<div class="section-title">8. ÉTAT DES LIEUX</div>
<p>Un état des lieux sera réalisé à l'entrée et à la sortie du
Locataire. Toute dégradation constatée sera imputée sur le dépôt de
garantie.</p>
<p>Fait à cotonou, le {{ $data->created_at->format('d/m/Y') }} </p>
<p>En deux exemplaires originaux,</p>
<div class="d-flex">
<div class="section-title1"><u>Pour l'Agence</u></div>
<div class="section-title2"><u>Pour le Locataire</u></div>
</div>
<p class="section-title1">Albert TCHEGNON</p>
<p class="section-title2">{{ $data->nom ?? null}} {{ $data->prenom ?? null}} </p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>