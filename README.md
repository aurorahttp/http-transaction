HTTP Transaction
================
[![Build Status](https://travis-ci.org/panlatent/http-transaction.svg)](https://travis-ci.org/panlatent/http-transaction)
[![Latest Stable Version](https://poser.pugx.org/panlatent/http-transaction/v/stable.svg)](https://packagist.org/packages/panlatent/http-transaction)
[![Total Downloads](https://poser.pugx.org/panlatent/http-transaction/downloads.svg)](https://packagist.org/packages/panlatent/http-transaction) 
[![Latest Unstable Version](https://poser.pugx.org/panlatent/http-transaction/v/unstable.svg)](https://packagist.org/packages/panlatent/http-transaction)
[![License](https://poser.pugx.org/panlatent/http-transaction/license.svg)](https://packagist.org/packages/panlatent/http-transaction)

HTTP Transaction manage a request to a response workflow. The workflow includes accept a request, apply HTTP request filters, 
apply HTTP middlewares, apply HTTP response filters and returns a response.

```
        ------ request  ---- Filter --->       
       |                               |    
   Transaction                    Middleware 
       |                               |
        <----- response ---- Filter ---- 
 ```
 It support HTTP Message Interface(PSR-7).
 
## License

The HTTP Transaction is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).