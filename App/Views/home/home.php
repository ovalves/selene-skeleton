<!-- include a partial template -->
{{ include /partials/header.php }}

<!-- Iterating in an array -->
{{ foreach $books as $book }}
    <p>{{ $book.terror }}</p>
    <p>{{ $book.romance }}</p>
{{ endforeach }}

<!-- Using template modification plugins -->
<p>{{ $toUpperCase | upper }}</p>
<p>{{ $toLowerCase | lower }}</p>

<!-- use of if tag in template -->
{{ if ($statement == 'compare') }}
    <!-- ...code -->
{{ elseif ($anotherStatement == 'compare') }}
    <!-- ...code -->
{{ else }}
    <!-- ...code -->
{{ endif }}

<!-- include a partial template -->
{{ include /partials/footer.php }}