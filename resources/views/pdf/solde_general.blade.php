<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #012970;
  color: white;
}
</style>
</head>
<body>

<h1 style="padding: 10px;"><u>Bénéfice réalisé chez tous les propriétaires du {{ Carbon\Carbon::parse($element2['date_debut'])->format('d/m/Y') }} au {{ Carbon\Carbon::parse($element2['date_fin'])->format('d/m/Y') }} </u></h1>

<table id="customers">
  <tr>
    <th scope="col">Agence</th>
    <th scope="col">Maison</th>
    <th scope="col">Quartier</th>
    <th scope="col">N° chambre</th>
    <th scope="col">Type chambre</th>
    <th scope="col">Prix</th>
    <th scope="col">Montant propriétaire</th>
  
   @if(isset($element2['donnees'] ))
      @foreach($element2['donnees'] as $items)
        <tr>
          <td>{{ $items->designation }}</td>
          <td>{{ $items->nom_maison }}</td>
          <td>{{ $items->quartier }}</td>
          <td>{{ $items->numero_chambre }}</td>
          <td>{{ $items->type_chambre }}</td>
          <td>{{ number_format( $items->montant,"0",",",".") }} XOF</td>
          <td>{{ number_format( ( $items->montant * $element2['pourcentage'] ) / 100,"0",",",".") }} XOF</td>
        </tr>
      @endforeach
    @endif
    <tr>
      <th colspan="6">Total</th>
        <td> {{  number_format($element2['garde'],"0",",",".") }}  XOF</td>
    </tr>
</table>

</body>
</html>