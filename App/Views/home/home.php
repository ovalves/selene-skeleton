<!-- Faz o include de um template -->
{{ include /partials/header.php }}

{{ foreach $tipos as $livro }}
    <p>{{ $livro.suspense }}</p>
    <p>{{ $livro.romance }}</p>
{{ endforeach }}

<!-- Itera por um array e printa seus valores -->
{{ foreach $pessoas as $person }}
    <p>{{ $person.nome }}</p>
    <p>{{ $person.idade }}</p>
    <p>{{ $person.endereco }}</p>
{{ endforeach }}


<!-- Uso de plugins de modificação no template -->
<p>{{ $pessoa | upper }}</p>
<p>{{ $pessoa2 | lower }}</p>

<!-- uso da tag if no template -->
{{ if ($pessoa == 'vini') }}
    <p>É o vini</p>
{{ elseif ($pessoa == 'vinicius') }}
    <p>É o vinicius doido</p>
{{ else }}
    <p>Não é o viniciussss</p>
{{ endif }}

{{ include /partials/footer.php }}