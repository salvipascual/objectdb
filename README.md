
ObjectDB
by Salvi Pascual
--------------------------------------------------------------------------
Accessing to databases  in  PHP,  not   compatible  with   object-oriented 
paradigm is complex  when  it  is  needed to store  and  retrieve  objects 
instead of separate variables. Many programmers   break  the encapsulation 
of code which could be  well   modeled   and easily  maintainable;  others 
create  their  own   methods  of  data  access, which  costs  much  of the 
development of the whole system.

ObjectDB creates a general  architecture for data access,  which  (through 
inheritance) can  be   increased  to  support  specific methods for  every 
application. With this library the programmer can obtain, save, and modify 
(among other operations) objects directly from the database without having
to memorize the extensive API of PHP and possessing high knowledge of SQL.
ObjectDB helps to write clearer code (and  therefore  more  maintainable), 
formable according to object-oriented methodologies  (currently  the  most 
used) and makes the programmer  completely  independent  of  the  database 
system to useObjectDB is designed to reduce work  to a  minimum.  In  use, 
tedious  operations  such  as  creating  relationships  between tables and 
finding the last inserted tuple,  become  routine  and use a  few lines of 
code. Although it might be thought that the work  of  an  extra  layer  of 
abstraction slows down the  application, caching  mechanisms  of  ObjectDB 
avoid redundant operations and streamline the  work  so  that  no  visible 
notice of time delays.
--------------------------------------------------------------------------

Find a quick start in ./examples/ folder.

Find official documentation in http://objectdb.pragres.com

Please report any bug or suggestion to salvi.pascual@pragres.com

Thank you! Enjoy ObjectDB
