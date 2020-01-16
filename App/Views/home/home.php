<!-- include a partial template -->
{{ include /partials/header.php }}

<!-- Iterating in an array -->
{{ foreach $books as $book }}
    <p>{{ $book.terror | lower }}</p>
    <p>{{ $book.romance | upper }}</p>
    <p>{{ $book.action | upper }}</p>
{{ endforeach }}

<!-- Using template modification plugins -->
<p>{{ $anotherStatement | lower }}</p>
<p>{{ $toUpperCase | upper }}</p>
<p>{{ $toLowerCase | lower }}</p>
<p>{{ $statement | upper }}</p>

<a href="/login">teste</a>
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