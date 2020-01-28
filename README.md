<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Configuration du fichier hosts (si pas de r�solution de NDD)

Emplacement : C:\Windows\System32\drivers\etc\hosts

(Ouvrir un �diteur de texte en tant qu'admin puis ouvrir le fichier hosts pour pouroir l'�diter)

Ajouter la ligne suivante 
`127.0.0.1 plp.acesi`

## Pour �xecuter les t�ches programm�es :
php artisan schedule:run

Ainsi pour le run avec crontab :
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1 
