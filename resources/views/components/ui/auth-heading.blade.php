@props(['eyebrow', 'title', 'description'])
<header class="fm-auth-heading">
    <p class="fm-auth-heading__eyebrow"><span aria-hidden="true"></span>{{ $eyebrow }}</p>
    <h1 class="fm-auth-heading__title">{{ $title }}</h1>
    <p class="fm-auth-heading__description">{{ $description }}</p>
</header>