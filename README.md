# Relish Presspack
WordPress like it's {{ 'now' | date('Y') }} yo. 

#### Intro
This is based on the wonderful [Presspack](https://github.com/jaredpalmer/presspack). Visit that repo for additional documentation and resources.

#### Getting Started

Installs dependencies, WP plugins and fire up a dockerized WP instance.
```
yarn install
composer install # for plugins
docker-compose up
```

#### Developing locally

Opens the site in a browser with auto-reloading when you make changes to theme files.
```
yarn start
```

Important URLs:
* WordPress site: http://localhost:9009 (port can be customized in docker-compose.yml)
* phpMyAdmin: http://localhost:8181 (user: wordpress, password: wordpress)

##### Plugins

Plugins are managed statelessly via Composer. Adding new plugins should be done via [WP Packagist](https://wpackagist.org/) and then adding them to `composer.json` before running `composer update`.

Any plugins that aren't available through WP Packagist (e.g. paid plugins) should be copied directly into the `/plugins` folder.

##### ACF blocks

There's a helper script to assist with creating and registering new ACF blocks:

```
yarn block myblock
```

Running this will:
* Create a template `myblock.twig` file in `/theme/twig/blocks`
* Create a template `myblock.scss` file in `/src/styles/blocks` and import it into `/src/style.scss`
* Outputs the PHP code needed to register the block in `/theme/inc/acf.php`

#### Build for production

Creates an optimized build for production.
```
yarn build
```


#### Troubleshooting

Sometimes WordPress will complain about folder permissions:

```
docker exec -u root -it {CONTAINER_ID} /bin/bash
chown -R www-data wp-content
chmod -R 755 wp-content
```