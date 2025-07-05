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


<h1 style="padding: 10px;"><u>Liste des dossiers des clients déjà traité du {{ Carbon\Carbon::parse($element2['date_debut'])->format('d/m/Y') }} au {{ Carbon\Carbon::parse($element2['date_fin'])->format('d/m/Y') }} </u></h1>

<table id="customers">
  <tr>
    <th scope="col">Agence</th>
    <th scope="col">Nom & prénom client</th>
    <th scope="col">Téléphone</th>
    <th scope="col">Zone voulue</th>
    <th scope="col">Superficie</th>
    <th scope="col">Budget</th>
    <th scope="col">Date cloture du dossier</th>
  
   @if(isset($element2['donnees'] ))
      @foreach($element2['donnees'] as $items)
        <tr>
          <td>{{ $items->designation }}</td>
          <td>{{ $items->nom }}  {{ $items->prenom }}</td>
          <td>{{ $items->telephone }}</td>
          <td>{{ $items->zone_voulu }}</td>
          <td>{{ $items->superficie }} m2</td>
          <td>{{ number_format( $items->budget,"0",",",".") }} XOF</td>
          <td>{{ Carbon\Carbon::parse($items->status)->format('d/m/Y') }}</td>
        </tr>
      @endforeach
    @endif
</table>

</body>
</html>