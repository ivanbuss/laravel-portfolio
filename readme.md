# Laravel

## Deploy
Install Docker and Docker compose
in the project directory run
`docker-compose up -d`

## Console commands

Migrate all users, decks, artists, manufacturer, brands and collections from the old site.
`php artisan p52:migrate --all`

index all Decks.
This command will competly remove the existing index, crate new index with mapping, index all the decks
`php artisan elastic:index Deck`