<h1>My classs List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Ps customer</th>
      <th>Name</th>
      <th>Code</th>
      <th>Description</th>
      <th>User created</th>
      <th>User updated</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($my_classs as $my_class): ?>
    <tr>
      <td><a href="<?php echo url_for('psClass/show?
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
id='.$my_class->getId()) ?>"><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
Id() ?></a></td>
      <td><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
PsCustomerId() ?></td>
      <td><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
Name() ?></td>
      <td><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
Code() ?></td>
      <td><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
Description() ?></td>
      <td><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
UserCreatedId() ?></td>
      <td><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
UserUpdatedId() ?></td>
      <td><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
CreatedAt() ?></td>
      <td><?php echo $my_class->get
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
UpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('psClass/new') ?>">New</a>
