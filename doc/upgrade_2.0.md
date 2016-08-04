# Upgrade from 1.x to 2.0

All required changes are located within your Admin class implementations.
 
## Use statements
 
 * Datagrid is not instanciated by the Admin class anymore.  You need instead to use the DatagridInterface 

   Before:

   ```php
   use Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid;
   ```

   After:

   ```php
   use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;
   ```

## Use FQN

 * getEntityClass() now expects a FQN as return value.  ::class notation is recommended.

   Before:

   ```php
   public function getEntityClass()
   {
      return 'AppBundle\Entity\Post';
   }
   ```

   After:

   ```php
   use AppBundle\Entity\Post;
      
   ... 
      
   public function getEntityClass()
   {
       return Post::class;
   }
   ```

 * getForm() now expects a FQN as return value.  ::class notation is recommended.

   Before:

   ```php
   public function getForm()
   {
      return 'AppBundle\Form\PostType';
   }
   ```

   After:

   ```php
   use AppBundle\Form\WordType;
   
   ... 
   
   public function getForm()
   {
       return PostType::class;
   }
   ```

## Admin configuration

 *  All non datagrid related configuration has been moved to a single getOptions() function.
 
   Before:

   ```php
   /**
    * @return string
    */
   public function getLabel()
   {
       return '{0} Posts|{1} Post|]1,Inf] Posts';
   }
   
   /**
    * {@inheritdoc}
    */
   public function getMenuSection()
   {
       return 'Blog';
   }
   
   /**
    * {@inheritdoc}
    */
   public function getOptions() {
       return array(
           'description' => 'A blog post is a journal entry.',
           'preview_url_callback' => function ($entity) {
               return $entity->getId();
           },
       );
   }

   ```

   After:

   ```php
   public function getOptions() {
       return [
           'label' => '{0} Posts|{1} Post|]1,Inf] Posts',
           'description' => 'A blog post is a journal entry.',
           'menu_section' => 'Blog',
           'preview_url_callback' => function($entity) {
                   return $entity->getId();
           }

       ];
   }
   ```
   
 
## Datagrid configuration
   
getDatagrid() has been replaced by multiple functions.

 * Datagrid configuration must be done in a dedicated function

   Before:

   ```php
   public function getDatagrid()
   {
       $datagrid = new Datagrid(
           $this, [
               'limit_per_page' => 25,
               'default_sort_column' => 'id',
               'default_sort_order' => 'asc',
               'show_actions_column' => true,
           ]
       );
   ... 
   ```

   After:

   ```php
   /**
    * @inheritDoc
    */
   public function getDatagridOptions()
   {
       return [
           'limit_per_page' => 25,
           'default_sort_column' => 'id',
           'default_sort_order' => 'asc',
           'show_actions_column' => true,
       ];

   }
   ```
   
 * Datagrid fields have to be attached using attachFields(DatagridInterface $datagrid)  

   Before:

   ```php
   public function getDatagrid()
   {
       $datagrid = new Datagrid($this, []);
       
       $datagrid
           ->addField('id', 'text')
           ->addField('title', 'text');
       
       return $datagrid;
   } 
   ```

   After:

   ```php
   /**
    * @inheritDoc
    */
   public function attachFields(DatagridInterface $datagrid)
   {
        $datagrid
            ->addField('id', 'text')
            ->addField('title', 'text');
   }
   ```

   
 * Datagrid actions have to be attached using attachActions(DatagridInterface $datagrid)  

   Before:

   ```php
   public function getDatagrid()
   {
       $datagrid = new Datagrid($this, []);
       
       $datagrid
           ->addAction(DatagridListAction::class, 'csv_export', array('label' => 'Export as CSV', 'icon' => 'save-file', 'btn-style' => 'primary', 'classes' => 'export-link'));
       
       return $datagrid;
   } 
   ```

   After:

   ```php
   /**
    * @inheritDoc
    */
   public function attachActions(DatagridInterface $datagrid)
   {
        $datagrid
            ->addAction(DatagridListAction::class, 'csv_export', array('label' => 'Export as CSV', 'icon' => 'save-file', 'btn-style' => 'primary', 'classes' => 'export-link'));
   }
   ```
   
   