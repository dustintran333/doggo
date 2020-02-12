# Doggo

Somar Technical Test

## Setup

### Requirements

- Working SilverStripe development environment (e.g. LAMP stack).
- PHP version 7.1 or greater
- Node version 10.* 
- [Composer](https://getcomposer.org/doc/00-intro.md)

Your web root **must** point to the `public/` folder (not the root of the project). 

### Installation

```
composer install
npm install

Either via command line:
    vendor/bin/sake dev/build flush
    vendor/bin/sake dev/tasks/Doggo-Task-FetchParksTask
Or browser:
    localhost/doggo/dev/build?flush=all
    localhost/doggo/dev/tasks/Doggo-Task-FetchParksTask

```

### Configuration

Create a `.env` file in your project root with the standard SilverStripe setup (we have provided a .env.example) as well as:

- `MAPBOX_TOKEN` set to the token pk.eyJ1Ijoic29tYXItZGVzaWduLXN0dWRpb3MiLCJhIjoiY2s1eWJuc2c4MXA5bzNsazBwYTZ6dnM3MiJ9.nReqnpF0FswusJzh405eWw.

### Compilation

```
npm run dev
```

## Access

Access the CMS and frontend how you usually would.

## Project Structure

*  `app/`: PHP (SilverStripe) code
   * `src/Model/Park.php`: Park data object
   * `src/Task/FetchParksTask.php`: Task to import parks from WCC data
* `public/`: Web root
* `app/client/js`: JavaScript (Vue) code
  * `App.vue`: - Top level application
  * `store.js`: VueX store where parks are fetched from SilverStripe API
* `app/client/scss/app.scss`: Scss stylesheet

## Troubleshooting
##### Node build errors
You will need to use a node version 10.* of node to install node_modules
##### SilverStripe API
The parks data is being served using [SilverStripe's built in restful server](https://github.com/silverstripe/silverstripe-restfulserver). The api pattern is /api/v1/(ClassName)/(ID).
##### Image upload API endpoint
You will need to create a new route and controller to handle uploading images to the server as the SilverStripe Restful Server module does not currently handle saving relations.  
