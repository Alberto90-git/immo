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

<h1 style="padding: 10px;"><u>Liste des propriétaires et leurs maisons</u></h1>

<table id="customers">
  <tr>
      <th scope="col">Agence</th>
      <th scope="col">Nom & prénom</th>
      <th scope="col">Téléphone</th>
      <th scope="col">Adresse</th>
      <th scope="col">Maison</th>
      <th scope="col">Quartier</th>
  </tr>
  
   @if(isset($element))
      @foreach($element as $items)
        <tr>
          <td>{{ $items->designation }}</td>
          <td>{{ $items->nom }}  {{ $items->prenom }}</td>
          <td>{{ $items->telephone }}</td>
          <td>{{ $items->adresse }}</td>
          <td>{{ $items->nom_maison }}</td>
          <td>{{ $items->quartier }}</td>
        </tr>
      @endforeach
    @endif
</table>

</body>
</html>


