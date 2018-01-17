HTTP Transaction
================

HTTP Transaction manage a request to a response workflow. The workflow includes accept a request, apply HTTP request filters, 
apply HTTP middlewares, apply HTTP response filters and returns a response.

```
   ------ request  ---- filter --->       
                                  |    
   Transaction               middleware 
                                  |
   <----- response ---- filter ---- 
 ```
 It support HTTP Message Interface(PSR-7).
