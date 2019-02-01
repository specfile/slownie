Slownie::print
==============

NAME
----
Slownie::print - print spelled out amount of money in Polish

SYNOPSIS
--------
```
use SpecFile;

Slownie::print(number $amount) : string
```

DESCRIPTION
-----------
The *print* method prints spelled out amount of money in Polish.

RETURN VALUES
-------------
Spelled out amount as string.

EXAMPLES
--------
*Slownie::print(123)* returns "sto dwadzieścia trzy złote 00/100" and *Slownie::print(1234.01)* - "tysiąc dwieście trzydzieści cztery złote 01/100".

EXCEPTIONS
----------
*NotANumberException* - if amount isn't a number.

BUGS
----
* it only spells out numbers in one format and only in one language,
* using int or floats as monetary values loses precission,
* source code may be unidiomatic PHP, but it was fixed by *php-cs-fixer*.