# Upgrade from 2.x to 3.0

All required changes are located within your Admin class implementations.
 
## Abstract Admin class renamed to AbstractAdmin 
 
 * For more consistency the abstract Wanjee\Shuwee\AdminBundle\Admin\Admin class has been renamed to Wanjee\Shuwee\AdminBundle\AbstractAdmin.
 You should therefore rename it in all your Admin implementations.
 
    Before:
 
    ```php
    use Wanjee\Shuwee\AdminBundle\Admin\Admin;
 
    class BlockAdmin extends Admin
    {
       // ...
    }
    ```
 
    After:
 
    ```php
    use Wanjee\Shuwee\AdminBundle\Admin\AbstractAdmin;
    
    class BlockAdmin extends AbstractAdmin
    {
       // ...
    }
    ```
  

   
   