# Google Tag Manager for SilverStripe

SilverStripe module to add Google Tag Manager code to all pages of a website.

## Installation

Download, clone or install from packagist (todo) into your Silverstripe root.
Visit http://yoursite.com/dev/build/?flush=1

## What does it do?

The module will output the following code to `<head>`...

```html
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXX');</script>
<!-- End Google Tag Manager -->
```

... and the following code just after `<body>`

```html
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

```

*GTM-XXXX* will be replace by a ContainerID which can be configured in _Site Settings_ > _Google Tag Manager_

## Todo

* Add support for passing in data layers
