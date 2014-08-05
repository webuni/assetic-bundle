WebuniAsseticBundle
===================

.. image:: https://travis-ci.org/webuni/assetic-bundle.png?branch=master
   :alt: Build status
   :target: https://travis-ci.org/webuni/assetic-bundle

Installation
------------

Edit your ``composer.json`` and add the following package as a **require**:

.. code-block:: json

    {
        "require": {
            "webuni/assetic-bundle": "1.0@dev"
        }
    }

Edit your ``app/AppKernel.php`` and add the bundle to the **registerBundles** method:

.. code-block:: php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Webuni\AsseticBundle\WebuniAsseticBundle(),
            // ...
        )
    }
