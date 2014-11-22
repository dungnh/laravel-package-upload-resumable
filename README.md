laravel-package-upload-resumable
================================

A package of Laravel to pause and resum file uploading progress

#Laravel resumable#
This is a laravel 4 package for the server and client side of resumablejs at http://www.resumablejs.com/
##Install##
#####use composer######
Require evolpas/resumable in composer.json and run composer update.
```
{
    "require": {
        "laravel/framework": "4.2.*",
        ...
        "bllim/datatables": "*"
    }
    ...
}
```
######Add service provider and alias ######
open app/config/app.php and add the service provider and alias as below:
```
'providers' => array(
    ...
   'Evolpas\Resumable\ResumableServiceProvider',
),



'aliases' => array(
    ...
   'Resumable' => 'Evolpas\Resumable\Facade\Resumable',
),
```
now you can use Resumable every where to handle uploading file
## Usage##
```
 \Resumable::upload(function($path, $filename) {
        
    });
```
You should define a callback, this callback will be call when the uploading success.
Have fun with this. 

## Demo##

[evolpas.com/demo-resumable/](http://evolpas.com/demo-resumable/)
